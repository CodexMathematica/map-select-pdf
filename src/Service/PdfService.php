<?php

namespace App\Service;

use Dompdf\Dompdf;

class PdfService
{
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new DomPdf();
    }
    /**
     * Pour telecharger en pdf le contenu html de la page
     *
     * @param string $html
     * @return void
     */
    public function showPdfFile(string $html): void
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("details.pdf", [
            'Attachement' => false
        ]);
    }

}