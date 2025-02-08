<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('callback', [\App\Http\Controllers\CallbackController::class,'callback']);
Route::post('webhook', [\App\Http\Controllers\WebhookController::class,'index']);
Route::post('faberlic', [\App\Http\Controllers\FaberlicController::class,'index']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




