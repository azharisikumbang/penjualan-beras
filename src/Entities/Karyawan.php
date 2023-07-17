<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';
require_once __DIR__ . '/../Enum/Jabatan.php';
require_once __DIR__ . '/Akun.php';

class Karyawan implements EntityInterface
{
    private int $id;

    private string $nama;

    private string $kontak;

    private Jabatan $jabatan;

    private ?Akun $akun = null;

    /**
     * @return int
     */
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
     * @return Jabatan
     */
    public function getJabatan(): Jabatan
    {
        return $this->jabatan;
    }

    /**
     * @param Jabatan $jabatan
     */
    public function setJabatan(Jabatan $jabatan): void
    {
        $this->jabatan = $jabatan;
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
            'jabatan' => $this->getJabatan()->value,
            'akun' => $this->getAkun()->toArray()
        );
    }
}