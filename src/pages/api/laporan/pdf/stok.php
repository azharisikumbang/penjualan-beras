<?php

if (
    is_null(session()->auth()) ||
    false === in_array(session()->auth()->getRole(), [Role::PIMPINAN, Role::ADMIN]) ||
    request()->notGetRequest()
) response()->notFound();

$laporan = app()->getManager()->getService('KelolaStok')->listStokBeras(100);

/** @var $pdf UnduhLaporanPDF */
$pdf = app()->getManager()->getService('UnduhLaporanPDF');
$pdf->unduhLaporanStok($laporan);
