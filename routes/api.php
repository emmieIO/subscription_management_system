<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function(Request $req){
    return response()->json([
        'message' => 'Welcome to the API'
    ]);
});
