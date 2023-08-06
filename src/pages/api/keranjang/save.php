<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['kode_kupon_promo'])
) response()->notFound();

$kodePromo = $_POST['kode_kupon_promo'];

/** @var $pelanggan Pelanggan */
$pelanggan = app()->getManager()->getService('InformasiPelanggan')->cariInformasiBerdasarkanAkun(session()->auth());

/** @var $keranjang Keranjang */
$keranjang = app()->getManager()->getService('KelolaKeranjang')->get();

if ($kodePromo != '' || $kodePromo != null) {
    /** @var $kelolaPromo KelolaPromo */
    $kelolaPromo = app()->getManager()->getService('KelolaPromo');
    $promo = $kelolaPromo->cekKodeKupon($kodePromo);

    if (is_null($promo)) response()->badRequest(['message' => 'Kode promo tidak valid, mohon coba kembali.']);
    $keranjang->setDiskon($promo->getKodeKupon());
}


/** @var $kelolaPesanan KelolaPesanan */
$kelolaPesanan = app()->getManager()->getService('KelolaPesanan');
$saved = $kelolaPesanan->buatPesananBaru($pelanggan, $keranjang);

if(!$saved) response()->badRequest(['message' => 'Gagal mencatat pesanan, mohon coba kembali beberapa saat.']);

session()->remove('keranjang');
session()->add('pesanan', $saved->getNomorPesanan());

response()->jsonOk([
    'nomor_pesanan' => $saved->getNomorPesanan(),
    'detail' => $saved->toArray()
]);