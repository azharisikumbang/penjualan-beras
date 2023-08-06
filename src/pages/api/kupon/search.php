<?php

if (
    is_null(session()->auth()) ||
    request()->notGetRequest() ||
    false === request()->has(['kupon'])
) response()->notFound();

$kupon = $_GET['kupon'];
$akun = session()->auth();

$pelanggan = app()->getManager()->getService('KelolaPelanggan')->cariBerdasarkanAkun($akun);
if (is_null($pelanggan)) response()->badRequest(['message' => 'You are not logged in.']);

$kuponUsed = app()->getManager()->getService('KelolaPesanan')->cekApakahKodePromoSudahTerpakai($kupon, $pelanggan->getId());

if ($kuponUsed) response()->badRequest([
    'message' => "Mohon maaf, Kode promo telah digunakan pada pemesanan lain.",
    'query' => [
        'kupon' => $kupon
    ]
]);

$promo = app()->getManager()->getService('KelolaPromo')->cekKodeKupon(strtoupper($kupon));

if (is_null($promo)) response()->badRequest([
    'message' => "Kode promo tidak valid, silahkan dicoba lagi.",
    'query' => [
        'kupon' => $kupon
    ]
]);

response()->jsonOk(
    $promo->toArray()
);