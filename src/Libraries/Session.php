<?php

require_once __DIR__ . '/../Contract/SessionInterface.php';
require_once __DIR__ . '/../Entities/Akun.php';
require_once __DIR__ . '/../Enum/Role.php';

class Session implements SessionInterface
{
    public function __construct()
    {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    }

    public function all() : array
    {
        return $_SESSION ?? [];
    }

    public function add(string $key, mixed $value) : void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key) : mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove(string $key) : void
    {
        unset($_SESSION[$key]);
    }

    public function destroy() : void
    {
        session_destroy();
    }

    public function exists(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }

    public function auth(): ?Akun
    {
        $user = session('auth');
        if(is_null($user)) return null;

        $akun = new Akun();
        $akun->setId($user['id']);
        $akun->setUsername($user['username']);
        $akun->setRole(Role::from(strtolower($user['role'])));

        return $akun;
    }

    public function isAuthenticatedAs(string|Role $role): bool
    {
        $auth = $this->auth();
        $role = is_string($role) ? Role::from(strtolower($role)) : $role;

        return $auth && $auth->getRole()->value == $role->value;
    }
}