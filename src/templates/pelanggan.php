<?php

/* TODO: change logo */

$route = strtolower(get_current_route());
/** @var $akun Akun */ $akun = session()->auth();

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-800">
    <nav class="bg-white border-gray-200">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4 border-b border-gray-200">
            <a href="https://flowbite.com" class="flex items-center">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">BUMDUS Kanterleans</span>
            </a>
            <div class="flex items-center">
                <span class="mr-6 text-sm  text-gray-500 italic">login sebagai <?= $akun->getUsername() ?></span>
                <a href="<?= site_url('logout') ?>" class="text-sm text-white bg-gray-500 rounded px-4 py-1 hover:bg-orange-500">Log out</a>
            </div>
        </div>
    </nav>
    <nav class="bg-white">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-4">
                    <li>
                        <a href="<?= site_url('pelanggan/beranda') ?>" aria-current="page" class="<?= $route == 'pelanggan/beranda' ? 'bg-orange-600 rounded text-white' : 'text-gray-600 hover:text-orange-600 hover:underline' ?> text-sm px-2 py-1">Beranda</a>
                    </li>
                    <li>
                        <a href="<?= site_url('pelanggan/pesan') ?>" aria-current="page" class="<?= $route == 'pelanggan/pesan' ? 'bg-orange-600 rounded text-white' : 'text-gray-600 hover:text-orange-600 hover:underline' ?> text-sm px-2 py-1">Pesan Beras</a>
                    </li>
                    <li>
                        <a href="<?= site_url('pelanggan/keranjang') ?>" aria-current="page" class="<?= $route == 'pelanggan/keranjang' ? 'bg-orange-600 rounded text-white' : 'text-gray-600 hover:text-orange-600 hover:underline' ?> text-sm px-2 py-1">Keranjang</a>
                    </li>
                    <li>
                        <a href="<?= site_url('pelanggan/riwayat') ?>" aria-current="page" class="<?= $route == 'pelanggan/riwayat' || $route == 'pelanggan/riwayat/detail' ? 'bg-orange-600 rounded text-white' : 'text-gray-600 hover:text-orange-600 hover:underline' ?> text-sm px-2 py-1">Riwayat Pembelian</a>
                    </li>
                    <li>
                        <a href="#" aria-current="page" class="<?= $route == 'pelanggan/pengaturan' ? 'bg-orange-600 rounded text-white' : 'text-gray-600 hover:text-orange-600 hover:underline' ?> text-sm px-2 py-1">Pengaturan Akun</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- main -->
    <div class="py-8 bg-gray-50 w-full">
        <div class="max-w-screen-2xl mx-auto">
            <?php require_once $content; ?>
        </div>
    </div>
    <footer class="text-center text-gray-400 my-8">
        &copy; <?= date('Y') ?> - BUMDUS Kanterleans Padang Panjang<br>
        Kontak Person: 0812 1345 1544 (Amel)
    </footer>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</body>
</html>