<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/../Enum/Role.php';

class Akun implements EntityInterface
{
    private int $id;

    private string $username;

    private string $password;

    private Role $role;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function toArray(bool $showPassword = false): array
    {
        return [
          'id' => $this->getId(),
          'username' => $this->getUsername(),
          'password' => $showPassword ? $this->getPassword() : null,
          'role' => $this->getRole()->value
        ];
    }
}
