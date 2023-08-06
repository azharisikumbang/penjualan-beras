<?php

require_once __DIR__ . '/../Repositories/StokRepository.php';

class KelolaStok
{
    private StokRepository $stokRepository;

    public function __construct()
    {
        $this->stokRepository = new StokRepository();
    }

    public function listStokBeras(int $total = 10, int $start = 1): array
    {
        $start = ($total * $start) - $total;
        $listStokBeras = $this->stokRepository->get($total, $start, withRelations: true);

        return array_map(fn($item) => $item->toArray(), $listStokBeras);
    }

    public function perbaharuiData(
        int $berasId,
        int $takaranId,
        int $stok,
        float $harga
    ): false|Stok {
        $stokEntity = new Stok();
        $stokEntity->setBerasId($berasId);
        $stokEntity->setTakaranId($takaranId);
        $stokEntity->setHarga($harga);
        $stokEntity->setStok($stok);

        $updated = $this->stokRepository->update($stokEntity);

        return $updated ? $this->stokRepository->findByBerasAndTakaran($stokEntity->getBerasId(), $stokEntity->getTakaranId()) : false;
    }

    private function sortingStokByJenisBeras(array $unsorted): array
    {
        $sorted = [];
        /** @var $stok Stok */
        foreach ($unsorted as $stok) {
            if (!isset($result[$stok->getBerasId()])) $sorted[$stok->getBerasId()] = [];

            $sorted[$stok->getBerasId()]['beras'] = $stok->getBeras()->toArray();
            $sorted[$stok->getBerasId()]['stok'][] = [
                'takaran' => $stok->getTakaran()->toArray(),
                'harga' => $stok->getHarga(),
                'stok' => $stok->getStok()
            ];
        }

        return array_values($sorted);
    }
}
