<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/../Enum/Jabatan.php';
require_once __DIR__ . '/Akun.php';

class Pelanggan implements EntityInterface
{
    private ?int $id = null;

    private string $nama;

    private string $kontak;

    private string $alamat;

    private ?Akun $akun = null;

    /**
     * @return int
     */
    public function getId(): ?int
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
     * @return string
     */
    public function getAlamat(): string
    {
        return $this->alamat;
    }

    /**
     * @param string $alamat
     */
    public function setAlamat(string $alamat): void
    {
        $this->alamat = $alamat;
    }

    /**
     * @return Akun|null
     */
    public function getAkun(): ?Akun
    {
        return $this->akun;
    }

    /**
     * @param Akun|null $akun
     */
    public function setAkun(?Akun $akun): void
    {
        $this->akun = $akun;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->getId(),
            'nama' => $this->getNama(),
            'kontak' => $this->getKontak(),
            'alamat' => $this->getAlamat(),
            'akun' => $this->getAkun()->toArray()
        );
    }
}