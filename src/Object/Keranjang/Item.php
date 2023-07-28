<?php

class Item
{
    private string $key;

    private Beras $detail;

    private int $jumlahBeli;

    private float $totalHarga;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return Beras
     */
    public function getBeras(): Beras
    {
        return $this->detail;
    }

    /**
     * @param Beras $detail
     */
    public function setBeras(Beras $detail): void
    {
        $this->detail = $detail;
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
    public function getTotalHarga(): float
    {
        return $this->totalHarga;
    }

    /**
     * @param float $totalHarga
     */
    public function setTotalHarga(float $totalHarga): void
    {
        $this->totalHarga = $totalHarga;
    }

    public function updateTotalHarga()
    {
        $total = $this->getBeras()->getHarga() * $this->getJumlahBeli();
        $this->setTotalHarga($total);
    }

    public static function create(Beras $detail, int $jumlahBeli = 1)
    {
        $item = new Item();
        $item->setKey($item->createKey());
        $item->setBeras($detail);
        $item->setJumlahBeli($jumlahBeli);
        $item->setTotalHarga(
            $detail->getHarga() * $jumlahBeli
        );

        return $item;
    }

    private function createKey(): string
    {
        return strtolower(md5(time()));
    }

    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'detail' => $this->getBeras()?->toArray(),
            'total_harga' => $this->getTotalHarga(),
            'jumlah_beli' => $this->getJumlahBeli()
        ];
    }

}