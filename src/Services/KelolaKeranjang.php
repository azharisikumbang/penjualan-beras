<?php

require_once __DIR__ . '/../Object/Keranjang/Keranjang.php';
require_once __DIR__ . '/../Object/Keranjang/Item.php';
require_once __DIR__ . '/../Repositories/BerasRepository.php';
require_once __DIR__ . '/../Repositories/TakaranRepository.php';
require_once __DIR__ . '/../Repositories/StokRepository.php';

class KelolaKeranjang
{
    public function buatKeranjangKosong() : Keranjang
    {
        return new Keranjang();
    }

    public function tambahkanProdukKeKeranjang(?string $key, int|Beras $beras, int|Takaran $takaran, int $jumlahBeli): Keranjang
    {
        /** @var $keranjang Keranjang */
        $keranjang = $this->get();

        if(is_string($key) && $key != '') {
            $item = $keranjang->search($key);
            if(is_null($item)) return $keranjang;

            $keranjang->updateItem($item, $jumlahBeli);
            session()->add('keranjang', $keranjang->toArray());

            return $keranjang;
        }

        if(is_int($beras)) {
            $berasRepository = new BerasRepository();
            $beras = $berasRepository->findById($beras);

            if(!$beras) return $keranjang;
        }

        if(is_int($takaran)) {
            $berasRepository = new TakaranRepository();
            $takaran = $berasRepository->findById($takaran);

            if(!$takaran) return $keranjang;
        }

        $stokRepository = new StokRepository();
        $stok = $stokRepository->findByBerasAndTakaran($beras, $takaran);
        if (is_null($stok)) return $keranjang;

        $item = Item::create($stok, $jumlahBeli);
        $keranjang->addItem($item);
        session()->add('keranjang', $keranjang->toArray());

        return $keranjang;
    }

    public function get() : Keranjang
    {
        $keranjang = session('keranjang');
        if(is_null($keranjang) || empty($keranjang)) {
            $keranjang = $this->buatKeranjangKosong();
            session()->add('keranjang', $keranjang->toArray());
        }

        return Keranjang::buildFromSessionArray(session('keranjang'));
    }

    public function hapusItemDariKeranjang(string $key) : Keranjang
    {
        /** @var $keranjang Keranjang */
        $keranjang = $this->get();

        if($keranjang->exists($key)) {
            $keranjang->removeItem($key);
            session()->add('keranjang', $keranjang->toArray());
        }

        return $keranjang;
    }
}
