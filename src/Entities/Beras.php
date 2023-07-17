<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';

class Beras implements EntityInterface
{

    private int $id;

    private string $jenis;

    private float $harga;

    private int $stok;


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
    public function getJenis(): string
    {
        return $this->jenis;
    }

    /**
     * @param string $jenis
     */
    public function setJenis(string $jenis): void
    {
        $this->jenis = $jenis;
    }

    /**
     * @return float
     */
    public function getHarga(): float
    {
        return $this->harga;
    }

    /**
     * @param float $harga
     */
    public function setHarga(float $harga): void
    {
        $this->harga = $harga;
    }

    /**
     * @return int
     */
    public function getStok(): int
    {
        return $this->stok;
    }

    /**
     * @param int $stok
     */
    public function setStok(int $stok): void
    {
        $this->stok = $stok;
    }

    public function toArray(): array
    {
        return [
          'id' => $this->getId(),
          'jenis' => $this->getJenis(),
          'harga' => $this->getHarga(),
          'stok'=> $this->getStok()
        ];
    }

}