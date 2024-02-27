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

    // $tanggal = isset($_GET['tanggal']) && $_GET['tanggal'] >= 1 ? $_GET['tanggal'] : "";
    // $bulan = isset($_GET['bulan']) && $_GET['bulan'] >= 1 ? $listBulan[$_GET['bulan'] - 1] : "";

    // $periode = trim(sprintf("%s %s %s", $tanggal, $bulan, $_GET['tahun']));

    $periode = isset($_GET['tahun']) ? $_GET['tahun'] : "-";
?>
<table style="width: 100%; margin-bottom: 20px" border="0">
    <tr>
        <td style="width: 120px;">Perihal</td>
        <td>: <strong>LAPORAN PENJUALAN</strong></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>: <?= $periode ?></td>
    </tr>
    <tr>
        <td>Tanggal Akses</td>
        <td>: <?= tanggal(date_create(), false, true) ?> WIB</td>
    </tr>
</table>
<table border="1" style="border-collapse: collapse; width: 100%" cellpadding="4">
    <tr style="text-align: center; font-weight: bold">
        <td style="width:20%">PERIODE</td>
        <td>JENIS BERAS</td>
        <td>TAKARAN</td>
        <td style="width: 30%;">TERJUAL (Karung)</td>
    </tr>
    <?php

    /** @var $data array */
    if ($data['data']) {

        // variable $data is served by app
        $no = 1;
        foreach ($data['data'] as $item) {
            $parentRow = true;
            foreach ($item as $penjualan) {
                $periode = match ($penjualan['type']) {
                    'YEAR' => $listBulan[$penjualan['periode'] - 1],
                    'MONTH' => $listBulan[$penjualan['periode'] - 1] . ' ' . date('Y'),
                    'DATE' => tanggal($penjualan['periode'])
                };
            ?>
            <tr>
                <?php
                if ($parentRow) {
                    $parentRow = false;
                    ?>
                    <td rowspan="<?= count($item) ?>" style="text-align: center"><?php echo $periode; // periode start with 1, but $listBulan index start with 0 ?></td>
                <?php } ?>
                <td style="text-align: center"><?php echo $penjualan['jenis']; ?></td>
                <td style="text-align: center"><?php echo $penjualan['variant']; ?></td>
                <td style="text-align: center"><?php echo rupiah($penjualan['terjual']); ?></td>
            </tr>
        <?php }
        }
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
            <td style="text-align: center"><?= $periode ?></td>
            <td colspan="3" style="text-align: center">Tidak ada penjualan.</td>
        </tr>
    <?php } ?>
</table>