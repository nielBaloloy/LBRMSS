<?php
require_once __DIR__ . '../../vendor/autoload.php';

use Mpdf\Mpdf;

$content = isset($_GET['date']) ? $_GET['date'] : 'No date provided'; // Safely handle the date
$name = $_GET['name'] ;
$priest = isset($_GET['priest']) ? $_GET['priest'] : 'No name provided'; // Safely handle the name
$dateString = isset($_GET['date']) ? $_GET['date'] : '2025-04-04';
$date = new DateTime($dateString);
$service = isset($_GET['service']) ? $_GET['service'] : 'No date provided';
$age = isset($_GET['age']) ? $_GET['age'] : 'No date provided';
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
            <p style='margin: 0;'>Port Area, Legazpi City<br>4500 Albay, Philippines</p>
        </div>
    
        <!-- Title -->
        <h2 style='text-align: center; text-decoration: underline; margin-top: 30px;'>BURIAL CERTIFICATE</h2>
    
        <!-- TO WHOM -->
        <p style='margin-top: 30px;'><strong>TO WHOM IT MAY CONCERN:</strong></p>
        <p style='margin-left: 20px;'>I hereby certify that in the Burial Records of this Parish there appear the following data:</p>
    
        <!-- Data Table -->
        <table style='width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 16px;'>
            <tr>
                <td style='width: 40%; font-weight: bold; padding: 4px;'>Name of Deceased</td>
                <td style='padding: 4px;'>: '.$name.'</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Age</td>
                <td style='padding: 4px;'>: 46 years old</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Nationality</td>
                <td style='padding: 4px;'>: Filipino</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Residence</td>
                <td style='padding: 4px;'>: Buyoan, Legazpi City</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Civil Status</td>
                <td style='padding: 4px;'>: Married</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Father</td>
                <td style='padding: 4px;'>: ------</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Mother</td>
                <td style='padding: 4px;'>: ------</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Surviving or Deceased Spouse</td>
                <td style='padding: 4px;'>: +Rosario Bañares</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Date of Death</td>
                <td style='padding: 4px;'>: July 9, 1966</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Cause of Death</td>
                <td style='padding: 4px;'>: Peptic Ulcer</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Date of Burial</td>
                <td style='padding: 4px;'>: July 10, 1966</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Place of Burial</td>
                <td style='padding: 4px;'>: Legazpi Catholic Cemetery</td>
            </tr>
            <tr>
                <td style='font-weight: bold; padding: 4px;'>Officiating Priest</td>
                <td style='padding: 4px;'>: Mons. Valentin L. Reamon</td>
            </tr>
        </table>
    
        <!-- Issued Date -->
        <p style='margin-top: 20px; font-style: italic;'>
            Given this <strong>01<sup>st</sup> day of August, 2022</strong>, at the Catholic Rectory of St. Raphael, Legazpi Port, Legazpi City, Philippines.
        </p>
    
        <!-- Signature -->
        <div style='text-align: center; margin-top: 20px;'>
            <p><strong>.$priest.</strong></p>
            <p>Parochial Vicar</p>
        </div>
    
        <!-- Book Info -->
        <div style='margin-top: 1px;'>
            <p><strong>Book No.:</strong> <span style='color: red;'>21</span></p>
            <p><strong>Page:</strong> <span style='color: red;'>146</span></p>
            <p><strong>No:</strong> <span style='color: red;'>436</span></p>
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