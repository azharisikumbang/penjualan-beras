<?php

require_once __DIR__ . '/../Repositories/AkunRepository.php';
require_once __DIR__ . '/../Entities/Akun.php';

class Otentikator
{
    private AkunRepository $akunRepository;

    public function __construct()
    {
        $this->akunRepository = new AkunRepository();
    }

    public function otentikasi(string $username, string $password): bool
    {
        $akun = $this->akunRepository->findByUsername($username);
        if (is_null($akun)) return false;

        $valid = $this->verifikasi($akun, $password);
        if (!$valid) return false;

        $this->createAuthenticatedSession($akun);

        return true;
    }

    public function verifikasi(Akun $akun, string $plainPassword) : bool
    {
        return password_verify($plainPassword, $akun->getPassword());
    }

    private function createAuthenticatedSession(Akun $akun): void
    {
        session()->add('auth', $akun->toArray());
    }
}