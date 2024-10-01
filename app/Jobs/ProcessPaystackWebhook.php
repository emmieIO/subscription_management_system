<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use App\Notifications\PaymentSuccess;
use Carbon\Carbon;

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

        
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $event = $this->payload->event;
        logger($event);
        if ($event == "charge.success") {
            $em = $this->payload->data->customer->email;
            $user = User::where('email', $em)->first();
            if ($user->hasRole('free_user')) {
                $user->removeRole('free_user');
                $user->assignRole('premium_user');
                $user->sub_expiresAt = Carbon::now()->addDays(30);
                $user->role = "premium_user";
                $user->save();


                $transaction = Transaction::where('reference', $this->payload->data->reference)->first();

                if ($transaction) {
                    $transaction->status = 'success';
                    $transaction->payment_link = null;
                    $transaction->save();
                } else {
                    logger('Transaction not found for reference: ' . $this->payload->data->reference);
                }
                // send email notification
                $user->notify(new PaymentSuccess());
            }
        }
    }
}
