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
    $html = '
    <div style="width: 800px; margin: auto; padding: 50px; font-family: Times New Roman, serif; border: 5px double black; background-color: #fff;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">PARISH OF ST. RAPHAEL THE ARCHANGEL</h1>
            <p style="margin: 0;">Diocese of Legazpi</p>
            <p style="margin: 0;">Port Area, Legazpi City</p>
            <p style="margin: 0;">Tel. 742-6346</p>
            <h2 style="margin-top: 30px; text-decoration: underline;">Certificate of Marriage</h2>
        </div>

        <p style="text-align: justify; font-size: 18px; line-height: 1.8;">
            This is to certify that <strong>'.$name.'</strong>, having duly fulfilled all the requirements for the Sacrament of Matrimony, were lawfully married according to the rites of the Holy Roman Catholic Church on <strong>'.$dtyOne.'</strong> at <strong> St Raphael Church</strong>.
        </p>

        <p style="text-align: justify; font-size: 18px; line-height: 1.8;">
            The marriage was solemnized by <strong>'.$priest.'</strong> and duly recorded in the Marriage Register of this Parish.
        </p>

     

        <div style="margin-top: 60px; text-align: right;">
            <p>__________________________</p>
            <p><strong>'.$priest.'</strong></p>
            <p>Officiating Priest</p>
        </div>

        <div style="margin-top: 40px; text-align: left;">
            <p><strong>Date Issued:</strong> '.$dtyOne.'</p>
        </div>
    </div>
    ';
 

    $mpdf->WriteHTML($html);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="certificate_' . $id . '.pdf"');
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    echo 'PDF Error: ' . $e->getMessage();
}