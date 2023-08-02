<?php

class Item
{
    private string $key;

    private Stok $detail;

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

    /**
     * @return Stok
     */
    public function getDetail(): Stok
    {
        return $this->detail;
    }

    /**
     * @param Stok $detail
     */
    public function setDetail(Stok $detail): void
    {
        $this->detail = $detail;
    }

    public function updateTotalHarga()
    {
        $total = $this->getDetail()->getHarga() * $this->getJumlahBeli();
        $this->setTotalHarga($total);
    }

    public static function create(Stok $stok, int $jumlahBeli = 1)
    {
        $item = new Item();
        $item->setKey($item->createKey());
        $item->setDetail($stok);
        $item->setJumlahBeli($jumlahBeli);
        $item->setTotalHarga(
            $stok->getHarga() * $jumlahBeli
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
            'detail' => $this->getDetail()->toArray(),
            'total_harga' => $this->getTotalHarga(),
            'jumlah_beli' => $this->getJumlahBeli()
        ];
    }

}