<?php

namespace App\Jobs\JobHelpers;

use App\Jobs\ProcessPaystackWebhook;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use Carbon\Carbon;
use App\Models\SubscriptionLog;
use App\Notifications\PaymentSuccess;


class PaystackWebhookResult extends ProcessPaystackWebhook
{
    public static function success($payload){

        $em = $payload->data->customer->email;
        $user = User::where('email', $em)->first();
        if(!$user){
            TransactionLog::create([
                'reference' => $payload->data->reference,
                'status' => $payload->data->status,
                'payload' => json_encode($payload),
                'error' => 'user not found',
            ]);
            throw new \Exception('user not found');
        }
        $subscriptionCheck = Carbon::parse($user->sub_expiresAt) < Carbon::now();
        if($subscriptionCheck){
            $user->sub_expiresAt  = Carbon::now()->addDays(30);
            $user->assignRole("premium_user");
            $user->removeRole("free_user");
        }else{
            $user->sub_expiresAt = Carbon::parse($user->sub_expiresAt)->addDays(30);
        }
        TransactionLog::create([
            'reference' => $payload->data->reference,
            'status' => $payload->data->status,
            'payload' => json_encode($payload),
            'user_id' => $user->id
        ]);
        $user->notify(new PaymentSuccess());
        $user->save();
    }
}
