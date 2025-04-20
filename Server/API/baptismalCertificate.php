<?php
require_once __DIR__ . '../../vendor/autoload.php';
use Mpdf\Mpdf;

$content = isset($_GET['date']) ? $_GET['date'] : 'No date provided'; // Safely handle the date
$name = $_GET['name'] ;
$priest = isset($_GET['priest']) ? $_GET['priest'] : 'No name provided'; // Safely handle the name
$dateString = isset($_GET['date']) ? $_GET['date'] : '2025-04-04';
$date = new DateTime($dateString);
$service = isset($_GET['service']) ? $_GET['service'] : 'No date provided';
// Convert number to words with ordinal
function numberToWordsWithOrdinal($num) {
    $suffix = ['st', 'nd', 'rd', 'th'];
    $modulo = $num % 10;
    $ordinal = ($num % 100 >= 11 && $num % 100 <= 13) ? 'th' : $suffix[min($modulo, 4)];
    return $num . $ordinal;
}

// Get day, month, and year
$day = $date->format('j');  // Day (without leading zero)
$month = $date->format('F'); // Month name
$year = $date->format('Y');  // Year
$dt = new DateTime();
$dty = $dt->format('Y-m-d H:i:s');
$dtyOne = $dt->format('Y-m-d');
// Get today's date
$dateT = new DateTime();

// Format today's date
$days = $dateT->format('j');  // Day (without leading zero)
$months = $dateT->format('F'); // Month name
$years = $dateT->format('Y');  // Year

// Generated date string for the letter
$dateStrings = "$months $days, $years";
try {
    $mpdf = new Mpdf();


    // Example dynamic content
    $html = "
    <div style='text-align: center;'>
        <h1>Certificate of Baptism</h1>
        <p>Diocese of Legazpi</p>
        <p>PARISH OF</p>
        <h2>ST. RAPHAEL THE ARCHANGEL</h2>
        <p>Legazpi City</p>
        <h3>This is to Certify</h3>
        <p>That <strong>JULLIEN CAMILLE BISMONTE</strong>, Sex: <strong>Female</strong></p>
        <p>Child of <strong>Joseph Marr Bismonte</strong></p>
        <p>and <strong>Ma. Zenith Apostol</strong></p>
        <p>Born in <strong>Legazpi City</strong></p>
        <p>On the <strong>23</strong> day of <strong>September</strong> <strong>2010</strong></p>
        <p>Has received in the Church of <strong>St. Raphael Parish</strong></p>
        <p>On the <strong>8</strong> day of <strong>January</strong> <strong>2011</strong></p>
        <h3>The Holy Sacrament of Baptism</h3>
        <p>By the Rev. <strong>Fr. Jose R. Bañares</strong></p>
        <p>The sponsors being <strong>Mario Bismonte Jr.</strong> and <strong>Teresita Costo</strong></p>
        <p>As appears in the Baptismal Register No. <strong>78</strong></p>
        <p>Page <strong>180</strong> No. <strong>02</strong> of this church.</p>
        <p>Date issued: <strong>December 01, 2022</strong></p>
        <p>Seal: FOR REFERENCE</p>
        <p><strong>REV. FR. JONATHAN L. CALLEJA</strong></p>
        <p>PARISH PRIEST / PAROCHIAL VICAR</p>
    </div>
";
    

    $mpdf->WriteHTML($html);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="certificate_' . $id . '.pdf"');
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    echo 'PDF Error: ' . $e->getMessage();
}