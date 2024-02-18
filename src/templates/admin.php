<?php /** @var $akun Akun */ $akun = session()->auth(); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bumdes Kanterleas - Panel Administrator</title>
    <script src="<?= assets('js/alpinejs.min.js') ?>" defer></script>
    <link rel="stylesheet" href="<?= assets('css/build.css') ?>">
</head>
<body class="bg-gray-50 dark:bg-gray-800" x-data="global">
    <nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex">
                    <span @click="toggleSidebar" class="hover:bg-gray-200 flex items-center border rounded px-2 cursor-pointer">
                        <svg class="w-5" viewBox="0 0 20 20">
                            <path fill="gray" d="M3.314,4.8h13.372c0.41,0,0.743-0.333,0.743-0.743c0-0.41-0.333-0.743-0.743-0.743H3.314
                                    c-0.41,0-0.743,0.333-0.743,0.743C2.571,4.467,2.904,4.8,3.314,4.8z M16.686,15.2H3.314c-0.41,0-0.743,0.333-0.743,0.743
                                    s0.333,0.743,0.743,0.743h13.372c0.41,0,0.743-0.333,0.743-0.743S17.096,15.2,16.686,15.2z M16.686,9.257H3.314
                                    c-0.41,0-0.743,0.333-0.743,0.743s0.333,0.743,0.743,0.743h13.372c0.41,0,0.743-0.333,0.743-0.743S17.096,9.257,16.686,9.257z"></path>
                        </svg>
                    </span>
                    <a href="<?= site_url() ?>" class="flex text-gray-600 ml-2 md:mr-24">
    <!--                    <img src="https://flowbite-admin-dashboard.vercel.app/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo">-->
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">BUMDES - Admin Panel</span>
                    </a>
                </div>
                <div class="italic text-sm text-gray-400">
                    <span>Login sebagai <?= $akun->getUsername() ?></span> -
                    <span class="hover:text-red-800 hover:underline text-red-500 cursor-pointer" @click="window.location.reload()">muat ulang halaman</span>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex pt-14 overflow-hidden bg-gray-50">
        <aside x-show="sites.show_sidebar" class="fixed transition-all top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-14 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
            <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <ul class="pb-2 space-y-2">
                            <li>
                                <a href="<?= site_url('admin/dashboard') ?>" class="flex font-semibold items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/pesanan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                        <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Pesanan Masuk</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/kelola/stok') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 10H1m0 0 3-3m-3 3 3 3m1-9h10m0 0-3 3m3-3-3-3"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Perbaharui Stok</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/broadcast-promo') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path d="M18 4H16V9C16 10.0609 15.5786 11.0783 14.8284 11.8284C14.0783 12.5786 13.0609 13 12 13H9L6.846 14.615C7.17993 14.8628 7.58418 14.9977 8 15H11.667L15.4 17.8C15.5731 17.9298 15.7836 18 16 18C16.2652 18 16.5196 17.8946 16.7071 17.7071C16.8946 17.5196 17 17.2652 17 17V15H18C18.5304 15 19.0391 14.7893 19.4142 14.4142C19.7893 14.0391 20 13.5304 20 13V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4Z" fill="currentColor"/>
                                        <path d="M12 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V9C0 9.53043 0.210714 10.0391 0.585786 10.4142C0.960859 10.7893 1.46957 11 2 11H3V13C3 13.1857 3.05171 13.3678 3.14935 13.5257C3.24698 13.6837 3.38668 13.8114 3.55279 13.8944C3.71889 13.9775 3.90484 14.0126 4.08981 13.996C4.27477 13.9793 4.45143 13.9114 4.6 13.8L8.333 11H12C12.5304 11 13.0391 10.7893 13.4142 10.4142C13.7893 10.0391 14 9.53043 14 9V2C14 1.46957 13.7893 0.960859 13.4142 0.585786C13.0391 0.210714 12.5304 0 12 0Z" fill="currentColor"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Broadcast Promo</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="py-2 space-y-2">
                            <li x-data="{ show: false }">
                                <button @click="show = !show" type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group" aria-controls="dropdown-layouts" data-collapse-toggle="dropdown-layouts">
                                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                                    <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item="">Kelola Data Beras</span>
                                    <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                                <ul id="dropdown-layouts" style="display: none" class="py-2 space-y-2 bg-gray-200 rounded px-2" x-show="show">
                                    <li>
                                        <a href="<?= site_url('admin/kelola/beras') ?>" class="flex items-center py-2 pl-10 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Jenis Beras</a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url('admin/kelola/takaran') ?>" class="flex items-center py-2 pl-10 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                            Jenis Takaran (Kiloan)
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/promo/buat') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path fill="currentColor" d="M4.3,15.249H3.428c-0.241,0-0.436,0.195-0.436,0.436c0,0.241,0.195,0.437,0.436,0.437H4.3c0.241,0,0.436-0.195,0.436-0.437C4.736,15.444,4.541,15.249,4.3,15.249 M6.916,15.249H6.044c-0.241,0-0.436,0.195-0.436,0.436c0,0.241,0.195,0.437,0.436,0.437h0.872c0.241,0,0.436-0.195,0.436-0.437C7.352,15.444,7.157,15.249,6.916,15.249 M13.894,8.271h0.872c0.241,0,0.437-0.195,0.437-0.437c0-0.241-0.195-0.436-0.437-0.436h-0.872c-0.241,0-0.437,0.194-0.437,0.436C13.457,8.077,13.652,8.271,13.894,8.271 M4.3,7.399H3.428c-0.241,0-0.436,0.194-0.436,0.436c0,0.242,0.195,0.437,0.436,0.437H4.3c0.241,0,0.436-0.195,0.436-0.437C4.736,7.594,4.541,7.399,4.3,7.399 M15.638,11.324c-0.241,0-0.436,0.194-0.436,0.436s0.194,0.437,0.436,0.437s0.437-0.195,0.437-0.437S15.879,11.324,15.638,11.324 M14.766,15.249h-0.872c-0.241,0-0.437,0.195-0.437,0.436c0,0.241,0.195,0.437,0.437,0.437h0.872c0.241,0,0.437-0.195,0.437-0.437C15.202,15.444,15.007,15.249,14.766,15.249 M12.149,7.399h-0.872c-0.241,0-0.437,0.194-0.437,0.436c0,0.242,0.195,0.437,0.437,0.437h0.872c0.24,0,0.436-0.195,0.436-0.437C12.585,7.594,12.39,7.399,12.149,7.399 M17.818,9.144V5.655c0-0.939-0.745-1.7-1.676-1.737l-0.104-0.859L9.276,3.88L2.824,2.151l-0.471,1.76H2.119c-0.963,0-1.744,0.781-1.744,1.744v10.466c0,0.963,0.781,1.744,1.744,1.744h13.955c0.963,0,1.744-0.781,1.744-1.744v-1.744c0.963,0,1.744-0.781,1.744-1.745v-1.744C19.562,9.925,18.781,9.144,17.818,9.144 M16.946,5.655v0.242c-0.18-0.104-0.377-0.178-0.589-0.213L16.25,4.801C16.646,4.882,16.946,5.234,16.946,5.655 M15.277,4.029l0.184,1.507l-3.929-1.052L15.277,4.029z M3.44,3.219l9.09,2.436H2.788L3.44,3.219z M1.247,5.655c0-0.481,0.39-0.872,0.871-0.872l-0.24,0.896C1.65,5.711,1.438,5.786,1.247,5.897V5.655z M16.946,16.121c0,0.48-0.392,0.872-0.872,0.872H2.119c-0.482,0-0.872-0.392-0.872-0.872V7.399c0-0.481,0.39-0.872,0.872-0.872h13.955c0.48,0,0.872,0.391,0.872,0.872v1.744h-1.744c-0.964,0-1.745,0.781-1.745,1.744v1.744c0,0.964,0.781,1.745,1.745,1.745h1.744V16.121z M18.69,12.632c0,0.481-0.392,0.873-0.872,0.873h-2.616c-0.482,0-0.873-0.392-0.873-0.873v-1.744c0-0.481,0.391-0.872,0.873-0.872h2.616c0.48,0,0.872,0.391,0.872,0.872V12.632z M12.149,15.249h-0.872c-0.241,0-0.437,0.195-0.437,0.436c0,0.241,0.195,0.437,0.437,0.437h0.872c0.24,0,0.436-0.195,0.436-0.437C12.585,15.444,12.39,15.249,12.149,15.249 M9.533,15.249H8.661c-0.241,0-0.436,0.195-0.436,0.436c0,0.241,0.195,0.437,0.436,0.437h0.872c0.241,0,0.436-0.195,0.436-0.437C9.969,15.444,9.774,15.249,9.533,15.249 M6.916,7.399H6.044c-0.241,0-0.436,0.194-0.436,0.436c0,0.242,0.195,0.437,0.436,0.437h0.872c0.241,0,0.436-0.195,0.436-0.437C7.352,7.594,7.157,7.399,6.916,7.399 M9.533,7.399H8.661c-0.241,0-0.436,0.194-0.436,0.436c0,0.242,0.195,0.437,0.436,0.437h0.872c0.241,0,0.436-0.195,0.436-0.437C9.969,7.594,9.774,7.399,9.533,7.399"></path>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Kelola Promo</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/pelanggan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                                        <path d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
                                        <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Kelola Data Pelanggan</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="py-2 space-y-2">
                            <li>
                                <a href="<?= site_url('admin/laporan/laporan-pemasukan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                        <path d="M4.319,8.257c-0.242,0-0.438,0.196-0.438,0.438c0,0.243,0.196,0.438,0.438,0.438c0.242,0,0.438-0.196,0.438-0.438C4.757,8.454,4.561,8.257,4.319,8.257 M7.599,10.396c0,0.08,0.017,0.148,0.05,0.204c0.034,0.056,0.076,0.104,0.129,0.144c0.051,0.04,0.112,0.072,0.182,0.097c0.041,0.015,0.068,0.028,0.098,0.04V9.918C7.925,9.927,7.832,9.958,7.747,10.02C7.648,10.095,7.599,10.22,7.599,10.396 M15.274,6.505H1.252c-0.484,0-0.876,0.392-0.876,0.876v7.887c0,0.484,0.392,0.876,0.876,0.876h14.022c0.483,0,0.876-0.392,0.876-0.876V7.381C16.15,6.897,15.758,6.505,15.274,6.505M1.69,7.381c0.242,0,0.438,0.196,0.438,0.438S1.932,8.257,1.69,8.257c-0.242,0-0.438-0.196-0.438-0.438S1.448,7.381,1.69,7.381M1.69,15.269c-0.242,0-0.438-0.196-0.438-0.438s0.196-0.438,0.438-0.438c0.242,0,0.438,0.195,0.438,0.438S1.932,15.269,1.69,15.269M14.836,15.269c-0.242,0-0.438-0.196-0.438-0.438s0.196-0.438,0.438-0.438s0.438,0.195,0.438,0.438S15.078,15.269,14.836,15.269M15.274,13.596c-0.138-0.049-0.283-0.08-0.438-0.08c-0.726,0-1.314,0.589-1.314,1.314c0,0.155,0.031,0.301,0.08,0.438H2.924c0.049-0.138,0.081-0.283,0.081-0.438c0-0.726-0.589-1.314-1.315-1.314c-0.155,0-0.3,0.031-0.438,0.08V9.053C1.39,9.103,1.535,9.134,1.69,9.134c0.726,0,1.315-0.588,1.315-1.314c0-0.155-0.032-0.301-0.081-0.438h10.678c-0.049,0.137-0.08,0.283-0.08,0.438c0,0.726,0.589,1.314,1.314,1.314c0.155,0,0.301-0.031,0.438-0.081V13.596z M14.836,8.257c-0.242,0-0.438-0.196-0.438-0.438s0.196-0.438,0.438-0.438s0.438,0.196,0.438,0.438S15.078,8.257,14.836,8.257 M12.207,13.516c-0.242,0-0.438,0.196-0.438,0.438s0.196,0.438,0.438,0.438s0.438-0.196,0.438-0.438S12.449,13.516,12.207,13.516 M8.812,11.746c-0.059-0.043-0.126-0.078-0.199-0.104c-0.047-0.017-0.081-0.031-0.117-0.047v1.12c0.137-0.021,0.237-0.064,0.336-0.143c0.116-0.09,0.174-0.235,0.174-0.435c0-0.092-0.018-0.17-0.053-0.233C8.918,11.842,8.87,11.788,8.812,11.746 M18.78,3.875H4.757c-0.484,0-0.876,0.392-0.876,0.876V5.19c0,0.242,0.196,0.438,0.438,0.438c0.242,0,0.438-0.196,0.438-0.438V4.752H18.78v7.888h-1.315c-0.242,0-0.438,0.196-0.438,0.438c0,0.243,0.195,0.438,0.438,0.438h1.315c0.483,0,0.876-0.393,0.876-0.876V4.752C19.656,4.268,19.264,3.875,18.78,3.875 M8.263,8.257c-1.694,0-3.067,1.374-3.067,3.067c0,1.695,1.373,3.068,3.067,3.068c1.695,0,3.067-1.373,3.067-3.068C11.33,9.631,9.958,8.257,8.263,8.257 M9.488,12.543c-0.062,0.137-0.147,0.251-0.255,0.342c-0.108,0.092-0.234,0.161-0.378,0.209c-0.123,0.041-0.229,0.063-0.359,0.075v0.347H8.058v-0.347c-0.143-0.009-0.258-0.032-0.388-0.078c-0.152-0.053-0.281-0.128-0.388-0.226c-0.108-0.098-0.191-0.217-0.25-0.359c-0.059-0.143-0.087-0.307-0.083-0.492h0.575c-0.004,0.219,0.046,0.391,0.146,0.518c0.088,0.109,0.207,0.165,0.388,0.185v-1.211c-0.102-0.031-0.189-0.067-0.3-0.109c-0.136-0.051-0.259-0.116-0.368-0.198c-0.109-0.082-0.198-0.183-0.265-0.306c-0.067-0.123-0.101-0.275-0.101-0.457c0-0.159,0.031-0.298,0.093-0.419c0.062-0.121,0.146-0.222,0.252-0.303S7.597,9.57,7.735,9.527C7.85,9.491,7.944,9.474,8.058,9.468V9.134h0.438v0.333c0.114,0.005,0.207,0.021,0.319,0.054c0.134,0.04,0.251,0.099,0.351,0.179c0.099,0.079,0.178,0.18,0.237,0.303c0.059,0.122,0.088,0.265,0.088,0.427H8.916c-0.007-0.169-0.051-0.297-0.134-0.387C8.712,9.968,8.626,9.932,8.496,9.919v1.059c0.116,0.035,0.213,0.074,0.333,0.118c0.145,0.053,0.272,0.121,0.383,0.203c0.111,0.083,0.2,0.186,0.268,0.308c0.067,0.123,0.101,0.273,0.101,0.453C9.581,12.244,9.549,12.406,9.488,12.543"></path>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Laporan Pemasukan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/laporan/laporan-penjualan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                        <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Laporan Penjualan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('admin/laporan/laporan-stok') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 10H1m0 0 3-3m-3 3 3 3m1-9h10m0 0-3 3m3-3-3-3"/>
                                    </svg>
                                    <span class="ml-3" sidebar-toggle-item="">Laporan Stok Beras</span>
                                </a>
                            </li>
                        </ul>
                        <div class="pt-2 space-y-2">
                            <a href="<?= site_url('admin/ganti-password') ?>" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                                    <path d="M7.324 9.917A2.479 2.479 0 0 1 7.99 7.7l.71-.71a2.484 2.484 0 0 1 2.222-.688 4.538 4.538 0 1 0-3.6 3.615h.002ZM7.99 18.3a2.5 2.5 0 0 1-.6-2.564A2.5 2.5 0 0 1 6 13.5v-1c.005-.544.19-1.072.526-1.5H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h7.687l-.697-.7ZM19.5 12h-1.12a4.441 4.441 0 0 0-.579-1.387l.8-.795a.5.5 0 0 0 0-.707l-.707-.707a.5.5 0 0 0-.707 0l-.795.8A4.443 4.443 0 0 0 15 8.62V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.12c-.492.113-.96.309-1.387.579l-.795-.795a.5.5 0 0 0-.707 0l-.707.707a.5.5 0 0 0 0 .707l.8.8c-.272.424-.47.891-.584 1.382H8.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1.12c.113.492.309.96.579 1.387l-.795.795a.5.5 0 0 0 0 .707l.707.707a.5.5 0 0 0 .707 0l.8-.8c.424.272.892.47 1.382.584v1.12a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1.12c.492-.113.96-.309 1.387-.579l.795.8a.5.5 0 0 0 .707 0l.707-.707a.5.5 0 0 0 0-.707l-.8-.795c.273-.427.47-.898.584-1.392h1.12a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5ZM14 15.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/>
                                </svg><span class="ml-3" sidebar-toggle-item="">Ganti Password</span>
                            </a>
                            <a href="<?= site_url('logout') ?>" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 16">
                                    <path d="M12.5 3.046H10v-.928A2.12 2.12 0 0 0 8.8.164a1.828 1.828 0 0 0-1.985.311l-5.109 4.49a2.2 2.2 0 0 0 0 3.24L6.815 12.7a1.83 1.83 0 0 0 1.986.31A2.122 2.122 0 0 0 10 11.051v-.928h1a2.026 2.026 0 0 1 2 2.047V15a.999.999 0 0 0 1.276.961A6.593 6.593 0 0 0 12.5 3.046Z"/>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item="">Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 text-center w-full p-4 space-x-4 bg-white" sidebar-bottom-menu="">
                    <p class="text-gray-500">&copy; 2023 BUMDES Kanterleans</p>
                </div>
            </div>
        </aside>
        <div id="main" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 ml:0 dark:bg-gray-900 transition-all">
            <?php require_once $content; ?>
        </div>
    </div>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.data('global', () => ({
                sites: {
                    show_sidebar: true
                },
                toggleSidebar: function() {
                    this.sites.show_sidebar = !this.sites.show_sidebar;
                    const main = document.getElementById('main');

                    if(this.sites.show_sidebar) {
                        main.classList.remove('lg:ml-0');
                        main.classList.add('lg:ml-64');

                        return;
                    }

                    main.classList.remove('lg:ml-64');
                    main.classList.add('lg:ml-0');
                }
            }));
        });
    </script>
</body>
</html>