<?php

require dirname(__DIR__, 1) . '/layouts/pdfReport.php';
print('ciao');
$html2pdf->setDefaultFont('helvetica');
$html2pdf->writeHTML($htmlReportPage);
$html2pdf->output($tmpDir . '/report_' . $_SESSION['id'] .'.pdf', 'F');