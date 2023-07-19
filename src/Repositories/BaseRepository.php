<?php

require_once __DIR__ . '/../Contract/RepositoryInterface.php';
require_once __DIR__ . '/../Contract/EntityInterface.php';

abstract class BaseRepository
{
    public function __construct(private ?PDO $db = null) {}

    protected function getDatabaseConnection() : ?PDO
    {
        // TODO: Implement database connection
         return $this->db ?? app()->getManager()->getDatabaseManager()->getInstance();
    }

    protected function basicFindById(int $id, string $select = "*"): ?EntityInterface
    {
        $stmt = $this->getDatabaseConnection()->prepare("SELECT {$select} from {$this->getTable()} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function basicGet(int $length = 10, int $start = 0, string $order = 'id', string $by = 'DESC'): array
    {
        $query = "SELECT * FROM {$this->getTable()} ORDER BY {$order} {$by} LIMIT {$start}, {$length}";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }

    protected function basicCreate(array $bind): false|EntityInterface
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

    protected function basicUpdate(int|EntityInterface $entity, array $attributes): bool
    {
        if (false === $this->basicExists($entity)) return false;

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

    protected function basicDelete(int|EntityInterface $entity): bool
    {
        $query = "DELETE FROM {$this->getTable()} WHERE id = :id";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        return $stmt->execute(['id' => is_int($entity) ? $entity: $entity->getId()]);
    }

    protected function basicExists(int|EntityInterface $entity): bool
    {
        $query = "SELECT EXISTS(SELECT id FROM {$this->getTable()} WHERE id = :id) as 'exists'";

        return $this->basicExistsBy($query, ['id' => is_int($entity) ? $entity : $entity->getId() ]);
    }

    protected function basicExistsBy(string $query, array $bind): bool
    {
        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute($bind);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC)['exists'];
    }

    abstract protected function getTable(): string;

    abstract protected function toEntity(array $rows): EntityInterface;
}

