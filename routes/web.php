<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Auth;

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
