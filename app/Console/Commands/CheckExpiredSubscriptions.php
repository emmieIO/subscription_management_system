<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\SubscriptionExpired;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'downgrade premium users to free users on subscription expiration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         // Get the current time
         $now = Carbon::now();
         $users = User::where('sub_expiresAt', "<=", $now)->get();
            foreach ($users as $user) {
                $user->removeRole('premium_user');
                $user->assignRole('free_user');
                $user->sub_expiresAt = null;
                $user->save();
                // notify user via email
                $user->notify(new SubscriptionExpired());
            }



    }
}
