<?php
require_once __DIR__ . '../../vendor/autoload.php';
use Mpdf\Mpdf;

$name = $_GET['name'] ?? 'N/A';
$purpose = $_GET['purpose'] ?? 'General';
$service = $_GET['service'] ?? 'Unknown';

try {
    $mpdf = new Mpdf();

    // Choose template based on purpose
    $html = '';
    $date = date('F j, Y');

    if (strcasecmp($purpose, 'For Good Moral') === 0) {
        $html = "
            <h1 style='text-align: center;'>Certificate of Good Moral</h1>
            <p>This is to certify that <strong>{$name}</strong> has exhibited good moral character during their affiliation with our parish community.</p>
            <p>This certification is issued upon the request of the above-named individual for the purpose of <strong>{$purpose}</strong>.</p>

            <p><strong>Date Issued:</strong> {$date}</p>
        ";
    } elseif (strcasecmp($purpose, 'Affidavit of Change Records') === 0) {
        $html = "
              <h2 style='text-align: center;'>AFFIDAVIT FOR CHANGES IN RECORDS</h2>
       
        <br><br>
        <p>I, the undersigned <strong>{$name}</strong>, of legal age, do hereby declare under solemn oath that:</p>
        <p>
            This affidavit is being executed in the presence of the undersigned priest, for the purpose of corresponding the aforementioned erroneous entries in the 
            (<strong>{$service}</strong>) Book Records.
        </p>

        <p>
            In truth whereof, I sign this affidavit on this <strong>" . date('jS') . "</strong> day of <strong>" . date('F, Y') . "</strong> at 
            <strong>St. Raphael Parish, Legazpi City</strong>.
        </p>

        <br><br><br>
        <p style='text-align: right;'>
            <strong>{$name}</strong><br>
            Affiant
        </p>

        <br><br>
        <p><strong>SWORN TO BEFORE ME:</strong></p>
        <p><strong>REV. FR. LEANDRO P. MURILLO</strong><br>Parish Priest</p>
        ";
    } elseif (strcasecmp($purpose, 'For Change of Records') === 0) {
    $fromName = isset($fromName) ? $fromName : "ROY SALVADOR";
    $toName = isset($toName) ? $toName : "RODY SALVADOR";
    
    $currentDate = date('F j, Y');
    
    $html = '
    <div style="font-family: Arial, sans-serif; line-height: 1.6; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">PARISH OF ST. RAPHAEL THE ARCHANGEL</div>
            <div style="font-size: 12px; margin-bottom: 5px;">Dona Maria Legazpi City</div>
            <div style="font-size: 12px; margin-bottom: 5px;">4500 Philippines</div>
            <div style="font-size: 12px; margin-bottom: 15px;">Tel 742-8346</div>
        </div>
        
        <div style="font-size: 16px; font-weight: bold; text-align: center; margin: 20px 0; text-decoration: underline;">REQUEST FOR PARTIAL CHANGES IN PARISH RECORDS</div>
        
        <div style="margin-bottom: 30px;">
            <p>MOST REV. JOEL Z. BAYLON, D.D.<br>
            Bishop of Legazpi<br>
            Bishop\'s Residence<br>
            St. Raphael St., Legazpi City</p>
        </div>
        
        <p>Your Excellency:</p>
        
        <div style="text-indent: 50px; margin-bottom: 30px;">
            <p>May I respectfully request Your Excellency to issue a written decree, authorizing me to effect in accordance with the Affidavit hereto attached the necessary changes or corrections in the Baptismal Record ' . htmlspecialchars($name) . ', in Book of. ' . htmlspecialchars($service).'.</p>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin: 20px 0;">
            <div style="width: 45%;">
                <div style="border-bottom: 1px solid #000; height: 25px;"> </div>
                <div style="font-size: 12px; text-align: center;">From</div>
            </div>
            <div style="width: 45%;">
                <div style="border-bottom: 1px solid #000; height: 25px;"> </div>
                <div style="font-size: 12px; text-align: center;">To</div>
            </div>
        </div>
        
        <div style="margin: 30px 0;">
            <p>Sincerely imploring your favorable response to the request and your pastoral blessing, I remain</p>
        </div>
        
        <div style="text-align: right; margin: 40px 0 20px 0;">
            <p>Respectfully yours,</p>
            <p style="font-weight: bold;">REV. FR. LEANDRO P. MURILLO</p>
            <p style="font-size: 12px;">Parish Priest</p>
        </div>
        
        <div style="margin-top: 10px; display: flex;">
            <div style="font-size: 12px; width: 40px;">Date:</div>
            <div style="border-bottom: 1px solid #000; width: 150px; padding-left: 5px;">' . $currentDate . '</div>
        </div>
        
        <div style="margin-top: 30px;">
            <div style="font-size: 12px; font-weight: bold; text-decoration: underline;">Encls:</div>
            <div style="margin-top: 5px; font-size: 12px;">
                1. A copy of the concerned Baptismal/Confirmation/Marriage.<br>
                2. Affidavit for Partial Changes of Records.<br>
                3. Authenticated Birth Certificate or any public document with needed data.
            </div>
        </div>
    </div>
    ';
    } else {
        // Default/general certificate
        $html = "
            <h1 style='text-align: center;'>Certificate Preview</h1>
            <p><strong>Issued to:</strong> {$name}</p>
            <p><strong>Sacrament:</strong> {$service}</p>
            <p><strong>Purpose:</strong> {$purpose}</p>
            <p><strong>Date:</strong> {$date}</p>
        ";
    }

    $mpdf->WriteHTML($html);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="certificate_' . urlencode($name) . '.pdf"');
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) {
    echo 'PDF Error: ' . $e->getMessage();
}
