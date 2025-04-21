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
        $serviceId = json_encode($payload);
        $ServiceID = json_decode($serviceId, true);
      
       $ID  = $ServiceID['service'];

       switch($ID){
          case 1 :
            //marriages  service Counter
         
        $Display_Pending = $this->db->rawQuery("SELECT count(ef.event_id) as counter
                                                FROM  
                                                lbrmss_event_fee ef 
                                                WHERE ef.remark = '1' AND ef.status = '1' AND ef.fee_type = '1'
                                                ORDER BY ef.reference_no DESC");
         
           $Display_Pending;
            echo json_encode(array("Status"=>"Success", "data"=>$Display_Pending));
            break;
       }
            
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