<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pesanan.php';
require_once __DIR__ . '/../Enum/KonfirmasiPembayaran.php';
require_once __DIR__ . '/../Enum/StatusPembayaran.php';

class Transaksi implements EntityInterface
{
    private ?int $id = null;

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
    public function setId(?int $id): void
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
    public function getNamaPembayaran(): null|string
    {
        return $this->namaPembayaran;
    }

    /**
     * @param string $namaPembayaran
     */
    public function setNamaPembayaran(null|string $namaPembayaran): void
    {
        $this->namaPembayaran = $namaPembayaran;
    }

    /**
     * @return string
     */
    public function getBankPembayaran(): null|string
    {
        return $this->bankPembayaran;
    }

    /**
     * @param string $bankPembayaran
     */
    public function setBankPembayaran(null|string $bankPembayaran): void
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
    public function getFileBuktiPembayaran(): null|string
    {
        return $this->fileBuktiPembayaran;
    }

    /**
     * @param string $fileBuktiPembayaran
     */
    public function setFileBuktiPembayaran(null|string $fileBuktiPembayaran): void
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

    public function isPaid()
    {
        return $this->getStatusPembayaran() == StatusPembayaran::LUNAS;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'tanggal_pembayaran' => $this->getTanggalPembayaran()?->format('Y-m-d H:i:s'),
            'nama_pembayaran' => $this->getNamaPembayaran(),
            'bank_pembayaran' => $this->getBankPembayaran(),
            'nominal_dibayarkan' => $this->getNominalDibayarkan(),
            'status_pembayaran' => $this->getStatusPembayaran()->getDisplay(),
            'status_pembayaran_color' => $this->getStatusPembayaran()->getColor(),
            'konfirmasi_pembayaran' => $this->getKonfirmasiPembayaran()->getDisplay(),
            'konfirmasi_pembayaran_color' => $this->getKonfirmasiPembayaran()->getColor(),
            'file_bukti_pembayaran' => $this->getFileBuktiPembayaran(),
            'lunas' => $this->isPaid()
        ];
    }

    public static function makeEmpty()
    {
        $transaksi = new Transaksi();
        $transaksi->setId(null);
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
