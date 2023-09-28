<?php

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();

?>
<main>
    <div class="p-6 bg-white rounded-lg border">
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Beranda</h1>
            <p>Selamat datang, untuk melihat riwayat pemesanan anda ada di menu 'Riwayat Pembelian' atau silahkan buat pembelian baru di menu Pesan Beras.</p>
        </div>
    </div>
</main>
