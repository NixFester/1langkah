<?php

namespace App\Contracts\Payment;

use App\Models\User;

interface PaymentDriverInterface
{
    /**
     * Create a payment transaction and return the checkout URL or payment details.
     *
     * @param  string  $itemType  course|bootcamp|event|mentor_session
     * @param  int  $amount  Amount in IDR (cents not needed for Xendit)
     * @param  array<string, mixed>  $metadata  Additional metadata
     * @return array<string, mixed> payment details including 'success', 'checkout_url', 'external_id', 'error'
     */
    public function createPayment(User $user, string $itemType, int $itemId, int $amount, string $itemName, array $metadata = []): array;

    /**
     * Get the status of a payment by external ID.
     *
     * @return array<string, mixed> status details
     */
    public function getPaymentStatus(string $externalId): array;

    /**
     * Handle webhook callback from payment provider.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed> result with 'success', 'message', 'external_id'
     */
    public function handleWebhook(array $payload): array;

    /**
     * Check if this payment requires redirect to external page.
     */
    public function requiresRedirect(): bool;
}
