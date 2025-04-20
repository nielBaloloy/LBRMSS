<?php
require_once __DIR__ . '../../vendor/autoload.php';
use Mpdf\Mpdf;

$id = $_GET['id'] ?? 'N/A'; // default fallback if id not set


try {
    $mpdf = new Mpdf();

    // Example dynamic content
    $html = '
        <h1 style="text-align: center;">Certificate Preview</h1>
        <p><strong>Certificate ID:</strong> ' . htmlspecialchars($id) . '</p>
        <p><strong>Sacrament:</strong> Baptism</p>
        <p><strong>Date:</strong> April 20, 2025</p>
    ';

    $mpdf->WriteHTML($html);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="certificate_' . $id . '.pdf"');
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    echo 'PDF Error: ' . $e->getMessage();
}