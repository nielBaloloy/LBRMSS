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

    <div style="width: 800px; margin: auto; padding: 40px; font-family: Arial, sans-serif; background-color: rgb(255, 255, 255);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">PARISH OF ST. RAPHAEL THE ARCHANGEL</h1>
            <div>Diocese of Legazpi</div>
            <div>Port Area, Legazpi City</div>
            <div>Tel 742-6346</div>
            <div>4500 Philippines</div>
        </div>
        <h2 style="text-align: center; margin: 20px 0;">REQUEST FOR PARTIAL CHANGES IN PARISH RECORDS</h2>
        
        <div style="margin-top: 20px;">
            <strong>MOST REV. JOEL Z. BAYLON, D.D.</strong><br>
            Bishop of Legazpi<br>
            Bishop\'s Residence<br>
            61 Sikatuna St., Legazpi City
        </div>

        <p style="margin-top: 30px;">Your Excellency,</p>
        
        <p>May I respectfully request Your Excellency to issue a written decree, authorizing me to effect in accordance with the Affidavit hereto attached the necessary changes or corrections in the '.$service.' Record of <strong>'.$name.'</strong> in Page <strong>167</strong>, No. <strong>03</strong>.</p>

        <div style="margin-top: 20px;">
            <strong>From:</strong> ROY SALVADOR <strong>To:</strong> RHOY SALVADOR <br>
        </div>
        
        <p>Sincerely imploring your favorable response to the request and your pastoral blessing, I remain.</p>

        <div style="margin-top: 40px; text-align: right;">
            <strong>Respectfully yours,</strong><br>
            <strong>REV. FR. LEANDRO P. MURILLO</strong> <br>
            Parish Priest
        </div>
        
        <div style="margin-top: 40px; text-align: left;">
            <strong>'.$dateStrings.'</strong><br>
            <strong>Date</strong>
        </div>

        <div style="margin-top: 20px;">
            <strong>Encls:</strong><br>
            1. A copy of the uncorrected Baptismal/Confirmation/Marriage & Death Certificates,<br>
            2. Affidavit for Partial Changes of Records,<br>
            3. Authenticated Birth Certificate or any public document with needed date
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