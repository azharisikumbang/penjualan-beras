<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notGetRequest()
) response()->notFound();

$nomorPesanan = $_GET['nomor'] ?? session('pesanan');
if(empty($nomorPesanan) || is_null($nomorPesanan)) response()->notFound();

/** @var $kelolaPesanan KelolaPesanan */
$kelolaPesanan = app()->getManager()->getService('KelolaPesanan');
$pesanan = $kelolaPesanan->cariBerdasarkanNomorPesanan($nomorPesanan);

if(is_null($pesanan)) response()->notFound();

$akun = session()->auth();
$valid = $kelolaPesanan->cekPemilikPesanan($pesanan, $akun);

if(false === $valid) response()->notFound();

$lunas = $pesanan->getTransaksi()?->isPaid();

?>
<main>
    <div class="p-6 bg-white rounded-lg border text-gray-600">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Informasi Pesanan: No. <?= $pesanan->getNomorPesanan() ?></h1>
        </div>
        <div class="grid grid-cols-3 gap-4 my-4">
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
                    <p><?= $pesanan->getNamaPesanan() ?? '-' ?> <small>(pelanggan)</small></p>
                </div>
                <div class="border-b text-gray-900 py-1 mb-2">
                    <p class="font-medium">Kontak Pemesan</p>
                    <p><?= $pesanan->getKontakPesanan() ?? '-' ?></p>
                </div>
                <div class="border-b text-gray-900 py-1 mb-2">
                    <p class="font-medium">Alamat Pengiriman</p>
                    <p><?= $pesanan->getAlamatPengiriman() ?? '-' ?></p>
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
                    <div class="flex justify-between items-center">
                        <p class="bg-<?= $pesanan->getTransaksi()->getStatusPembayaran()->getColor() ?>-400 inline text-sm px-1 rounded text-white"><?= $pesanan->getTransaksi()->getStatusPembayaran()->getDisplay() ?></p>
                        <?php if (!$lunas): ?>
                            <a class="text-red-500 hover:underline" href="<?= site_url('pelanggan/pembayaran?nomor=' . $pesanan->getNomorPesanan()) ?>">Bayar Sekarang</a>
                        <?php endif; ?>
                    </div>
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
                    <a href="<?= site_url('uploaded/bukti-pembayaran/' . $pesanan->getTransaksi()->getFileBuktiPembayaran()) ?>" class="bg-gray-400 text-center py-2 block text-white rounded mt-2 hover:bg-gray-500" download>Unduh untuk melihat lebih detail.</a>
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
                            Harga Satuan (Rupiah)
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Jumlah Beli (satuan: Takaran)
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
                            <td class="p-4 text-gray-500 text-base text-center">
                                <?= rupiah($item->getJumlahBeli()) ?>
                                x <?= $item->getTakaranBeras() ?>
                            </td>
                            <td class="p-4 text-gray-500 text-base text-right">Rp. <?= rupiah($item->getTotal()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr class="border-t">
                        <td class="text-gray-500 text-base text-right pt-4" colspan="4">Sub Total :</td>
                        <td class="text-gray-500 text-base text-right pt-4 pr-4 font-bold">Rp. <?= rupiah($pesanan->getSubTotal()) ?></td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 text-base text-right" colspan="4">Diskon :</td>
                        <td class="text-gray-500 text-base text-right pr-4 font-bold">Rp. <?= rupiah($pesanan->getDiskon()) ?></td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 text-base text-right" colspan="4">Total Tagihan :</td>
                        <td class="text-gray-500 text-base text-right pr-4 font-bold">Rp. <?= rupiah($pesanan->getTotalTagihan()) ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
</main>
