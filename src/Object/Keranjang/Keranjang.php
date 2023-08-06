<?php

require_once __DIR__ . '/Item.php';
require_once __DIR__ . '/Pengiriman.php';
require_once __DIR__ . '/Pembayaran.php';
require_once __DIR__ . '/Pembeli.php';
require_once __DIR__ . '/Diskon.php';
require_once __DIR__ . '/../../Entities/Beras.php';

class Keranjang
{
    private array $items = [];

    private float $total = 0;

    private ?Diskon $diskon = null;

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

    /**
     * @return Diskon|null
     */
    public function getDiskon(): ?Diskon
    {
        return $this->diskon;
    }

    /**
     * @param Diskon|null $diskon
     */
    public function setDiskon(null|string|Diskon $diskon): void
    {
        if (is_string($diskon)) $diskon = new Diskon($diskon);

        $this->diskon = $diskon;
    }

    public function addItem(Item $item) : false|Item
    {
        if($this->exists($item->getKey())) return false;
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
                $beras->setId($item['detail']['beras_id']);
                $beras->setJenis($item['detail']['relations']['beras']['jenis']);

                $takaran = new Takaran();
                $takaran->setId($item['detail']['takaran_id']);
                $takaran->setVariant($item['detail']['relations']['takaran']['variant']);

                $stok = Stok::makeEmpty($beras, $takaran);
                $stok->setTakaranId($item['detail']['takaran_id']);
                $stok->setBerasId($item['detail']['beras_id']);
                $stok->setStok($item['detail']['jumlah_stok']);
                $stok->setHarga($item['detail']['harga']);

                $keranjangItem->setDetail($stok);
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

    public function updateItem(Item $item, int $jumlahBeli) : void
    {
        $item->setJumlahBeli($jumlahBeli);
        $item->updateTotalHarga();

        foreach ($this->getItems() as $index => $existing)
            if($item->getKey() == $existing->getKey()) {
                $this->items[$index] = $item;
                $this->updateTotal();
                break;
            }
    }

    public function removeItem(string $key) : void
    {
        foreach ($this->getItems() as $index => $existing)
            if($key == $existing->getKey()) {
                unset($this->items[$index]);
                $this->setItems(array_values($this->getItems()));
                $this->updateTotal();
                break;
            }
    }
}