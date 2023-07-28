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

    public function tambahkanProdukKeKeranjang(int|Beras $beras, int $jumlahBeli): Keranjang
    {
        $keranjang = $this->get();

        $beras = is_int($beras) ? $this->berasRepository->findById($beras) : $beras;
        if(!$beras) return $keranjang;

        $item = Item::create($beras, $jumlahBeli);

        /** @var $keranjang Keranjang */
        $keranjang->addItem($item);

        session()->add('keranjang', $keranjang->toArray());
        return $keranjang;
    }

    public function perbaharuiStokBeli(string $key, int $stokBaru): void
    {
        
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
}
