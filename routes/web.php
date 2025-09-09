<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\TwilioController;

Route::get('/checkout', [PayPalController::class, 'checkout'])->name('checkout');
Route::get('/payment/success', [PayPalController::class, 'success'])->name('paypal.success');// returns JSON
Route::get('/payment/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
Route::get('/whatsappTest', [TwilioController::class, 'testForm'])->name('whatsapp.test');
// WhatsApp Text
Route::post('/whatsapp/send', [TwilioController::class, 'sendWhatsApp'])->name('whatsapp.send');

// WhatsApp Invoice PDF
Route::post('/whatsapp/invoice', [TwilioController::class, 'sendInvoicePdf'])->name('whatsapp.invoice');



/**
 * Dummy login route to stop redirect error during testing.
 */
Route::get('/login', function () {
    return redirect('/facilities');
})->name('login');

/**
 * Public route to view facilities (no login needed)
 */
Route::get('/', [FacilityController::class, 'index']);
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');

/**
 * Admin-only routes (require auth and authorization)
 */
Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create')->middleware(['auth', 'role:admin']);
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store')->middleware(['auth', 'role:admin']);
Route::get('/facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit')->middleware(['auth', 'role:admin']);
Route::put('/facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update')->middleware(['auth', 'role:admin']);
Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy')->middleware(['auth', 'role:admin']);

Route::get('admin/users', [AdminController::class, 'users'])->name('users.index')->middleware(['auth', 'role:admin']);
Route::post('/admin/users/{user}/activate', [AccountController::class, 'activate'])->name('admin.users.activate')->middleware(['auth', 'role:admin']);
Route::post('/admin/users/{user}/deactivate', [AccountController::class, 'deactivate'])->name('admin.users.deactivate')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/{user}', [AdminController::class, 'viewUser'])->name('admin.users.view')->middleware(['auth', 'role:admin']);

Route::get('/', fn() => view('welcome', ['user' => Auth::user()]));

Route::get('/register', [AccountController::class, 'showRegister'])->name('register');
Route::post('/register', [AccountController::class, 'register']);

Route::get('/login', [AccountController::class, 'showLogin'])->name('login');
Route::post('/login', [AccountController::class, 'login']);

Route::post('/logout', [AccountController::class, 'logout'])->name('logout');

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'role:admin']);
Route::get('/student', [StudentController::class, 'index'])->middleware(['auth', 'role:student']);
Route::get('/public', [PublicController::class, 'index'])->middleware(['auth', 'role:public']);

// Route::get('/admin', function () {
//     return view('admin');
// })->middleware('auth');


// PayPal
Route::post('/paypal/pay', [PayPalController::class, 'pay'])->name('paypal.pay');

// WhatsApp
Route::post('/whatsapp/send', [TwilioController::class, 'sendWhatsApp'])->name('whatsapp.send');