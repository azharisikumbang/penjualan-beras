<?php

require_once __DIR__ . '/Item.php';
require_once __DIR__ . '/Pengiriman.php';
require_once __DIR__ . '/Pembayaran.php';
require_once __DIR__ . '/Pembeli.php';
require_once __DIR__ . '/../../Entities/Beras.php';

class Keranjang
{
    private array $items = [];

    private float $total = 0;

    private ?Pembeli $pembeli = null;

    private ?Pembayaran $pembayaran = null;

    private ?Pengiriman $pengiriman = null;

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
    public function getPembeli(): ?Pembeli
    {
        return $this->pembeli;
    }

    /**
     * @param Pembeli $pembeli
     */
    public function setPembeli(?Pembeli $pembeli): void
    {
        $this->pembeli = $pembeli;
    }

    /**
     * @return Pembayaran
     */
    public function getPembayaran(): ?Pembayaran
    {
        return $this->pembayaran;
    }

    /**
     * @param Pembayaran $pembayaran
     */
    public function setPembayaran(?Pembayaran $pembayaran): void
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * @return Pengiriman
     */
    public function getPengiriman(): ?Pengiriman
    {
        return $this->pengiriman;
    }

    /**
     * @param Pengiriman $pengiriman
     */
    public function setPengiriman(?Pengiriman $pengiriman): void
    {
        $this->pengiriman = $pengiriman;
    }

    public function addItem(Item $item) : false|Item
    {
        if($this->exists($item->getKey())) return false; // TODO: should be update jumlah_beli
        $this->items[] = $item;
        $this->updateTotal();

        return $item;
    }

    public function search(string $key) : ?Item
    {
        /** @var $item Item */
        foreach ($this->getItems() as $item)
            if($item->getKey() == $key) return $item;

        return null;
    }

    public function exists(string $key): bool
    {
        /** @var $item Item */
        foreach ($this->getItems() as $item)
            if($item->getKey() == $key) return true;

        return false;
    }

    public function toArray(): array
    {
        return [
            'items' => array_map(fn(Item $item) => $item->toArray(), $this->getItems()),
            'pembeli' => $this->getPembeli()?->toArray(),
            'pembayaran' => $this->getPembayaran()?->toArray(),
            'pengiriman' => $this->getPengiriman()?->toArray(),
            'total' => $this->getTotal()
        ];
    }

    public static function buildFromSessionArray(array $attributes) : static
    {
        $self = new self;
        $self->setTotal($attributes['total'] ?? 0);

        $items = [];
        if(isset($attributes['items'])) {
            foreach ($attributes['items'] as $item) {
                $keranjangItem = new Item();
                $keranjangItem->setKey($item['key']);

                $beras = new Beras();
                $beras->setId($item['detail']['id']);
                $beras->setJenis($item['detail']['jenis']);
                $beras->setStok($item['detail']['stok']);
                $beras->setHarga($item['detail']['harga']);

                $keranjangItem->setBeras($beras);
                $keranjangItem->setTotalHarga($item['total_harga']);
                $keranjangItem->setJumlahBeli($item['jumlah_beli']);

                $items[] = $keranjangItem;
            }
        }

        $self->setItems($items);

        $pembeli = !isset($attributes['pembeli']) || is_null($attributes['pembeli']) ? null : new Pembeli();
        if($pembeli) {
            $pembeli->setNama($attributes['pembeli']['nama']);
            $pembeli->setKontak($attributes['pembeli']['kontak']);
            $pembeli->setPelangganId($attributes['pembeli']['pelanggan_id']);
        }

        $self->setPembeli($pembeli);

        $pembayaran = !isset($attributes['pembayaran']) || is_null($attributes['pembayaran']) ? null : new Pembayaran();
        if($pembayaran) {
            $pembayaran->setNama($attributes['pembayaran']['nama']);
            $pembayaran->setBank($attributes['pembayaran']['bank']);
            $pembayaran->setNominal($attributes['pembayaran']['nominal']);
            $pembayaran->setTanggal(date_create($attributes['pembayaran']['tanggal']));
        }

        $self->setPembayaran($pembayaran);

        $pengiriman = !isset($attributes['pengiriman']) || is_null($attributes['pengiriman']) ? null : new Pengiriman();
        if($pengiriman) $pengiriman->setAlamat($attributes['pengiriman']['alamat']);

        $self->setPengiriman($pengiriman);

        return $self;
    }

    private function updateTotal(): void
    {
        /** @var $item Item */
        $total = array_sum(
            array_map(fn ($item) => $item->getTotalHarga(), $this->getItems())
        );

        $this->setTotal($total);
    }

}