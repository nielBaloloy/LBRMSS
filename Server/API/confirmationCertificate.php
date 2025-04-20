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
    <div style="width: 800px; margin: auto; padding: 40px 50px; border: 2px solid #b20000; font-family: Times New Roman, serif; background-color: #fcf9f4;">
    
     <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="flex: 1;">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/5/57/Diocese_of_Legazpi_Seal.png/120px-Diocese_of_Legazpi_Seal.png" style="height: 80px;">
            </div>
            <div style="flex: 4; text-align: center;">
                <div style="font-size: 18px;">Diocese of Legazpi</div>
                <div style="font-size: 24px; font-weight: bold; color: #0018a8; margin: 5px 0;">CERTIFICATE OF CONFIRMATION</div>
                <div style="font-size: 16px; font-weight: bold;">PARISH OF</div>
                <div style="border-bottom: 1px solid black; width: 300px; margin: 5px auto;"></div>
            </div>
            <div style="flex: 1;"></div>
        </div>
    
      <div style="text-align: center; color: red; font-weight: bold; font-size: 18px; margin-top: 40px;">
        This   is to Certify
      </div>
    
      <div style="text-align: center; margin-top: 30px; font-size: 18px;">
        That                  <b>'. $name.'</b>                         <span style="border-bottom: 1px solid black; display: inline-block; width: 300px;"></span>
        was Confirmed
      </div>
    
      <div style="text-align: center; margin-top: 20px; font-size: 18px;">
        on the '.numberToWordsWithOrdinal($day).'<span style="border-bottom: 1px solid black; display: inline-block; width: 70px;"></span>
        day of '.$month.' <span style="border-bottom: 1px solid black; display: inline-block; width: 120px;"></span>
        '.$year.'<span style="border-bottom: 1px solid black; display: inline-block; width: 50px;"></span>
      </div>
    
      <div style="text-align: center; font-size: 18px; margin-top: 30px; font-weight: bold; color: red;">
        According to the Rite of the Roman Catholic Church
      </div>
    
      <div style="text-align: center; margin-top: 20px; font-size: 16px;">
        by His Excellency
      </div>
    
      <div style="text-align: center; margin-top: 30px; font-size: 18px;">
        Most  '.$priest.' <span style="border-bottom: 1px solid black; display: inline-block; width: 300px;"></span>
      </div>
    
      <div style="text-align: center; font-size: 16px; margin-top: 10px; font-style: italic;">
        as appears on the Confirmation Register of this church.
      </div>
    
     <div style="margin-top: 60px; display: flex; justify-content: space-between; font-size: 16px;">
  <div style="text-align: left;">
    Seal<br>
    <span style="display: inline-block; border-bottom: 1px solid black; width: 200px;"></span>
  </div>
  <div style="text-align: right;">
  '.$priest.'
  <br>
    <span style="display: inline-block; border-bottom: 1px solid black; width: 200px;"></span><br>
    Pastor/Parochial Vicar
  </div>
</div>
    
        <div style="margin-top: 50px; display: flex; justify-content: space-between; font-size: 14px;">
          <div style="line-height: 2;">
            No. <span style="border-bottom: 1px solid black; display: inline-block; width: 100px;"></span><br>
            Page <span style="border-bottom: 1px solid black; display: inline-block; width: 100px;"></span><br>
            Book No. <span style="border-bottom: 1px solid black; display: inline-block; width: 100px;"></span>
          </div>
          <div style="line-height: 2;">
            Purpose <span style="border-bottom: 1px solid black; display: inline-block; width: 150px;"></span><br>
            Date Issued : '.$dtyOne.' <span style="border-bottom: 1px solid black; display: inline-block; width: 150px;"></span>
          </div>
        </div>
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