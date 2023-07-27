<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['id', 'jenis', 'harga', 'stok'])
) response()->notFound();

/** @var $kelolaBeras KelolaBeras */
$kelolaBeras = app()->getManager()->getService('KelolaBeras');
if($_POST['id'] < 1) {
    $created = $kelolaBeras->tambahkanBerasBaru($_POST['jenis'], $_POST['harga'], $_POST['stok']);
    if(!$created) response()->badRequest(['Gagal menyimpan data, mohon coba lagi.']);

    response()->jsonOk($created->toArray());
}

$updated = $kelolaBeras->perbaharuiDataBeras($_POST['id'], $_POST['jenis'], $_POST['harga'], $_POST['stok']);
if(!$updated) response()->badRequest(['Gagal memperbaharui data, mohon coba lagi.']);

response()->jsonOk($updated->toArray());