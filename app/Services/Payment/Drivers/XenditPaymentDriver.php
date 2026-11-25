<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class XenditPaymentDriver implements PaymentDriverInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('payment.xendit.environment') === 'production'
            ? 'https://api.xendit.co'
            : 'https://api.xendit.co';
    }

    /**
     * Create an invoice payment via Xendit.
     */
    public function createPayment(?User $user, string $itemType, int $itemId, int $amount, string $itemName, array $metadata = []): array
    {
        $useSandbox = (bool) ($metadata['use_sandbox'] ?? false);
        $apiKey = $useSandbox
            ? config('payment.xendit.sandbox_api_key')
            : config('payment.xendit.api_key');

        if (empty($apiKey)) {
            return [
                'success' => false,
                'error' => $useSandbox
                    ? 'Xendit Sandbox API key (XENDIT_API_SANDBOX) not configured'
                    : 'Xendit API key not configured',
            ];
        }

        $actorId = $user?->id ?? 0;
        $externalId = $this->generateExternalId($itemType, $itemId, $actorId, $useSandbox);

        $rawName = trim((string) ($user?->name ?: ($metadata['customer_name'] ?? 'Peserta SIAP')));
        $nameParts = preg_split('/\s+/', $rawName, 2);
        $givenNames = $user?->first_name ?: ($nameParts[0] ?? 'Customer');
        $surname = $user?->last_name ?: ($nameParts[1] ?? null);

        $customer = array_filter([
            'given_names' => $givenNames,
            'surname' => $surname,
            'email' => $user?->email ?: ($metadata['customer_email'] ?? null),
            'mobile_number' => $user?->phone ?: ($metadata['customer_phone'] ?? null),
        ], fn ($value) => $value !== null && $value !== '');

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => "Payment for {$itemName}",
            'customer' => $customer,
            'customer_notification_preference' => [
                'invoice_created' => ['email'],
                'invoice_reminder' => ['email'],
                'invoice_paid' => ['email'],
            ],
            'success_redirect_url' => $this->getSuccessRedirectUrl($itemType, $itemId),
            'failure_redirect_url' => $this->getFailureRedirectUrl(),
            'currency' => 'IDR',
            'metadata' => array_merge($metadata, [
                'item_type' => $itemType,
                'item_id' => $itemId,
                'user_id' => $user?->id,
                'item_name' => $itemName,
                'is_sandbox' => $useSandbox,
            ]),
        ];

        try {
            $response = Http::withBasicAuth($apiKey, '')
                ->withOptions(['verify' => (bool) config('payment.xendit.verify_ssl', true)])
                ->timeout(30)
                ->post("{$this->baseUrl}/v2/invoices", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Create transaction record
                PaymentTransaction::create([
                    'user_id' => $user?->id,
                    'external_id' => $data['external_id'] ?? $externalId,
                    'payment_id' => $data['id'] ?? null,
                    'driver' => 'xendit',
                    'item_type' => $itemType,
                    'item_id' => $itemId,
                    'amount' => $amount,
                    'status' => $data['status'] ?? 'PENDING',
                    'item_name' => $itemName,
                    'metadata' => array_merge($metadata, [
                        'item_type' => $itemType,
                        'item_id' => $itemId,
                        'user_id' => $user?->id,
                        'item_name' => $itemName,
                        'is_sandbox' => $useSandbox,
                    ]),
                ]);

                return [
                    'success' => true,
                    'checkout_url' => $data['invoice_url'] ?? null,
                    'external_id' => $data['external_id'] ?? $externalId,
                    'payment_id' => $data['id'] ?? null,
                    'status' => $data['status'] ?? 'PENDING',
                    'message' => 'Invoice created successfully',
                ];
            }

            Log::error('Xendit invoice creation failed', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
                'external_id' => $externalId,
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to create invoice',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Xendit API error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Get payment status from Xendit.
     */
    public function getPaymentStatus(string $externalId): array
    {
        $transaction = PaymentTransaction::where('external_id', $externalId)->first();

        if (! $transaction) {
            return [
                'success' => false,
                'error' => 'Transaction not found',
            ];
        }

        $useSandbox = (bool) ($transaction->metadata['is_sandbox'] ?? false)
            || str_starts_with($transaction->external_id, '1LANGKAH-SANDBOX-');
        $apiKey = $useSandbox
            ? config('payment.xendit.sandbox_api_key')
            : config('payment.xendit.api_key');

        if (empty($apiKey)) {
            return [
                'success' => true,
                'external_id' => $transaction->external_id,
                'status' => $transaction->status,
                'message' => 'Transaction found but Xendit API not configured',
            ];
        }

        try {
            $invoiceId = $transaction->payment_id ?: $externalId;
            $response = Http::withBasicAuth($apiKey, '')
                ->withOptions(['verify' => (bool) config('payment.xendit.verify_ssl', true)])
                ->timeout(30)
                ->get("{$this->baseUrl}/v2/invoices/{$invoiceId}");

            if ($response->successful()) {
                $data = $response->json();

                // Update transaction status
                $transaction->update(['status' => $data['status']]);

                return [
                    'success' => true,
                    'external_id' => $data['external_id'],
                    'status' => $data['status'],
                    'payment_id' => $data['id'],
                    'message' => 'Status retrieved successfully',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to get invoice status',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Xendit API error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Handle webhook callback from Xendit.
     */
    public function handleWebhook(array $payload): array
    {
        $callbackToken = config('payment.xendit.callback_token');

        // Verify callback token if configured
        if ($callbackToken) {
            $providedToken = request()->header('x-callback-token');
            if ($providedToken !== $callbackToken) {
                return [
                    'success' => false,
                    'message' => 'Invalid callback token',
                    'external_id' => null,
                ];
            }
        }

        $eventType = $payload['event'] ?? $payload['type'] ?? null;
        $invoice = $payload['data'] ?? $payload;

        $externalId = $invoice['external_id'] ?? null;
        $status = $invoice['status'] ?? null;

        if ($eventType !== 'invoice.paid' && $status !== 'PAID') {
            return [
                'success' => true,
                'message' => 'Event type not relevant or payment not completed',
                'external_id' => $externalId,
            ];
        }

        return [
            'success' => true,
            'message' => 'Webhook processed',
            'external_id' => $externalId,
            'status' => $status,
        ];
    }

    /**
     * Xendit payments require redirect to invoice page.
     */
    public function requiresRedirect(): bool
    {
        return true;
    }

    /**
     * Generate a unique external ID for the payment.
     */
    private function generateExternalId(string $itemType, int $itemId, int $userId, bool $useSandbox = false): string
    {
        $prefix = $useSandbox ? '1LANGKAH-SANDBOX' : '1LANGKAH';

        return strtoupper("{$prefix}-{$itemType}-{$itemId}-{$userId}-".Str::random(8));
    }

    /**
     * Get success redirect URL.
     */
    private function getSuccessRedirectUrl(string $itemType, int $itemId): string
    {
        if ($itemType === 'siap_order') {
            return URL::temporarySignedRoute('siap.order.success', now()->addHours(24), ['order' => $itemId]);
        }

        $baseUrl = (! app()->runningInConsole() && request())
            ? request()->getSchemeAndHttpHost()
            : config('app.url');

        return match ($itemType) {
            'course' => "{$baseUrl}/kursus/{$itemId}",
            'online', 'offline' => "{$baseUrl}/bootcamp/{$itemId}",
            'event' => "{$baseUrl}/event/{$itemId}",
            'mentor_session' => "{$baseUrl}/my-sessions",
            'quiz' => "{$baseUrl}/quiz/start/{$itemId}?started=1",
            'quiz_attempt' => "{$baseUrl}/quiz/result/{$itemId}",
            default => "{$baseUrl}/dashboard",
        };
    }

    /**
     * Get failure redirect URL.
     */
    private function getFailureRedirectUrl(): string
    {
        $baseUrl = (! app()->runningInConsole() && request())
            ? request()->getSchemeAndHttpHost()
            : config('app.url');

        return $baseUrl.'/pembayaran';
    }
}
