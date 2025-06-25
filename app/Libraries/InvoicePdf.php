<?php

namespace App\Libraries;

use TCPDF;

class InvoicePdf extends TCPDF
{
    protected $headerTitle = '';

    public function Header()
    {
        if ($this->headerTitle) {
            $this->SetFont('helvetica', 'B', 16);
            $this->Cell(0, 10, $this->headerTitle, 0, 1, 'C');
        }
    }

    public function setHeaderTitle($title)
    {
        $this->headerTitle = $title;
    }
}
