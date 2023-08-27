<?php /** @var $akun Akun */ $akun = session()->auth(); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <!-- <img src="https://flowbite-admin-dashboard.vercel.app/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo">-->
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
                            <a href="https://flowbite-admin-dashboard.vercel.app/" class="flex font-semibold items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item="">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('pimpinan/laporan-penjualan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                    <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item="">Laporan Penjualan</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('pimpinan/laporan-stok') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 10H1m0 0 3-3m-3 3 3 3m1-9h10m0 0-3 3m3-3-3-3"/>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item="">Laporan Stok Beras</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('pimpinan/laporan-pelanggan') ?>" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                                    <path d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
                                    <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item="">Laporan Data Pelanggan</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pt-2 space-y-2">
                        <a href="<?= site_url('pimpinan/ganti-password') ?>" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                            <span class="ml-3" sidebar-toggle-item="">Ganti Password</span>
                        </a>
                        <a href="<?= site_url('logout') ?>" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path></svg>
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
    <div id="main" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900 transition-all">
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

                if(this.sites.show_sidebar) main.classList.remove('lg:ml-0');
                else main.classList.add('lg:ml-0');
            }
        }));
    });
</script>
</body>
</html>