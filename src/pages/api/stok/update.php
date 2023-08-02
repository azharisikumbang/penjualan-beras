<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['beras_id', 'takaran_id', 'harga', 'stok'])
) response()->notFound();

if ($_POST['stok'] < 0) response()->badRequest(['success' => false, 'message' => 'Stok yang anda masukkan dibawah 0 Kg. Mohon periksa dan coba kembali']);
if ($_POST['harga'] < 0) response()->badRequest(['success' => false, 'message' => 'Harga yang anda masukkan dibawah Rp 0. Mohon periksa dan coba kembali']);

$updatedStok = app()->getManager()->getService('KelolaStok')->perbaharuiData(
    $_POST['beras_id'],
    $_POST['takaran_id'],
    $_POST['stok'],
    $_POST['harga']
);

if(false === $updatedStok) response()->serverError(['success' => false, 'message' => 'Server Error: terjadi kegagalan dalam menamhah data, mohon tunggu beberapa saat dan coba kembali.']);

response()->jsonOk($updatedStok->toArray());