<?php

require_once __DIR__ . '/../Object/Keranjang/Keranjang.php';
require_once __DIR__ . '/../Object/Keranjang/Item.php';
require_once __DIR__ . '/../Repositories/BerasRepository.php';

class KelolaKeranjang
{
    private BerasRepository $berasRepository;

    public function __construct()
    {
        $this->berasRepository = new BerasRepository();
    }

    public function buatKeranjangKosong() : Keranjang
    {
        return new Keranjang();
    }

    public function tambahkanProdukKeKeranjang(?string $key, int|Beras $beras, int $jumlahBeli): Keranjang
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

        $beras = is_int($beras) ? $this->berasRepository->findById($beras) : $beras;
        if(!$beras) return $keranjang;
        $item = Item::create($beras, $jumlahBeli);
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
