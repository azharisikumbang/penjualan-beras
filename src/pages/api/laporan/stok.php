<?php

if (
    false === session()->isAuthenticatedAs('pimpinan') ||
    request()->notPostRequest() ||
    false === request()->has(['kriteria'])
) response()->notFound();


try {
    /** @var $kelolaLaporan KelolaLaporan */
    $kelolaLaporan = app()->getManager()->getService('KelolaLaporan');
    $filename = "KANTERLEANS_LAPORAN-STOK-BERAS.xlsx";
    $generatedExcel = $kelolaLaporan->buatExcelLaporanStokBeras();
    $kelolaLaporan->forceDownloadSpreadsheet($generatedExcel, $filename);

} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    response()->serverError($e->getMessage());
}
exit();

