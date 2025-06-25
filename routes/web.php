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

Route::get('/pasien', \App\Livewire\Pasien\PasienIndex::class)
    ->middleware('auth')
    ->name('pasien');

Route::get('/pasien/tambah', \App\Livewire\Pasien\PasienTambah::class)
    ->middleware('auth')
    ->name('pasien-tambah');

Route::get('/pasien/edit/{id}', \App\Livewire\Pasien\PasienTambah::class)
    ->middleware('auth')
    ->name('pasien-edit');

Route::get('/pendaftaran', \App\Livewire\Pendaftaran\PendaftaranIndex::class)
    ->middleware('auth')
    ->name('pendaftaran');

Route::get('/pemeriksaan/beautycare/{id}', \App\Livewire\Pemeriksaan\BeautyCare::class)
    ->middleware('auth')
    ->name('periksa-beautycare');

Route::get('/pemeriksaan/umum/{id}', \App\Livewire\Pemeriksaan\KesehatanUmum::class)
    ->middleware('auth')
    ->name('periksa-umum');

Route::get('/antrian', \App\Livewire\Pendaftaran\AntrianIndex::class)
    ->middleware('auth')
    ->name('antrian');

Route::get('/permintaan-resep', \App\Livewire\Resep\PermintaanResep::class)
    ->middleware('auth')
    ->name('permintaan-resep');

Route::get('/permintaan-resep/detail/{id}', \App\Livewire\Resep\DetailPermintaan::class)
    ->middleware('auth')
    ->name('permintaan-resep-detail');

Route::get('/permintaan-produk-bc/detail/{id}', \App\Livewire\Resep\DetailPermintaanProdukBc::class)
    ->middleware('auth')
    ->name('permintaan-produkbc-detail');

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

Route::get('stok-rak', \App\Livewire\Rak\StokRakIndex::class)
    ->middleware('auth')
    ->name('stok-rak');

Route::get('stok-rak/detail/{id}', \App\Livewire\Rak\StokRakDetail::class)
    ->middleware('auth')
    ->name('stok-rak.detail');

Route::get('/barang-keluar', \App\Livewire\Barang\BarangKeluarIndex::class)
    ->middleware('auth')
    ->name('barang-keluar');
