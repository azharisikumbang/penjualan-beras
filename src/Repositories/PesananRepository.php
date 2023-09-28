<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/TransaksiRepository.php';
require_once __DIR__ . '/DetailPesananRepository.php';
require_once __DIR__ . '/../Entities/Pesanan.php';
require_once __DIR__ . '/../Entities/Pelanggan.php';
require_once __DIR__ . '/../Entities/DetailPesanan.php';

class PesananRepository extends BaseRepository
{
    private string $table = 'pesanan';

    public function findLatestNomorPesanan() : int|null
    {
        $query = "SELECT nomor_iterasi_pesanan 
            FROM {$this->getTable()}
            WHERE tanggal_pemesanan LIKE :tanggal_pemesanan
            ORDER BY id DESC
            LIMIT 1";

        $stmt = $this->getDatabaseConnection()->prepare($query);
        $stmt->execute([
            'tanggal_pemesanan' => sprintf("%s", date('Y-m')) . "%"
        ]);

        return $stmt->rowCount() ? $stmt->fetch(PDO::FETCH_ASSOC)['nomor_iterasi_pesanan'] : null;
    }

    public function findByNomorPesanan(string $nomor, bool $detail = false) : ?Pesanan
    {
        $appendQuery = "WHERE p.nomor_pesanan = :nomor_pesanan";
        if(!$detail) {
            $query = $this->queryGetWithoutRelations($appendQuery);
            $stmt = $this->execute($query, ['nomor_pesanan' => $nomor]);

            if($stmt->rowCount() < 1) return null;

            return $this->toPesanan($stmt->fetch(PDO::FETCH_ASSOC));
        }

        $query = "SELECT p.*,
               dp.jenis_beras as dp_jenis_beras,
               dp.takaran_beras as dp_takaran_beras,
               dp.harga_satuan as dp_harga_satuan,
               dp.jumlah_beli as dp_jumlah_beli,
               dp.total as dp_total,
               dp.id as dp_id,
               dp.ref_beras_id as dp_ref_beras_id,
               dp.ref_takaran_id as dp_ref_takaran_id,
               t.id as t_id,
               t.tanggal_pembayaran as t_tanggal_pembayaran,
               t.nama_pembayaran as t_nama_pembayaran,
               t.bank_pembayaran as t_bank_pembayaran,
               t.nominal_dibayarkan as t_nominal_dibayarkan,
               t.status_pembayaran as t_status_pembayaran,
               t.konfirmasi_pembayaran as t_konfirmasi_pembayaran,
               t.file_bukti_pembayaran as t_file_bukti_pembayaran,
               p2.id as p2_id,
               p2.nama as p2_nama,
               p2.kontak as p2_kontak,
               p2.alamat as p2_alamat,
               p2.akun_id as p2_akun_id
            FROM pesanan p
            LEFT JOIN detail_pesanan dp on p.id = dp.pesanan_id
            LEFT JOIN transaksi t on p.id = t.pesanan_id
            LEFT JOIN pelanggan p2 on p2.id = p.pemesan_id
            WHERE p.nomor_pesanan = :nomor_pesanan";

        $stmt = $this->execute($query, ['nomor_pesanan' => $nomor]);

        if($stmt->rowCount() < 1) return null;

        $pesanan = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($pesanan) || $pesanan->getId() != $row['id']) {
                $pesanan = $this->toPesanan($row);

                $transaksi = new Transaksi();
                $transaksi->setId($row['t_id']);
                $transaksi->setNamaPembayaran($row['t_nama_pembayaran']);
                $transaksi->setBankPembayaran($row['t_bank_pembayaran']);
                $transaksi->setNominalDibayarkan($row['t_nominal_dibayarkan']);
                $transaksi->setStatusPembayaran(StatusPembayaran::from($row['t_status_pembayaran']));
                $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($row['t_konfirmasi_pembayaran']));
                $transaksi->setFileBuktiPembayaran($row['t_file_bukti_pembayaran']);
                $transaksi->setTanggalPembayaran(is_null($row['t_tanggal_pembayaran']) ? null : date_create($row['t_tanggal_pembayaran']));

