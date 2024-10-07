<?php

namespace App\Jobs\JobHelpers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaystackWebhookResult
{
    public static function success($payload){

        $em = $payload->data->customer->email;
        $user = User::where('email', $em)->first();
        if(!$user){
            Log::error("User not found");
            return false;
        }
        $subscriptionCheck = Carbon::parse($user->sub_expiresAt) < Carbon::now();
        if($subscriptionCheck){
            $user->sub_expiresAt  = Carbon::now()->addDays(30);
            $user->asignRole("premium_user");
        }else{
            $user->sub_expiresAt = Carbon::parse($user->sub_expiresAt)->addDays(30);
        }
    }
}
