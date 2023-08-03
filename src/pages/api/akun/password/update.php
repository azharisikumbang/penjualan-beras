<?php

if (
    is_null(session()->auth()) ||
    request()->notPostRequest() ||
    false === request()->has(['password', 'password_confirmation'])
) response()->notFound();

$newPassword = $_POST['password'];
$passwordConfirmation = $_POST['password_confirmation'];

if ($newPassword == '' || $passwordConfirmation == '') response()->badRequest(['message' => 'Password tidak boleh kosong.']);
if ($newPassword !== $passwordConfirmation) response()->badRequest(['message' => 'Password konfirmasi tidak sama, mohon ketik ulang.']);

$auth = session()->auth();
$updated = app()->getManager()->getService('KelolaAkun')->perbaharuiKataSandi($auth, $newPassword, $passwordConfirmation);

if (!$updated) response()->serverError(['message' => 'Gagal mengganti password, mohon tunggu dan coba kembali beberapa saat.']);

response()->jsonOk([
    'message' => 'Password berhasil diperbaharui.'
]);