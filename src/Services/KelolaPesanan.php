<?php

require_once __DIR__ . '/../Repositories/PesananRepository.php';
require_once __DIR__ . '/../Repositories/AkunRepository.php';
require_once __DIR__ . '/../Repositories/StokRepository.php';
require_once __DIR__ . '/../Services/KelolaPromo.php';
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
        if ($keranjang->getItems() < 1) return false;

        $promo = null;
        $kodePromo = $keranjang->getDiskon()?->getKodePromo();
        if (!is_null($kodePromo) || $kodePromo != '') {
            $promoService = new KelolaPromo();
            $promo = $promoService->cekKodeKupon($keranjang->getDiskon()->getKodePromo());
        }

        $nomorPesananArray = $this->createNomorPesanan();
        $transaksi = Transaksi::makeEmpty();

        $pesanan = new Pesanan();
        $pesanan->setNomorPesanan($nomorPesananArray['nomor_pesanan']);
        $pesanan->setNomorIterasiPesanan($nomorPesananArray['nomor_iterasi_pesanan']);
        $pesanan->setTanggalPemesanan(date_create('now'));
        $pesanan->setTransaksi($transaksi);
        $pesanan->setPemesan($pelanggan);
        $pesanan->setSubTotal($keranjang->getTotal());
        $pesanan->setKodePromo($kodePromo);

        if (null !== $promo) {
            $diskon = $promo->getPotonganHarga();
            if ($promo->isPercent()) $diskon = ($pesanan->getSubTotal() * $promo->getPotonganHarga()) / 100;

            $pesanan->setDiskon($diskon);
        }

        $pesanan->setTotalTagihan($pesanan->getSubTotal() - $pesanan->getDiskon());

        foreach ($keranjang->getItems() as $item) {
            /** @var $item Item */
            $detail = new DetailPesanan();
            $detail->setJumlahBeli($item->getJumlahBeli());
            $detail->setTotal($item->getTotalHarga());
            $detail->setHargaSatuan($item->getDetail()->getHarga());
            $detail->setJenisBeras($item->getDetail()->getBeras()->getJenis());
            $detail->setTakaranBeras($item->getDetail()->getTakaran()->getVariant());
            $detail->setRefBerasId($item->getDetail()->getBerasId());
            $detail->setRefTakaranId($item->getDetail()->getTakaranId());

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

    public function konfirmasiPembayaran(string $nomor, string $status) : bool
    {
        $status = KonfirmasiPembayaran::tryFrom($status);
        if (is_null($status)) return false;

        $pesanan = $this->pesananRepository->findByNomorPesanan($nomor, true);
        if (is_null($pesanan) || $pesanan->getTransaksi()->getStatusPembayaran() != StatusPembayaran::LUNAS) return false;

        $updated = $this->transaksiRepository->update($pesanan->getTransaksi(), [
            'konfirmasi_pembayaran' => $status->value
        ]);

        return $updated ? $this->kurangiStokSesuaiPesanan($pesanan) : false;
    }

    public function kurangiStokSesuaiPesanan(Pesanan $pesanan) : bool
    {
        $stokRepository = new StokRepository();
        return $stokRepository->updateBatchStok($pesanan->getListPesanan());
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

    public function listPesananWithFilter(
        int $total = 10,
        int $start = 0,
        ?string $periode = null,
        ?string $search = null,
        ?string $statusPemesanan = null,
        ?string $statusPembayaran = null
    ): array {
        $start = ($start * $total) - $total;
        $searchable = [];

        if ($periode == 'today') $searchable['tanggal_pemesanan'] = date("Y-m-d"). '%';
        if ($search != null || $search != '') $searchable['nomor_pesanan'] = trim($search);
        if ($enumStatusPemesanan = StatusPembayaran::tryFrom(strtoupper((string) $statusPemesanan))) {
            if ($enumStatusPemesanan != null) $searchable['status_pembayaran'] = $enumStatusPemesanan->value;
        }
        if ($enumKonfirmasiPembayaran = KonfirmasiPembayaran::tryFrom(strtoupper((string) $statusPembayaran))) {

            if ($enumKonfirmasiPembayaran != null) $searchable['konfirmasi_pembayaran'] = $enumKonfirmasiPembayaran->value;
        }

        return $this->pesananRepository->findWithRelationsWhere($searchable, $total, $start);
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
        if (false === $fileBuktiPembayaran) return false;

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

    public function cariBerdasarkanPemesan(int|Pelanggan $pelanggan, bool $detail = false, array $filters = []): array
    {
        return $this->pesananRepository->findByPemesanId($pelanggan, $detail, $filters);
    }

    public function cekApakahKodePromoSudahTerpakai(string $kode, int $pelanggan) : bool
    {
        return $this->pesananRepository->isExistsByKodePromoAndPemesanId($kode, $pelanggan);
    }
}