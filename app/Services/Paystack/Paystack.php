<?php
namespace App\Services\Paystack;

use App\Models\TransactionLog;
use App\Models\User;
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
        
        $user = User::where("email", $data["email"])->first();
        if($response['status']){
            TransactionLog::create([
                'user_id' => $user->id,
                "transaction_reference" => $response['data']['reference'],
                "amount" => $data['amount'],
                "status" => "pending",
                "message"=> "Subscription payment initialized",
            ]);
            return $response->json();

        }else{
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

