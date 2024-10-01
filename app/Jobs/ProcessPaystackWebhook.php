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
                $user->notify(new PaymentSuccess());

                $transaction = Transaction::where('reference', $this->payload->data->reference)->first();

                if ($transaction) {
                    $transaction->status = 'completed';
                    $transaction->payment_link = null;
                    $transaction->save();
                } else {
                    logger('Transaction not found for reference: ' . $this->payload->data->reference);
                }
                // send email notification
            }
        }
    }

    /**
     * Determine the number of seconds to wait before retrying the job.
     */
    public function backoff()
    {
        // Retry after 1 minute, 5 minutes, then 10 minutes
        return [60, 300, 600]; 
    }

    
    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The job failed to process.
     */
    public function failed(\Throwable $exception): void
    {
        logger('Webhook processing failed: ' . $exception->getMessage());
    }

}
