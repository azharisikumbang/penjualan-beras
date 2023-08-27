<?php

require_once __DIR__ . '/../Repositories/PelangganRepository.php';
require_once __DIR__ . '/../Repositories/AkunRepository.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';
require_once __DIR__ . '/../Entities/Akun.php';

class InformasiPelanggan
{
    private AkunRepository $akunRepository;

    private PelangganRepository $pelangganRepository;

    public function __construct()
    {
        $this->pelangganRepository = new PelangganRepository();
        $this->akunRepository = new AkunRepository();
    }

    public function tentangSaya(): Pelanggan
    {}

    public function rubahInformasiSaya()
    {}

    public function rubahPasswordAkun()
    {}

    public function cariInformasiBerdasarkanAkun(int|Akun $akun): false|Pelanggan
    {
        $akun = is_int($akun) ? $this->akunRepository->findById($akun) : $akun;
        if (is_null($akun)) return false;

        $pelanggan = $this->pelangganRepository->findByAkunId($akun->getId());
        if (is_null($pelanggan)) return false;

        $pelanggan->setAkun($akun);

        return $pelanggan;
    }
}
