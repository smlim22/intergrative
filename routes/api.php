<?php
// filepath: routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\Payment\PaymentStatusApiController;
use App\Http\Controllers\Api\Booking\BookingApi;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

// ===== PUBLIC API ROUTES (No Authentication Required) =====
Route::prefix('facilities')->group(function () {
    // Get all facilities (with filters and pagination)
    Route::get('/', [FacilityController::class, 'apiIndex']);
    
    // Get single facility
    Route::get('/{facility}', [FacilityController::class, 'apiShow']);
    
    // Get facilities by category
    Route::get('/category/{category}', [FacilityController::class, 'apiByCategory']);
    
    // Search facilities
    Route::get('/search', [FacilityController::class, 'apiSearch']);
    
    // Get all categories
    Route::get('/categories', [FacilityController::class, 'apiCategories']);
    
    // Get facility statistics
    Route::get('/stats', [FacilityController::class, 'apiStats']);
});

// ===== ADMIN-ONLY API ROUTES (Authentication Required) =====
Route::middleware(['auth', 'role:admin'])->prefix('facilities')->group(function () {
    // Create new facility
    Route::post('/', [FacilityController::class, 'apiStore']);
    
    // Update facility
    Route::put('/{facility}', [FacilityController::class, 'apiUpdate']);
    
    // Disable facility
    Route::patch('/{facility}/disable', [FacilityController::class, 'apiDisable']);
    
    // Enable facility
    Route::patch('/{facility}/enable', [FacilityController::class, 'apiEnable']);
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
Route::post('/booking/check-availability', [BookingApi::class, 'checkAvail']);
Route::get('/booking/schedule', [BookingApi::class, 'getSchedule']);