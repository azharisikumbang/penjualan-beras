<?php

enum KonfirmasiPembayaran: string
{
    case BELUM_DIKONFIRMASI = 'BELUM_DIKONFIRMASI';

    case MENUNGGU_KONFIRMASI = 'MENUNGGU_KONFIRMASI';

    case DITOLAK = 'DITOLAK';

    case DITERIMA = 'DITERIMA';
}