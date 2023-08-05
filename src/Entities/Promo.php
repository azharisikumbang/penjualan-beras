<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';

class Promo implements EntityInterface
{
    private int $id;

    private string $jenisPromo;

    private string $kodeKupon;

    private ?DateTimeInterface $tanggalKadaluarsa;

    private float $minimumPembelian;

    private float $potonganHarga;

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
    public function getJenisPromo(): string
    {
        return $this->jenisPromo;
    }

    /**
     * @param string $jenisPromo
     */
    public function setJenisPromo(string $jenisPromo): void
    {
        $this->jenisPromo = $jenisPromo;
    }

    /**
     * @return string
     */
    public function getKodeKupon(): string
    {
        return $this->kodeKupon;
    }

    /**
     * @param string $kodeKupon
     */
    public function setKodeKupon(string $kodeKupon): void
    {
        $this->kodeKupon = $kodeKupon;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getTanggalKadaluarsa(): ?DateTimeInterface
    {
        return $this->tanggalKadaluarsa;
    }

    /**
     * @param DateTimeInterface|null $tanggalKadaluarsa
     */
    public function setTanggalKadaluarsa(?DateTimeInterface $tanggalKadaluarsa): void
    {
        $this->tanggalKadaluarsa = $tanggalKadaluarsa;
    }

    /**
     * @return float
     */
    public function getMinimumPembelian(): float
    {
        return $this->minimumPembelian;
    }

    /**
     * @param float $minimumPembelian
     */
    public function setMinimumPembelian(float $minimumPembelian): void
    {
        $this->minimumPembelian = $minimumPembelian;
    }

    /**
     * @return float
     */
    public function getPotonganHarga(): float
    {
        return $this->potonganHarga;
    }

    /**
     * @param float $potonganHarga
     */
    public function setPotonganHarga(float $potonganHarga): void
    {
        $this->potonganHarga = $potonganHarga;
    }

    private function isOutOfDate(): bool
    {
        if (is_null($this->getTanggalKadaluarsa())) return false;

        return (date('Y-m-d') > $this->getTanggalKadaluarsa()->format('Y-m-d'));
    }

    private function isLowerThan100(): bool
    {
        return $this->getPotonganHarga() <= 100;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'jenis_promo' => $this->getJenisPromo(),
            'kode_kupon' => $this->getKodeKupon(),
            'tanggal_kadaluarsa' => $this->getTanggalKadaluarsa()?->format('Y-m-d'),
            'minimum_pembelian' => $this->getMinimumPembelian(),
            'potongan_harga' => $this->getPotonganHarga(),
            'kadaluarsa' => $this->isOutOfDate(),
            'is_persen' => $this->isLowerThan100()
        ];
    }
}