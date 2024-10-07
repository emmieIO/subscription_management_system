<?php

namespace App\Services;

use App\Jobs\VerifyAndSaveTransaction;
use App\Interfaces\PaymentGateWayInterface;
use Illuminate\Http\Request;

class PaymentService
{
    protected PaymentGateWayInterface $paymentGateWay;
    public function __construct(PaymentGateWayInterface $paymentGateWay){
            $this->paymentGateWay = $paymentGateWay;
    }

    public function makePayment($data){
        $data->validated();
        $plan = PlanService::find($data->planId);
            return $this->paymentGateWay->initializePayment([
                'email' => $data->user()->email,
                'amount' => $plan->price * 100
                ]);
    }

    public function verifyPayment($reference){
        return $this->paymentGateWay->verifyPayment($reference);
    }
    public function handleWebhook($request){
        return $this->paymentGateWay->verifyWebhook($request);
    }
}
