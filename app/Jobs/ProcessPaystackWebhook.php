<?php

namespace App\Jobs;

use App\Jobs\JobHelpers\PaystackWebhookResult;
use App\Models\Transaction;
use App\Models\TransactionLog;
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
        switch ($event) {
            case 'charge.success':
                PaystackWebhookResult::success($this->payload);
                break;
            default:
                echo "failed";
                break;
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
