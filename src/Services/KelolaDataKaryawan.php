<?php

require_once __DIR__ . '/../Repositories/KaryawanRepository.php';
require_once __DIR__ . '/../Entities/Karyawan.php';

class KelolaDataKaryawan
{
    private KaryawanRepository $karyawanRepository;

    public function __construct()
    {
        $this->karyawanRepository = new KaryawanRepository();
        $this->akunRepository = new AkunRepository();
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
        if($kontak) $updated['kontak'] = $kontak;

        return $this->karyawanRepository->update($karyawan, $updated);
    }

    public function hapusDataKaryawan(int $id): bool
    {
        return $this->karyawanRepository->remove($id);
    }

    public function listKaryawan(int $total = 10, int $start = 0): array
    {
        return $this->karyawanRepository->get($total, $start, 'nama', 'ASC');
    }
}
