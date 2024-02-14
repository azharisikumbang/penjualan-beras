<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';
require_once __DIR__ . '/../Enum/Role.php';

class PelangganRepository extends BaseRepository
{
    private string $table = 'pelanggan';

    protected function getTable(): string
    {
        return $this->table;
    }

    public function findById(int $id): ?Pelanggan
    {
        $query = "SELECT p.*, a.username as akun_username, a.password as akun_password, a.role as akun_role 
            FROM {$this->getTable()} p JOIN akun a on p.akun_id = a.id
            WHERE p.id = :id";

        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute(['id' => $id]) ? $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC)) : null;
    }

    public function findByAkunId(int $id): ?Pelanggan
    {
        $query = "SELECT * FROM {$this->getTable()} WHERE akun_id = :akun_id";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute(['akun_id' => $id]);

        if($stmt->rowCount() < 1) return null;

        return  $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC), false);
    }

    public function get(int $length = 10, int $start = 0, string $order = 'id', string $by = 'DESC'): array
    {
        $query = "SELECT p.*, a.username as akun_username, a.password as akun_password, a.role as akun_role 
            FROM {$this->getTable()} p JOIN akun a on p.akun_id = a.id
            ORDER BY {$order} {$by} LIMIT {$start}, {$length}";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }


    protected function toEntity(array $rows, bool $hasRelations = true): Pelanggan
    {
        $pelanggan = new Pelanggan();
        $pelanggan->setId($rows['id']);
        $pelanggan->setNama($rows['nama']);
        $pelanggan->setKontak($rows['kontak']);
        $pelanggan->setAlamat($rows['alamat']);
        $pelanggan->setAkunId($rows['akun_id']);

        if($hasRelations) {
            $akun = new Akun();
            $akun->setId($rows['akun_id']);
            $akun->setUsername($rows['akun_username']);
            $akun->setPassword($rows['akun_password'], false);
            $akun->setRole(Role::from($rows['akun_role']));
            $pelanggan->setAkun($akun);
        }

        return $pelanggan;
    }

    public function save(Pelanggan $entity) : false|Pelanggan
    {
        $attributes = [
            'nama' => $entity->getNama(),
            'kontak' => $entity->getKontak(),
            'alamat' => $entity->getAlamat(),
            'akun_id' => $entity->getAkun()->getId()
        ];

        return $this->basicSave($entity, $attributes);
    }

    public function findByNama(string $nama, int $length = 10, int $start = 0, string $order = 'id', string $by = 'DESC'): array
    {
        $query = "SELECT p.*, a.username as akun_username, a.password as akun_password, a.role as akun_role 
            FROM {$this->getTable()} p JOIN akun a on p.akun_id = a.id
            WHERE p.nama LIKE :nama
            ORDER BY {$order} {$by} LIMIT {$start}, {$length}";;

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute(['nama' => '%' . $nama . '%']);

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }
}