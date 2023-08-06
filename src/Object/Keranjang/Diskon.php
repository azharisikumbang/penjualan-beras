<?php

class Diskon
{
    public function __construct(private string $kodePromo = "")
    {
    }

    /**
     * @return string
     */
    public function getKodePromo(): string
    {
        return $this->kodePromo;
    }

    /**
     * @param string $kodePromo
     */
    public function setKodePromo(string $kodePromo): void
    {
        $this->kodePromo = $kodePromo;
    }

}
