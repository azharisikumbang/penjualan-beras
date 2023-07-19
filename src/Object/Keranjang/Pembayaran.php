<?php

class Pembayaran
{
    private string $nama;

    private string $bank;

    private float $nominal;

    private DateTimeInterface $tanggal;

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
    public function getBank(): string
    {
        return $this->bank;
    }

    /**
     * @param string $bank
     */
    public function setBank(string $bank): void
    {
        $this->bank = $bank;
    }

    /**
     * @return float
     */
    public function getNominal(): float
    {
        return $this->nominal;
    }

    /**
     * @param float $nominal
     */
    public function setNominal(float $nominal): void
    {
        $this->nominal = $nominal;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTanggal(): DateTimeInterface
    {
        return $this->tanggal;
    }

    /**
     * @param DateTimeInterface $tanggal
     */
    public function setTanggal(DateTimeInterface $tanggal): void
    {
        $this->tanggal = $tanggal;
    }

    public function toArray(): array
    {
        return [
            'nama' => $this->getNama(),
            'bank' => $this->getBank(),
            'nominal' => $this->getNominal(),
            'tanggal' => $this->getTanggal()->format('Y-m-d H:i:s')
        ];
    }
}
