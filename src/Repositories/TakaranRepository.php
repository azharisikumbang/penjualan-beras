<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Takaran.php';

class TakaranRepository extends BaseRepository
{
    private string $table = 'varian_takaran';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Takaran
    {
        $takaran = new Takaran();
        $takaran->setId($rows['id']);
        $takaran->setVariant($rows['variant']);

        return $takaran;
    }

    public function save(Takaran $entity): false|Takaran
    {
        $attributes = [
            'variant' => $entity->getVariant()
        ];

        return $this->basicSave($entity, $attributes);
    }
}