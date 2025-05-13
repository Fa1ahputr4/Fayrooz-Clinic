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
Route::get('/user', \App\Livewire\User\UserIndex::class)
    ->middleware('auth')
    ->name('user');

Route::get('/layanan', \App\Livewire\Layanan\LayananIndex::class)
    ->middleware('auth')
    ->name('layanan');

Route::get('/barang', \App\Livewire\Barang\BarangIndex::class)
    ->middleware('auth')
    ->name('barang');

Route::get('/barang-masuk', \App\Livewire\Barang\BarangMasukIndex::class)
    ->middleware('auth')
    ->name('barang-masuk');

Route::get('barang/riwayat-barang/{id}', \App\Livewire\Barang\RiwayatBarang::class)
    ->middleware('auth')
    ->name('barang.riwayat');

Route::get('rak', \App\Livewire\Rak\RakIndex::class)
    ->middleware('auth')
    ->name('rak');

Route::get('stok-rak', \App\Livewire\Rak\StokRakindex::class)
    ->middleware('auth')
    ->name('stok-rak');

Route::get('stok-rak/detail/{id}', \App\Livewire\Rak\StokRakDetail::class)
    ->middleware('auth')
    ->name('stok-rak.detail');

Route::get('/barang-keluar', \App\Livewire\Barang\BarangKeluarIndex::class)
    ->middleware('auth')
    ->name('barang-keluar');
