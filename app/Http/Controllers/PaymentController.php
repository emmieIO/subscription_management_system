<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\initializePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;


;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function initialize(InitializePaymentRequest $req)
    {
        return $this->paymentService->makePayment($req);
    }

    public function checkStatus($reference){
        return $this->paymentService->verifyPayment($reference);
    }

    public function webhook(Request $request){
        return $this->paymentService->handleWebhook($request);
    }


}
