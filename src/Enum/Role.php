<?php

enum Role : string
{
    case ADMIN = 'admin';

    case PELANGGAN = 'pelanggan';

    case PIMPINAN = 'pimpinan';

    case PUBLIC = 'public';

    public function pageTemplate()
    {
        return match ($this) {
            Role::PIMPINAN => 'pimpinan',
            Role::PELANGGAN => 'pelanggan',
            Role::ADMIN => 'admin',
            default => 'public'
        };
    }

    public function redirectPage()
    {
        return match ($this) {
            Role::PIMPINAN => 'pimpinan/beranda',
            Role::PELANGGAN => 'pelanggan/beranda',
            Role::ADMIN => 'admin',
            default => 'public'
        };
    }
}
