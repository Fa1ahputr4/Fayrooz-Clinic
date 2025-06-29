<?php

$icon = config('icon');

return [
    [
        'label' => 'Dashboard',
        'url'   => 'dashboard',
        'icon'  => $icon['dashboard'],
        'roles' => ['resepsionis', 'apoteker', 'dokter'],
    ],
    [
        'label' => 'Pasien',
        'url'   => 'pasien',
        'icon'  => $icon['patient'],
        'roles' => ['resepsionis', 'dokter'],
    ],
    [
        'label' => 'Pendaftaran Pasien',
        'url'   => 'pendaftaran',
        'icon'  => $icon['registration-patient'],
        'roles' => ['resepsionis'],
    ],
    [
        'label' => 'Antrian Pasien',
        'url'   => 'antrian',
        'icon'  => $icon['registration-patient'],
        'roles' => ['resepsionis', 'dokter'],
    ],
    [
        'label' => 'Permintaan Resep',
        'url'   => 'permintaan-resep',
        'icon'  => $icon['registration-patient'],
        'roles' => ['apoteker'],
    ],
    [
        'label' => 'User Management',
        'url'   => 'user',
        'icon'  => $icon['user'],
        'roles' => [],
    ],

    [
        'label' => 'Layanan',
        'url'   => 'layanan',
        'icon'  => $icon['service'],
        'roles' => [],
    ],
    [
        'label' => 'Keluhan',
        'url'   => 'keluhan',
        'icon'  => $icon['service'],
        'roles' => ['dokter'],
    ],
    [
        'label' => 'Diagnosis',
        'url'   => 'diagnosis',
        'icon'  => $icon['service'],
        'roles' => ['dokter'],
    ],
    [
        'label' => 'Pengaturan WA',
        'url'   => 'whatsapp-api',
        'icon'  => $icon['service'],
        'roles' => [],
    ],
    [
        'label' => 'Log Whatsapp',
        'url'   => 'log-whatsapp',
        'icon'  => $icon['service'],
        'roles' => [],
    ],
    [
        'label' => 'Stock Barang',
        'url'   => '#',
        'icon'  => $icon['stock'],
        'roles' => ['apoteker'],
        'submenu' => [
            [
                'label' => 'Obat & Produk',
                'url' => 'barang',
                'icon' => $icon['drug'],
                'roles' => ['apoteker'],

            ],
            [
                'label' => 'Rak',
                'url' => 'rak',
                'icon' => $icon['beauty-product'],
                'roles' => ['apoteker'],
            ],
            [
                'label' => 'Stok Rak',
                'url' => 'stok-rak',
                'icon' => $icon['beauty-product'],
                'roles' => ['apoteker'],
            ],
            [
                'label' => 'Barang Masuk',
                'url' => 'barang-masuk',
                'icon' => $icon['beauty-product'],
                'roles' => ['apoteker'],
            ],

            [
                'label' => 'Barang Keluar',
                'url' => 'barang-keluar',
                'icon' => $icon['beauty-product'],
                'roles' => ['apoteker'],
            ],
        ],
    ],
];
