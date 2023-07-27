<?php

require_once __DIR__ . '/../Repositories/BerasRepository.php';
require_once __DIR__ . '/../Entities/Beras.php';

class KelolaBeras
{

    private BerasRepository $berasRepository;

    public function __construct()
    {
        $this->berasRepository = new BerasRepository();
    }

    public function tambahkanBerasBaru(string $jenis, int|float $harga, int $stok): false|Beras
    {
        if ($harga < 0 || $stok < 0) return false;

        $beras = new Beras();
        $beras->setJenis($jenis);
        $beras->setHarga($harga);
        $beras->setStok($stok);

        return $this->berasRepository->save($beras);
    }

    public function perbaharuiDataBeras(int $id, ?string $jenis = null, null|int|float $harga = null, ?int $stok = null): false|Beras
    {
        $beras = $this->berasRepository->findById($id);
        if (is_null($beras))  return false;

        $updatable = [];
        if($jenis) $updatable['jenis'] = $jenis;
        if($harga) {
            if($harga < 0) return false;

            $updatable['harga'] = $harga;
        }

        if(!is_null($stok)) {
            if ($stok < 0) return false;

            $updatable['stok'] = $stok;
        }

        $updated = $this->berasRepository->update($beras, $updatable);

        if (false === $updated) return false;

        return $this->berasRepository->findById($beras->getId());
    }

    public function hapusDataBeras(int $id): bool
    {
        return $this->berasRepository->remove($id);
    }

    public function rubahStokBeras(int $id, int $stokBaru): false|Beras
    {
        $beras = $this->berasRepository->findById($id);
        if (is_null($beras))  return false;

        $updatable = ['stok' => $beras->getStok()];
        if($stokBaru) {
            if ($stokBaru < 0) return false;

            $updatable['stok'] = $stokBaru;
        }

        return $this->berasRepository->update($beras, $updatable);
    }

    public function listBeras(int $total = 10, int $start = 0): array
    {
        return $this->berasRepository->get($total, $start, 'jenis', 'ASC');
    }
}