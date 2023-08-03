<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['nomor', 'status'])
) response()->notFound();

/** @var $service KelolaPesanan */
$service = app()->getManager()->getService('KelolaPesanan');
$updated = $service->konfirmasiPembayaran($_POST['nomor'], $_POST['status']);

if(!$updated) response()->badRequest(['status' => false, 'Gagal menyimpan perubahan, mohon tunggu beberapa saat dan coba kembali. (halaman akan dimuat ulang otomatis)']);

response()->jsonOk([
    'message' => 'Informasi pembayaran berhasil diperbaharui.'
]);