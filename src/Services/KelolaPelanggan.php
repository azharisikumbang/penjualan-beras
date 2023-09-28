<?php

require_once __DIR__ . '/../Repositories/PelangganRepository.php';
require_once __DIR__ . '/../Services/KelolaPesanan.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';

class KelolaPelanggan
{
    private PelangganRepository $pelangganRepository;

    public function __construct()
    {
        $this->pelangganRepository = new PelangganRepository();
    }

    public function listPelanggan(int $total = 10, int $start = 0): array
    {
        $start = ($total * $start) - $total;

        return $this->pelangganRepository->get($total, $start, 'nama', 'ASC');
    }

    public function cariBerdasarkanNamaPelanggan(string $nama, int $total = 10, int $start = 0): array
    {
        $start = ($total * $start) - $total;

        return $this->pelangganRepository->findByNama($nama, $total, $start);
    }

    public function cariBerdasarkanAkun(Akun $akun) : ?Pelanggan
    {
        $pelanggan = $this->pelangganRepository->findByAkunId($akun->getId());
        if (is_null($pelanggan)) return null;

        $pelanggan->setAkun($akun);

        return $pelanggan;
    }

    public function listPesananByPelanggan(Pelanggan|Akun $pelanggan, array $filters): array
    {
        $pelanggan = $pelanggan instanceof Pelanggan ? $pelanggan : $this->pelangganRepository->findByAkunId($pelanggan->getId());
        if(is_null($pelanggan)) return [];

        $kelolaPesanan = new KelolaPesanan();
        return $kelolaPesanan->cariBerdasarkanPemesan($pelanggan, true, $filters);
    }

    public function perbaharuiDataPelanggan(Akun $akun, string $nama, string $kontak, string $alamat): bool
    {
        $pelanggan = $this->pelangganRepository->findByAkunId($akun->getId());
        if (is_null($pelanggan)) return false;

        return $this->pelangganRepository->update($pelanggan, [
            'nama' => $nama,
            'kontak' => $kontak,
            'alamat' => $alamat
        ]);
    }
}
