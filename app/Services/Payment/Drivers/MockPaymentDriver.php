<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\PaymentTransaction;
use App\Models\User;
use Carbon\Carbon;

class MockPaymentDriver implements PaymentDriverInterface
{
    /**
     * Create a mock payment (always succeeds immediately).
     */
    public function createPayment(User $user, string $itemType, int $itemId, int $amount, string $itemName, array $metadata = []): array
    {
        $externalId = 'MOCK-'.strtoupper(uniqid()).'-'.time();

        // Create transaction record
        PaymentTransaction::create([
            'user_id' => $user->id,
            'external_id' => $externalId,
            'payment_id' => $externalId,
            'driver' => 'mock',
            'item_type' => $itemType,
            'item_id' => $itemId,
            'amount' => $amount,
            'status' => 'PAID',
            'item_name' => $itemName,
            'metadata' => $metadata,
            'paid_at' => Carbon::now(),
        ]);

        return [
            'success' => true,
            'checkout_url' => null,
            'external_id' => $externalId,
            'payment_id' => $externalId,
            'status' => 'PAID',
            'message' => 'Mock payment created successfully',
        ];
    }

    /**
     * Get mock payment status.
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

        return [
            'success' => true,
            'external_id' => $transaction->external_id,
            'status' => $transaction->status,
            'message' => 'Payment status retrieved successfully',
        ];
    }

    /**
     * Handle mock webhook (no-op).
     */
    public function handleWebhook(array $payload): array
    {
        return [
            'success' => true,
            'message' => 'Webhook received (mock)',
            'external_id' => $payload['external_id'] ?? null,
        ];
    }

    /**
     * Mock payments don't require redirect.
     */
    public function requiresRedirect(): bool
    {
        return false;
    }
}
