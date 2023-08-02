<?php

require_once __DIR__ . '/../Repositories/BerasRepository.php';
require_once __DIR__ . '/../Repositories/TakaranRepository.php';
require_once __DIR__ . '/../Repositories/StokRepository.php';
require_once __DIR__ . '/../Entities/Beras.php';
require_once __DIR__ . '/../Entities/Takaran.php';

class KelolaBeras
{

    private BerasRepository $berasRepository;

    private TakaranRepository $takaranRepository;

    private StokRepository $stokRepository;

    public function __construct()
    {
        $this->berasRepository = new BerasRepository();
        $this->takaranRepository = new TakaranRepository();
        $this->stokRepository = new StokRepository();
    }

    public function tambahkanBerasBaru(string $jenis, array $listTakaran = []): false|Beras
    {
        // TODO: should be  in transaction
        $beras = new Beras();
        $beras->setJenis($jenis);

        foreach ($listTakaran as $takaran) {
            $takaran = $this->takaranRepository->findById((int) $takaran);
            if(is_null($takaran)) continue;

            $beras->addTakaran($takaran);
        }

        $savedBeras = $this->berasRepository->save($beras);
        $savedBeras->setListTakaran($beras->getListTakaran());

        foreach ($savedBeras->getListTakaran() as $takaran) {
            $stok = new Stok();
            $stok->setBeras($savedBeras);
            $stok->setTakaran($takaran);
            $stok->setStok(0);
            $stok->setHarga(0);

            $isStokSaved = $this->stokRepository->save($stok);

            if (false === $isStokSaved) return false;
        }

        return $savedBeras;
    }

    public function perbaharuiDataBeras(int $id, ?string $jenis = null, array $listTakaran = []): false|Beras
    {
        if (is_null($jenis) || $jenis == '') return false;

        /** @var $beras Beras */
        $beras = $this->berasRepository->findById($id);
        if (is_null($beras)) return false;

        $listStokBerasOld = $this->stokRepository->findByBeras($beras, $this->berasRepository);
        $listTakaranExisting = array_map(fn ($item) => /** @var $item Stok */  $item->getTakaran()->getId(), $listStokBerasOld);

        foreach ($listTakaran as $takaran) {
            /** @var $takaranEntity Takaran */
            $takaranEntity = $this->takaranRepository->findById((int) $takaran);
            if(is_null($takaranEntity)) continue;

            // jika terdapat di database : continue
            if (in_array($takaran, $listTakaranExisting)) {
                if (($key = array_search($takaran, $listTakaranExisting)) !== false) {
                    // remove from existing, so it's not deleted after
                    unset($listTakaranExisting[$key]);
                }

                continue;
            };

            // jika tidak: tambahkan stok baru
            $stok = Stok::makeEmpty($beras, $takaranEntity);
            $this->stokRepository->save($stok);
        }

        // jika terdapat di database tetapi tidak terdapat pada request: delete
        foreach ($listTakaranExisting as $deleted)
            $this->stokRepository->deleteByBerasAndTakaran($beras, $deleted);

        $updated = $this->berasRepository->update($beras, ['jenis' => $jenis]);

        return $updated ? $this->berasRepository->findById($beras->getId()) : false;
    }

    public function hapusDataBeras(int $id): bool
    {
        $this->stokRepository->deleteByBeras($id);

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