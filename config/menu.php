<?php

$icon = config('icon');

return [
    [
        'label' => 'Dashboard',
        'url'   => 'dashboard',
        'icon'  => $icon['dashboard'],
        'roles' => ['resepsionis', 'apoteker'], 
    ],
    [
        'label' => 'Pasien',
        'url'   => 'da',
        'icon'  => $icon['patient'],
        'roles' => [], 
    ],
    [
        'label' => 'Pendaftaran Pasien',
        'url'   => 'settings',
        'icon'  => $icon['registration-patient'],
        'roles' => [], 
    ],
    [
        'label' => 'Jurnal',
        'url'   => 'settings',
        'icon'  => $icon['journal'],
        'roles' => [], 
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
        'label' => 'Stock Barang',
        'url'   => '#',
        'icon'  => $icon['stock'],
        'roles' => [],
        'submenu' => [
            [
                'label' => 'Obat',
                'url' => 'dashboard',
                'icon' => $icon['drug'],
            ],
            [
                'label' => 'Produk Kecantikan',
                'url' => 'produk',
                'icon' => $icon['beauty-product'],
            ],
        ], 
    ],
];
