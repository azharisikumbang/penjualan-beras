<?php

require_once __DIR__ . '/../Repositories/AkunRepository.php';

class KelolaAkun
{
    private AkunRepository $akunRepository;

    public function __construct()
    {
        $this->akunRepository = new AkunRepository();
    }

    public function perbaharuiKataSandi(Akun $akun, string $password, string $konfirmasi): bool
    {
        if ($password == '') return false;
        if ($password !== $konfirmasi) return false;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $this->akunRepository->update($akun, ['password' => $hashedPassword]);
    }
}