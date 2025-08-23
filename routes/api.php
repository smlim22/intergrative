<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacilityApiController;
use App\Http\Controllers\TwilioController;

Route::get('/facilities', [FacilityApiController::class, 'index']);
Route::get('/facilities/{facility}', [FacilityApiController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/facilities', [FacilityApiController::class, 'store']);
    Route::put('/facilities/{id}', [FacilityApiController::class, 'update']);
    Route::delete('/facilities/{id}', [FacilityApiController::class, 'destroy']);
});

Route::post('/send-whatsapp', [TwilioController::class, 'sendWhatsApp']);