<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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
    public function createPayment(User $user, string $itemType, int $itemId, int $amount, string $itemName, array $metadata = []): array
    {
        $apiKey = config('payment.xendit.api_key');

        if (empty($apiKey)) {
            return [
                'success' => false,
                'error' => 'Xendit API key not configured',
            ];
        }

        $externalId = $this->generateExternalId($itemType, $itemId, $user->id);

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => "Payment for {$itemName}",
            'customer' => [
                'given_names' => $user->first_name ?? explode(' ', $user->name)[0],
                'surname' => $user->last_name ?? (isset(explode(' ', $user->name)[1]) ? explode(' ', $user->name)[1] : ''),
                'email' => $user->email,
                'mobile_number' => $user->phone ?? null,
            ],
            'customer_notification_preference' => [
                'invoice_created' => ['email_enabled' => true, 'sms_enabled' => false],
                'invoice_reminder' => ['email_enabled' => true, 'sms_enabled' => false],
                'invoice_paid' => ['email_enabled' => true, 'sms_enabled' => true],
            ],
            'success_redirect_url' => $this->getSuccessRedirectUrl($itemType, $itemId),
            'failure_redirect_url' => $this->getFailureRedirectUrl(),
            'currency' => 'IDR',
            'metadata' => array_merge($metadata, [
                'item_type' => $itemType,
                'item_id' => $itemId,
                'user_id' => $user->id,
                'item_name' => $itemName,
            ]),
        ];

        try {
            $response = Http::withBasicAuth($apiKey, '')
                ->timeout(30)
                ->post("{$this->baseUrl}/v2/invoices", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Create transaction record
                PaymentTransaction::create([
                    'user_id' => $user->id,
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
                        'user_id' => $user->id,
                        'item_name' => $itemName,
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

        $apiKey = config('payment.xendit.api_key');

        if (empty($apiKey)) {
            return [
                'success' => true,
                'external_id' => $transaction->external_id,
                'status' => $transaction->status,
                'message' => 'Transaction found but Xendit API not configured',
            ];
        }

        try {
            $response = Http::withBasicAuth($apiKey, '')
                ->timeout(30)
                ->get("{$this->baseUrl}/v2/invoices/{$externalId}");

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
    private function generateExternalId(string $itemType, int $itemId, int $userId): string
    {
        return strtoupper("1LANGKAH-{$itemType}-{$itemId}-{$userId}-".Str::random(8));
    }

    /**
     * Get success redirect URL.
     */
    private function getSuccessRedirectUrl(string $itemType, int $itemId): string
    {
        $baseUrl = config('app.url');

        return match ($itemType) {
            'course' => "{$baseUrl}/kursus/{$itemId}",
            'online', 'offline' => "{$baseUrl}/bootcamp/{$itemId}",
            'event' => "{$baseUrl}/event/{$itemId}",
            'mentor_session' => "{$baseUrl}/my-sessions",
            default => "{$baseUrl}/dashboard",
        };
    }

    /**
     * Get failure redirect URL.
     */
    private function getFailureRedirectUrl(): string
    {
        return config('app.url').'/pembayaran';
    }
}
