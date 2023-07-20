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
        $beras->setHarga($rows['harga']);
        $beras->setStok($rows['stok']);

        return $beras;
    }

    public function save(Beras $entity): false|Beras
    {
        $attributes = [
            'jenis' => $entity->getJenis(),
            'harga' => $entity->getHarga(),
            'stok' => $entity->getStok()
        ];

        return $this->basicSave($entity, $attributes);
    }
}