<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0) html_not_found();
}

$page = $_GET['page'] ?? 1;

/** @var $service KelolaPelanggan */
$service = app()->getManager()->getService('KelolaPelanggan');

if (isset($_GET['cari'])) {
    $listPelanggan = $service->cariBerdasarkanNamaPelanggan($_GET['cari'], 10, $page);
} else {
    $listPelanggan = $service->listPelanggan(10, $page);
}

?>
<main x-data="container">
    <div class="px-4 pt-6">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Kelola Data Pelanggan</h1>
        </div>
        <div>
            <div class="px-4 py-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                <form action="" method="GET" class="flex flex-row justify-start items-center mb-4">
                    <div class="flex justify-end w-2/6 ">
                        <input type="text" name="cari" id="products-search" class="bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block w-full p-2.5" placeholder="Cari nama pelanggan.." value="<?= $_GET['cari'] ?? '' ?>">
                        <buttton type="submit" class="border border-gray-300 cursor-pointer rounded-tr-lg rounded-br-lg bg-gray-100 hover:bg-yellow-800 px-5 py-2 focus:outline-none outline-none hover:bg-gray-200">
                            <svg class="w-5 h-6 text-gray-100" viewBox="0 0 20 20">
                                <path d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896
                        c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519
                        c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461
                        s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path>
                            </svg>
                        </buttton>
                    </div>
                </form>
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            No
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                            Nama Pelanggan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Kontak
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Alamat
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php if($listPelanggan): ?>
                        <template x-for="(item, index) in properties.data.list_pelanggan" :key="index">
                            <tr>
                                <td class="w-4 p-4" x-text="index + 1"></td>
                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-base font-semibold text-gray-900" x-text="item.nama"></p>
                                </td>
                                <td class="p-4 text-gray-500 text-base text-center" x-text="item.kontak">
                                <td class="p-4 text-gray-500 text-base text-center" x-text="item.alamat"></td>
                            </tr>
                        </template>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-4 text-gray-500 text-base text-center">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end text-gray-600">
                <div class="px-4 py-2 rounded w-32 hover:underline cursor-pointer">
                    <?php if($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>">Sebelumnya</a>
                    <?php else: ?>
                        <span>Sebelumnya</span>
                    <?php endif; ?>
                </div>
                <div class="px-4 py-2 rounded hover:underline cursor-pointer">
                    <a href="?page=<?= $page + 1 ?>">Selanjutnya</a>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {};

        const utils = {};

        Alpine.data('container',
            () => ({
                ...actions,
                ...utils,
                "properties": {
                    "sites": {
                        "api_url": "<?= site_url() ?>"
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {
                        "list_pelanggan": JSON.parse('<?= json_encode(array_map(fn ($item) => $item->toArray(), $listPelanggan)) ?>')
                    },
                    "form": {}
                },
                "init": function() {}
            })
        );
    });
</script>
