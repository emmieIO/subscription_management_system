<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Paystack;

class PaymentController extends Controller
{
    protected $paystack;

    public function __construct(Paystack $paystack)
    {
        $this->paystack = $paystack;
    }
    public function initalize(Request $req){
        $payment = $this->paystack->initializeTransaction("2000", "markonuoha6@gmail.com");
    }
}
