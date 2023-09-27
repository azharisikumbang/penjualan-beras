        <div style="margin-top: 20px; text-align: right; width: 100%">
            <div style="width: 220px; text-align: left; float:right">
                <div style="margin-bottom: 60px"><?= config('pdf', 'ttd_kota') ?>, <?php echo date('d F Y') ?></div>
                <div>
                    <div style="text-decoration: underline; font-weight: bold"><?= config('pdf', 'ttd_nama') ?></div>
                    <div><?= config('pdf', 'jabatan') ?></div>
                </div>
            </div>
        </div>
    </body>
</html>