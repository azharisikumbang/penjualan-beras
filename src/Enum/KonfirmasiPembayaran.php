<?php

enum KonfirmasiPembayaran: string
{
    case BELUM_BAYAR = 'BELUM_BAYAR';

    case MENUNGGU_KONFIRMASI = 'MENUNGGU_KONFIRMASI';

    case DITOLAK = 'DITOLAK';

    case DITERIMA = 'DITERIMA';
}