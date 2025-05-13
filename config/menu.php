<?php

$icon = config('icon');

return [
    [
        'label' => 'Dashboard',
        'url'   => 'dashboard',
        'icon'  => $icon['dashboard'],
        'roles' => ['resepsionis', 'apoteker'], 
    ],
    // [
    //     'label' => 'Pasien',
    //     'url'   => '#',
    //     'icon'  => $icon['patient'],
    //     'roles' => [], 
    // ],
    // [
    //     'label' => 'Pendaftaran Pasien',
    //     'url'   => '#s',
    //     'icon'  => $icon['registration-patient'],
    //     'roles' => [], 
    // ],
    // [
    //     'label' => 'Jurnal',
    //     'url'   => '#',
    //     'icon'  => $icon['journal'],
    //     'roles' => [], 
    // ],
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
        'label' => 'Stock Barang',
        'url'   => '#',
        'icon'  => $icon['stock'],
        'roles' => [],
        'submenu' => [
            [
                'label' => 'Obat & Produk',
                'url' => 'barang',
                'icon' => $icon['drug'],
            ],
            [
                'label' => 'Rak',
                'url' => 'rak',
                'icon' => $icon['beauty-product'],
            ],
            [
                'label' => 'Stok Rak',
                'url' => 'stok-rak',
                'icon' => $icon['beauty-product'],
            ],
            [
                'label' => 'Barang Masuk',
                'url' => 'barang-masuk',
                'icon' => $icon['beauty-product'],
            ],
            
            [
                'label' => 'Barang Keluar',
                'url' => 'barang-keluar',
                'icon' => $icon['beauty-product'],
            ],
        ], 
    ],
];
