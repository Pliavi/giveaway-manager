<?php

use App\Http\Controllers\GiveawayController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('giveaway', GiveawayController::class);
});

Route::apiResource('login', LoginController::class);
