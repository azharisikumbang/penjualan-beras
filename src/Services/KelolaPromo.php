<?php

require_once __DIR__ . '/../Repositories/PromoRepository.php';

class KelolaPromo
{
    private PromoRepository $promoRepository;

    public function __construct()
    {
        $this->promoRepository = new PromoRepository();
    }

    public function listPromo(int $total = 10, int $start = 1): array
    {
        if ($total < 0) {
            $listPromo = $this->promoRepository->all();
            return array_map(fn ($item) => $item->toArray(), $listPromo);
        }

        $start = ($total * $start) - 10;
        $listPromo = $this->promoRepository->get($total, $start, 'tanggal_kadaluarsa', 'ASC');

        return array_map(fn ($item) => $item->toArray(), $listPromo);
    }

    public function listPromoBukanKadaluarsa()
    {
        $listPromo = $this->promoRepository->findWhereNotOutOfDate();

        return array_map(fn ($item) => $item->toArray(), $listPromo);
    }

    public function simpanPromoBaru(
        string $jenis,
        string $kupon,
        ?DateTimeInterface $kadaluarsa,
        float $minimumPembelian,
        float $potonganHarga
    ) : false|Promo {
        $exists = $this->promoRepository->isKuponKodeExists($kupon);
        if ($exists) return false;

        $promo = new Promo();
        $promo->setJenisPromo($jenis);
        $promo->setKodeKupon($kupon);
        $promo->setMinimumPembelian($minimumPembelian);
        $promo->setTanggalKadaluarsa($kadaluarsa);
        $promo->setPotonganHarga($potonganHarga);

        return $this->promoRepository->save($promo);
    }

    public function generateCouponCode(): string
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 6; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        return $res;
    }
}
