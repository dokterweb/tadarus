<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login',[LoginController::class, 'handleLogin'])->name('login');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
