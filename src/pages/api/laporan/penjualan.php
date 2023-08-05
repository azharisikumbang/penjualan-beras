<?php

if (
    false === session()->isAuthenticatedAs('pimpinan') ||
    request()->notGetRequest() ||
    false === request()->has(['kriteria'])
) response()->notFound();


try {
    /** @var $kelolaLaporan KelolaLaporan */
    $kelolaLaporan = app()->getManager()->getService('KelolaLaporan');
    $filename = sprintf("KANTERLEANS_LAPORAN-PENJUALAN_%s.xlsx", time());
    $generatedExcel = $kelolaLaporan->buatExcelLaporanPenjualan($_GET['kriteria']);
    $kelolaLaporan->forceDownloadSpreadsheet($generatedExcel, $filename);
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    response()->serverError($e->getMessage());
}

exit();

