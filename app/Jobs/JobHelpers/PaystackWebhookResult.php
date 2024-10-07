<?php

namespace app\Jobs\JobHelpers;

use App\Models\User;
use Carbon\Carbon;

class PaystackWebhookResult
{
    public static function success($payload){

        $em = $payload->data->customer->email;
        $user = User::where('email', $em)->first();
        if(!$user){
            return false;
        }
        $subscriptionCheck = $user->
        if($user->hasRole('free_user')){
            $user->removeRole('free_user');
            $user->assignRole('premium_user');
            $user->sub_expiresAt = Carbon::now()->addDays(30);

        }
        echo "success";
    }

}
