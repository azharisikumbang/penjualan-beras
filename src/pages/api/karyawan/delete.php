<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has('id')
) response()->notFound();

/** @var $service KelolaKaryawan */
$service = app()->getManager()->getService('KelolaKaryawan');
$deleted = $service->hapusDataKaryawan($_POST['id']);

if(!$deleted) response()->serverError('Gagal Menghapus Data');

response()->jsonOk(['deleted_id' => $_POST['id']]);