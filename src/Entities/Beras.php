<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/Takaran.php';

class Beras implements EntityInterface
{

    private ?int $id;

    private string $jenis;

    private array $listTakaran = [];

    public function getId(): ?int
    {
        return $this->id ?? null;
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
     * @return array
     */
    public function getListTakaran(): array
    {
        return $this->listTakaran;
    }

    /**
     * @param array $listTakaran
     */
    public function setListTakaran(array $listTakaran): void
    {
        $this->listTakaran = $listTakaran;
    }

    public function addTakaran(Takaran $takaran) : bool
    {
        foreach ($this->getListTakaran() as $item)
            if($item->getId() == $takaran->getId()) return false;

        $this->listTakaran[] = $takaran;

        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'jenis' => $this->getJenis(),
            'list_takaran' => array_map(fn ($item) => $item->toArray(), $this->getListTakaran())
        ];
    }

}