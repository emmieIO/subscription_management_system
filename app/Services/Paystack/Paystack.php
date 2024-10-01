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
        ])->post($url, $data);
        Log::info("Payment process initiated.");
        if($response['status']){
            Log::info("Payment Link generated.");
            return $response->json();

        }else{

            Log::error("Payment failed.");
            return $response->json();
        }
    }

    public function verifyPayment($reference)
    {
        $url = "https://api.paystack.co/transaction/verify/{$reference}";
        $secret = config('services.paystack.secret');
        try {
            $response = Http::withOptions([
                'verify'=> false,
            ])
            ->withHeaders([
                'Authorization' => "Bearer {$secret}",
                'Cache-Control' => 'no-cache',
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Handle cases where the API request failed
                return [
                    'status' => false,
                    'message' => 'Unable to verify payment. Please try again later.'
                ];
            }
        } catch (\Exception $e) {
            // Handle exceptions like connection issues, etc.
            return [
                'status' => false,
                'message' => 'An error occurred while verifying the payment.',
                'error' => $e->getMessage()
            ];
        }
    }

}

