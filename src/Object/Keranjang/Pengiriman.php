<?php

class Pengiriman
{
    public function __construct(private string $alamat)
    {}

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

    public function toArray(): array
    {
        return [
          'alamat' => $this->getAlamat()
        ];
    }

}
