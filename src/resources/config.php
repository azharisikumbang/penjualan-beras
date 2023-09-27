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
        'username' => 'bumdes_kanterleans',
        'password' => '12345678',
        'database' => 'bumdes_kanterleans',
        'port' => 3306
    ],
    'disk' => [
        'bukti_pembayaran' => __DIR__ . '/../storages/bukti-pembayaran/'
    ],
    'whatsapp'=> [
        'token' => 'EAASxysSBPZAQBO2iqNqD7jzZAZCh0gdZCnc0cdxbZBZCei4wr7epAp2JedFgZCazGdUsFiZA3iaX1AZB6IlKZBKXBQqDwx5HApFhnpQ4qVIVTwDbeMNapZCpexZBJapLwISMRvamJZCBusMvcpZBxQegdmZBo3erJrnTtUogbwseJPMi76bfEGGq3EX67NAjSOCM4lMwaMwD0qgSo9TyZBwZC5AURBH93PEst6OlXMEhnPbUZD', // untuk uji coba berganti setiap 24 jam
        'sender_number' => '107920529067316', // whatsapp test phone number (ganti dengan nomor telepon seharusnya)
        'template' => 'promosi_kupon'
    ],
    'pdf' => [
        'ttd_nama' => 'Suhermen',
        'jabatan' => 'Pimpinan Bumdes',
        'ttd_kota' => 'Tanah Datar'
    ]
];
