<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PaymentTransaction;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_mock_driver_by_default()
    {
        Config::set('payment.driver', 'mock');
        
        $service = new PaymentService();
        
        $this->assertTrue($service->isMockDriver());
        $this->assertFalse($service->isXenditDriver());
        $this->assertEquals('mock', $service->getDriverName());
        $this->assertFalse($service->requiresRedirect());
    }

    public function test_it_resolves_xendit_driver_when_configured()
    {
        Config::set('payment.driver', 'xendit');
        
        $service = new PaymentService();
        
        $this->assertFalse($service->isMockDriver());
        $this->assertTrue($service->isXenditDriver());
        $this->assertEquals('xendit', $service->getDriverName());
        $this->assertTrue($service->requiresRedirect());
    }

    public function test_mock_driver_creates_payment_successfully()
    {
        Config::set('payment.driver', 'mock');
        
        $user = User::factory()->create();
        $service = new PaymentService();
        
        $response = $service->createPayment($user, 'course', 1, 150000, 'Test Course');
        
        $this->assertTrue($response['success']);
        $this->assertEquals('PAID', $response['status']);
        $this->assertNotNull($response['external_id']);
        $this->assertNull($response['checkout_url']);
        
        $this->assertDatabaseHas('payment_transactions', [
            'user_id' => $user->id,
            'driver' => 'mock',
            'amount' => 150000,
            'status' => 'PAID',
        ]);
    }

    public function test_xendit_driver_creates_payment_successfully()
    {
        Config::set('payment.driver', 'xendit');
        Config::set('payment.xendit.api_key', 'test_key');
        
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'inv_123456',
                'external_id' => 'EXT-123',
                'status' => 'PENDING',
                'invoice_url' => 'https://checkout.xendit.co/web/123456',
            ], 200),
        ]);
        
        $user = User::factory()->create();
        $service = new PaymentService();
        
        $response = $service->createPayment($user, 'course', 1, 150000, 'Test Course');
        
        $this->assertTrue($response['success']);
        $this->assertEquals('PENDING', $response['status']);
        $this->assertEquals('EXT-123', $response['external_id']);
        $this->assertEquals('https://checkout.xendit.co/web/123456', $response['checkout_url']);
        
        $this->assertDatabaseHas('payment_transactions', [
            'user_id' => $user->id,
            'driver' => 'xendit',
            'amount' => 150000,
            'status' => 'PENDING',
        ]);
    }
}
