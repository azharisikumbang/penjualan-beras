<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Transaksi.php';
require_once __DIR__ . '/../Entities/Pesanan.php';
require_once __DIR__ . '/PesananRepository.php';

class TransaksiRepository extends BaseRepository
{
    private string $table = 'transaksi';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Transaksi
    {
        $transaksi = new Transaksi();
        $transaksi->setId($rows['is']);
        $transaksi->setNamaPembayaran($rows['nama_pembayaran']);
        $transaksi->setBankPembayaran($rows['bank_pembayaran']);
        $transaksi->setTanggalPembayaran(date_create($rows['tanggal_pembayaran']));
        $transaksi->setNominalDibayarkan($rows['nominal_dibayarkan']);
        $transaksi->setStatusPembayaran(StatusPembayaran::from($rows['status_pembayaran']));
        $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($rows['konfirmasi_pembayaran']));
        $transaksi->setFileBuktiPembayaran($rows['file_bukti_pemnayaran']);

        return $transaksi;
    }

    public function save(Transaksi $transaksi, int|Pesanan $pesanan, PesananRepository $pesananRepository) : false|Transaksi
    {
        if(is_int($pesanan)) $pesanan = $pesananRepository->findById($pesanan);

        if(false === $pesananRepository->exists($pesanan->getId())) return false;

        $transaksi->setPesanan($pesanan);

        return parent::basicSave($transaksi, [
            'tanggal_pembayaran' => $transaksi->getTanggalPembayaran()->format('Y-m-d H:i:s'),
            'bank_pembayaran' => $transaksi->getBankPembayaran(),
            'nama_pembayaran' => $transaksi->getNamaPembayaran(),
            'nominal_dibayarkan' => $transaksi->getNominalDibayarkan(),
            'status_pembayaran' => $transaksi->getStatusPembayaran()->value,
            'konfirmasi_pembayaran' => $transaksi->getKonfirmasiPembayaran()->value,
            'file_bukti_pembayaran' => $transaksi->getFileBuktiPembayaran(),
            'pesanan_id' => $pesanan->getId()
        ]);
    }
}
