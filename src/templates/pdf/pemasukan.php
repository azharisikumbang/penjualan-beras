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

    /** @var $data array */
    if ($data['data']) {

        // variable $data is served by app
        $no = 1;
        foreach ($data['data'] as $pemasukan) {
        $periode = match ($pemasukan['type']) {
            'YEAR' => $listBulan[$pemasukan['periode'] - 1],
            'MONTH' => $listBulan[$pemasukan['periode'] - 1] . ' ' . $data['query']['tahun'],
            'DATE' => tanggal($pemasukan['periode'])
        };
        ?>
        <tr>
            <td style="text-align: center"><?php echo $no++; ?></td>
            <td style="text-align: center"><?php echo $listBulan[$pemasukan['periode'] - 1]; // periode start with 1, but $listBulan index start with 0 ?></td>
            <td style="text-align: center">Rp <?php echo rupiah($pemasukan['total']); ?></td>
        </tr>
    <?php }
    } else {
    $type = 'DATE';
    if ($data['query']['tanggal'] == 0) {
        $type = 'MONTH';
    }

    if($data['query']['bulan'] == 0) {
        $type = 'YEAR';
    }

    $periode = match ($type) {
        'YEAR' => $listBulan[$data['query']['bulan'] - 1],
        'MONTH' => $listBulan[$data['query']['bulan'] - 1] . ' ' . $data['query']['tahun'],
        'DATE' => tanggal(
            date_create(sprintf("%s-%s-%s", $data['query']['tahun'], $data['query']['bulan'], $data['query']['tanggal']))
        )
    };

    ?>
        <tr>
            <td colspan="2" style="text-align: center"><?= $periode ?></td>
            <td style="text-align: center">Rp 0</td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="2" style="text-align: right; font-weight: 600;">Total Pemasukan</td>
        <td style="text-align: center; font-weight: 600">Rp <?php echo rupiah($data['total']) ?></td>
    </tr>
</table>