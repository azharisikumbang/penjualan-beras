<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pesanan.php';
require_once __DIR__ . '/../Enum/KonfirmasiPembayaran.php';
require_once __DIR__ . '/../Enum/StatusPembayaran.php';

class Transaksi implements EntityInterface
{
    private ?int $id;

    private ?DateTimeInterface $tanggalPembayaran;

    private ?string $namaPembayaran;

    private ?string $bankPembayaran;

    private float $nominalDibayarkan;

    private ?string $fileBuktiPembayaran;

    private KonfirmasiPembayaran $konfirmasiPembayaran;

    private StatusPembayaran $statusPembayaran;

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
     * @return ?DateTimeInterface
     */
    public function getTanggalPembayaran(): ?DateTimeInterface
    {
        return $this->tanggalPembayaran;
    }

    /**
     * @param ?DateTimeInterface $tanggalPembayaran
     */
    public function setTanggalPembayaran(?DateTimeInterface $tanggalPembayaran): void
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
            'file_bukti_pembayaran' => $this->getFileBuktiPembayaran()
        ];
    }

    public static function makeEmpty()
    {
        $transaksi = new Transaksi();
        $transaksi->setStatusPembayaran(StatusPembayaran::BELUM_BAYAR);
        $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::BELUM_BAYAR);
        $transaksi->setFileBuktiPembayaran(null);
        $transaksi->setNominalDibayarkan(0);
        $transaksi->setTanggalPembayaran(null);
        $transaksi->setNamaPembayaran(null);
        $transaksi->setBankPembayaran(null);

        return $transaksi;
    }
}
