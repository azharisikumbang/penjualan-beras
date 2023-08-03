<?php

enum StatusPembayaran : string
{
    case BELUM_BAYAR = 'BELUM_BAYAR';

    case BAYAR_SETENGAH = 'BAYAR_SETENGAH';

    case LUNAS = 'LUNAS';

    public function getDisplay()
    {
        return match ($this) {
            self::BAYAR_SETENGAH => 'BAYAR SEBAGIAN',
            self::LUNAS => 'LUNAS',
            default => 'BELUM BAYAR'
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::BAYAR_SETENGAH => 'yellow',
            self::LUNAS => 'green',
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