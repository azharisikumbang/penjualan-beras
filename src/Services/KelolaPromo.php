<?php

require_once __DIR__ . '/../Repositories/PromoRepository.php';
require_once __DIR__ . '/../Libraries/Whatsapp.php';

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

    public function cekKodeKupon(string $kupon): ?Promo
    {
        /** @var $promo Promo */
        $promo = $this->promoRepository->findBy('kode_kupon', $kupon);
        if (is_null($promo) || $promo->isOutOfDate()) return null;

        return $promo;
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

    public function broadcastPromo(array $targetPhoneNumbers, string $kupon) : bool
    {
        $promo = $this->cekKodeKupon($kupon);
        if (is_null($promo)) return false;

        $messageBody = [
            ['type' => 'text', 'text' => $promo->getNominalDiskonAsString()], // nominal diskon,
            ['type' => 'text', 'text' => $promo->getKodeKupon()], // kode promo
            ['type' => 'text', 'text' => rupiah($promo->getMinimumPembelian())], // minimum pembelian
            ['type' => 'text', 'text' => tanggal($promo->getTanggalKadaluarsa())], // tanggal kadaluarsa
        ];

        foreach ($targetPhoneNumbers as $phoneNumber)
            Whatsapp::sendMessage($phoneNumber, $messageBody);

        return true;
    }

    public function broadcastStaticPromo(array $targetPhoneNumbers, string $kupon): bool
    {
        $promo = $this->cekKodeKupon($kupon);
        if (is_null($promo)) return false;

        $messageBody = sprintf("Dapatkan promo dari Bumdes untuk sebesar *%s* dengan memakai kode kupon: %s dengan minimum pembelian Rp %s untuk semua jenis beras. 
        
Jangan sampai terlewat, promo hanya berlaku sampai dengan Sabtu, %s.",
            $promo->getNominalDiskonAsString(),
            $promo->getKodeKupon(),
            rupiah($promo->getMinimumPembelian()),
            tanggal($promo->getTanggalKadaluarsa())
        );

        foreach ($targetPhoneNumbers as $phoneNumber) {
            Whatsapp::sentStaticMessage($phoneNumber, $messageBody);
        }

        return true;
    }
}
