<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has('id')
) response()->notFound();

/** @var $kelolaBeras KelolaBeras */
$kelolaBeras = app()->getManager()->getService('KelolaBeras');
$deleted = $kelolaBeras->hapusDataBeras($_POST['id']);

if(!$deleted) response()->serverError('Gagal Menghapus Data');

response()->jsonOk(['deleted_id' => $_POST['id']]);