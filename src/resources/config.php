<?php

return [
    'app' => [
        'site_title' => 'BUMDUS KANTERLEANS',
        'base_dir' => __DIR__ . '/../../',
        'site_url' => 'http://localhost/penjualan-beras/public/',
        'base_url' => 'http://localhost/penjualan-beras',
    ],
    'database' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'bumdes_kanterleans',
        'port' => 3306
    ],
    'disk' => [
        'bukti_pembayaran' => __DIR__ . '/../storages/bukti-pembayaran/'
    ],
    'whatsapp' => [
        'token' => 'TOKEN_WHATSAPP', // untuk uji coba berganti setiap 24 jam
        'sender_number' => 'NOMOR_SENDER', // whatsapp test phone number (ganti dengan nomor telepon seharusnya)
        'template' => 'promosi_kupon'
    ],
    'pdf' => [
        'ttd_nama' => 'Suhermen',
        'jabatan' => 'Pimpinan Bumdus',
        'ttd_kota' => 'Tanah Datar'
    ]
];
