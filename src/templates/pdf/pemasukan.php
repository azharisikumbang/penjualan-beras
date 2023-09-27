<table style="width: 100%; margin-bottom: 20px" border="0">
    <tr>
        <td style="width: 120px;">Perihal</td>
        <td>: <strong>LAPORAN PEMASUKAN BUMDES</strong></td>
    </tr>
    <tr>
        <td>Tanggal Akses</td>
        <td>: <?= tanggal(date_create(), false, true) ?> WIB</td>
    </tr>
</table>
<table border="1" style="border-collapse: collapse; width: 100%" cellpadding="4">
    <tr style="text-align: center; font-weight: bold">
        <td style="width:10%">NO</td>
        <td>PERIODE</td>
        <td style="width: 30%;">PEMASUKAN (Rupiah)</td>
    </tr>
    <?php

    $listBulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    // variable $data is served by app
    $no = 1;
    /** @var $data array */
    foreach ($data['data'] as $d) { ?>
        <tr>
            <td style="text-align: center"><?php echo $no++; ?></td>
            <td style="text-align: center"><?php echo $listBulan[$d['periode'] - 1]; // periode start with 1, but $listBulan index start with 0 ?></td>
            <td style="text-align: center">Rp <?php echo rupiah($d['total']); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="2" style="text-align: right; font-weight: 600;">Total Pemasukan</td>
        <td style="text-align: center; font-weight: 600">Rp <?php echo rupiah($data['total']) ?></td>
    </tr>
</table>