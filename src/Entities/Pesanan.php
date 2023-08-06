<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pelanggan.php';
require_once __DIR__ . '/DetailPesanan.php';

class Pesanan implements EntityInterface
{
    private ?int $id = null;

    private string $nomorPesanan;

    private int $nomorIterasiPesanan;

    private string $namaPesanan = "";

    private ?string $kontakPesanan = "";

    private DateTimeInterface $tanggalPemesanan;

    private float $totalTagihan;

    private float $subTotal;

    private float $diskon = 0;

    private ?string $kodePromo;

    private string $alamatPengiriman = "";

    private ?Pelanggan $pemesan;

    private array $listPesanan = [];

    private ?Transaksi $transaksi = null;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNomorPesanan(): string
    {
        return $this->nomorPesanan;
    }

    /**
     * @param string $nomorPesanan
     */
    public function setNomorPesanan(string $nomorPesanan): void
    {
        $this->nomorPesanan = $nomorPesanan;
    }

    /**
     * @return string
     */
    public function getNamaPesanan(): string
    {
        return $this->namaPesanan;
    }

    /**
     * @param string $namaPesanan
     */
    public function setNamaPesanan(string $namaPesanan): void
    {
        $this->namaPesanan = $namaPesanan;
    }

    /**
     * @return string
     */
    public function getKontakPesanan(): ?string
    {
        return $this->kontakPesanan;
    }

    /**
     * @param string $kontakPesanan
     */
    public function setKontakPesanan(?string $kontakPesanan): void
    {
        $this->kontakPesanan = $kontakPesanan;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTanggalPemesanan(): DateTimeInterface
    {
        return $this->tanggalPemesanan;
    }

    /**
     * @param DateTimeInterface $tanggalPemesanan
     */
    public function setTanggalPemesanan(DateTimeInterface $tanggalPemesanan): void
    {
        $this->tanggalPemesanan = $tanggalPemesanan;
    }

    /**
     * @return float
     */
    public function getTotalTagihan(): float
    {
        return $this->totalTagihan;
    }

    /**
     * @param float $totalTagihan
     */
    public function setTotalTagihan(float $totalTagihan): void
    {
        $this->totalTagihan = $totalTagihan;
    }

    /**
     * @return string
     */
    public function getAlamatPengiriman(): string
    {
        return $this->alamatPengiriman;
    }

    /**
     * @param string $alamatPengiriman
     */
    public function setAlamatPengiriman(string $alamatPengiriman): void
    {
        $this->alamatPengiriman = trim($alamatPengiriman);
    }

    /**
     * @return Pelanggan
     */
    public function getPemesan(): ?Pelanggan
    {
        return $this->pemesan;
    }

    /**
     * @param Pelanggan $pemesan
     */
    public function setPemesan(?Pelanggan $pemesan): void
    {
        $this->pemesan = $pemesan;
    }

    public function addDetailPesanan(DetailPesanan $detailPesanan): void
    {
        $this->listPesanan[] = $detailPesanan;
    }

    public function getListPesanan(): array
    {
        return $this->listPesanan;
    }

    /**
     * @return int
     */
    public function getNomorIterasiPesanan(): int
    {
        return $this->nomorIterasiPesanan;
    }

    /**
     * @param int $nomorIterasiPesanan
     */
    public function setNomorIterasiPesanan(int $nomorIterasiPesanan): void
    {
        $this->nomorIterasiPesanan = $nomorIterasiPesanan;
    }

    /**
     * @return Transaksi
     */
    public function getTransaksi(): ?Transaksi
    {
        return $this->transaksi;
    }

    /**
     * @param null|Transaksi $transaksi
     */
    public function setTransaksi(?Transaksi $transaksi): void
    {
        $this->transaksi = $transaksi;
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     */
    public function setSubTotal(float $subTotal): void
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return float
     */
    public function getDiskon(): float
    {
        return $this->diskon;
    }

    /**
     * @param float $diskon
     */
    public function setDiskon(float $diskon): void
    {
        $this->diskon = $diskon;
    }

    /**
     * @return string|null
     */
    public function getKodePromo(): ?string
    {
        return $this->kodePromo;
    }

    /**
     * @param string|null $kodePromo
     */
    public function setKodePromo(?string $kodePromo): void
    {
        $this->kodePromo = $kodePromo;
    }

    public function informasiPengirimanIsFilled() : bool
    {
        return $this->getAlamatPengiriman() && $this->getNamaPesanan() && $this->getKontakPesanan();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nomor_pesanan' => $this->getNomorPesanan(),
            'nomor_iterasi_pesanan' => $this->getNomorIterasiPesanan(),
            'nama_pesanan' => $this->getNamaPesanan(),
            'kontak_pesanan' => $this->getKontakPesanan(),
            'alamat_pengiriman' => $this->getAlamatPengiriman(),
            'tanggal_pemesanan' => $this->getTanggalPemesanan()->format('Y-m-d H:i:s'),
            'total_tagihan' => $this->getTotalTagihan(),
            'sub_total' => $this->getSubTotal(),
            'diskon' => $this->getSubTotal(),
            'kode_promo' => $this->getKodePromo(),
            'pemesan' => $this->getPemesan()?->toArray(),
            'list_pesanan' => array_map(fn ($item) => $item->toArray(), $this->getListPesanan()),
            'transaksi' => $this->getTransaksi()?->toArray()
        ];
    }
}