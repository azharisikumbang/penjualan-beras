<?php

require_once __DIR__ . '/../Repositories/TakaranRepository.php';
require_once __DIR__ . '/../Entities/Takaran.php';

class KelolaTakaran
{
    private TakaranRepository $takaranRepository;

    public function __construct()
    {
        $this->takaranRepository = new TakaranRepository();
    }

    public function tambahkanDataTakaran(
        string $variant
    ): false|Takaran {
        $takaran = new Takaran();
        $takaran->setVariant($variant);

        return $this->takaranRepository->save($takaran);
    }

    public function rubahDataTakaran(
        int $id,
        ?string $variant = null
    ): false|Takaran {
        if (is_null($variant) || $variant == '') return false;

        $takaran = $this->takaranRepository->findById($id);
        if (is_null($takaran)) return false;

        $updated = $this->takaranRepository->update($takaran, ['variant' => $variant]);
        if(false === $updated) return false;

        return $this->takaranRepository->findById($takaran->getId());
    }

    public function hapusDataTakaran(int $id): bool
    {
        return $this->takaranRepository->remove($id);
    }

    public function listTakaran(int $total = 10, int $start = 0): array
    {
        return $this->takaranRepository->get($total, $start, 'variant', 'ASC');
    }
}
