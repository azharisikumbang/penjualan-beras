<?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['beras', 'takaran', 'jumlah_beli', 'key'])
) response()->notFound();

$keyItemKeranjang = $_POST['key'];
/** @var $service KelolaKeranjang */
$service = app()->getManager()->getService('KelolaKeranjang');
$keranjang = $service->tambahkanProdukKeKeranjang($keyItemKeranjang, $_POST['beras'], $_POST['takaran'], $_POST['jumlah_beli']);

response()->jsonOk($keranjang->toArray());