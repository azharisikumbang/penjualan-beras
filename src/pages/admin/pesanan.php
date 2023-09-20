<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

require_once __DIR__ . '/../../Enum/StatusPembayaran.php';
require_once __DIR__ . '/../../Enum/KonfirmasiPembayaran.php';

if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0) html_not_found();
}

$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? null;
$filterStatus = $_GET['status'] ?? null;
$filterPembayaran = $_GET['konfirmasi'] ?? null;
$filterPeriode = $_GET['periode'] ?? null;

// remove path and page from query string
$httpGetQuery = $_GET;
unset($httpGetQuery['path']);
unset($httpGetQuery['page']);
$paginationQuery = http_build_query($httpGetQuery);

$listPesanan = app()->getManager()->getService('KelolaPesanan')->listPesananWithFilter(10, $page, $filterPeriode, $search, $filterStatus, $filterPembayaran);
$listFilterStatusPembayaran = StatusPembayaran::toArray();
$listFilterKonfirmasiPembayaran = KonfirmasiPembayaran::toArray();

?>
<main x-data="container">
    <div class="px-4 py-6">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Pesanan Masuk</h1>
        </div>
        <?php if(session('temp')): ?>
            <div class="mb-4 block w-full text-base font-regular px-4 py-4 rounded-lg bg-green-500 text-white">
                <?php echo session('temp')['message'] ?>
            </div>
        <?php endif; ?>
        <template x-if="properties.messages.errors">
            <template x-for="error in properties.messages.errors">
                <?php html_alert("error.message", "error.color"); ?>
            </template>
        </template>
        <template x-if="properties.messages.normal">
            <template x-for="normal in properties.messages.normal">
                <?php html_alert("normal.message", "normal.color"); ?>
            </template>
        </template>
        <div class="p-6 bg-white rounded-lg border">
            <div class="col-span-2">
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <span class="mr-2">Pencarian: </span>
                        <form action="" method="GET" class="flex flex-row justify-start items-center">
                            <div class="flex justify-start w-72 text-sm">
                                <input value="<?= $search ?? '' ?>" type="text" name="search" class="text-sm bg-gray-50 border rounded-lg border-gray-300 text-gray-900 p-2" placeholder="Ketik nomor pesanan...">
                                <button type="submit" class="ml-1 text-sm bg-blue-700 border rounded-lg border-blue-700 text-white px-2 py-1 cursor-pointer hover:bg-blue-800 hover:border-blue-800 ">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2">Filter: </span>
                        <form action="" method="get" class="flex items-center gap-2">
                            <select name="periode" class="text-sm bg-gray-50 border rounded-lg border-gray-300 text-gray-900 p-2 cursor-pointer">
                                <option value="-1">-- Pilih Periode Pemesanan--</option>
                                <option value="-1" <?= ('-1' == $filterPeriode) ? 'selected' : '' ?>>periode: Tampilkan Semua</option>
                                <option value="today" <?= ('today' == $filterPeriode) ? 'selected' : '' ?>>periode: Hari Ini</option>
                            </select>
                            <select name="status" class="text-sm bg-gray-50 border rounded-lg border-gray-300 text-gray-900 p-2 cursor-pointer">
                                <option value="-1">-- Pilih Status Pemesanan--</option>
                                <option value="-1">status: Tampilkan Semua</option>
                                <?php foreach ($listFilterStatusPembayaran as $filter): ?>
                                    <option value="<?= $filter['value'] ?>" <?= ($filter['value'] == $filterStatus) ? 'selected' : '' ?>>status: <?= ucwords(strtolower($filter['display_as'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="konfirmasi" class="text-sm bg-gray-50 border rounded-lg border-gray-300 text-gray-900 p-2 cursor-pointer">
                                <option value="-1">-- Pilih Status Konfirmasi --</option>
                                <option value="-1">status: Tampilkan Semua</option>
                                <?php foreach ($listFilterKonfirmasiPembayaran as $filter): ?>
                                    <option value="<?= $filter['value'] ?>" <?= ($filter['value'] == $filterPembayaran) ? 'selected' : '' ?>>status: <?= ucwords(strtolower($filter['display_as'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="text-sm bg-blue-700 border rounded-lg border-blue-700 text-white px-4 py-2 cursor-pointer hover:bg-blue-800 hover:border-blue-800 ">
                                <svg class="w-4 h-4 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.133 2.6 5.856 6.9L8 14l4 3 .011-7.5 5.856-6.9a1 1 0 0 0-.804-1.6H2.937a1 1 0 0 0-.804 1.6Z"/>
                                </svg>
                                Buat Filter
                            </button>
                        </form>
                    </div>
                </div>
                <table class="my-4 w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            No
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase w-56">
                            Tanggal Pemesanan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                            Nomor Pesanan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Atas Nama Pemesan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Status Pemesanan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Status Konfirmasi oleh Admin
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Total Tagihan
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php if($listPesanan): ?>
                        <template x-for="(item, index) in properties.data.list_pesanan">
                            <tr>
                                <td class="w-4 p-4 text-center" x-text="index + 1"></td>
                                <td class="p-4 text-gray-500 text-base text-left" x-text="tanggalToIndo(item.tanggal_pemesanan)"></td>
                                <td class="p-4 whitespace-nowrap">
                                    <p class="font-semibold" x-text="item.nomor_pesanan"></p>
                                </td>
                                <td class="p-4 text-gray-500 text-base text-center"><span x-text="item.nama_pesanan"></span></td>
                                <td class="p-4 text-gray-500 text-base text-center"><span :class="'bg-' + item.transaksi.status_pembayaran_color + '-400'" class="inline text-sm px-1 rounded text-white" x-text="item.transaksi.status_pembayaran"></span></td>
                                <td class="p-4 text-gray-500 text-base text-center"><span :class="'bg-' + item.transaksi.konfirmasi_pembayaran_color + '-400'" class="inline text-sm px-1 rounded text-white" x-text="item.transaksi.konfirmasi_pembayaran"></span></td>
                                <td class="p-4 text-gray-500 text-base text-center" x-text="currencyToRupiah(item.total_tagihan)"></td>
                                <td class="p-4 text-gray-500 text-base text-center">
                                    <a class="text-red-500 hover:underline" :href="'<?= site_url('admin/pesanan/detail?nomor=') ?>' + item.nomor_pesanan">Lihat Detail</a>
                                </td>
                            </tr>
                        </template>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="p-4 text-gray-500 text-base text-center italic">Tidak ada data.</td>
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
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {};

        const utils = {
            "tanggalToIndo": function (tanggal, details = false) {
                if (!tanggal) return null;
                let date = new Date(tanggal);

                let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                if (details) {
                    options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: false, second: 'numeric', timeZoneName: 'short' };
                }

                return date.toLocaleDateString('id-ID', options);
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
                        "list_pesanan": JSON.parse('<?= json_encode(array_map(fn ($item) => $item->toArray(), $listPesanan)) ?>')
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
