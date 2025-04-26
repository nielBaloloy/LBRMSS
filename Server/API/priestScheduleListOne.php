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
        $accountID =  $arr['acc'];
        date_default_timezone_set('Asia/Manila');
        $timeFrom   =  date("H:i:s");
        $timeTo     =  date("H:i:s");
        $date       =  date("Y-m-d");

        $schedules =$this->db->rawQuery("SELECT *, c.* FROM lbrmss_priest_schedule as a 
                                          LEFT JOIN lbrmss_priest_main as b ON a.priest_id = b.acc_id 
                                          LEFT JOIN lbrmss_event_table_main as c ON c.event_id = a.sched_event_id 
                                          LEFT JOIN lbrmss_event_services as d ON d.etype_id =c.service_id
                                          WHERE b.acc_id = '$accountID' AND a.date_from = '$date' AND a.sched_status = '1' AND c.event_progress = '1' AND a.remark = '1' GROUP BY a.sched_id ORDER BY a.time_from DESC ");
  
       if($schedules){
          echo json_encode(array("Status"=>"Success","data"=> $schedules));
       }else{
        echo json_encode(array("Status"=>"Failed","data"=> []));
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