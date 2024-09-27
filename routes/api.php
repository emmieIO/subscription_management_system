<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function(Request $req){
    return response()->json([
        'message' => 'Welcome to the API'
    ]);
})->middleware(['auth:sanctum','role:free_user|premium_user|admin']);


Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
