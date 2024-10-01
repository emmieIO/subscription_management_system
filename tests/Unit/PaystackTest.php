<?php

namespace Tests\Unit\Services\Paystack;

use Tests\TestCase;
use App\Services\Paystack\Paystack;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayStackTest extends TestCase
{
    public function test_process_payment_success()
    {
        // Fake the HTTP response
        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Payment initialized',
                'data' => ['authorization_url' => 'https://paystack.com/pay/link'],
            ], 200)
        ]);

        // Fake logging
        Log::shouldReceive('info')->once()->with('Payment process initiated.');
        Log::shouldReceive('info')->once()->with('Payment Link generated.');

        // Prepare the data for the payment
        $data = [
            'email' => 'test@example.com',
            'amount' => 5000,
        ];

        // Call the Paystack service
        $paystack = new Paystack();
        $response = $paystack->processPayment($data);

        // Assert the response
        $this->assertTrue($response['status']);
        $this->assertEquals('https://paystack.com/pay/link', $response['data']['authorization_url']);
    }

    public function test_process_payment_failure()
    {
        // Fake the HTTP response for a failed payment initialization
        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => false,
                'message' => 'Payment failed',
            ], 400)
        ]);

        // Fake logging
        Log::shouldReceive('info')->once()->with('Payment process initiated.');
        Log::shouldReceive('error')->once()->with('Payment failed.');

        // Prepare the data for the payment
        $data = [
            'email' => 'test@example.com',
            'amount' => 5000,
        ];

        // Call the Paystack service
        $paystack = new Paystack();
        $response = $paystack->processPayment($data);

        // Assert the response
        $this->assertFalse($response['status']);
        $this->assertEquals('Payment failed', $response['message']);
    }
}
