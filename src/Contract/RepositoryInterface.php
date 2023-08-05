<?php

require_once __DIR__ . '/EntityInterface.php';

interface RepositoryInterface
{
    public function exists(int $id): bool;

    public function findById(int $ind): ?EntityInterface;

    public function findBy(string $column, mixed $value, string $agg = '='): ?EntityInterface;

    public function get(int $from = 0, int $total = 10, string $order = 'order', string $by = 'id') : array;

    public function save(EntityInterface $entity): false|EntityInterface;

    public function remove(int $id): bool;
}
