<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notGetRequest()
) response()->notFound();

if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0) html_not_found();
}

$page = $_GET['page'] ?? 1;
$search = $_GET['cari'] ?? null;
$filter = [
    'search' => $search,
    'page' => $page
];

// remove path and page from query string
$httpGetQuery = $_GET;
unset($httpGetQuery['path']);
unset($httpGetQuery['page']);
$paginationQuery = http_build_query($httpGetQuery);

/** @var $kelolaPelanggan KelolaPelanggan */
$kelolaPelanggan = app()->getManager()->getService('KelolaPelanggan');
$listPesananSaya = $kelolaPelanggan->listPesananByPelanggan(session()->auth(), $filter);

?>
<main x-data="container">
    <div class="p-6 bg-white rounded-lg border">
        <div class="col-span-2">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Riwayat Pemesanan</h1>
                    <?php if ($search): ?><p class="italic text-gray-400 text-sm mt-4">Pencarian: <?= $search ?></p><?php endif; ?>
                </div>
                <form action="" method="GET" class="flex flex-row justify-start items-center">
                    <div class="flex justify-end w-96 text-sm">
                        <input required value="<?= $_GET['cari'] ?? '' ?>" type="text" name="cari" class="w-96 bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block p-2.5" placeholder="ketik nomor pesanan..">
                        <button type="submit" class="border border-gray-300 cursor-pointer rounded-tr-lg rounded-br-lg bg-gray-100 hover:bg-gray-200 px-5 py-2 focus:outline-none outline-none hover:bg-gray-200">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
            <table class="my-4 w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        No
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                        Nomor Pesanan
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        Tanggal Pemesanan
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        Atas Nama Pemesan
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        Status Pemesanan
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        Total Tagihan
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php if($listPesananSaya): ?>
                    <template x-for="(item, index) in properties.data.list_pesanan">
                        <tr>
                            <td class="w-4 p-4 text-center" x-text="index + 1"></td>
                            <td class="p-4 whitespace-nowrap">
                                <p class="font-semibold" x-text="item.nomor_pesanan"></p>
                            </td>
                            <td class="p-4 text-gray-500 text-base text-center" x-text="tanggalToIndo(item.tanggal_pemesanan)"></td>
                            <td class="p-4 text-gray-500 text-base text-center">
                                <span x-text="item.nama_pesanan"></span>
                            </td>
                            <td class="p-4 text-gray-500 text-base text-center">
                                <span :class="'bg-' + item.transaksi.status_pembayaran_color + '-400'" class="inline text-sm px-1 rounded text-white" x-text="item.transaksi.status_pembayaran"></span>
                            </td>
                            <td class="p-4 text-gray-500 text-base text-center" x-text="currencyToRupiah(item.total_tagihan)"></td>
                            <td class="p-4 text-gray-500 text-base text-center">
                                <a class="text-red-500 hover:underline" :href="'<?= site_url('pelanggan/riwayat/detail?nomor=') ?>' + item.nomor_pesanan">Lihat Detail</a>
                                <span>-</span>
                                <a x-show="!item.lunas" class="text-red-500 hover:underline" :href="'<?= site_url('pelanggan/pembayaran?nomor=') ?>' + item.nomor_pesanan">Bayar</a>
                            </td>
                        </tr>
                    </template>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-gray-600 italic p-4 text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end text-gray-600 gap-2 my-4">
        <div class="px-4 py-2 bg-gray-100 rounded w-32">
            <?php if($page > 1): ?>
                <a class="hover:underline cursor-pointer"  href="?page=<?= ($page - 1) . '&' . $paginationQuery ?>">Sebelumnya</a>
            <?php else: ?>
                <span class="hover:cursor-not-allowed">Sebelumnya</span>
            <?php endif; ?>
        </div>
        <div class="px-4 py-2 rounded bg-gray-100 hover:underline hover:bg-gray-300 cursor-pointer">
            <a href="?page=<?= ($page + 1) . '&' . $paginationQuery ?>">Selanjutnya</a>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {};

        const utils = {
            "tanggalToIndo": function (tanggal) {
                if (!tanggal) return null;
                let date = new Date(tanggal);

                return date.toLocaleDateString('id-ID',  { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },
            "currencyToRupiah": function (number) {
                return 'Rp ' + this.addDotToCurrentcy(number);
            },
            "addDotToCurrentcy": function (number) {
                return (new Intl.NumberFormat('id-Id', {"maximumSignificantDigits": 3}).format(number));
            }
        };

        Alpine.data('container',
            () => ({
                ...actions,
                ...utils,
                "properties": {
                    "sites": {
                        "api_url": "<?= site_url() ?>",
                        "query_title": null
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {
                        "list_pesanan": JSON.parse('<?= json_encode(array_map(fn ($item) => $item->toArray(), $listPesananSaya)) ?>')
                    },
                    "form": {
                        "search": ""
                    }
                },
                "init": function() {
                }
            })
        );
    });
</script>
