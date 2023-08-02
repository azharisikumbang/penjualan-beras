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
        'password' => '',
        'database' => 'bumdus_kanterleans',
        'port' => 3306
    ],
    'disk' => [
        'bukti_pembayaran' => __DIR__ . '/../storages/bukti-pembayaran/'
    ]
];
