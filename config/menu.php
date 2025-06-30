<?php

$icon = config('icon');

return [
    [
        'section' => 'Utama',
        'items' => [
            ['label' => 'Dashboard', 'url' => 'dashboard', 'icon' => $icon['dashboard'], 'roles' => ['resepsionis', 'apoteker', 'dokter']],
            ['label' => 'Pasien', 'url' => 'pasien', 'icon' => $icon['patient'], 'roles' => ['resepsionis', 'dokter']],
        ],
    ],
    [
        'section' => 'Pelayanan',
        'items' => [
            ['label' => 'Pendaftaran Pasien', 'url' => 'pendaftaran', 'icon' => $icon['registration-patient'], 'roles' => ['resepsionis']],
            ['label' => 'Pemeriksaan Pasien', 'url' => 'antrian', 'icon' => $icon['pemeriksaan'], 'roles' => ['resepsionis', 'dokter']],
            ['label' => 'Permintaan Resep', 'url' => 'permintaan-resep', 'icon' => $icon['resep'], 'roles' => ['apoteker']],
        ],
    ],
    [
        'section' => 'Stok Barang',
        'items' => [
            ['label' => 'Rak', 'url' => 'rak', 'icon' => $icon['rak'], 'roles' => ['apoteker']],
            ['label' => 'Stok Rak', 'url' => 'stok-rak', 'icon' => $icon['stok-rak'], 'roles' => ['apoteker']],
            ['label' => 'Barang Masuk', 'url' => 'barang-masuk', 'icon' => $icon['barang-in'], 'roles' => ['apoteker']],
            ['label' => 'Barang Keluar', 'url' => 'barang-keluar', 'icon' => $icon['barang-out'], 'roles' => ['apoteker']],
        ],
    ],
    [
        'section' => 'Data Master',
        'items' => [
            ['label' => 'Obat & Produk', 'url' => 'barang', 'icon' => $icon['drug'], 'roles' => ['apoteker']],
            ['label' => 'User Management', 'url' => 'user', 'icon' => $icon['user'], 'roles' => []],
            ['label' => 'Layanan', 'url' => 'layanan', 'icon' => $icon['service'], 'roles' => []],
            ['label' => 'Keluhan', 'url' => 'keluhan', 'icon' => $icon['complaint'], 'roles' => ['dokter']],
            ['label' => 'Diagnosis', 'url' => 'diagnosis', 'icon' => $icon['diagnosis'], 'roles' => ['dokter']],
        ],
    ],
    [
        'section' => 'Sistem',
        'items' => [
            ['label' => 'Pengaturan WA', 'url' => 'whatsapp-api', 'icon' => $icon['chat-setting'], 'roles' => []],
            ['label' => 'Log Whatsapp', 'url' => 'log-whatsapp', 'icon' => $icon['log-message'], 'roles' => []],
        ],
    ],
];
