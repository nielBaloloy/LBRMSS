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
        $data = json_encode($payload);
        $arr = json_decode($data, true);
        
        $getScheduleToday  = $this->db->rawQuery("SELECT a.* , CONCAT(b.fname, ' ', b.lname) as fullname ,c.event_name as servicename FROM lbrmss_event_table_main a
        LEFT JOIN lbrmss_priest_main as b ON a.priest_assigned_id = b.acc_id
        LEFT JOIN lbrmss_event_services as c ON c.etype_id =a.service_id
        WHERE a.date =CURDATE() AND a.remark='1' AND a.event_progress = '1' GROUP BY a.event_id ORDER BY a.time_from DESC ");

        if(count($getScheduleToday) > 0){
            echo json_encode(array("Status"=>"Success", "data"=>$getScheduleToday));
        }else{
            echo json_encode(array("Status"=>"Failed", "data"=>[] ));
        }
   
      

      }
      public function httpPost($payload)
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