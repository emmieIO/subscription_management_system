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
})->middleware(['auth:sanctum','role:premium_user|admin']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
    Route::get('/payments/status/{ref}', [PaymentController::class,'checkStatus']);
});

Route::middleware(["auth:sanctum",'role:admin'])->prefix('plans')->group(function(){
    Route::get('',[PlanController::class, "index"]);
    Route::get('/{plan}',[PlanController::class, "show"]);
    Route::post("", [PlanController::class, 'store']);
    Route::put("/update/{plan}", [PlanController::class, 'update']);
    Route::delete('/destroy/{plan}', [PlanController::class, 'destroy']);
});

Route::post('/payments/webhook', [PaymentController::class,'webhook'])
->middleware('ip.whitelist');

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
