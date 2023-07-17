<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pesanan.php';
require_once __DIR__ . '/../Enum/KonfirmasiPembayaran.php';
require_once __DIR__ . '/../Enum/StatusPembayaran.php';

class Transaksi implements EntityInterface
{
    private int $id;

    private DateTimeInterface $tanggalPembayaran;

    private string $namaPembayaran;

    private string $bankPembayaran;

    private float $nominalDibayarkan;

    private string $fileBuktiPembayaran;

    private KonfirmasiPembayaran $konfirmasiPembayaran;

    private StatusPembayaran $statusPembayaran;

    private Pesanan $pesanan;

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
     * @return DateTimeInterface
     */
    public function getTanggalPembayaran(): DateTimeInterface
    {
        return $this->tanggalPembayaran;
    }

    /**
     * @param DateTimeInterface $tanggalPembayaran
     */
    public function setTanggalPembayaran(DateTimeInterface $tanggalPembayaran): void
    {
        $this->tanggalPembayaran = $tanggalPembayaran;
    }

    /**
     * @return string
     */
    public function getNamaPembayaran(): string
    {
        return $this->namaPembayaran;
    }

    /**
     * @param string $namaPembayaran
     */
    public function setNamaPembayaran(string $namaPembayaran): void
    {
        $this->namaPembayaran = $namaPembayaran;
    }

    /**
     * @return string
     */
    public function getBankPembayaran(): string
    {
        return $this->bankPembayaran;
    }

    /**
     * @param string $bankPembayaran
     */
    public function setBankPembayaran(string $bankPembayaran): void
    {
        $this->bankPembayaran = $bankPembayaran;
    }

    /**
     * @return float
     */
    public function getNominalDibayarkan(): float
    {
        return $this->nominalDibayarkan;
    }

    /**
     * @param float $nominalDibayarkan
     */
    public function setNominalDibayarkan(float $nominalDibayarkan): void
    {
        $this->nominalDibayarkan = $nominalDibayarkan;
    }

    /**
     * @return string
     */
    public function getFileBuktiPembayaran(): string
    {
        return $this->fileBuktiPembayaran;
    }

    /**
     * @param string $fileBuktiPembayaran
     */
    public function setFileBuktiPembayaran(string $fileBuktiPembayaran): void
    {
        $this->fileBuktiPembayaran = $fileBuktiPembayaran;
    }

    /**
     * @return KonfirmasiPembayaran
     */
    public function getKonfirmasiPembayaran(): KonfirmasiPembayaran
    {
        return $this->konfirmasiPembayaran;
    }

    /**
     * @param KonfirmasiPembayaran $konfirmasiPembayaran
     */
    public function setKonfirmasiPembayaran(KonfirmasiPembayaran $konfirmasiPembayaran): void
    {
        $this->konfirmasiPembayaran = $konfirmasiPembayaran;
    }

    /**
     * @return StatusPembayaran
     */
    public function getStatusPembayaran(): StatusPembayaran
    {
        return $this->statusPembayaran;
    }

    /**
     * @param StatusPembayaran $statusPembayaran
     */
    public function setStatusPembayaran(StatusPembayaran $statusPembayaran): void
    {
        $this->statusPembayaran = $statusPembayaran;
    }

    /**
     * @return Pesanan
     */
    public function getPesanan(): Pesanan
    {
        return $this->pesanan;
    }

    /**
     * @param Pesanan $pesanan
     */
    public function setPesanan(Pesanan $pesanan): void
    {
        $this->pesanan = $pesanan;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'tanggal_pembayaran' => $this->getTanggalPembayaran()->format('Y-m-d H:i:s'),
            'nama_pembayaran' => $this->getNamaPembayaran(),
            'bank_pembayaran' => $this->getBankPembayaran(),
            'nominal_dibayarkan' => $this->getNominalDibayarkan(),
            'status_pembayaran' => $this->getStatusPembayaran()->value,
            'konfirmasi_pembayaran' => $this->getKonfirmasiPembayaran()->value,
            'file_bukti_pembayaran' => $this->getFileBuktiPembayaran(),
            'pesanan' => $this->getPesanan()->toArray()
        ];
    }
}
