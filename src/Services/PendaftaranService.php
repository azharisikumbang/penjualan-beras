<?php

require_once __DIR__ . '/../Repositories/AkunRepository.php';
require_once __DIR__ . '/../Repositories/PelangganRepository.php';
require_once __DIR__ . '/../Entities/Akun.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';

class PendaftaranService
{
    private AkunRepository $akunRepository;

    private PelangganRepository $pelangganRepository;

    public function __construct()
    {
        $this->pelangganRepository = new PelangganRepository();
        $this->akunRepository = new AkunRepository();
    }

    public function daftarkan(
        string $username,
        string $password,
        string $nama,
        string $kontak,
        string $alamat
    ): false|Pelanggan {
        if ($this->cekApakahUsernameTerdaftar($username)) return false;

        $hashedPassword = password_verify($password, PASSWORD_DEFAULT);
        $akun = new Akun();
        $akun->setUsername($username);
        $akun->setPassword($hashedPassword);
        $akun->setRole(Role::PELANGGAN);

        $savedAkun = $this->akunRepository->save($akun);

        if (false === $savedAkun) return false;

        $pelanggan = new Pelanggan();
        $pelanggan->setNama($nama);
        $pelanggan->setKontak($kontak);
        $pelanggan->setAlamat($alamat);
        $pelanggan->setAkun($savedAkun);

        return $this->pelangganRepository->save($pelanggan);
    }

    public function cekApakahUsernameTerdaftar(string $username): bool
    {
        return $this->akunRepository->existsByUsername($username);
    }
}