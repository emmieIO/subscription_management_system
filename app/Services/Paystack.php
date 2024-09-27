<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Paystack
{
    protected $secretKey;
    public function __construct(){
        $this->secretKey = config("services.paystack.secret");
    }

    public function initializeTransaction($amount, $email): mixed
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $data = [
            "amount" => $amount,
            "email" => $email
        ];

        $response = Http::withHeaders(
            [
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Cache-Control' => 'no-cache',
            ])
            ->post($url, $data);

            if($response->successful()){
                return $response->json();
            }
            return $response->json();
    }
}