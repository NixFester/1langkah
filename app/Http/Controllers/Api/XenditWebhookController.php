<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\QuizResultMail;
use App\Models\Bootcamp;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MentorSession;
use App\Models\PaymentTransaction;
use App\Models\Quiz;
use App\Models\SiapOrder;
use App\Models\TestAttempt;
use App\Models\UserActivityLog;
use App\Services\NotificationService;
use App\Services\Payment\PaymentService;
use App\Services\SiapAssessmentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class XenditWebhookController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Handle Xendit webhook callback.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Xendit webhook received', ['payload' => $payload]);

        // Validate callback token if configured (supports both production and sandbox tokens)
        $prodToken = config('payment.xendit.callback_token');
        $sandboxToken = config('payment.xendit.sandbox_callback_token');
        $providedToken = $request->header('x-callback-token');

        $configuredTokens = array_filter([$prodToken, $sandboxToken], fn ($t) => ! empty($t));
        if (! empty($configuredTokens) && ! in_array($providedToken, $configuredTokens, true)) {
            Log::warning('Xendit webhook: Invalid callback token');

            return response()->json(['success' => false, 'message' => 'Invalid callback token'], 401);
        }

        $eventType = $payload['event'] ?? null;
        $invoice = $payload['data'] ?? $payload;

        $externalId = $invoice['external_id'] ?? null;
        $status = $invoice['status'] ?? null;

        if (! $externalId) {
            Log::warning('Xendit webhook: Missing external_id');

            return response()->json(['success' => false, 'message' => 'Missing external_id'], 400);
        }

        // Find the transaction
        $transaction = PaymentTransaction::where('external_id', $externalId)
            ->where('driver', 'xendit')
            ->first();

        if (! $transaction) {
            Log::warning('Xendit webhook: Transaction not found', ['external_id' => $externalId]);

            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        $isSandboxTx = (bool) ($transaction->metadata['is_sandbox'] ?? false)
            || str_starts_with($transaction->external_id, '1LANGKAH-SANDBOX-');

        if ($isSandboxTx && $sandboxToken && $providedToken !== $sandboxToken) {
            Log::warning('Xendit webhook: Sandbox transaction requires sandbox callback token', ['external_id' => $externalId]);

            return response()->json(['success' => false, 'message' => 'Invalid callback token'], 401);
        }

        if (! $isSandboxTx && $prodToken && $providedToken !== $prodToken) {
            Log::warning('Xendit webhook: Production transaction requires production callback token', ['external_id' => $externalId]);

            return response()->json(['success' => false, 'message' => 'Invalid callback token'], 401);
        }

        // Only process paid/settled invoices
        if (! in_array(strtoupper((string) $status), ['PAID', 'SETTLED'], true)) {
            Log::info('Xendit webhook: Ignoring non-paid status', ['status' => $status]);

            return response()->json(['success' => true, 'message' => 'Status not relevant']);
        }

        // Check if already processed
        if ($transaction->isPaid()) {
            Log::info('Xendit webhook: Transaction already processed', ['external_id' => $externalId]);

            return response()->json(['success' => true, 'message' => 'Already processed']);
        }

        // Update transaction status
        $transaction->update([
            'status' => 'PAID',
            'paid_at' => Carbon::now(),
            'payment_id' => $invoice['id'] ?? $transaction->payment_id,
        ]);

        // Process enrollment/enrollment based on item type
        $this->processSuccessfulPayment($transaction);

        Log::info('Xendit webhook: Payment processed successfully', [
            'external_id' => $externalId,
            'user_id' => $transaction->user_id,
            'item_type' => $transaction->item_type,
            'item_id' => $transaction->item_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Webhook processed']);
    }

    /**
     * Process successful payment by creating enrollment/registration.
     */
    private function processSuccessfulPayment(PaymentTransaction $transaction): void
    {
        $userId = $transaction->user_id;
        $itemType = $transaction->item_type;
        $itemId = $transaction->item_id;
        $purchasableType = $this->getPurchasableType($itemType);

        // Handle mentor session booking
        if ($itemType === 'mentor_session') {
            $this->processMentorSessionBooking($transaction);

            return;
        }

        // Handle event registration
        if ($itemType === 'event') {
            $this->processEventRegistration($transaction);

            return;
        }

        // Handle quiz / quiz attempt unlock
        if (in_array($itemType, ['quiz', 'quiz_attempt'], true)) {
            $this->processQuizUnlock($transaction);

            return;
        }

        // Handle SIAP assessment / Super Finger / Bundle orders
        if ($itemType === 'siap_order') {
            $siapOrder = SiapOrder::find($itemId);
            if ($siapOrder) {
                app(SiapAssessmentService::class)->fulfillPaidOrder($siapOrder);
            } else {
                Log::error('Xendit webhook: SiapOrder not found', ['order_id' => $itemId]);
            }

            return;
        }

        // Handle course/bootcamp enrollment
        if ($purchasableType) {
            $this->processEnrollment($transaction, $purchasableType);
        }
    }

    /**
     * Process mentor session booking.
     */
    private function processMentorSessionBooking(PaymentTransaction $transaction): void
    {
        $userId = $transaction->user_id;
        $metadata = $transaction->metadata ?? [];
        $mentorId = $metadata['mentor_id'] ?? null;
        $bookedDate = $metadata['booked_date'] ?? null;
        $bookedTime = $metadata['booked_time'] ?? null;

        if (! $mentorId || ! $bookedDate || ! $bookedTime) {
            Log::error('Xendit webhook: Missing mentor session data', [
                'transaction_id' => $transaction->id,
            ]);

            return;
        }

        // Create mentor session
        MentorSession::create([
            'user_id' => $userId,
            'mentor_id' => $mentorId,
            'booked_date' => $bookedDate,
            'booked_time' => $bookedTime,
            'notes' => $metadata['notes'] ?? null,
            'status' => MentorSession::STATUS_PENDING,
        ]);

        Log::info('Mentor session created via webhook', [
            'user_id' => $userId,
            'mentor_id' => $mentorId,
        ]);
    }

    /**
     * Process event registration.
     */
    private function processEventRegistration(PaymentTransaction $transaction): void
    {
        $userId = $transaction->user_id;
        $eventId = $transaction->item_id;

        $event = Event::find($eventId);
        if (! $event) {
            Log::error('Xendit webhook: Event not found', ['event_id' => $eventId]);

            return;
        }

        // Check if already registered
        $existingReg = EventRegistration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existingReg) {
            return;
        }

        // Create registration
        $ticketCode = 'EVT-'.strtoupper(uniqid()).'-'.date('Ymd');
        EventRegistration::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'status' => 'registered',
            'ticket_code' => $ticketCode,
            'registered_at' => Carbon::now(),
        ]);

        // Update registered count
        $event->increment('registered_count');

        Log::info('Event registration created via webhook', [
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
    }

    /**
     * Process enrollment.
     */
    private function processEnrollment(PaymentTransaction $transaction, string $purchasableType): void
    {
        $userId = $transaction->user_id;
        $itemId = $transaction->item_id;

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $userId)
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $itemId)
            ->first();

        if ($existingEnrollment) {
            return;
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $userId,
            'purchasable_type' => $purchasableType,
            'purchasable_id' => $itemId,
            'status' => 'active',
        ]);

        // Log activity
        UserActivityLog::create([
            'user_id' => $userId,
            'action' => 'enrolled',
            'loggable_type' => $purchasableType,
            'loggable_id' => $itemId,
        ]);

        // Send notification
        app(NotificationService::class)->enrolled($userId, $transaction->item_name, $transaction->item_type, $itemId);

        Log::info('Enrollment created via webhook', [
            'user_id' => $userId,
            'item_type' => $transaction->item_type,
            'item_id' => $itemId,
        ]);
    }

    /**
     * Process quiz unlock after Xendit payment.
     */
    private function processQuizUnlock(PaymentTransaction $transaction): void
    {
        $userId = $transaction->user_id;
        $metadata = $transaction->metadata ?? [];
        $quizId = $metadata['quiz_id'] ?? ($transaction->item_type === 'quiz' ? $transaction->item_id : null);
        $attemptId = $metadata['attempt_id'] ?? ($transaction->item_type === 'quiz_attempt' ? $transaction->item_id : null);

        if (! $quizId && $attemptId) {
            $attempt = TestAttempt::find($attemptId);
            $quizId = $attempt?->quiz_id;
        }

        if (! $quizId) {
            Log::error('Xendit webhook: Missing quiz_id for quiz unlock', [
                'transaction_id' => $transaction->id,
            ]);

            return;
        }

        TestAttempt::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->update([
                'is_paid' => true,
                'paid_at' => Carbon::now(),
                'payment_amount' => $transaction->amount,
            ]);

        if ($attemptId) {
            $attempt = TestAttempt::with('user')->find($attemptId);
            $quiz = Quiz::with('questions.answers')->find($quizId);

            if ($attempt && $quiz && ! $attempt->email_sent_at) {
                try {
                    $recipient = $attempt->google_email ?: $attempt->user?->email;
                    if ($recipient) {
                        Mail::to($recipient)->send(new QuizResultMail($attempt, $quiz, true));
                        $attempt->update(['email_sent_at' => Carbon::now()]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Xendit webhook: Failed to send quiz result email: '.$e->getMessage());
                }
            }
        }

        Log::info('Quiz unlocked via webhook', [
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'attempt_id' => $attemptId,
        ]);
    }

    /**
     * Get the purchasable type class.
     */
    private function getPurchasableType(string $itemType): ?string
    {
        return match ($itemType) {
            'course' => Course::class,
            'online', 'offline', 'bootcamp' => Bootcamp::class,
            default => null,
        };
    }
}
