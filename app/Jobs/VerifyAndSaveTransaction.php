<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Paystack\Paystack;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerifyAndSaveTransaction implements ShouldQueue
{
    use Queueable;
    public $payload;
    public $user_id;
    protected $paystack;

    /**
     * Create a new job instance.
     */
    public function __construct($payload, $user_id)
    {
        $this->payload = $payload;
        $this->user_id = $user_id;
        $this->paystack = new Paystack();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try { 
        $verifyTransaction = $this->paystack->verifyPayment($this->payload->reference);
        $res = json_decode(json_encode($verifyTransaction));
            $user = User::findOrFail($this->user_id);
            $user->transactions()->create([
                "reference"=> $this->payload->reference,
                "user_id"=> $this->user_id,
                "amount"=> $res->data->amount,
                "payment_link"=>$this->payload->authorization_url,
                "status"=> "pending",
            ]);
        } catch (\Exception $e) {
            Log::error("Transaction Error: " . $e->getMessage());
        }
    }
}
