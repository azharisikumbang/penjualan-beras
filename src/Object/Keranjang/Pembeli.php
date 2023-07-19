<?php

class Pembeli
{
    private string $nama;

    private string $kontak;

    private ?int $pelangganId = null;

    /**
     * @return string
     */
    public function getNama(): string
    {
        return $this->nama;
    }

    /**
     * @param string $nama
     */
    public function setNama(string $nama): void
    {
        $this->nama = $nama;
    }

    /**
     * @return string
     */
    public function getKontak(): string
    {
        return $this->kontak;
    }

    /**
     * @param string $kontak
     */
    public function setKontak(string $kontak): void
    {
        $this->kontak = $kontak;
    }

    /**
     * @return int|null
     */
    public function getPelangganId(): ?int
    {
        return $this->pelangganId;
    }

    /**
     * @param int|null $pelangganId
     */
    public function setPelangganId(?int $pelangganId): void
    {
        $this->pelangganId = $pelangganId;
    }

    public function toArray(): array
    {
        return [
            'pelanggan_id' => $this->getPelangganId(),
            'nama' => $this->getNama(),
            'kontak' => $this->getKontak()
        ];
    }
}
