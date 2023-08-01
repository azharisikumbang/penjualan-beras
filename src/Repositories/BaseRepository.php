<?php

require_once __DIR__ . '/../Contract/RepositoryInterface.php';

abstract class BaseRepository
{
    public function __construct(private ?PDO $db = null) {}

    protected function getDatabaseConnection() : ?PDO
    {
        // TODO: Implement database connection
         return $this->db ?? app()->getManager()->getDatabaseManager()->getInstance();
    }

    public function findById(int $id): ?EntityInterface
    {
        $stmt = $this->getDatabaseConnection()->prepare("SELECT * from {$this->getTable()} WHERE id = :id");
        return $stmt->execute(['id' => $id]) ? $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC)) : null;
    }

    public function get(int $length = 10, int $start = 0, string $order = 'id', string $by = 'DESC'): array
    {
        $query = "SELECT * FROM {$this->getTable()} ORDER BY {$order} {$by} LIMIT {$start}, {$length}";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }

    public function create(array $bind): false|string
    {
        $bindKeys = [];
        foreach ($bind as $attr => $value) $bindKeys[] = ":" . $attr;

        $valueKeys = implode(", ", array_keys($bind));
        $bindKeys = implode(", ", $bindKeys);

        $query = $this
            ->getDatabaseConnection()
            ->prepare("INSERT INTO {$this->getTable()} ($valueKeys) VALUES({$bindKeys})");

        return $query->execute($bind) ? $this->getDatabaseConnection()->lastInsertId() : false;
    }

    public function update(int|EntityInterface $entity, array $attributes): bool
    {
        if (false === $this->exists($entity)) return false;

        $bindKeys = [];
        foreach ($attributes as $attr => $value) $bindKeys[] = $attr . " = :" . $attr;
        $bindKeys = implode(", ", $bindKeys);

        $query = $this
            ->getDatabaseConnection()
            ->prepare("UPDATE {$this->getTable()} SET {$bindKeys} WHERE id = :id");

        return $query->execute([
            'id' => is_int($entity) ? $entity : $entity->getId(),
            ...$attributes
        ]);

    }

    public function remove(int|EntityInterface $entity): bool
    {
        $query = "DELETE FROM {$this->getTable()} WHERE id = :id";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        return $stmt->execute(['id' => is_int($entity) ? $entity: $entity->getId()]);
    }

    public function exists(int|EntityInterface $entity): bool
    {
//        if($entity instanceof EntityInterface) {
//            if(is_null($entity->getId())) return false;
//
//            $id = $entity->getId();
//        }

        $query = "SELECT EXISTS(SELECT id FROM {$this->getTable()} WHERE id = :id) as 'exists'";

        return $this->existsBy($query, ['id' => is_int($entity) ? $entity : $entity->getId() ]);
    }

    public function existsBy(string $query, array $bind): bool
    {
        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute($bind);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC)['exists'];
    }

    public function findBy(string $column, mixed $value, string $agg = "="): ?EntityInterface
    {
        $query = "SELECT * from {$this->getTable()} WHERE {$column} {$agg} :{$column}";
        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute([$column => $value]);

        return $stmt->rowCount() ? $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC)) : null;
    }

    protected function execute($query, array $bind = []) : false|PDOStatement
    {
        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute($bind);

        return $stmt;
    }

    protected function basicSave(EntityInterface $entity, array $attributes = []): false|EntityInterface
    {
        if($this->exists($entity)) return $this->update($entity, $attributes);

        $insertedId = $this->create($attributes);
        if(!$insertedId) return false;

        $entity->setId((int) $insertedId);

        return $entity;
    }

    abstract protected function getTable(): string;

    abstract protected function toEntity(array $rows): EntityInterface;
}

