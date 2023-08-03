<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/DetailPesanan.php';
require_once __DIR__ . '/../Entities/Pesanan.php';
require_once __DIR__ . '/PesananRepository.php';

class DetailPesananRepository extends BaseRepository
{
    private string $table = 'detail_pesanan';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): DetailPesanan
    {
        $detailPesanan = new DetailPesanan();
        $detailPesanan->setId($rows['id']);
        $detailPesanan->setTotal($rows['total']);
        $detailPesanan->setJenisBeras($rows['jenis_beras']);
        $detailPesanan->setHargaSatuan($rows['harga_satuan']);
        $detailPesanan->setRefBerasId($rows['ref_beras_id']);
        $detailPesanan->setRefTakaranId($rows['ref_takaran_id']);
        $detailPesanan->setJumlahBeli($rows['jumlah_beli']);
        $detailPesanan->setRefBerasId($rows['ref_stok_id']);

        return $detailPesanan;
    }

    public function save(DetailPesanan $detailPesanan, int|Pesanan $pesanan, PesananRepository $pesananRepository) : false|DetailPesanan
    {
        if(is_int($pesanan)) $pesanan = $pesananRepository->findById($pesanan);
        if(false === $pesananRepository->exists($pesanan->getId())) return false;

        return parent::basicSave($detailPesanan, [
            'jenis_beras' => $detailPesanan->getJenisBeras(),
            'takaran_beras' => $detailPesanan->getTakaranBeras(),
            'harga_satuan' => $detailPesanan->getHargaSatuan(),
            'ref_beras_id' => $detailPesanan->getRefBerasId(),
            'ref_takaran_id' => $detailPesanan->getRefTakaranId(),
            'jumlah_beli' => $detailPesanan->getJumlahBeli(),
            'total' => $detailPesanan->getTotal(),
            'pesanan_id' => $pesanan->getId()
        ]);
    }
}
