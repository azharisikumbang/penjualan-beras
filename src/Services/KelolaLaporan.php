<?php

require_vendor();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once __DIR__ . '/../Repositories/StokRepository.php';
require_once __DIR__ . '/../Repositories/PesananRepository.php';
require_once __DIR__ . '/../Repositories/PelangganRepository.php';

class KelolaLaporan
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function buatExcelLaporanStokBeras(string $kriteria = "")
    {
        // suply data
        $stokRepository = new StokRepository();
        $data = $stokRepository->getDataForLaporanStokBeras();
        $headers = [
            'No',
            'Jenis Beras',
            'Takaran Penjualan',
            'Harga Per Takaran',
            'Stok Tersisa (Takaran)',
            'Terjual (Takaran)'
        ];

        return $this->createFromTemplate($data, $headers);
    }

    public function buatExcelLaporanPenjualan(string $kriteria = 'all')
    {
        $pesananRepository = new PesananRepository();
        $dirtyData = $pesananRepository->getDataForLaporanPenjualan($kriteria);

        $data = [];
        $number = 1;
        /** @var $pesanan Pesanan */
        /** @var $detailPesanan DetailPesanan */
        foreach ($dirtyData as $pesanan) {
            foreach ($pesanan->getListPesanan() as $detailPesanan) {
                $data[] = [
                    [ 'type' => 'number', 'value' => $number++ ], // No
                    [ 'type' => 'date', 'value' => $pesanan->getTanggalPemesanan()->format('Y-m-d H:i:s') ], // Tanggal Pemesanan
                    [ 'type' => 'text', 'value' => $pesanan->getNomorPesanan() ], // Nomor Pemesanan
                    [ 'type' => 'text', 'value' => $detailPesanan->getJenisBeras() ], // Jenis Beras
                    [ 'type' => 'text', 'value' => $detailPesanan->getTakaranBeras() ], // Takaran
                    [ 'type' => 'number', 'value' => $detailPesanan->getJumlahBeli() ], // Jumlah Beli
                    [ 'type' => 'currency', 'value' => $detailPesanan->getHargaSatuan() ], // Harga Satuan
                    [ 'type' => 'currency', 'value' => $detailPesanan->getTotal() ], // Sub Total
                    [ 'type' => 'currency', 'value' => $pesanan->getTotalTagihan() ], // Total (Akumulatif)
                    [ 'type' => 'text', 'value' => $pesanan->getNamaPesanan() ], // Nama Pemesan
                    [ 'type' => 'text', 'value' => $pesanan->getKontakPesanan() ], // Kontak
                    [ 'type' => 'text', 'value' => $pesanan->getAlamatPengiriman() ], // Alamat Pengiriman
                    [ 'type' => 'text', 'value' => $pesanan->getTransaksi()->getStatusPembayaran()->getDisplay() ], // Status Pembayaran
                    [ 'type' => 'text', 'value' => $pesanan->getTransaksi()->getKonfirmasiPembayaran()->getDisplay() ], // Konfirmasi Admin
                    [ 'type' => 'date', 'value' => $pesanan->getTransaksi()->getTanggalPembayaran()?->format('Y-m-d H:i:s') ], // Tanggal Pembayaran
                    [ 'type' => 'text', 'value' => $pesanan->getTransaksi()->getNamaPembayaran() ], // Atas Nama Pembayaran
                    [ 'type' => 'text', 'value' => $pesanan->getTransaksi()->getBankPembayaran() ], // Bank Pembayaran
                    [ 'type' => 'currency', 'value' => $pesanan->getTransaksi()->getNominalDibayarkan() ] // Nominal Dibayarkan
                ];
            }
        }

        $headers = [
            'No',
            'Tanggal Pemesanan',
            'Nomor Pemesanan',
            'Jenis Beras',
            'Takaran',
            'Jumlah Beli (satuan: Takaran)',
            'Harga Satuan (per Takaran)',
            'Sub Total',
            'Total (Akumulasi)',
            'Nama Pemesan',
            'Kontak',
            'Alamat Pengiriman',
            'Status Pembayaran',
            'Konfirmasi Admin',
            'Tanggal Pembayaran',
            'Atas Nama Pembayaran',
            'Bank Pembayaran',
            'Nominal Dibayarkan',
        ];

        return $this->createFromTemplate($data, $headers);
    }

    public function buatExcelLaporanDataPelanggan(string $kriteria = 'all')
    {
        $pelangganRepository = new PelangganRepository();
        $dirtyData = $pelangganRepository->get(1000, 0, 'nama', 'ASC');

        $data = [];
        $number = 1;
        /** @var $pelanggan Pelanggan */
        foreach ($dirtyData as $pelanggan) {
            $data[] = [
                [ 'type' => 'number', 'value' => $number++ ], // No
                [ 'type' => 'text', 'value' => $pelanggan->getNama() ], // Nama Pelanggnan
                [ 'type' => 'text', 'value' => $pelanggan->getKontak() ], // Kontak Pelanggan
                [ 'type' => 'text', 'value' => $pelanggan->getAlamat() ], // Alamat Pelanggan
            ];
        }

        $headers = [
            'No',
            'Nama Terdaftar',
            'Kontak',
            'Alamat'
        ];

        return $this->createFromTemplate($data, $headers);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function createFromTemplate(
        array $data,
        array $headers
    ): false|Spreadsheet {
        $headerLength = count($headers);
        if (count($data) > 0) {
            $dataColumnLength = count($data[0]); // dengan asumsi banyak data di semua elemen sama (TODO: write better handler)

            if ($dataColumnLength != $headerLength) return false;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel Header */

        $borderStyle = [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ]
        ];

        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => $borderStyle,
        ];

        $cellStyle = [
          'borders' => $borderStyle
        ];

        $iterator = $sheet->getColumnIterator();
        $dataItemIndex = 0;
        for($i = 0; $i < $headerLength; $i++) {
            $currentCell = $iterator->current();
            $cellRowIndex = $currentCell->getColumnIndex();
            $cellIndex = sprintf("%s1", $cellRowIndex);
            $sheet->setCellValue($cellIndex, $headers[$i]);
            $sheet->getStyle($cellIndex)->applyFromArray($headerStyle);

            $rowStart = 2;
            for ($dataIndex = 0; $dataIndex < count($data); $dataIndex++) {
                $dataCellIndex = sprintf("%s%s", $cellRowIndex, $rowStart);
                $cell = $sheet->getCell($dataCellIndex);
                $cell->setValue($data[$dataIndex][$dataItemIndex]['value']);
                $cell->getStyle()->applyFromArray($cellStyle);
                switch ($data[$dataIndex][$dataItemIndex]['type']) {
                    case 'number':
                        $cell->getStyle()->getNumberFormat()->setFormatCode('#,##0_-');
                        break;

                    case 'date':
                        $cell->getStyle()->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
                        break;

                    case 'currency':
                        $cell->getStyle()->getNumberFormat()->setFormatCode('Rp #,##0_-');
                        break;
                }

                $rowStart++;
            }


            $iterator->next();
            $dataItemIndex++;
        }

        $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function forceDownloadSpreadsheet(Spreadsheet $spreadsheet, string $filename)
    {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename  .'"');
        $writer->save('php://output');
    }

}