<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Entities/Transaksi.php';
require_once __DIR__ . '/../Entities/Pesanan.php';
require_once __DIR__ . '/PesananRepository.php';

class TransaksiRepository extends BaseRepository
{
    private string $table = 'transaksi';

    protected function getTable(): string
    {
        return $this->table;
    }

    protected function toEntity(array $rows): Transaksi
    {
        $transaksi = new Transaksi();
        $transaksi->setId($rows['id']);
        $transaksi->setNamaPembayaran($rows['nama_pembayaran']);
        $transaksi->setBankPembayaran($rows['bank_pembayaran']);
        $transaksi->setTanggalPembayaran(date_create($rows['tanggal_pembayaran']));
        $transaksi->setNominalDibayarkan($rows['nominal_dibayarkan']);
        $transaksi->setStatusPembayaran(StatusPembayaran::from($rows['status_pembayaran']));
        $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($rows['konfirmasi_pembayaran']));
        $transaksi->setFileBuktiPembayaran($rows['file_bukti_pemnayaran']);

        return $transaksi;
    }

    public function save(Transaksi $transaksi, int|Pesanan $pesanan, PesananRepository $pesananRepository) : false|Transaksi
    {
        if(is_int($pesanan)) $pesanan = $pesananRepository->findById($pesanan);

        if(false === $pesananRepository->exists($pesanan->getId())) return false;

        return parent::basicSave($transaksi, [
            'tanggal_pembayaran' => $transaksi->getTanggalPembayaran()?->format('Y-m-d H:i:s'),
            'bank_pembayaran' => $transaksi->getBankPembayaran(),
            'nama_pembayaran' => $transaksi->getNamaPembayaran(),
            'nominal_dibayarkan' => $transaksi->getNominalDibayarkan(),
            'status_pembayaran' => $transaksi->getStatusPembayaran()->value,
            'konfirmasi_pembayaran' => $transaksi->getKonfirmasiPembayaran()->value,
            'file_bukti_pembayaran' => $transaksi->getFileBuktiPembayaran(),
            'pesanan_id' => $pesanan->getId()
        ]);
    }


    public function getDataForLaporanPemasukan(int $year, int $month = 0, int $date = 0): array
    {
        $type = 'YEAR';
        $where = "YEAR(tanggal_pembayaran) = $year";
        $group = 'MONTH(tanggal_pembayaran)';

        if ($month > 0) {
            $type = 'MONTH';
            $where = sprintf("YEAR(tanggal_pembayaran) = '%s' AND MONTH(tanggal_pembayaran) = '%s'", $year, $month);
            $group = "MONTH(tanggal_pembayaran)";
        }

        if ($date > 0) {
            $type = 'DATE';
            $where = sprintf("DATE(tanggal_pembayaran) = '%s-%s-%s'", $year, $month, $date);
            $group = 'DATE(tanggal_pembayaran)';
        }

        $query = "SELECT '{$type}' as type, {$group} as periode, SUM(nominal_dibayarkan) as total
            FROM {$this->getTable()}
            WHERE konfirmasi_pembayaran = 'DITERIMA' AND {$where}
            GROUP BY {$group}";

        $stmt = $this->execute($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataForLaporanPenjualan(int $year, int $month = 0, int $date = 0): array
    {
        $type = 'YEAR';
        $where = "YEAR(tanggal_pembayaran) = $year";
        $group = 'MONTH(tanggal_pembayaran)';

        if ($month > 0) {
            $type = 'MONTH';
            $where = sprintf("YEAR(tanggal_pembayaran) = '%s' AND MONTH(tanggal_pembayaran) = '%s'", $year, $month);
            $group = "MONTH(tanggal_pembayaran)";
        }

        if ($date > 0) {
            $type = 'DATE';
            $where = sprintf("DATE(tanggal_pembayaran) = '%s-%s-%s'", $year, $month, $date);
            $group = 'DATE(tanggal_pembayaran)';
        }

        $query = "SELECT 
                '{$type}' as type, 
                {$group} as periode, b.jenis, 
                vt.variant, SUM(dp.jumlah_beli) as 'terjual',
                b.id as b_beras_id,
                vt.id as vt_variant_id
            FROM (SELECT id, pesanan_id, tanggal_pembayaran FROM transaksi WHERE konfirmasi_pembayaran = 'DITERIMA' AND {$where}) t
            JOIN pesanan p on p.id = t.pesanan_id
            JOIN detail_pesanan dp on p.id = dp.pesanan_id
            JOIN beras b on b.id = dp.ref_beras_id
            JOIN varian_takaran vt on vt.id = dp.ref_takaran_id
            GROUP BY b.jenis, vt.variant, b.id, vt.id, {$group}
            ORDER BY periode, b.jenis, vt.variant";


        try {
            $stmt = $this->execute($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            return [];
        }
    }

    public function getDataForLaporanPenjualanByJenisBeras(int $berasId, int $year, int $month = 0, int $date = 0): array
    {
        $type = 'YEAR';
        $where = "YEAR(tanggal_pembayaran) = $year";
        $group = 'MONTH(tanggal_pembayaran)';
        $queryBeras = "";

        if ($month > 0) {
            $type = 'MONTH';
            $where = sprintf("YEAR(tanggal_pembayaran) = '%s' AND MONTH(tanggal_pembayaran) = '%s'", $year, $month);
            $group = "MONTH(tanggal_pembayaran)";
        }

        if ($date > 0) {
            $type = 'DATE';
            $where = sprintf("DATE(tanggal_pembayaran) = '%s-%s-%s'", $year, $month, $date);
            $group = 'DATE(tanggal_pembayaran)';
        }

        if ($berasId > 0) {
            $queryBeras = "WHERE b.id = $berasId";
        }

        $query = "SELECT 
                '{$type}' as type, 
                {$group} as periode, b.jenis, 
                vt.variant, SUM(dp.jumlah_beli) as 'terjual',
                b.id as b_beras_id,
                vt.id as vt_variant_id
            FROM (SELECT id, pesanan_id, tanggal_pembayaran FROM transaksi WHERE konfirmasi_pembayaran = 'DITERIMA' AND {$where}) t
            JOIN pesanan p on p.id = t.pesanan_id
            JOIN detail_pesanan dp on p.id = dp.pesanan_id
            JOIN beras b on b.id = dp.ref_beras_id
            JOIN varian_takaran vt on vt.id = dp.ref_takaran_id
            {$queryBeras}
            GROUP BY b.jenis, vt.variant, b.id, vt.id, {$group}
            ORDER BY periode, b.jenis, vt.variant";

        try {
            $stmt = $this->execute($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            return [];
        }
    }
}
