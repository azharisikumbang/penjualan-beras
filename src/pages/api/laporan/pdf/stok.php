<?php

if (
    false === session()->isAuthenticatedAs('pimpinan') ||
    request()->notGetRequest()
) response()->notFound();

$laporan = app()->getManager()->getService('KelolaStok')->listStokBeras(100);

/** @var $pdf UnduhLaporanPDF */
$pdf = app()->getManager()->getService('UnduhLaporanPDF');
$pdf->unduhLaporanStok($laporan);
