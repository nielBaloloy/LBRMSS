<?php
require_once __DIR__ . '../../vendor/autoload.php';
use Mpdf\Mpdf;

$content = isset($_GET['date']) ? $_GET['date'] : 'No date provided'; // Safely handle the date
$name = isset($_GET['name']) ? $_GET['name'] : 'No name provided'; // Safely handle the name
$priest = isset($_GET['priest']) ? $_GET['priest'] : 'No name provided'; // Safely handle the name
$dateString = isset($_GET['date']) ? $_GET['date'] : '2025-04-04';
$date = new DateTime($dateString);

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
        <hr style="border: 1px solid black;">
        <h2 style="text-align: center; margin: 20px 0;">AFFIDAVIT FOR CHANGES IN RECORDS</h2>
        <p>I, the undersigned <strong>ROY SALVADOR BODOPOL</strong>, of legal age, resident of Naga City, do hereby declare under solemn oath that:</p>
        <ol>
            <li>I authorized the bearer <strong>Mrs. Francia Rosario B. Perez</strong> ____________________</li>
            <li>________________________________________</li>
            <li>________________________________________</li>
        </ol>
        <p>This affidavit is being executed in the presence of the undersigned priest, for the purpose of corresponding the aforementioned erroneous entries in the <strong>(Baptismal/Confirmation/Matrimony)</strong> Book Records.</p>
        <p>In truth whereof, I sign this affidavit on this <strong>1<sup>st</sup></strong> day of October, 2022 at St. Raphael Parish, Legazpi City.</p>
        <div style="margin-top: 40px; text-align: right;">
            <strong>FRANCIA ROSARIO B. PEREZ</strong> <br>
            Affiant
        </div>
        <div style="margin-top: 40px; text-align: left;">
            <p>SWORN TO BEFORE ME:</p>
            <strong>REV. FR. LEANDRO P. MURILLO</strong> <br>
            Parish Priest
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