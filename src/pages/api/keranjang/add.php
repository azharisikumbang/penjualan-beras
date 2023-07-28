<?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['beras', 'jumlah_beli'])
) response()->notFound();

/** @var $service KelolaKeranjang */
$service = app()->getManager()->getService('KelolaKeranjang');
$keranjang = $service->tambahkanProdukKeKeranjang($_POST['beras'], $_POST['jumlah_beli']);

response()->jsonOk($keranjang->toArray());