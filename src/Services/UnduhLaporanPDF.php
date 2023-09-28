<?php

require_vendor(); // load composer autoloader
use Dompdf\Dompdf;
use Dompdf\Options;

class UnduhLaporanPDF
{
    public function unduhLaporanPemasukan(array $data) : void
    {
        $headerPage = 'pdf/components/header';
        $contentPage = 'pdf/pemasukan';
        $footerPage = 'pdf/components/footer';

        $this->download(
            $contentPage,
            'BUMDES KANTERLEANS - Laporan Pemasukan.pdf',
            $data,
            $headerPage,
            $footerPage
        );
    }

    public function unduhLaporanPenjualan(array $data) : void
    {
        $headerPage = 'pdf/components/header';
        $contentPage = 'pdf/penjualan';
        $footerPage = 'pdf/components/footer';

        $this->download(
            $contentPage,
            'BUMDES KANTERLEANS - Laporan Penjualan.pdf',
            $data,
            $headerPage,
            $footerPage
        );
    }

    private function download(
        string $page,
        string $title,
        mixed $data,
        string $header = 'header',
        string $footer = 'footer',
        $autoDownload = false
    ) : void {
        require_once template_dir($header);
        require_once template_dir($page);
        require_once template_dir($footer);

        $content = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream($title, ['Attachment' => $autoDownload]);
    }
}