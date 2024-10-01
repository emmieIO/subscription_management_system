<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;

class ProcessPaystackWebhook implements ShouldQueue
{
    use Queueable;
    private $payload;
    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;

        $event = $this->payload->event;
        if ($event == "charge.success") {
            $em = $this->payload->data->customer->email;
            $user = User::where('email', $em)->first();
            if ($user->hasRole('free_user')) {
                $user->removeRole('free_user');
                $user->assignRole('premium_user');
                $user->save();
                $user->notify(new PaymentSuccess());

                // dispatch job to save to transaction_logs

                // dispatch job to update to transactions table
                
            }
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}