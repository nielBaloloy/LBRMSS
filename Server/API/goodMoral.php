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
    <div style='font-family: Times New Roman, serif; line-height: 1.6; margin: 40px;'>
    
        <!-- Header -->
        <div style='text-align: center;'>
            <h2 style='margin: 0;'>PARISH OF ST. RAPHAEL THE ARCHANGEL</h2>
            <p style='margin: 0;'>DIOCESE OF LEGAZPI</p>
            <p style='margin: 0;'>TEL. NO. (052) 742-6346</p>
            <p style='margin: 0;'>Port Area, Legazpi City</p>
            <p style='margin: 0;'>4500 Albay, Philippines</p>
        </div>
    
        <!-- Title -->
        <h2 style='text-align: center; text-decoration: underline; margin-top: 30px;'>CERTIFICATION</h2>
    
        <!-- TO WHOM -->
        <p style='margin-top: 30px;'><strong>TO WHOM IT MAY CONCERN:</strong></p>
    
        <!-- Body Content -->
        <p style='text-align: justify; text-indent: 40px; margin-top: 20px;'>
            Upon the recommendation of the Barangay Pastoral Coordinator of Dap-Dap, Legazpi City, I hereby certify that <strong>JOHN ERROL S. LOTIVIO</strong>, a resident of the same barangay, is of good moral character.
        </p>
    
        <p style='text-align: justify; text-indent: 40px; margin-top: 20px;'>
            This certification is issued upon request of the above mentioned name for PRC Board Exam.
        </p>
    
        <p style='text-indent: 40px; margin-top: 30px;'>
            Issued this <strong>3<sup>rd</sup> day of October, 2022</strong> at St. Raphael Parish Office.
        </p>
    
        <!-- Signature -->
        <div style='text-align: center; margin-top: 60px;'>
            <p><strong>REV. FR. LEANDRO P. MURILLO</strong></p>
            <p>Parish Priest</p>
        </div>
    
        <!-- Footer -->
        <div style='margin-top: 50px;'>
            <p style='font-size: 12px; font-weight: bold;'>THIS DOCUMENT IS NOT VALID<br>WITHOUT THE PARISH DRY SEAL</p>
        </div>
    
    </div>
    ";

    $mpdf->WriteHTML($html);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="certificate_' . $id . '.pdf"');
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    echo 'PDF Error: ' . $e->getMessage();
}