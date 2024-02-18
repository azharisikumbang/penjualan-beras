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
        'token' => 'EAAD8ipWTn4oBO17ZCodD8Kh3x49JRIIB1ZAwLPvRpboUB7uZBTtiymyrKU0teJVrQWCHsDRnR1YssqF1NFyxLF8wOKYleW6AGF0tPLsVot5TDHILgFn2X5Iu9DystMsz30lWextx33VZCOQLqwIEINCfRTUkdvPqYp7jDJfdDcRdPg1XKdrFfkowhWWMB7HCFvuBsrSYmcQ52YANBJsZD', // untuk uji coba berganti setiap 24 jam
        'sender_number' => '256438874210069', // whatsapp test phone number (ganti dengan nomor telepon seharusnya)
        'template' => 'promosi_kupon'
    ],
    'pdf' => [
        'ttd_nama' => 'Suhermen',
        'jabatan' => 'Pimpinan Bumdes',
        'ttd_kota' => 'Tanah Datar'
    ]
];
