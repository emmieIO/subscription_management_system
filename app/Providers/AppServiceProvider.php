<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('response_success',function(string $message, array $data, int $status=200){
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
    }
}
