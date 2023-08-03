<?php

enum KonfirmasiPembayaran: string
{
    case BELUM_BAYAR = 'BELUM_BAYAR';

    case MENUNGGU_KONFIRMASI = 'MENUNGGU_KONFIRMASI';

    case DITOLAK = 'DITOLAK';

    case DITERIMA = 'DITERIMA';

    public function getDisplay()
    {
        return match ($this) {
            self::MENUNGGU_KONFIRMASI => 'MENUNGGU KONFIRMASI ADMIN',
            self::DITOLAK => 'DITOLAK',
            self::DITERIMA => 'DITERIMA',
            default => 'BELUM BAYAR'
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::MENUNGGU_KONFIRMASI => 'yellow',
            self::DITOLAK => 'red',
            self::DITERIMA => 'green',
            default => 'gray'
        };
    }

    public static function toArray() : array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[] = [
                'name' => $case->name,
                'value' => $case->value,
                'color' => $case->getColor(),
                'display_as' => $case->getDisplay()
            ];
        }

        return $result;
    }
}