<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\UserManagement;
use App\Http\Middleware\RoleMiddleware;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', \App\Livewire\Dashboard\DashboardIndex::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // === User Management & WA Pengaturan (admin only)
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', \App\Livewire\User\UserIndex::class)->name('user');
        });

        Route::get('/layanan', \App\Livewire\Layanan\LayananIndex::class)->name('layanan');
        Route::get('/whatsapp-api', \App\Livewire\Whatsapp\PengaturanWa::class)->name('whatsapp-api');
    });

    // === Apotek: Admin, Apoteker, Staff
    Route::middleware([RoleMiddleware::class . ':admin,apoteker,staff'])->group(function () {
        Route::get('/permintaan-resep', \App\Livewire\Resep\PermintaanResep::class)->name('permintaan-resep');
        Route::get('/permintaan-resep/detail/{id}', \App\Livewire\Resep\DetailPermintaan::class)->name('permintaan-resep-detail');
        Route::get('/permintaan-produk-bc/detail/{id}', \App\Livewire\Resep\DetailPermintaanProdukBc::class)->name('permintaan-produk bc-detail');

        Route::get('/barang', \App\Livewire\Barang\BarangIndex::class)->name('barang');
        Route::get('/barang-masuk', \App\Livewire\Barang\BarangMasukIndex::class)->name('barang-masuk');
        Route::get('/barang/riwayat-barang/{id}', \App\Livewire\Barang\RiwayatBarang::class)->name('barang.riwayat');

        Route::get('/rak', \App\Livewire\Rak\RakIndex::class)->name('rak');
        Route::get('/stok-rak', \App\Livewire\Rak\StokRakIndex::class)->name('stok-rak');
        Route::get('/stok-rak/detail/{id}', \App\Livewire\Rak\StokRakDetail::class)->name('stok-rak.detail');

        Route::get('/barang-keluar', \App\Livewire\Barang\BarangKeluarIndex::class)->name('barang-keluar');
    });

    // === Pasien (admin, dokter, resepsionis, terapis)
    Route::get('/pasien', \App\Livewire\Pasien\PasienIndex::class)
        ->middleware([RoleMiddleware::class . ':admin,resepsionis,dokter,terapis'])
        ->name('pasien');

    // === Tambah/Edit Pasien (admin, resepsionis only)
    Route::get('/pasien/tambah', \App\Livewire\Pasien\PasienTambah::class)
        ->middleware([RoleMiddleware::class . ':admin,resepsionis'])
        ->name('pasien-tambah');

    Route::get('/pasien/edit/{id}', \App\Livewire\Pasien\PasienTambah::class)
        ->middleware([RoleMiddleware::class . ':admin,resepsionis,dokter'])
        ->name('pasien-edit');

    // === Pendaftaran (admin, resepsionis)
    Route::get('/pendaftaran', \App\Livewire\Pendaftaran\PendaftaranIndex::class)
        ->middleware([RoleMiddleware::class . ':admin,resepsionis'])
        ->name('pendaftaran');

    // === Antrian (admin, resepsionis, dokter, terapis)
    Route::get('/antrian', \App\Livewire\Pendaftaran\AntrianIndex::class)
        ->middleware([RoleMiddleware::class . ':admin,resepsionis,dokter,terapis'])
        ->name('antrian');

    // === Pemeriksaan dan Rekam Medis (admin, dokter, terapis)
    Route::middleware([RoleMiddleware::class . ':admin,dokter,terapis'])->group(function () {
        Route::get('/pemeriksaan/beautycare/{id}', \App\Livewire\Pemeriksaan\BeautyCare::class)->name('periksa-beautycare');
        Route::get('/pemeriksaan/umum/{id}', \App\Livewire\Pemeriksaan\KesehatanUmum::class)->name('periksa-umum');

        Route::get('/pasien/rekam-medis/beautycare/{id}', \App\Livewire\RekamMedis\BeautyCare::class)->name('rekmed-beautycare');
        Route::get('/pasien/rekam-medis/beautycare/detail/{id}', \App\Livewire\RekamMedis\DetailRekmedBeautycare::class)->name('rekmed-beautycare-detail');

        Route::get('/pasien/rekam-medis/umum/{id}', \App\Livewire\RekamMedis\KesehatanUmum::class)->name('rekmed-umum');
        Route::get('/pasien/rekam-medis/umum/detail/{id}', \App\Livewire\RekamMedis\DetailRekmedUmum::class)->name('rekmed-umum-detail');

        Route::get('/diagnosis', \App\Livewire\Diagnosis\DiagnosisIndex::class)->name('diagnosis');
        Route::get('/keluhan', \App\Livewire\Keluhan\KeluhanIndex::class)->name('keluhan');
        Route::get('/log-whatsapp', \App\Livewire\Whatsapp\LogWa::class)->name('log-whatsapp');
    });
});