                $pelanggan = new Pelanggan();
                $pelanggan->setId($row['p2_id']);
                $pelanggan->setNama($row['p2_nama']);
                $pelanggan->setKontak($row['p2_kontak']);
                $pelanggan->setAlamat($row['p2_alamat']);
                $pelanggan->setAkunId($row['p2_akun_id']);
                $pelanggan->setAkun(null);

                $pesanan->setTransaksi($transaksi);
                $pesanan->setPemesan($pelanggan);
            }

            $detailPesanan = new DetailPesanan();
            $detailPesanan->setId($row['dp_id']);
            $detailPesanan->setTotal($row['dp_total']);
            $detailPesanan->setJenisBeras($row['dp_jenis_beras']);
            $detailPesanan->setHargaSatuan($row['dp_harga_satuan']);
            $detailPesanan->setJumlahBeli($row['dp_jumlah_beli']);
            $detailPesanan->setTakaranBeras($row['dp_takaran_beras']);
            $detailPesanan->setRefTakaranId($row['dp_ref_takaran_id']);
            $detailPesanan->setRefBerasId($row['dp_ref_beras_id']);


            $pesanan->addDetailPesanan($detailPesanan);
        }

        return $pesanan;
    }

    public function all(bool $withRelations = false) : array
    {
        if (!$withRelations) {
            $stmt = $this->execute($this->queryGetWithoutRelations());
            if ($stmt->rowCount() < 1) return [];

            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) $result[] = $this->toEntity($row);

            return $result;
        }

        $stmt = $this->execute($this->queryGetWithRelations());
        if($stmt->rowCount() < 1) return [];

        $listPesanan = [];
        $pesanan = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($pesanan) || $pesanan->getId() != $row['id']) {
                $pesanan = $this->toPesanan($row);

                $transaksi = new Transaksi();
                $transaksi->setId($row['t_id']);
                $transaksi->setNamaPembayaran($row['t_nama_pembayaran']);
                $transaksi->setBankPembayaran($row['t_bank_pembayaran']);
                $transaksi->setNominalDibayarkan($row['t_nominal_dibayarkan']);
                $transaksi->setStatusPembayaran(StatusPembayaran::from($row['t_status_pembayaran']));
                $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($row['t_konfirmasi_pembayaran']));
                $transaksi->setFileBuktiPembayaran($row['t_file_bukti_pembayaran']);
                $transaksi->setTanggalPembayaran(is_null($row['t_tanggal_pembayaran']) ? null : date_create($row['t_tanggal_pembayaran']));

                $pelanggan = new Pelanggan();
                $pelanggan->setId($row['p2_id']);
                $pelanggan->setNama($row['p2_nama']);
                $pelanggan->setKontak($row['p2_kontak']);
                $pelanggan->setAlamat($row['p2_alamat']);
                $pelanggan->setAkunId($row['p2_akun_id']);
                $pelanggan->setAkun(null);

                $pesanan->setTransaksi($transaksi);
                $pesanan->setPemesan($pelanggan);
            }

            $detailPesanan = new DetailPesanan();
            $detailPesanan->setId($row['dp_id']);
            $detailPesanan->setTotal($row['dp_total']);
            $detailPesanan->setJenisBeras($row['dp_jenis_beras']);
            $detailPesanan->setHargaSatuan($row['dp_harga_satuan']);
            $detailPesanan->setJumlahBeli($row['dp_jumlah_beli']);
            $detailPesanan->setTakaranBeras($row['dp_takaran_beras']);
            $detailPesanan->setRefTakaranId($row['dp_ref_takaran_id']);
            $detailPesanan->setRefBerasId($row['dp_ref_beras_id']);

            $pesanan->addDetailPesanan($detailPesanan);

            $listPesanan[$row['id']] = $pesanan;
        }

        return array_values($listPesanan);
    }

    protected function getTable(): string
    {
        return $this->table;
    }

    public function save(Pesanan $pesanan, TransaksiRepository $transaksiRepository, DetailPesananRepository $detailPesananRepository) : false|Pesanan
    {
        $dbh = $this->getDatabaseConnection();

        if ($dbh->beginTransaction()) {
            try {
                $queryPesanan = "INSERT INTO {$this->getTable()} (
                    nomor_pesanan, 
                    nomor_iterasi_pesanan, 
                    nama_pesanan,
                    alamat_pengiriman,
                    tanggal_pemesanan,
                    sub_total,
                    diskon,
                    kode_promo,
                    total_tagihan,
                    pemesan_id
                    ) VALUES (
                    :nomor_pesanan, 
                    :nomor_iterasi_pesanan, 
                    :nama_pesanan,
                    :alamat_pengiriman,
                    :tanggal_pemesanan,
                    :sub_total,
                    :diskon,
                    :kode_promo,
                    :total_tagihan,
                    :pemesan_id)";


                $stmtPesanan = $dbh->prepare($queryPesanan);
                $pesananCreated = $stmtPesanan->execute([
                    'nomor_pesanan' => $pesanan->getNomorPesanan(),
                    'nomor_iterasi_pesanan' => $pesanan->getNomorIterasiPesanan(),
                    'nama_pesanan' => $pesanan->getNamaPesanan(),
                    'alamat_pengiriman' => $pesanan->getAlamatPengiriman(),
                    'tanggal_pemesanan' => $pesanan->getTanggalPemesanan()->format('Y-m-d H:i:s'),
                    'total_tagihan' => $pesanan->getTotalTagihan(),
                    'pemesan_id' => $pesanan->getPemesan()->getId(),
                    'diskon' => $pesanan->getDiskon(),
                    'kode_promo' => $pesanan->getKodePromo(),
                    'sub_total' => $pesanan->getSubTotal()
                ]);

                if(!$pesananCreated) return false;

                $pesanan->setId($dbh->lastInsertId());

                /** @var $detail DetailPesanan */
                foreach ($pesanan->getListPesanan() as $detail) {
                    $detailPesananRepository->save($detail, $pesanan, $this);
                }

                $transaksiRepository->save($pesanan->getTransaksi(), $pesanan, $this);

                $dbh->commit();

                return $pesanan;
            } catch (Exception $exception) {
                $dbh->rollBack();
//                die ($exception->getMessage());

                return false;
            }
        }

        return false;
    }

    public function findByPemesanId(Pelanggan $pelanggan, bool $detail = false, array $filters = []) : array
    {
        $appendQuery = "WHERE pemesan_id = :pemesan_id";
        $whereList = ['pemesan_id' => $pelanggan->getId()];
        $total = 10;
        $start = 0;

        if ($filters) {
            if (isset($filters['search'])) {
                $appendQuery .= " AND nomor_pesanan = :nomor_pesanan";
                $whereList['nomor_pesanan'] = $filters['search'];
            }

            if (isset($filters['page'])) {
                $start = ($total * $filters['page']) - $total;
            }
        }

        if(!$detail) {
            $query = $this->queryGetWithoutRelations($appendQuery);
            $stmt = $this->execute($query, $whereList);

            if($stmt->rowCount() < 1) return [];

            return [$this->toPesanan($stmt->fetch(PDO::FETCH_ASSOC))];
        }

        $query = $this->queryGetWithRelations($appendQuery, $total, $start);
        $stmt = $this->execute($query, $whereList);

        if($stmt->rowCount() < 1) return [];

        $listPesanan = [];
        $pesanan = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($pesanan) || $pesanan->getId() != $row['id']) {
                $pesanan = $this->toPesanan($row);

                $transaksi = new Transaksi();
                $transaksi->setId($row['t_id']);
                $transaksi->setNamaPembayaran($row['t_nama_pembayaran']);
                $transaksi->setBankPembayaran($row['t_bank_pembayaran']);
                $transaksi->setNominalDibayarkan($row['t_nominal_dibayarkan']);
                $transaksi->setStatusPembayaran(StatusPembayaran::from($row['t_status_pembayaran']));
                $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($row['t_konfirmasi_pembayaran']));
                $transaksi->setFileBuktiPembayaran($row['t_file_bukti_pembayaran']);
                $transaksi->setTanggalPembayaran(is_null($row['t_tanggal_pembayaran']) ? null : date_create($row['t_tanggal_pembayaran']));

                $pelanggan = new Pelanggan();
                $pelanggan->setId($row['p2_id']);
                $pelanggan->setNama($row['p2_nama']);
                $pelanggan->setKontak($row['p2_kontak']);
                $pelanggan->setAlamat($row['p2_alamat']);
                $pelanggan->setAkunId($row['p2_akun_id']);
                $pelanggan->setAkun(null);

                $pesanan->setTransaksi($transaksi);
                $pesanan->setPemesan($pelanggan);
            }

            $detailPesanan = new DetailPesanan();
            $detailPesanan->setId($row['dp_id']);
            $detailPesanan->setTotal($row['dp_total']);
            $detailPesanan->setJenisBeras($row['dp_jenis_beras']);
            $detailPesanan->setHargaSatuan($row['dp_harga_satuan']);
            $detailPesanan->setJumlahBeli($row['dp_jumlah_beli']);
            $detailPesanan->setTakaranBeras($row['dp_takaran_beras']);
            $detailPesanan->setRefTakaranId($row['dp_ref_takaran_id']);
            $detailPesanan->setRefBerasId($row['dp_ref_beras_id']);

            $pesanan->addDetailPesanan($detailPesanan);

            $listPesanan[$pesanan->getNomorPesanan()] = $pesanan;
        }

        return array_values($listPesanan);
    }

    public function findWithRelationsWhere(array $searchable, int $total, int $start)
    {
        $appendQuery = "";
        $appendQueryList = [];
        $whereList = [];
        if (count($searchable)) {
            if (isset($searchable['tanggal_pemesanan'])) {
                $appendQueryList[] = " p.tanggal_pemesanan LIKE :tanggal_pemesanan";
                $whereList['tanggal_pemesanan'] = $searchable['tanggal_pemesanan'];
            }

            if (isset($searchable['nomor_pesanan'])) {
                $appendQueryList[] .= " p.nomor_pesanan = :nomor_pesanan";
                $whereList['nomor_pesanan'] = $searchable['nomor_pesanan'];
            }

            if (isset($searchable['status_pembayaran'])) {
                $appendQueryList[] .= " t.status_pembayaran = :status_pembayaran";
                $whereList['status_pembayaran'] = $searchable['status_pembayaran'];
            }

            if (isset($searchable['konfirmasi_pembayaran'])) {
                $appendQueryList[] .= " t.konfirmasi_pembayaran = :konfirmasi_pembayaran";
                $whereList['konfirmasi_pembayaran'] = $searchable['konfirmasi_pembayaran'];
            }

            $appendQuery .= "WHERE";
            $appendQuery .= implode(" AND ", $appendQueryList);
        }

        $query = $this->queryGetWithRelations($appendQuery, $total, $start);

        $stmt = $this->execute($query, $whereList);

        if($stmt->rowCount() < 1) return [];

        $listPesanan = [];
        $pesanan = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($pesanan) || $pesanan->getId() != $row['id']) {
                $pesanan = $this->toPesanan($row);

                $transaksi = new Transaksi();
                $transaksi->setId($row['t_id']);
                $transaksi->setNamaPembayaran($row['t_nama_pembayaran']);
                $transaksi->setBankPembayaran($row['t_bank_pembayaran']);
                $transaksi->setNominalDibayarkan($row['t_nominal_dibayarkan']);
                $transaksi->setStatusPembayaran(StatusPembayaran::from($row['t_status_pembayaran']));
                $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($row['t_konfirmasi_pembayaran']));
                $transaksi->setFileBuktiPembayaran($row['t_file_bukti_pembayaran']);
                $transaksi->setTanggalPembayaran(is_null($row['t_tanggal_pembayaran']) ? null : date_create($row['t_tanggal_pembayaran']));

                $pelanggan = new Pelanggan();
                $pelanggan->setId($row['p2_id']);
                $pelanggan->setNama($row['p2_nama']);
                $pelanggan->setKontak($row['p2_kontak']);
                $pelanggan->setAlamat($row['p2_alamat']);
                $pelanggan->setAkunId($row['p2_akun_id']);
                $pelanggan->setAkun(null);

                $pesanan->setTransaksi($transaksi);
                $pesanan->setPemesan($pelanggan);
            }

            $detailPesanan = new DetailPesanan();
            $detailPesanan->setId($row['dp_id']);
            $detailPesanan->setTotal($row['dp_total']);
            $detailPesanan->setJenisBeras($row['dp_jenis_beras']);
            $detailPesanan->setHargaSatuan($row['dp_harga_satuan']);
            $detailPesanan->setJumlahBeli($row['dp_jumlah_beli']);
            $detailPesanan->setTakaranBeras($row['dp_takaran_beras']);
            $detailPesanan->setRefTakaranId($row['dp_ref_takaran_id']);
            $detailPesanan->setRefBerasId($row['dp_ref_beras_id']);

            $pesanan->addDetailPesanan($detailPesanan);

            $listPesanan[$row['id']] = $pesanan;
        }

        return array_values($listPesanan);
    }

    public function getDataForLaporanPenjualan(string $tanggalPemesanan = 'all') : array
    {
        $queryTanggalPemesanan = "";
        $appendQuery = "";
        $hasWhere = false;
        switch ($tanggalPemesanan) {
            case 'today':
                $queryTanggalPemesanan = "DATE(p.tanggal_pemesanan) = CURDATE()";
                $hasWhere = true;
                break;
            case 'month':
                $hasWhere = true;
                $queryTanggalPemesanan = "MONTH(p.tanggal_pemesanan) = MONTH(CURRENT_DATE()) AND YEAR(p.tanggal_pemesanan) = YEAR(CURRENT_DATE())";
                break;
            default:
                $hasWhere = false;
                break;
        }

        if ($hasWhere) {
            $appendQuery = "WHERE $queryTanggalPemesanan";
        }

        $stmt = $this->execute($this->queryGetWithRelations($appendQuery));
        if($stmt->rowCount() < 1) return [];

        $listPesanan = [];
        $pesanan = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(is_null($pesanan) || $pesanan->getId() != $row['id']) {
                $pesanan = $this->toPesanan($row);

                $transaksi = new Transaksi();
                $transaksi->setId($row['t_id']);
                $transaksi->setNamaPembayaran($row['t_nama_pembayaran']);
                $transaksi->setBankPembayaran($row['t_bank_pembayaran']);
                $transaksi->setNominalDibayarkan($row['t_nominal_dibayarkan']);
                $transaksi->setStatusPembayaran(StatusPembayaran::from($row['t_status_pembayaran']));
                $transaksi->setKonfirmasiPembayaran(KonfirmasiPembayaran::from($row['t_konfirmasi_pembayaran']));
                $transaksi->setFileBuktiPembayaran($row['t_file_bukti_pembayaran']);
                $transaksi->setTanggalPembayaran(is_null($row['t_tanggal_pembayaran']) ? null : date_create($row['t_tanggal_pembayaran']));

                $pelanggan = new Pelanggan();
                $pelanggan->setId($row['p2_id']);
                $pelanggan->setNama($row['p2_nama']);
                $pelanggan->setKontak($row['p2_kontak']);
                $pelanggan->setAlamat($row['p2_alamat']);
                $pelanggan->setAkunId($row['p2_akun_id']);
                $pelanggan->setAkun(null);

                $pesanan->setTransaksi($transaksi);
                $pesanan->setPemesan($pelanggan);
            }

            $detailPesanan = new DetailPesanan();
            $detailPesanan->setId($row['dp_id']);
            $detailPesanan->setTotal($row['dp_total']);
            $detailPesanan->setJenisBeras($row['dp_jenis_beras']);
            $detailPesanan->setHargaSatuan($row['dp_harga_satuan']);
            $detailPesanan->setJumlahBeli($row['dp_jumlah_beli']);
            $detailPesanan->setTakaranBeras($row['dp_takaran_beras']);
            $detailPesanan->setRefTakaranId($row['dp_ref_takaran_id']);
            $detailPesanan->setRefBerasId($row['dp_ref_beras_id']);

            $pesanan->addDetailPesanan($detailPesanan);

            $listPesanan[$row['id']] = $pesanan;
        }

        return array_values($listPesanan);
    }

    protected function toEntity(array $rows): Pesanan
    {
        $pesanan = $this->toPesanan($rows);

        return $pesanan;
    }

    private function toPesanan(array $rows): Pesanan
    {
        $pesanan = new Pesanan();
        $pesanan->setId($rows['id']);
        $pesanan->setNomorPesanan($rows['nomor_pesanan']);
        $pesanan->setNomorIterasiPesanan($rows['nomor_iterasi_pesanan']);
        $pesanan->setNamaPesanan($rows['nama_pesanan']);
        $pesanan->setKontakPesanan($rows['kontak_pesanan']);
        $pesanan->setTanggalPemesanan(date_create($rows['tanggal_pemesanan']));
        $pesanan->setAlamatPengiriman($rows['alamat_pengiriman']);
        $pesanan->setTotalTagihan($rows['total_tagihan']);
        $pesanan->setSubTotal($rows['sub_total']);
        $pesanan->setDiskon($rows['diskon']);
        $pesanan->setKodePromo($rows['kode_promo']);
        $pesanan->setPemesan(null);

        return $pesanan;
    }

    private function queryGetWithRelations(string $append = '', int $limit = 10, int $offset = 0) : string
    {
        return "SELECT p.*,
               dp.jenis_beras as dp_jenis_beras,
               dp.takaran_beras as dp_takaran_beras,
               dp.harga_satuan as dp_harga_satuan,
               dp.jumlah_beli as dp_jumlah_beli,
               dp.total as dp_total,
               dp.id as dp_id,
               dp.ref_beras_id as dp_ref_beras_id,
               dp.ref_takaran_id as dp_ref_takaran_id,
               t.id as t_id,
               t.tanggal_pembayaran as t_tanggal_pembayaran,
               t.nama_pembayaran as t_nama_pembayaran,
               t.bank_pembayaran as t_bank_pembayaran,
               t.nominal_dibayarkan as t_nominal_dibayarkan,
               t.status_pembayaran as t_status_pembayaran,
               t.konfirmasi_pembayaran as t_konfirmasi_pembayaran,
               t.file_bukti_pembayaran as t_file_bukti_pembayaran,
               p2.id as p2_id,
               p2.nama as p2_nama,
               p2.kontak as p2_kontak,
               p2.alamat as p2_alamat,
               p2.akun_id as p2_akun_id
            FROM (
                SELECT *
                FROM {$this->getTable()}
                ORDER BY nomor_pesanan DESC
                LIMIT {$limit} OFFSET {$offset}
                 ) as p
            LEFT JOIN detail_pesanan dp on p.id = dp.pesanan_id
            LEFT JOIN transaksi t on p.id = t.pesanan_id
            LEFT JOIN pelanggan p2 on p2.id = p.pemesan_id
            {$append}";
    }

    private function queryGetWithoutRelations(string $append = '') : string
    {
        return "SELECT * FROM {$this->getTable()} p {$append}";
    }

    public function isExistsByKodePromoAndPemesanId(string $kode, int $pelanggan): bool
    {
        $query = "SELECT EXISTS(SELECT id FROM {$this->getTable()} WHERE kode_promo = :kode_promo AND pemesan_id = :pemesan_id) as 'exists'";

        return $this->existsBy($query, ['kode_promo' => $kode, 'pemesan_id' => $pelanggan]);
    }
}
