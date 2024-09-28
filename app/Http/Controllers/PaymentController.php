<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function initialize(Request $req)
    {
        $req->validate([
            'amount' => 'required|numeric'
        ]);

        $payment = $this->paystackService->processPayment([
            "firs_tname" => $req->user()->firstname,
            "last_name" => $req->user()->lastname,
            "email" => $req->user()->email,
            "amount" => $req->amount * 100,
        ]);

        return $payment;
    }

    public function webHookHandler(Request $req)
    {
        $input = $req->getContent();
        $secret = config('services.paystack.secret');
        $signature = hash_hmac('sha512', $input, $secret);
        $header = $req->header('x-paystack-signature');

        if($signature !== $header){
            return response()->json([
                "message" => "Invalid signature"
            ], 400);
        }
        http_response_code(200);

        $response = json_decode($input);
        $event = $response->event;
        if($event == "charge.success"){
            $em = $response->data->customer->email;
            $user = User::where('email', $em)->first();
            if($user->hasRole('free_user')){
                $user->removeRole('free_user');
                $user->assignRole('premium_user');
                $user->role = 'premium_user';
                $user->save();
                // store transtion to trasactions table
            }
        };
    }

    public function verifyPaymentStatus($ref){
        if(!$ref){
            return response()->json([
                "message" => "reference code is required"
            ]);
        }
        $paymentVerification = $this->paystackService->verifyPayment($ref);
        Log::info("Checked payment status");
        return $paymentVerification;
    }
}