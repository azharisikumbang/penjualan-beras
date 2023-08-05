<?php

require_once __DIR__ . '/../Repositories/BerasRepository.php';
require_once __DIR__ . '/../Entities/Stok.php';
require_once __DIR__ . '/../Entities/DetailPesanan.php';

class StokRepository
{
    private string $table = 'stok';

    public function __construct(private ?PDO $db = null) {}

    protected function getDatabaseConnection() : ?PDO
    {
        return $this->db ?? app()->getManager()->getDatabaseManager()->getInstance();
    }

    public function get(int $length = 10, int $start = 0, bool $withRelations = false): array
    {
        $query = "SELECT s.*, b.jenis as b_jenis, vt.variant as vt_variant
            FROM {$this->getTable()} s
            JOIN varian_takaran vt on vt.id = s.varian_takaran_id
            JOIN beras b on b.id = s.beras_id
            ORDER BY b.jenis
            LIMIT {$start}, {$length}";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row, $withRelations);

        return $result;
    }

    public function findByBeras(int|Beras $beras, BerasRepository $berasRepository): array
    {
        $beras = is_int($beras) ? $berasRepository->findById($beras) : $beras;

        $query = "SELECT s.*, vt.variant as vt_variant
            FROM stok s 
            JOIN beras b on b.id = s.beras_id 
            JOIN varian_takaran vt on vt.id = s.varian_takaran_id
            WHERE s.beras_id = :beras_id
            ORDER BY b.jenis";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute([ 'beras_id' => $beras->getId() ]);

        if ($stmt->rowCount() < 1) return [];
        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stok = new Stok();
            $stok->setBeras($beras);
            $stok->setHarga($row['harga']);
            $stok->setStok($row['jumlah_stok']);

            $takaran = new Takaran();
            $takaran->setId($row['varian_takaran_id']);
            $takaran->setVariant($row['vt_variant']);
            $stok->setTakaran($takaran);

            $result[] = $stok;
        }

        return $result;
    }

    public function findByBerasAndTakaran(int|Beras $beras, int|Takaran $takaran): ?Stok
    {
        $query = "SELECT s.*, vt.variant as vt_variant, b.jenis as b_jenis
            FROM stok s 
            JOIN beras b on b.id = s.beras_id 
            JOIN varian_takaran vt on vt.id = s.varian_takaran_id
            WHERE s.beras_id = :beras_id AND s.varian_takaran_id = :takaran_id
            ORDER BY b.jenis";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute([
            'beras_id' => is_int($beras) ? $beras : $beras->getId(),
            'takaran_id' => is_int($takaran) ? $takaran : $takaran->getId()
        ]);

        if ($stmt->rowCount() < 1) return null;

        return $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC), true);
    }

    public function save(Stok $stok): bool
    {
        $query = "INSERT INTO {$this->getTable()} (beras_id, varian_takaran_id, jumlah_stok, harga) VALUES (:beras_id, :varian_takaran_id, :jumlah_stok, :harga)";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        return $stmt->execute([
           'beras_id' => $stok->getBeras()->getId(),
           'varian_takaran_id' => $stok->getTakaran()->getId(),
           'jumlah_stok' => $stok->getStok(),
           'harga' => $stok->getHarga()
        ]);
    }

    public function update(Stok $stok): bool
    {
        $query = "UPDATE {$this->getTable()} SET jumlah_stok = :jumlah_stok, harga = :harga WHERE beras_id = :beras_id AND varian_takaran_id = :takaran_id";
        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute([
            'beras_id' => $stok->getBerasId(),
            'takaran_id' => $stok->getTakaranId(),
            'jumlah_stok' => $stok->getStok(),
            'harga' => $stok->getHarga()
        ]);
    }

    public function deleteByTakaran(int|Takaran $takaran): bool
    {
        $takaran = is_int($takaran) ? $takaran : $takaran->getId();
        $query = "DELETE FROM {$this->getTable()} WHERE varian_takaran_id = :varian_takaran_id";
        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute(['varian_takaran_id' => $takaran]);
    }

    public function deleteByBerasAndTakaran(int|Beras $beras, int|Takaran $takaran): bool
    {
        $takaran = is_int($takaran) ? $takaran : $takaran->getId();
        $beras = is_int($beras) ? $beras : $beras->getId();

        $query = "DELETE FROM {$this->getTable()} WHERE varian_takaran_id = :varian_takaran_id AND beras_id = :beras_id";
        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute([
            'varian_takaran_id' => $takaran,
            'beras_id' => $beras
        ]);
    }

    public function deleteByBeras(int|Beras $beras) : bool
    {
        $beras = is_int($beras) ? $beras : $beras->getId();
        $query = "DELETE FROM {$this->getTable()} WHERE beras_id = :beras_id";
        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute(['beras_id' => $beras]);
    }

    protected function toEntity(array $rows, bool $withRelations = false) : Stok
    {
        $stok = new Stok();
        $stok->setStok($rows['jumlah_stok']);
        $stok->setHarga($rows['harga']);
        $stok->setBerasId($rows['beras_id']);
        $stok->setTakaranId($rows['varian_takaran_id']);

        if($withRelations) {
            $stok->createBerasRelations($rows['beras_id'], $rows['b_jenis']);
            $stok->createTakaranRelations($rows['varian_takaran_id'], $rows['vt_variant']);
        }

        return $stok;
    }

    protected function getTable(): string
    {
        return $this->table;
    }

    public function updateBatchStok(array $listDetailPesanan) : bool
    {
        $dbh = $this->getDatabaseConnection();

        if ($dbh->beginTransaction()) {
            try {
                /** @var $detailPesanan DetailPesanan */
                foreach ($listDetailPesanan as $detailPesanan) {
                    $existsStok = $this->findByBerasAndTakaran($detailPesanan->getRefBerasId(), $detailPesanan->getRefTakaranId());
                    if ($existsStok) {
                        if ($detailPesanan->getJumlahBeli() > $existsStok->getStok()) continue;
                        $stokBaru = $existsStok->getStok() - $detailPesanan->getJumlahBeli();
                        $existsStok->setStok($stokBaru);

                        $this->update($existsStok);
                    }
                }

                $dbh->commit();

                return true;
            }
            catch (Exception $exception) {
                $dbh->rollBack();
                var_dump($exception->getMessage());
            }
        }

        return false;
    }

    public function getDataForLaporanStokBeras() : array
    {
        $query = "SELECT
                b.jenis as b_jenis,
                vt.variant as vt_variant,
                SUM(dp.jumlah_beli) as stok_terjual,
                s.jumlah_stok,
                s.harga
            FROM stok s
            JOIN varian_takaran vt on vt.id = s.varian_takaran_id
            JOIN beras b on b.id = s.beras_id
            LEFT JOIN detail_pesanan dp on dp.ref_beras_id = s.beras_id AND dp.ref_takaran_id = s.varian_takaran_id
            GROUP BY s.beras_id, s.varian_takaran_id";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() < 1) return [];

        $result = [];
        $number = 1;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [
                [
                    'type' => 'number',
                    'value' => $number++
                ],[
                    'type' => 'text',
                    'value' => $row['b_jenis']
                ],[
                    'type' => 'text',
                    'value' => $row['vt_variant']
                ],[
                    'type' => 'currency',
                    'value' => $row['harga']
                ],[
                    'type' => 'number',
                    'value' => $row['jumlah_stok']
                ],[
                    'type' => 'number',
                    'value' =>  (int) $row['stok_terjual']
                ]
            ];
        }

        return $result;
    }
}