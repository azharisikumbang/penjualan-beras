<?php

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();

$listBeras = app()->getManager()->getService('KelolaBeras')->listBeras();

?>
<main>
    <div class="p-6 bg-white rounded-lg border">
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Beranda</h1>
        </div>
    </div>
</main>
