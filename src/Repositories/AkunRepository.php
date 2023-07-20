<?php

require_once __DIR__ . '/../Contract/RepositoryInterface.php';
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Akun.php';

class AkunRepository extends BaseRepository
{
    private string $table = 'akun';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Akun
    {
        $akun = new Akun();
        $akun->setId($rows['id']);
        $akun->setUsername($rows['username']);
        $akun->setPassword($rows['password']);
        $akun->setRole(Role::from($rows['role']));

        return $akun;
    }

    public function save(Akun $entity): false|Akun
    {
        $attributes = [
            'username' => $entity->getUsername(),
            'password' => $entity->getPassword(),
            'role' => $entity->getRole()->value
        ];

        return $this->basicSave($entity, $attributes);
    }
}