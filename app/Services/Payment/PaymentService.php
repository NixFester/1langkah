<?php

namespace App\Services\Payment;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\User;
use App\Services\Payment\Drivers\MockPaymentDriver;
use App\Services\Payment\Drivers\XenditPaymentDriver;

class PaymentService
{
    private PaymentDriverInterface $driver;

    public function __construct()
    {
        $this->driver = $this->resolveDriver();
    }

    /**
     * Create a payment for the given item.
     */
    public function createPayment(User $user, string $itemType, int $itemId, int $amount, string $itemName, array $metadata = []): array
    {
        return $this->driver->createPayment($user, $itemType, $itemId, $amount, $itemName, $metadata);
    }

    /**
     * Get the status of a payment.
     */
    public function getPaymentStatus(string $externalId): array
    {
        return $this->driver->getPaymentStatus($externalId);
    }

    /**
     * Handle webhook from payment provider.
     */
    public function handleWebhook(array $payload): array
    {
        return $this->driver->handleWebhook($payload);
    }

    /**
     * Check if payments require redirect to external page.
     */
    public function requiresRedirect(): bool
    {
        return $this->driver->requiresRedirect();
    }

    /**
     * Get the current payment driver name.
     */
    public function getDriverName(): string
    {
        return config('payment.driver', 'mock') ?? 'mock';
    }

    /**
     * Check if using mock driver.
     */
    public function isMockDriver(): bool
    {
        return $this->getDriverName() === 'mock';
    }

    /**
     * Check if using xendit driver.
     */
    public function isXenditDriver(): bool
    {
        return $this->getDriverName() === 'xendit';
    }

    /**
     * Resolve the payment driver based on configuration.
     */
    private function resolveDriver(): PaymentDriverInterface
    {
        $driver = config('payment.driver', 'mock');

        return match ($driver) {
            'xendit' => new XenditPaymentDriver,
            default => new MockPaymentDriver,
        };
    }
}
