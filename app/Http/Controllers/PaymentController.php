<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPaystackWebhook;
use App\Models\Transaction;
use App\Jobs\VerifyAndSaveTransaction;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function initialize(Request $req)
    {
        $req->validate([
            'amount' => 'required|numeric'
        ]);
        // check if the user is already a premium user
        if ($req->user()->hasRole('premium_user')) {
            return response()->json([
                "message" => "You are already a premium user"
            ], 400);
        }

        $payment = $this->paystackService->processPayment([
            "first_name" => $req->user()->firstname,
            "last_name" => $req->user()->lastname,
            "email" => $req->user()->email,
            "amount" => $req->amount * 100,
        ]);

        // Convert the payment array to a JSON object
        $paymentJson = json_decode(json_encode($payment));

        if (isset($paymentJson->status) && $paymentJson->status) {
            //  dispatch a background process to verify the transaction and save it
            VerifyAndSaveTransaction::dispatch($paymentJson->data, $req->user()->id);
        }

        // Return JSON response
        return response()->json($payment);
    }

    public function webHookHandler(Request $req)
    {
        $input = $req->getContent();
        $secret = config('services.paystack.secret');
        $signature = hash_hmac('sha512', $input, $secret);
        logger($signature);
        $header = $req->header('x-paystack-signature');

        if ($signature !== $header) {
            return response()->json([
                "message" => "Invalid signature"
            ], 400);
        }

        $response = json_decode($input);
        ProcessPaystackWebhook::dispatch($response);
        return response()->json(['status' => 'success'], 200);
    }

    public function verifyPaymentStatus(Request $req, $ref)
    {
        if (!$ref) {
            return response()->json([
                "message" => "reference code is required"
            ]);
        }

        $paymentVerification = $this->paystackService->verifyPayment($ref);
        Log::info("Checked payment status");
        // Send a notification to the user
        $paymentResponse = json_decode(json_encode($paymentVerification));
        if($req->user()->email != $paymentResponse->data->customer->email){
           return response()->json([
                "message" => "Forbidden",
                "status" => false
            ], 403);
        }
        return $paymentResponse;
    }
}