<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Karyawan.php';
require_once __DIR__ . '/../Enum/Jabatan.php';

class KaryawanRepository extends BaseRepository
{
    private string $table = 'karyawan';

    protected function getTable(): string
    {
        return $this->table;
    }

    public function findById(int $id): ?Karyawan
    {
        $query = "SELECT k.*, a.username as akun_username, a.password as akun_password, a.role as akun_role 
            FROM {$this->getTable()} k JOIN akun a on k.akun_id = a.id
            WHERE k.id = :id";

        $stmt = $this->getDatabaseConnection()->prepare($query);

        return $stmt->execute(['id' => $id]) ? $this->toEntity($stmt->fetch(PDO::FETCH_ASSOC)) : null;
    }

    public function get(int $length = 10, int $start = 0, string $order = 'id', string $by = 'DESC'): array
    {
        $query = "SELECT k.*, a.username as akun_username, a.password as akun_password, a.role as akun_role 
            FROM {$this->getTable()} k JOIN akun a on k.akun_id = a.id
            ORDER BY {$order} {$by} LIMIT {$start}, {$length}";;

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

        return $result;
    }


    protected function toEntity(array $rows): Karyawan
    {
        $akun = new Akun();
        $akun->setId($rows['akun_id']);
        $akun->setUsername($rows['akun_username']);
        $akun->setPassword($rows['akun_password'], false);
        $akun->setRole(Role::from($rows['akun_role']));

        $keryawan = new Karyawan();
        $keryawan->setId($rows['id']);
        $keryawan->setNama($rows['nama']);
        $keryawan->setKontak($rows['kontak']);
        $keryawan->setJabatan(Jabatan::from($rows['jabatan']));
        $keryawan->setAkun($akun);

        return $keryawan;
    }
}