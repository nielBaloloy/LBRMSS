<?php
   
  // Tells the browser to allow code from any origin to access
  header('Access-Control-Allow-Origin: *');
  // Tells browsers whether to expose the response to the frontend JavaScript code when the request's credentials mode (Request.credentials) is include
  header("Access-Control-Allow-Credentials: true");
  // Specifies one or more methods allowed when accessing a resource in response to a preflight request
  header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
  // Used in response to a preflight request which includes the Access-Control-Request-Headers to indicate which HTTP headers can be used during the actual request
  header("Access-Control-Allow-Headers: Content-Type");
 
  require_once('../MysqlIDb.php');
 
 
  class API
  {
      public function __construct()
      {
       $this->db = new MysqlIDB('localhost', 'root', '', 'lbrmss_db');
       
      }


      public function httpGet($payload)
      {
       
        $getfinanceRecord = $this->db->rawQuery("SELECT a.event_id AS main, a.*,b.*, c.*,d.* FROM lbrmss_event_fee  AS a 
        LEFT JOIN lbrmss_event_table_main b ON a.event_id = b.event_id 
        LEFT JOIN lbrmss_event_services c ON c.etype_id = a.service_id
          LEFT JOIN lbrmss_client_list d ON d.cid = b.client
        WHERE a.remark = '1' and a.status IN (2,3) GROUP BY a.event_id
        ");
$getCountToday= $this->db->rawQuery("SELECT SUM(a.amount_total) as total FROM lbrmss_event_fee  AS a 
            LEFT JOIN lbrmss_event_table_main b ON a.event_id = b.event_id 
            LEFT JOIN lbrmss_event_services c ON c.etype_id = a.service_id
            WHERE a.remark = '1' AND a.status IN (2,3) 
            ");
$totals =$getCountToday[0]['total'];

$data=[];
// Final data array with custom logic
foreach($getfinanceRecord as $getfinanceRecord){
    $paymentTypeLabel = $getfinanceRecord['payment_type'] == 1 ? "Full Payment" : "Downpayment";
    $feeTypeLabel     = (strpos($getfinanceRecord['reference_no'], "REFCERT") !== false) ? "Certificate" : "Ceremony";

    $data []= [
        "amount_total"        => $getfinanceRecord['amount_total'],
        "balance"             => $getfinanceRecord['balance'],
        "cid"                 => $getfinanceRecord['cid'],
        "client"              => $getfinanceRecord['client'],
        "contact_no"          => $getfinanceRecord['contact_no'],
        "created_at"          => $getfinanceRecord['created_at'],
        "created_by"          => $getfinanceRecord['created_by'],
        "date"                => $getfinanceRecord['date'],
        "date_to"             => $getfinanceRecord['date_to'],
        "days"                => $getfinanceRecord['days'],
        "due_date"            => $getfinanceRecord['due_date'],
        "duration"            => $getfinanceRecord['duration'],
        "etype_id"            => $getfinanceRecord['etype_id'],
        "event_description"   => $getfinanceRecord['event_description'],
        "event_fee_id"        => $getfinanceRecord['event_fee_id'],
        "event_id"            => $getfinanceRecord['event_id'],
        "event_name"          => $getfinanceRecord['event_name'],
        "event_progress"      => $getfinanceRecord['event_progress'],
        "fee_type"            => $feeTypeLabel,
        "main"                => $getfinanceRecord['main'],
        "name"                => !empty($getfinanceRecord['name']) ? $getfinanceRecord['name'] : 'Anonymous',
        "payment"             => $getfinanceRecord['payment'],
        "payment_type"        => $paymentTypeLabel,
        "priest_assigned_id"  => $getfinanceRecord['priest_assigned_id'],
        "reference_no"        => $getfinanceRecord['reference_no'],
        "remark"              => $getfinanceRecord['remark'],
        "requirement_status"  => $getfinanceRecord['requirement_status'],
        "service_id"          => $getfinanceRecord['service_id'],
        "status"              => $getfinanceRecord['status'],
        "time_from"           => $getfinanceRecord['time_from'],
        "time_to"             => $getfinanceRecord['time_to'],
        "type"                => $getfinanceRecord['type'],
        "updated_at"          => $getfinanceRecord['updated_at'],
        "updated_by"          => $getfinanceRecord['updated_by'],
        "venue_name"          => $getfinanceRecord['venue_name'],
        "venue_type"          => $getfinanceRecord['venue_type']
];
}
echo json_encode(array("Status"=>"Success","data"=>$data, "overall"=>$totals));
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
         
        
    }
 
     
      public function httpPut($payload)
      {
        
  

    }
      public function httpDelete($payload)
      {
        
        
       
       
      }
  }
 


    $request_method = $_SERVER['REQUEST_METHOD'];
    $received_data = json_decode(file_get_contents('php://input'));
   


  $api = new API;


  if ($request_method == 'GET') {
      $api->httpGet($_GET);
  }
  if ($request_method == 'POST') {
      $api->httpPost($received_data);
  }
  if ($request_method == 'PUT') {
      $api->httpPut($received_data);
  }
  if ($request_method == 'DELETE') {
      $api->httpDelete($received_data);
  }
  ?>