<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['id', 'jenis', 'takaran'])
) response()->notFound();

if(empty($_POST['takaran']) || $_POST['takaran'] == "" || is_null($_POST['takaran'])) response()->badRequest(['Mohon pilih takaran beras yang dijual, jika tersedia mohon tambahkan takaran baru.']);

$listTakaran = is_string($_POST['takaran']) ? explode(',', $_POST['takaran']) : $_POST['takaran'];

/** @var $kelolaBeras KelolaBeras */
$kelolaBeras = app()->getManager()->getService('KelolaBeras');
if($_POST['id'] < 1) {
    $created = $kelolaBeras->tambahkanBerasBaru($_POST['jenis'], $listTakaran);
    if(!$created) response()->badRequest(['Gagal menyimpan data, mohon coba lagi.']);

    response()->jsonOk($created->toArray());
}

$updated = $kelolaBeras->perbaharuiDataBeras($_POST['id'], $_POST['jenis'], $listTakaran);
if(!$updated) response()->badRequest(['Gagal memperbaharui data, mohon coba lagi.']);

response()->jsonOk($updated->toArray());