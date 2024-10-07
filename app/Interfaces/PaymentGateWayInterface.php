<?php

namespace App\Interfaces;

use Illuminate\Http\Client\Response;

interface PaymentGateWayInterface
{
    public function initializePayment($data): array;
    public function verifyPayment($reference): array;
    public function verifyWebhook($request);
}
