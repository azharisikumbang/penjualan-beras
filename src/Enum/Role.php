<?php

enum Role : string
{
    case ADMIN = 'admin';

    case PELANGGAN = 'pelangggan';

    case KARYAWAN = 'karyawan';

    case PUBLIC = 'public';

    public function pageTemplate()
    {
        return match ($this) {
            Role::KARYAWAN => 'karyawan',
            Role::PELANGGAN => 'pelanggan',
            Role::ADMIN => 'admin',
            default => 'public'
        };
    }

    public function redirectPage()
    {
        return match ($this) {
            Role::KARYAWAN => 'karyawan',
            Role::PELANGGAN => 'pelanggan',
            Role::ADMIN => 'admin',
            default => 'public'
        };
    }
}
