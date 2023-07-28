<?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notPostRequest() ||
    false === request()->has(['key'])
) response()->notFound();

/** @var $service KelolaKeranjang */
$service = app()->getManager()->getService('KelolaKeranjang');
$keranjang = $service->hapusItemDariKeranjang($_POST['key']);

response()->jsonOk($keranjang?->toArray());