<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['id', 'nama', 'kontak'])
) response()->notFound();

/** @var $kelolaBeras KelolaKaryawan */
$kelolaBeras = app()->getManager()->getService('KelolaKaryawan');
if($_POST['id'] < 1) {
    $created = $kelolaBeras->tambahkanDataKaryawan($_POST['nama'], $_POST['kontak']);
    if(!$created) response()->badRequest(['Gagal menyimpan data, mohon coba lagi.']);

    response()->jsonOk($created->toArray());
}

$updated = $kelolaBeras->rubahDataKaryawan($_POST['id'], $_POST['nama'], $_POST['kontak']);
if(!$updated) response()->badRequest(['Gagal memperbaharui data, mohon coba lagi.']);

response()->jsonOk($updated->toArray());