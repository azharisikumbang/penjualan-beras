<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has('id')
) response()->notFound();

/** @var $service KelolaTakaran */
$service = app()->getManager()->getService('KelolaTakaran');
$deleted = $service->hapusDataTakaran($_POST['id']);

if(!$deleted) response()->serverError('Gagal Menghapus Data');

response()->jsonOk(['deleted_id' => $_POST['id']]);