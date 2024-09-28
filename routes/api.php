<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;



Route::get('/', function(Request $req){
    return response()->json([
        'message' => 'Welcome to the API'
    ]);

})->middleware(['auth:sanctum','role:free_user|premium_user|admin']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
    Route::get('/payments/status/{ref}', [PaymentController::class,'verifyPaymentStatus']);
});

Route::post('/payments/webhook', [PaymentController::class,'webHookHandler']);
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
