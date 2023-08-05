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
        $id = is_int($akun) ? $akun : $akun->getId();
        if(!$this->akunRepository->exists($id)) return false;

        $pelanggan = $this->pelangganRepository->findByAkunId($id);
        $pelanggan->setAkun($akun);

        return $pelanggan;
    }
}
