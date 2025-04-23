<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\UserManagement;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
// routes/web.php
Route::get('/user', function () {
    return view('user.index');
})->middleware(middleware: 'auth');

Route::get('/layanan', function () {
    return view('layanan.index');
})->middleware(middleware: 'auth');
