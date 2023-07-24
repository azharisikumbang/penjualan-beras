<?php

if (session()->auth()) response()->redirectTo(site_url(session()->auth()->getRole()->redirectPage()));
if(!request()->isPostRequest()) response()->notFound();

$username = $_POST['username'];
$password = $_POST['password'];

/** @var $otentikator Otentikator */
$otentikator = app()->getManager()->getService('Otentikator');

if (false === $otentikator->otentikasi($username, $password))
    response()->redirectTo(site_url('login'), ['status' => false, 'message' => 'Username atau password salah, silahkan coba kembali.']);

/** @var $user Akun */
$user = session()->auth();
response()->redirectTo(site_url($user->getRole()->redirectPage()));
