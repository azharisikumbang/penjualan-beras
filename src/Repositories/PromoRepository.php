<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Promo.php';

class PromoRepository extends BaseRepository
{
    private string $table = 'promo';

    public function save(Promo $promo): false|Promo
    {
        $created = $this->create([
            'jenis_promo' => $promo->getJenisPromo(),
            'kode_kupon' => $promo->getKodeKupon(),
            'tanggal_kadaluarsa' => $promo->getTanggalKadaluarsa()?->format('Y-m-d'),
            'minimum_pembelian' => $promo->getMinimumPembelian(),
            'potongan_harga' => $promo->getPotonganHarga()
        ]);

        if (!$created) return false;
        $promo->setId((int) $created);

        return $promo;
    }

    public function isKuponKodeExists(string $kupon): bool
    {
        $query = "SELECT EXISTS(SELECT id FROM {$this->getTable()} WHERE kode_kupon = :kode_kupon) as 'exists'";

        return $this->existsBy($query, ['kode_kupon' => $kupon]);
    }

    public function all() : array
    {
        $query = "SELECT * FROM {$this->getTable()} ORDER BY tanggal_kadaluarsa";
        $stmt = $this->execute($query);

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }

    public function findWhereNotOutOfDate()
    {
        $query = "SELECT * FROM promo WHERE tanggal_kadaluarsa >= CURDATE()";
        $stmt = $this->execute($query);

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Promo
    {
        $promo = new Promo();
        $promo->setId($rows['id']);
        $promo->setJenisPromo($rows['jenis_promo']);
        $promo->setKodeKupon($rows['kode_kupon']);
        $promo->setMinimumPembelian($rows['minimum_pembelian']);
        $promo->setPotonganHarga($rows['potongan_harga']);
        $promo->setTanggalKadaluarsa(
            is_null($rows['tanggal_kadaluarsa']) ? null : date_create($rows['tanggal_kadaluarsa'])
        );

        return $promo;
    }
}
