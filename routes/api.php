<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacilityApiController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\Payment\PaymentStatusApiController;
use App\Http\Controllers\Api\Booking\BookingApi;


Route::get('/facilities', [FacilityApiController::class, 'index']);
Route::get('/facilities/{facility}', [FacilityApiController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/facilities', [FacilityApiController::class, 'store']);
    Route::put('/facilities/{id}', [FacilityApiController::class, 'update']);
    Route::delete('/facilities/{id}', [FacilityApiController::class, 'destroy']);
});

Route::prefix('admin/users')->group(function () {
    // Admin routes
    Route::get('/', [AdminApiController::class, 'index']);
    Route::get('{user}', [AdminApiController::class, 'show']);
    Route::put('{user}', [AdminApiController::class, 'update']);
    Route::delete('{user}', [AdminApiController::class, 'destroy']);
    Route::patch('{user}/activate', [AdminApiController::class, 'activate']);
    Route::patch('{user}/deactivate', [AdminApiController::class, 'deactivate']);

});

Route::post('/send-whatsapp', [TwilioController::class, 'sendWhatsApp']);
Route::get('/payment-status', [PaymentStatusApiController::class, 'checkPayment']);
Route::get('/booking/check-availability', [BookingApi::class, 'checkAvail']);
Route::get('/booking/schedule', [BookingApi::class, 'getSchedule']);