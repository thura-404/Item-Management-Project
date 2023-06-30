<?php

namespace App\DBTransactions\Items;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFDownload
{
    private $itemsToDownload;

    public function __construct($itemsToDownload)
    {
        $this->itemsToDownload = $itemsToDownload;
    }

    public function downloadItemsAsPDF()
    {
        // Generate the HTML content for the PDF
        $html = view('pages.pdf', ['items' => $this->itemsToDownload]);

        // Create a new instance of Dompdf with options
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation (optional)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML content to PDF
        $dompdf->render();

        // Generate a unique filename for the PDF
        $filename = 'items_' . date('YmdHis') . '.pdf';

        // Download the PDF file
        return $dompdf->stream($filename);
    }
}
