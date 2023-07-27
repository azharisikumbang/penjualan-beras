<?php

require_once __DIR__ . '/../Repositories/PelangganRepository.php';
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
        return $this->pelangganRepository->get($total, $start, 'nama', 'ASC');
    }
}
