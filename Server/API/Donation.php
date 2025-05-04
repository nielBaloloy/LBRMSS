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
       
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
       
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $dtyOne = $dt->format('Y-m-d');
        $cleanDate = str_replace("-", "", $dtyOne);
        $eve_id = $this->db->getMaxId(" lbrmss_donations","donation_id");
        $data = array(
          "donation_id"        => '',
          "donor"           => $arr['donor']['name'],
          "donation_type"   => $arr['donation']['paymentMethod']['value'],
          "purpose"         => $arr['donation']['type']['label'],
          "amount_donated"  => $arr['donation']['amount'],
          "donation_date"   => date('Y-m-d'), // or replace with actual date if available
          "notes"           => $arr['donation']['description'],
          "reference_no"    => "DON-" . $cleanDate . $eve_id,
          "created_at"      => date('Y-m-d H:i:s'),
          "created_by"      => '1', // replace with actual user ID variable
          "remark"          => 1 // or use 0 depending on your logic (1 = show, 0 = hide)
      );
      $insertdonation = $this->db->insert("lbrmss_donations",$data);
      if($insertdonation){
        echo json_encode(array("message"=>""));
      }else{
        echo json_encode(array("message"=>"Failed"));
      }
     
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