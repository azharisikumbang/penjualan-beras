<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Pesanan.php';

class DetailPesanan implements EntityInterface
{
    private int $id;

    private string $jenisBeras;

    private float $hargaSatuan;

    private int $jumlahBeli;

    private float $total;

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
    public function getJenisBeras(): string
    {
        return $this->jenisBeras;
    }

    /**
     * @param string $jenisBeras
     */
    public function setJenisBeras(string $jenisBeras): void
    {
        $this->jenisBeras = $jenisBeras;
    }

    /**
     * @return float
     */
    public function getHargaSatuan(): float
    {
        return $this->hargaSatuan;
    }

    /**
     * @param float $hargaSatuan
     */
    public function setHargaSatuan(float $hargaSatuan): void
    {
        $this->hargaSatuan = $hargaSatuan;
    }

    /**
     * @return int
     */
    public function getJumlahBeli(): int
    {
        return $this->jumlahBeli;
    }

    /**
     * @param int $jumlahBeli
     */
    public function setJumlahBeli(int $jumlahBeli): void
    {
        $this->jumlahBeli = $jumlahBeli;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function toArray(): array
    {
        return [
          'id' => $this->getId(),
          'jenis_beras' => $this->getJenisBeras(),
          'harga_satuan' => $this->getHargaSatuan(),
          'jumlah_beli' => $this->getJumlahBeli(),
          'total' => $this->getTotal(),
        ];
    }
}
