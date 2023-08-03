<?php

if (
    is_null(session()->auth()) ||
    request()->notPostRequest() ||
    false === request()->has(['nama', 'kontak', 'alamat'])
) response()->notFound();

$auth = session()->auth();
$updated = app()->getManager()->getService('KelolaPelanggan')->perbaharuiDataPelanggan($auth, $_POST['nama'], $_POST['kontak'], $_POST['alamat']);

if (!$updated) response()->serverError(['message' => 'Gagal memperbaharui profil, mohon tunggu dan coba kembali beberapa saat.']);

response()->jsonOk([
    'message' => 'Profil berhasil diperbaharui.'
]);