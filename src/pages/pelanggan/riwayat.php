<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notGetRequest()
) response()->notFound();

/** @var $kelolaPelanggan KelolaPelanggan */
$kelolaPelanggan = app()->getManager()->getService('KelolaPelanggan');
$listPesananSaya = $kelolaPelanggan->listPesananByPelanggan(session()->auth());

?>
<main x-data="container">
    <div class="p-6 bg-white rounded-lg border">
        <div class="col-span-2">
            <div class="flex justify-between">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Riwayat Pemesanan</h1>
                <form action="" method="GET" class="flex flex-row justify-start items-center">
                    <div class="flex justify-end w-96 text-sm">
                        <input type="text" name="cari" class="bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block w-full p-2.5" placeholder="ketik nomor pesanan..">
                        <buttton type="submit" class="border border-gray-300 cursor-pointer rounded-tr-lg rounded-br-lg bg-gray-100 hover:bg-gray-200 px-5 py-2 focus:outline-none outline-none hover:bg-gray-200">
                            <svg class="w-5 h-6 text-gray-100" viewBox="0 0 20 20">
                                <path d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896
                        c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519
                        c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461
                        s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path>
                            </svg>
                        </buttton>
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
                            <td class="p-4 text-gray-500 text-base text-center"><span x-text="item.nama_pesanan"></span></td>
                            <td class="p-4 text-gray-500 text-base text-center"><span :class="'bg-' + item.transaksi.konfirmasi_pembayaran_color + '-400'" class="inline text-sm px-1 rounded text-white" x-text="item.transaksi.konfirmasi_pembayaran"></span></td>
                            <td class="p-4 text-gray-500 text-base text-center" x-text="currencyToRupiah(item.total_tagihan)"></td>
                            <td class="p-4 text-gray-500 text-base text-center">
                                <a class="text-red-500 hover:underline" :href="'<?= site_url('pelanggan/riwayat/detail?nomor=') ?>' + item.nomor_pesanan">Lihat Detail</a>
                            </td>
                        </tr>
                    </template>
                <?php else: ?>
                    <div class="text-center py-4">Tidak ada data.</div>
                <?php endif; ?>
                </tbody>
            </table>
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
