<?php
namespace App\Services\Paystack;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Paystack
{
    public function processPayment($data): mixed
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $secret = config("services.paystack.secret");
        $response = Http::withHeaders([
            "Authorization" => "Bearer $secret",
            "Cache-Control" => "no-cache"
        ])
            ->post($url, $data);
            Log::info("Payment process initiated.");

        if ($response->successful()) {
            Log::info("Payment Link generated.");
            return $response->json();
        }
        Log::error("Payment failed.");
        return $response->json();

    }

    public function verifyPayment($reference)
    {
        $url = "https://api.paystack.co/transaction/verify/$reference";
        $secret = config("services.paystack.secret");
        $response = Http::withHeaders([
            "Authorization" => "Bearer $secret",
            "Cache-Control" => "no-cache"
        ])->get($url);

        return $response->json();
    }
    
    public function handleWebhook($request)
    {
        
    }
}

