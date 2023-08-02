<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest()
) response()->notFound();

/** @var $keranjang Keranjang */
$keranjang = app()->getManager()->getService('KelolaKeranjang')->get();

/** @var $pelanggan Pelanggan */
$pelanggan = app()->getManager()->getService('InformasiPelanggan')->cariInformasiBerdasarkanAkun(session()->auth());

/** @var $kelolaPesanan KelolaPesanan */
$kelolaPesanan = app()->getManager()->getService('KelolaPesanan');
$saved = $kelolaPesanan->buatPesananBaru($pelanggan, $keranjang);

if(!$saved) response()->badRequest(['Gagal mencatat pesanan, mohon coba kembali beberapa saat.']);

session()->remove('keranjang');
session()->add('pesanan', $saved->getNomorPesanan());

response()->jsonOk([
    'nomor_pesanan' => $saved->getNomorPesanan(),
    'detail' => $saved->toArray()
]);