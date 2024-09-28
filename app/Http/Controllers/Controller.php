<?php

namespace App\Http\Controllers;

use App\Services\Paystack\Paystack;

abstract class Controller
{
    protected $paystackService;
    // constructor
    public function __construct()
    {
        $this->paystackService = new Paystack();
    }
}
