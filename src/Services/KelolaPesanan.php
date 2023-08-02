<?php

require_once __DIR__ . '/../Repositories/PesananRepository.php';
require_once __DIR__ . '/../Repositories/AkunRepository.php';
require_once __DIR__ . '/../Libraries/Disk.php';

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

    public function buatPesananBaru(Pelanggan $pelanggan, Keranjang $keranjang): false|Pesanan
    {
        $nomorPesananArray = $this->createNomorPesanan();
        $transaksi = Transaksi::makeEmpty();

        $pesanan = new Pesanan();
        $pesanan->setNomorPesanan($nomorPesananArray['nomor_pesanan']);
        $pesanan->setNomorIterasiPesanan($nomorPesananArray['nomor_iterasi_pesanan']);
        $pesanan->setTanggalPemesanan(date_create('now'));
        $pesanan->setTotalTagihan($keranjang->getTotal());
        $pesanan->setTransaksi($transaksi);
        $pesanan->setPemesan($pelanggan);

        foreach ($keranjang->getItems() as $item) {
            /** @var $item Item */
            $detail = new DetailPesanan();
            $detail->setJumlahBeli($item->getJumlahBeli());
            $detail->setTotal($item->getTotalHarga());
            $detail->setHargaSatuan($item->getDetail()->getHarga());
            $detail->setJenisBeras($item->getDetail()->getBeras()->getJenis());
            $detail->setTakaranBeras($item->getDetail()->getTakaran()->getVariant());

            $pesanan->addDetailPesanan($detail);
        }

        return $this->pesananRepository->save($pesanan, $this->transaksiRepository, $this->detailPesananRepository);
    }

    public function cariBerdasarkanNomorPesanan(string $nomorPesanan): ?Pesanan
    {
        return $this->pesananRepository->findByNomorPesanan($nomorPesanan, detail: true);
    }

    public function cekPemilikPesanan(Pesanan $pesanan, Akun $akun): bool
    {
        if(is_null($pesanan->getPemesan()->getAkun())) {
            $akunRepository = new AkunRepository();
            $pelangganAkun = $akunRepository->findById($pesanan->getPemesan()->getAkunId());
            if(is_null($pelangganAkun)) return false;

            $pesanan->getPemesan()->setAkun($pelangganAkun);
        }

        return $pesanan->getPemesan()->getAkun()->getId() == $akun->getId();
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
        $latestNomorIterasiPesanan = $this->pesananRepository->findLatestNomorPesanan() ?? 0;
        $nomorIterasi = $latestNomorIterasiPesanan + 1;

        return [
            'nomor_iterasi_pesanan' => $nomorIterasi,
            'nomor_pesanan' => sprintf("KANTERLEANS/%s/%s/%s", date('Y'), date('m'), str_pad($nomorIterasi, 6, "0", STR_PAD_LEFT))
        ];
    }

    public function simpanInformasiPengiriman(string|Pesanan $pesanan, string $nama, string $kontak, string $alamat) : bool
    {
        $pesanan = is_string($pesanan) ? $this->pesananRepository->findByNomorPesanan($pesanan) : $pesanan;
        if(is_null($pesanan)) return false;

        return $this->pesananRepository->update($pesanan, [
            'nama_pesanan' => $nama,
            'kontak_pesanan' => $kontak,
            'alamat_pengiriman' => $alamat
        ]);
    }

    public function simpanInformasiPembayaran(string|Pesanan $pesanan, string $nama, string $bank, int|float $nominal, array $bukti) : bool
    {
        $pesanan = is_string($pesanan) ? $this->pesananRepository->findByNomorPesanan($pesanan, true) : $pesanan;
        if(is_null($pesanan)) return false;

        if (false === $this->validasiNominalBayar($pesanan, $nominal)) return false;

        $fileBuktiPembayaran = Disk::simpanBuktiPembayaran($bukti);

        $transaksi = $pesanan->getTransaksi();

        return $this->transaksiRepository->update($transaksi, [
            'nama_pembayaran' => $nama,
            'tanggal_pembayaran' => date_create('now')->format('Y-m-d H:i:s'),
            'bank_pembayaran' => $bank,
            'nominal_dibayarkan' => $nominal,
            'status_pembayaran' => StatusPembayaran::LUNAS->value,
            'konfirmasi_pembayaran' => KonfirmasiPembayaran::MENUNGGU_KONFIRMASI->value,
            'file_bukti_pembayaran' => $fileBuktiPembayaran
        ]);
    }

    public function validasiNominalBayar(Pesanan $pesanan, int|float $nominal): bool
    {
        return $nominal >= $pesanan->getTotalTagihan();
    }

    public function cariBerdasarkanPemesan(int|Pelanggan $pelanggan, bool $detail = false): array
    {
        return $this->pesananRepository->findByPemesanId($pelanggan, $detail);
    }
}