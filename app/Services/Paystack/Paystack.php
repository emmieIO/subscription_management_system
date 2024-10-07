<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Services\Paystack;

use App\Interfaces\PaymentGateWayInterface;
use App\Jobs\ProcessPaystackWebhook;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


class Paystack implements  PaymentGateWayInterface
{
    public function initializePayment($data): array
    {
        $response = Http::paystack()->post('transaction/initialize', $data);
        return $response->json();
    }

    public function verifyPayment($reference) : array
    {
        try {
            $response = Http::paystack()->get("/transaction/verify/$reference");
            return $response->json();
        } catch (\Exception $e) {
            // Handle exceptions like connection issues, etc.
            return response()->response_error($e->getMessage());
        }
    }

    public function verifyWebhook($request){
        $secret = config('services.paystack.secret');
        $signature = hash_hmac('sha512', $request->getContent(), $secret);
        $header = $request->header('x-paystack-signature');
        logger($signature);
        if($signature !== $header){
            return response()->response_error("Invalid signature", 400);
        }
        $payload = json_decode($request->getContent());
        ProcessPaystackWebhook::dispatch($payload);
        return response()->response_success("webhook connection success");
    }

}

