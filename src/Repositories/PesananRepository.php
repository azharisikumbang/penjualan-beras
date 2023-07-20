<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Pesanan.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';

class PesananRepository extends BaseRepository
{
    private string $table = 'pesanan';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): EntityInterface
    {
        $pelanggan = new Pelanggan();
        $pelanggan->setId($rows['pemesan_id']);

        $pesanan = $this->toPesanan($rows);
        $pesanan->setPemesan($pelanggan);

        return $pesanan;
    }

    private function toPesanan(array $rows): Pesanan
    {
        $pesanan = new Pesanan();
        $pesanan->setId($rows['id']);
        $pesanan->setNomorPesanan($rows['nomor_pesanan']);
        $pesanan->setNomorIterasiPesanan($rows['nomor_iterasi_pesanan']);
        $pesanan->setNamaPesanan($rows['nama_pemesan']);
        $pesanan->setTanggalPemesanan(date_create($rows['tanggal_pemesanan']));
        $pesanan->setAlamatPengiriman($rows['alamat_pengiriman']);
        $pesanan->setTotalTagihan($rows['total_tagihan']);

        return $pesanan;
    }
}
