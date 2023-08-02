 <?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['nama', 'kontak', 'alamat'])
) response()->notFound();

///** @var $service KelolaPesanan */
$service = app()->getManager()->getService('KelolaPesanan');
$nomorPesanan = session('pesanan'); // TODO: validasi apakah nomor pesanan adalah kepunyaan dari sesi yang berjalan
$saved = $service->simpanInformasiPengiriman($nomorPesanan, $_POST['nama'], $_POST['kontak'], $_POST['alamat']);

if(!$saved) response()->badRequest('gagal meyimpan informasi pemesanan, mohon coba lagi.');

response()->jsonOk([
    'message' => 'Informasi pemesanan berhasil disimpan, anda akan segera dialihkan untuk menyelesaikan pemesanan.',
    'data' => [
        'nomor_pesanan' => $nomorPesanan
    ]
]);