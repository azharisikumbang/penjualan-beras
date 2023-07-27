<?php

require_once __DIR__ . '/../Repositories/KaryawanRepository.php';
require_once __DIR__ . '/../Entities/Karyawan.php';

class KelolaKaryawan
{
    private KaryawanRepository $karyawanRepository;

    public function __construct()
    {
        $this->karyawanRepository = new KaryawanRepository();
    }

    public function tambahkanDataKaryawan(
        string $nama,
        string $kontak
    ): false|Karyawan {
        $karyawan = new Karyawan();
        $karyawan->setNama($nama);
        $karyawan->setKontak($kontak);
        $karyawan->setJabatan(Jabatan::KARYAWAN);

        return $this->karyawanRepository->save($karyawan);
    }

    public function rubahDataKaryawan(
        int $id,
        ?string $nama = null,
        ?string $kontak = null
    ): false|Karyawan {

        $karyawan = $this->karyawanRepository->findById($id);

        if (is_null($karyawan)) return false;

        $updated = [];
        if ($nama) $updated['nama'] = $nama;
        if ($kontak) $updated['kontak'] = $kontak;
        if(empty($updated)) return false;

        $updated = $this->karyawanRepository->update($karyawan, $updated);
        if(false === $updated) return false;

        return $this->karyawanRepository->findById($karyawan->getId());
    }

    public function hapusDataKaryawan(int $id): bool
    {
        return $this->karyawanRepository->remove($id);
    }

    public function listKaryawan(int $total = 10, int $start = 0): array
    {
        return $this->karyawanRepository->get($total, $start, 'nama', 'ASC');
    }

    public function listJabatanKaryawan()
    {
        return Jabatan::cases();
    }
}
