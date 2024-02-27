<?php

if (
    is_null(session()->auth()) ||
    false === in_array(session()->auth()->getRole(), [Role::PIMPINAN, Role::ADMIN]) ||
    request()->notGetRequest()
) response()->notFound();

$tahun = $_GET['tahun'] ?? date('Y');
$bulan = $_GET['bulan'] ?? 0;
$tanggal = 0;

if (isset($_GET['tanggal'])) {
    $bulan = $_GET['bulan'] ?? date('m');
    $tanggal = $_GET['tanggal'];
}

if ($tahun > date('Y') || $bulan > 12 || $tanggal > 31) {
    response()->badRequest('Periode laporan tidak sesuai, mohon periksa kembali');
}

/** @var $kelolaLaporan KelolaLaporan */
$kelolaLaporan = app()->getManager()->getService('KelolaLaporan');
$laporan = $kelolaLaporan->laporanPenjualanBerasPerPeriodeBerdasarkanJenisBeras($_GET['jenis_beras'] ?? 0, $tahun, $bulan, $tanggal);

/** @var $pdf UnduhLaporanPDF */
$pdf = app()->getManager()->getService('UnduhLaporanPDF');
$pdf->unduhLaporanPenjualan(data: $laporan);