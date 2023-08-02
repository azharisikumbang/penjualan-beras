<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Beras.php';
require_once __DIR__ . '/Takaran.php';

class Stok implements EntityInterface
{
    private ?int $id = null;

    private Beras $beras;

    private ?int $berasId;

    private Takaran $takaran;

    private ?int $takaranId;

    private float $harga = 0;

    private int $stok = 0;

    public static function makeEmpty(Beras $beras, Takaran $takaran): self
    {
        $self = new self;
        $self->setBeras($beras);
        $self->setTakaran($takaran);
        $self->setHarga(0);
        $self->setStok(0);

        return $self;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * @return Beras
     */
    public function getBeras(): Beras
    {
        return $this->beras;
    }

    /**
     * @param Beras $beras
     */
    public function setBeras(Beras $beras): void
    {
        $this->beras = $beras;
    }

    /**
     * @return Takaran
     */
    public function getTakaran(): Takaran
    {
        return $this->takaran;
    }

    /**
     * @param Takaran $takaran
     */
    public function setTakaran(Takaran $takaran): void
    {
        $this->takaran = $takaran;
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

    /**
     * @return int|null
     */
    public function getBerasId(): ?int
    {
        return $this->berasId;
    }

    /**
     * @param int|null $berasId
     */
    public function setBerasId(?int $berasId): void
    {
        $this->berasId = $berasId;
    }

    /**
     * @return int|null
     */
    public function getTakaranId(): ?int
    {
        return $this->takaranId;
    }

    /**
     * @param int|null $takaranId
     */
    public function setTakaranId(?int $takaranId): void
    {
        $this->takaranId = $takaranId;
    }

    public function createBerasRelations(int $id, string $jenis, array $listTakaran = []): self
    {
        $beras = new Beras();
        $beras->setId($id);
        $beras->setJenis($jenis);
        $beras->setListTakaran($listTakaran);

        $this->setBeras($beras);

        return $this;
    }

    public function createTakaranRelations(int $id, string $varian): self
    {
        $takaran = new Takaran();
        $takaran->setId($id);
        $takaran->setVariant($varian);

        $this->setTakaran($takaran);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'beras_id' => $this->getBerasId(),
            'takaran_id' => $this->getTakaranId(),
            'harga' => $this->getHarga(),
            'jumlah_stok' => $this->getStok(),
            'relations' => [
                'beras' => $this->getBeras()?->toArray(),
                'takaran' => $this->getTakaran()?->toArray()
            ]
        ];
    }

}
