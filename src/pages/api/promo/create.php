<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['jenis_promo', 'kode_kupon', 'tanggal_kadaluarsa', 'minimum_pembelian', 'potongan_harga'])
) response()->notFound();

/** @var $service KelolaPromo */
$service = app()->getManager()->getService('KelolaPromo');

// set null jika tanpa tanggal kadaluarsa atau promo berlaku selamanya
$tanggalKadaluarsa = (is_null($_POST['tanggal_kadaluarsa']) || empty($_POST['tanggal_kadaluarsa']) || $_POST['tanggal_kadaluarsa'] == '')
        ? null
        : date_create($_POST['tanggal_kadaluarsa']);

$created = $service->simpanPromoBaru(
    $_POST['jenis_promo'],
    $_POST['kode_kupon'],
    $tanggalKadaluarsa,
    $_POST['minimum_pembelian'],
    $_POST['potongan_harga']
);

if(!$created) response()->badRequest(['message' => 'Gagal membuat promo, mohon coba kembali.']);

response()->jsonOk($created->toArray());