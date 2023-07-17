<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pelanggan.php';
require_once __DIR__ . '/DetailPesanan.php';

class Pesanan implements EntityInterface
{
    private int $id;

    private string $nomorPesanan;

    private int $nomorIterasiPesanan;

    private string $namaPesanan;

    private DateTimeInterface $tanggalPemesanan;

    private float $totalTagihan;

    private string $alamatPengiriman;

    private Pelanggan $pemesan;

    private array $listPesanan = [];

    /**
     * @return int
     */
    public function getId(): int
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
        $this->alamatPengiriman = $alamatPengiriman;
    }

    /**
     * @return Pelanggan
     */
    public function getPemesan(): Pelanggan
    {
        return $this->pemesan;
    }

    /**
     * @param Pelanggan $pemesan
     */
    public function setPemesan(Pelanggan $pemesan): void
    {
        $this->pemesan = $pemesan;
    }

    public function addDetailPesanan(DetailPesanan $detailPesanan): void
    {
        // TODO: Add Detail Pesanan To List
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


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nomor_pesanan' => $this->getNomorPesanan(),
            'nomor_iterasi_pesanan' => $this->getNomorIterasiPesanan(),
            'nama_pesanan' => $this->getNamaPesanan(),
            'alamat_pengiriman' => $this->getAlamatPengiriman(),
            'tanggal_pemesanan' => $this->getTanggalPemesanan()->format('Y-m-d H:i:s'),
            'total_tagihan' => $this->getTotalTagihan(),
            'pemesan' => $this->getPemesan()->toArray(),
            'list_pesanan' => array_map(fn ($item) => $item->toArray(), $this->getListPesanan())
        ];
    }
}