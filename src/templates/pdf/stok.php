<table style="width: 100%; margin-bottom: 20px" border="0">
    <tr>
        <td style="width: 120px;">Perihal</td>
        <td>: <strong>LAPORAN STOK</strong></td>
    </tr>
    <tr>
        <td>Tanggal Akses</td>
        <td>: <?= tanggal(date_create(), false, true) ?> WIB</td>
    </tr>
</table>
<table border="1" style="border-collapse: collapse; width: 100%" cellpadding="4">
    <tr style="text-align: center; font-weight: bold">
        <td style="width: 5%">NO</td>
        <td style="text-align: center; width: 25%">JENIS BERAS</td>
        <td>TAKARAN (Kg)</td>
        <td>STOK TERSEDIA</td>
        <td style="width: 30%;">HARGA JUAL (Rupiah)</td>
    </tr>
    <?php

    /** @var $data array */
    if ($data) {

        // variable $data is served by app
        $no = 1;
        foreach ($data as $item) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['relations']['beras']['jenis'] ?></td>
                    <td style="text-align: center"><?= trim(str_replace("KG", "", strtoupper($item['relations']['takaran']['variant']))) ?></td>
                    <td style="text-align: center"><?= rupiah($item['jumlah_stok']) ?></td>
                    <td style="text-align: center">Rp <?= rupiah($item['harga']) ?></td>
                </tr>
            <?php }
    } else {
        ?>
        <tr>
            <td style="text-align: center"></td>
            <td colspan="3" style="text-align: center">Tidak ada data.</td>
        </tr>
    <?php } ?>
</table>