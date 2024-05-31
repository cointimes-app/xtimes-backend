<?php

use App\Http\Controllers\BalanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\AiController;

Route::post('/wallet/register', [UserController::class, 'register']);
Route::post('/ai', [AiController::class, 'categorize']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/data/profile', [DataController::class, 'getProfile']);
    Route::post('/data/profile', [DataController::class, 'saveUserProfile']);
    Route::get('/balance', [BalanceController::class, 'getBalance']);
    Route::post('/balance/withdraw', [WithdrawController::class, 'withdraw']);
});




