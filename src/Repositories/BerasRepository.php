<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Beras.php';

class BerasRepository extends BaseRepository
{
    private string $table = 'beras';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Beras
    {
        $beras = new Beras();
        $beras->setId($rows['id']);
        $beras->setJenis($rows['jenis']);

        return $beras;
    }

    public function save(Beras $entity): false|Beras
    {
        $attributes = [
            'jenis' => $entity->getJenis()
        ];

        return $this->basicSave($entity, $attributes);
    }

    public function get(int $length = 10, int $start = 0, string $order = 'jenis', string $by = 'ASC'): array
    {
        $query = "SELECT b.*, vt.id as vt_id, vt.variant as vt_variant
            FROM beras b
            JOIN stok s on b.id = s.beras_id
            JOIN varian_takaran vt on s.varian_takaran_id = vt.id
            ORDER BY {$order} {$by} 
            LIMIT {$start}, {$length}";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() < 1) return [];

        $result = [];
        $beras = null;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($beras) || $beras->getId() != $row['id']) $beras = $this->toEntity($row);

            $takaran = new Takaran();
            $takaran->setId($row['vt_id']);
            $takaran->setVariant($row['vt_variant']);
            $beras->addTakaran($takaran);

            $result[$row['id']] = $beras;
        }

        return array_values($result);
    }

    public function findById(int $id): ?Beras
    {
        $query = "SELECT b.*, vt.id as vt_id, vt.variant as vt_variant
            FROM beras b
            JOIN stok s on b.id = s.beras_id
            JOIN varian_takaran vt on s.varian_takaran_id = vt.id
            WHERE beras_id = :beras_id";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute(['beras_id' => $id]);

        if ($stmt->rowCount() < 1) return null;

        $beras = null;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($beras)) $beras = $this->toEntity($row);

            $takaran = new Takaran();
            $takaran->setId($row['vt_id']);
            $takaran->setVariant($row['vt_variant']);
            $beras->addTakaran($takaran);
        }

        return $beras;
    }
}