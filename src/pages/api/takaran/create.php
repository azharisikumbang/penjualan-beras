<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['id', 'variant'])
) response()->notFound();

/** @var $service KelolaTakaran */
$service = app()->getManager()->getService('KelolaTakaran');
if($_POST['id'] < 1) {
    $created = $service->tambahkanDataTakaran($_POST['variant']);
    if(!$created) response()->badRequest(['Gagal menyimpan data, mohon coba lagi.']);

    response()->jsonOk($created->toArray());
}

$updated = $service->rubahDataTakaran($_POST['id'], $_POST['variant']);
if(!$updated) response()->badRequest(['Gagal memperbaharui data, mohon coba lagi.']);

response()->jsonOk($updated->toArray());