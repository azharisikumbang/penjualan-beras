<?php

if (
    false === session()->isAuthenticatedAs('admin') ||
    request()->notPostRequest() ||
    false === request()->has(['list_target', 'promo'])
) response()->notFound();

$listTargetPromo = is_string($_POST['list_target']) ? explode(",", $_POST['list_target']) : $_POST['list_target'];

if (count($listTargetPromo) < 1 || empty($listTargetPromo)) response()->badRequest(['message' => 'Daftar nomor penerima tidak sesuai, mohon perhatikan kembali daftar nomor yang anda pilih']);

/** @var $service KelolaPromo */
$service = app()->getManager()->getService('KelolaPromo');

$sent = $service->broadcastStaticPromo($listTargetPromo, $_POST['promo']);

if (!$sent) response()->badRequest(['message' => 'Promo yang anda pilih tidak valid, mohon cek dan coba kembali.']);

response()->jsonOk([
    'status' => 'MESSAGES_SENT',
    'message' => 'Pesan broadcast promo berhasil dikirim.',
    'query' => [
        'list_target' => $listTargetPromo
    ]
]);