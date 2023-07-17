<?php

enum StatusPembayaran : string
{
    case BELUM_BAYAR = 'BELUM_BAYAR';

    case BAYAR_SETENGAH = 'BAYAR_SETENGAH';

    case LUNAS = 'LUNAS';
}