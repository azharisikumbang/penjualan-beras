<?php

if (false === session()->isAuthenticatedAs('admin') ||
    request()->notGetRequest()
) response()->notFound();

$nomorPesanan = $_GET['nomor'] ?? session('pesanan');
if(empty($nomorPesanan) || is_null($nomorPesanan)) response()->notFound();

/** @var $kelolaPesanan KelolaPesanan */
$kelolaPesanan = app()->getManager()->getService('KelolaPesanan');
$pesanan = $kelolaPesanan->cariBerdasarkanNomorPesanan($nomorPesanan);

if(is_null($pesanan)) response()->notFound();

$lunas = $pesanan->getTransaksi()?->getStatusPembayaran()->value == 'LUNAS';
$confirmable = ($pesanan->getTransaksi()->getStatusPembayaran()->name == 'LUNAS' && $pesanan->getTransaksi()->getKonfirmasiPembayaran()->name == 'MENUNGGU_KONFIRMASI');

?>
<main x-data="container">
    <div class="px-4 py-4">
        <?php if($confirmable): ?>
            <div class="my-4 text-blue-600 border rounded-lg border-blue-600 px-2 py-2">
                <strong>Perhatian</strong>
                <p>
                    Mengkonfirmasi pembayaran akan secara otomatis mengurangi stok beras tersedia sesuai dengan jenis beras yang dipesan.
                    Klik <a href="<?= site_url('admin/kelola/stok') ?>" class="underline hover:text-blue-700">disini</a> untuk mengelola stok tersedia.
                </p>
            </div>
        <?php endif; ?>
        <div class="mb-4 flex justify-between">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Informasi Pesanan: No. <?= $pesanan->getNomorPesanan() ?></h1>
            <?php if($confirmable): ?>
            <div>
                <button @click="terimaPembayaran" type="button" class="px-4 py-2 text-sm bg-green-500 text-white rounded-lg shadow hover:bg-green-600">
                    <svg class="w-3 h-3 inline mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                    </svg>
                    Konfirmasi Pembayaran
                </button>
                <button @click="tolakPembayaran" type="button" class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg shadow hover:bg-red-600">
                    <svg class="w-3 h-3 inline mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    Tolak Pembayaran
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="p-6 bg-white rounded-lg border text-gray-600">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <h4 class="text-lg font-bold mb-2">Informasi Pemesanan</h4>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Nomor Pemesanan</p>
                        <p><?= $pesanan->getNomorPesanan() ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Tanggal Pemesanan</p>
                        <p><?= tanggal($pesanan->getTanggalPemesanan(), false, true) ?> WIB</p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Nama Pemesan</p>
                        <p><?= $pesanan->getNamaPesanan() ?: '-' ?> <small>(pelanggan)</small></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Kontak Pemesan</p>
                        <p><?= $pesanan->getKontakPesanan() ?: '-' ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Alamat Pengiriman</p>
                        <p><?= $pesanan->getAlamatPengiriman() ?: '-' ?></p>
                    </div>
                    <div class="items-center border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Total Tagihan</p>
                        <p>Rp <?= rupiah($pesanan->getTotalTagihan()) ?></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-2">Informasi Pembayaran</h4>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Tanggal Pembayaran</p>
                        <p><?= ($lunas) ? tanggal($pesanan->getTransaksi()->getTanggalPembayaran(), false, true) : '-' ?> WIB</p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Atas Nama Pembayaran</p>
                        <p><?= ($lunas) ? $pesanan->getTransaksi()->getNamaPembayaran() : '-' ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Bank Pembayaran</p>
                        <p><?= ($lunas) ? $pesanan->getTransaksi()->getBankPembayaran() : '-' ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Nominal Dibayarkan</p>
                        <p>Rp <?= ($lunas) ? rupiah($pesanan->getTransaksi()->getNominalDibayarkan()) : '-' ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Status Pembayaran</p>
                        <p class="bg-<?= $lunas ? 'green' : 'yellow' ?>-400 inline text-sm px-1 rounded text-white"><?= $pesanan->getTransaksi()->getStatusPembayaran()->getDisplay() ?></p>
                    </div>
                    <div class="border-b text-gray-900 py-1 mb-2">
                        <p class="font-medium">Konfirmasi Pembayaran</p>
                        <p class="bg-<?= $pesanan->getTransaksi()->getKonfirmasiPembayaran()->getColor() ?>-400 inline text-sm px-1 rounded text-white"><?= $pesanan->getTransaksi()->getKonfirmasiPembayaran()->getDisplay() ?></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg mb-2">Bukti Pembayaran:</h4>
                    <?php if($lunas): ?>
                        <img src="<?= site_url('uploaded/bukti-pembayaran/' . $pesanan->getTransaksi()->getFileBuktiPembayaran()) ?>" alt="<?= $pesanan->getNomorPesanan() ?>">
                        <a href="<?= site_url('uploaded/bukti-pembayaran/' . $pesanan->getTransaksi()->getFileBuktiPembayaran()) ?>" class="bg-gray-400 text-center py-2 block text-white rounded mt-2 hover:bg-gray-500" download>Unduh bukti pembayaran</a>
                    <?php else: ?>
                        <div>Tidak ada.</div>
                    <?php endif; ?>
                </div>
                <div class="col-span-3">
                    <h4 class="text-lg font-bold my-2">Rincian Pembelian</h4>
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                No
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Jenis Beras
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                Harga Satuan (Rp)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                Jumlah Beli (kg)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                Total
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pesanan->getListPesanan() as $index => $item): /** @var $item DetailPesanan */?>
                            <tr>
                                <td class="w-4 p-4 text-center"><?= $index + 1 ?></td>
                                <td class="p-4 whitespace-nowrap">
                                    <p class="font-semibold"><?= $item->getJenisBeras() ?></p>
                                    <p class="text-sm italic">Takaran: <?= $item->getTakaranBeras() ?></p>
                                </td>
                                <td class="p-4 text-gray-500 text-base text-center">Rp <?= rupiah($item->getHargaSatuan()) ?></td>
                                <td class="p-4 text-gray-500 text-base text-center"><?= rupiah($item->getJumlahBeli()) ?> kg</td>
                                <td class="p-4 text-gray-500 text-base text-center">Rp. <?= rupiah($item->getTotal()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr class="border-t">
                            <td class="p-4 text-gray-500 text-base text-right" colspan="4">Total Tagihan :</td>
                            <td class="p-4 text-gray-500 text-base text-center font-bold">Rp. <?= rupiah($pesanan->getTotalTagihan()) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "terimaPembayaran": function () {
                this.$event.target.innerText = "Mohon tunggu..";
                this.$event.target.classList.add('opacity-50');
                this.properties.form.status = 'DITERIMA';
                this.konfirmasiPembayaran();
            },
            "tolakPembayaran": function () {
                let confirmed = confirm('Anda takin ingin menolak bukti pembayaran ini ?');
                if (confirmed) {
                    this.$event.target.innerText = "Mohon tunggu..";
                    this.$event.target.classList.add('opacity-50');
                    this.properties.form.status = 'DITOLAK';
                    this.konfirmasiPembayaran();
                }
            },
            "konfirmasiPembayaran": function () {
                this.clearMassage();
                let alpineObj = this;

                this.postData(
                    '/api/pesanan/pembayaran/konfirmasi-pembayaran/update',
                    this.createFormData({
                        'nomor': this.properties.form.nomor,
                        'status': this.properties.form.status
                    }),
                    function (response) {
                        window.location.reload();
                    },
                    function (err) {
                        console.error(err);
                        alpineObj.addErrorMassage('bad_request', err.response.data.errors['message']);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000)
                    }
                )
            }
        };

        const utils = {
            "postData": function (to, data, callback, callbackError) {
                let that = this;
                return axios
                    .post(this.properties.sites.api_url + to, data)
                    .then(res => callback(res))
                    .catch(err => callbackError(err));
            },
            "createFormData": function (data) {
                const form = new FormData();
                for (const key in data) form.append(key, data[key]);

                return form;
            },
            "addErrorMassage": function (name, message) {
                this.addMessage(name, message);
            },
            "addNormalMessage": function (name, message) {
                this.addMessage(name, message, 'normal', 'green');
            },
            "addMessage": function (name, message, container = 'errors', color = 'red') {
                let exists = this.properties.messages[container].findIndex(item => item.name == name);

                if (exists != -1) {
                    this.properties.messages[container][exists] = { 'name': name, 'message': message, 'color': color };

                    return;
                }

                this.properties.messages[container].push({ 'name': name, 'message': message, 'color': color });
            },
            "clearMassage": function(){
                this.properties.messages.errors = [];
                this.properties.messages.normal = [];
            }
        };

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
                    "form": {
                        'nomor': '<?= $pesanan->getNomorPesanan() ?>',
                        'status': null,
                    }
                },
                "init": function() {}
            })
        );
    });
</script>
