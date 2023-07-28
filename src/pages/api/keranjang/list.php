<?php

if (
    false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notGetRequest()
) response()->notFound();

/** @var $service KelolaKeranjang */
$service = app()->getManager()->getService('KelolaKeranjang');
$keranjang = $service->get();

response()->jsonOk($keranjang?->toArray());
