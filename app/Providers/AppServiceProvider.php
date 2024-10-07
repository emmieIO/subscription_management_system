<?php

namespace App\Providers;

use App\Interfaces\PaymentGateWayInterface;
use App\Services\Paystack\Paystack;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateWayInterface::class, Paystack::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('response_success',function(string $message, $data=null, int $status=200){
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ], $status);
        });

        Response::macro('response_error',function(string $message, int $status=400){
            return response()->json([
                'status' => 'error',
                'message' => $message
            ], $status);
        });

        Http::macro('paystack', function(){
            $secret = config('services.paystack.secret');
            return Http::withHeaders([
                'Authorization' => "Bearer $secret",
                'Cache-Control' => 'no-cache',
            ])->baseUrl(config('services.paystack.url'));
        });
    }
}
