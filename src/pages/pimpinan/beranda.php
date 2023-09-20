<?php if (false === session()->isAuthenticatedAs('pimpinan')) html_unauthorized(); ?>
<main>
    <div class="px-4 pt-6">
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Dashboard</h1>
        </div>
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            Selamat datang, <?= $akun->getUsername() ?>.
        </div>
    </div>
</main>
