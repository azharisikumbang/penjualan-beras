<?php

require_once __DIR__ . '/../Repositories/PesananRepository.php';

class KelolaPesanan
{
    private readonly PesananRepository $pesananRepository;

    private readonly TransaksiRepository $transaksiRepository;

    private readonly DetailPesananRepository $detailPesananRepository;

    public function __construct()
    {
        $this->pesananRepository = new PesananRepository();
        $this->transaksiRepository = new TransaksiRepository();
        $this->detailPesananRepository = new DetailPesananRepository();
    }

    public function buatPesananBaru(Keranjang $keranjang): false|Pesanan
    {
        $nomorPesananArray = $this->createNomorPesanan();
        $transaksi = Transaksi::makeEmpty();

        $pesanan = new Pesanan();
        $pesanan->setNomorPesanan($nomorPesananArray['nomor_pesanan']);
        $pesanan->setNomorIterasiPesanan($nomorPesananArray['nomor_iterasi_pesanan']);
        $pesanan->setNamaPesanan($keranjang->getPembeli()->getNama());
        $pesanan->setTanggalPemesanan(date_create('now'));
        $pesanan->setAlamatPengiriman($keranjang->getPengiriman()->getAlamat());
        $pesanan->setTotalTagihan($keranjang->getTotal());
        $pesanan->setTransaksi($transaksi);

        foreach ($keranjang->getItems() as $detail) $pesanan->addDetailPesanan($detail);

        return $this->pesananRepository->save($pesanan, $this->transaksiRepository, $this->detailPesananRepository);
    }

    public function terimaPembayaran(string $nomor): bool
    {
        $pesanan = $this->pesananRepository->findBy('nomor_pesanan', $nomor);
        if(is_null($pesanan)) return false;

        $transaksi = $pesanan->getTransaksi();
        if(is_null($transaksi)) return false;

        return (bool) $this->transaksiRepository->update($transaksi, [
            'konfirmasi_pembayaran' => KonfirmasiPembayaran::DITERIMA
        ]);
    }

    public function tolakPembayaran(string $nomor): bool
    {
        $pesanan = $this->pesananRepository->findBy('nomor_pesanan', $nomor);
        if(is_null($pesanan)) return false;

        $transaksi = $pesanan->getTransaksi();
        if(is_null($transaksi)) return false;

        return (bool) $this->transaksiRepository->update($transaksi, [
            'konfirmasi_pembayaran' => KonfirmasiPembayaran::DITOLAK
        ]);
    }

    public function listPesanan(int $total = 10, int $start = 0): array
    {
        return $this->pesananRepository->get($total, $start, 'tanggal_pemesanan', 'DESC');
    }

    private function createNomorPesanan() : array
    {
        $latestNomorIterasiPesanan = $this->pesananRepository->findLatestNomorPesanan();

        return [
            'nomor_iterasi_pesanan' => $latestNomorIterasiPesanan + 1,
            'nomor_pesanan' => sprintf("KANTERLEANS/%s/%s/%s", date('Y'), date('m'), $latestNomorIterasiPesanan)
        ];
    }
}