<?php

require_once __DIR__ . '/Item.php';
require_once __DIR__ . '/Pengiriman.php';
require_once __DIR__ . '/Pembayaran.php';
require_once __DIR__ . '/Pembeli.php';

class Keranjang
{
    private array $items = [];

    private float $total = 0;

    private Pembeli $pembeli;

    private Pembayaran $pembayaran;

    private Pengiriman $pengiriman;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
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

    /**
     * @return Pembeli
     */
    public function getPembeli(): Pembeli
    {
        return $this->pembeli;
    }

    /**
     * @param Pembeli $pembeli
     */
    public function setPembeli(Pembeli $pembeli): void
    {
        $this->pembeli = $pembeli;
    }

    /**
     * @return Pembayaran
     */
    public function getPembayaran(): Pembayaran
    {
        return $this->pembayaran;
    }

    /**
     * @param Pembayaran $pembayaran
     */
    public function setPembayaran(Pembayaran $pembayaran): void
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * @return Pengiriman
     */
    public function getPengiriman(): Pengiriman
    {
        return $this->pengiriman;
    }

    /**
     * @param Pengiriman $pengiriman
     */
    public function setPengiriman(Pengiriman $pengiriman): void
    {
        $this->pengiriman = $pengiriman;
    }

    public function toArray(): array
    {
        return [
            'items' => array_map(fn(Item $item) => $item->toArray(), $this->getItems()),
            'pembeli' => $this->getPembeli()->toArray(),
            'pembayaran' => $this->getPembayaran()->toArray(),
            'pengiriman' => $this->getPengiriman()->toArray(),
            'total' => $this->getTotal()
        ];
    }
}