<?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['nama', 'bank', 'nominal'])
) response()->notFound();

if(!isset($_FILES['file_bukti'])) {
    response()->badRequest("Bukti pembayaran belum diunggah. Mohon coba kembali.");
}

$bukti = $_FILES['file_bukti'];

if($bukti['error']) {
    response()->badRequest("Terjadi kesalaahan pada saat unggah bukti pembayaran. Mohon coba kembali.");
}

/** @var $service KelolaPesanan */
$service = app()->getManager()->getService('KelolaPesanan');
$nomorPesanan = session('pesanan'); // TODO: validasi apakah nomor pesanan adalah kepunyaan dari sesi yang berjalan
$saved = $service->simpanInformasiPembayaran($nomorPesanan, $_POST['nama'], $_POST['bank'], $_POST['nominal'], $bukti);

if(!$saved) response()->badRequest('gagal meyimpan informasi pembayaran, mohon coba lagi.');

response()->jsonOk([
    'message' => 'Informasi pembayaran berhasil disimpan, anda akan segera dialihkan untuk menyelesaikan pemesanan.',
    'data' => [
        'nomor_pesanan' => $nomorPesanan
    ]
]);