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
        date_default_timezone_set('Asia/Manila');

        $getNoPriestEvent = $this->db->rawQuery("
        SELECT a.*, b.*, c.*
        FROM `lbrmss_event_table_main` AS a
        LEFT JOIN lbrmss_event_services AS b ON a.service_id = b.etype_id
        LEFT JOIN lbrmss_client_list AS c ON c.cid = a.client
        WHERE 
            a.remark = '1' 
            AND (
                (b.etype_id IN (5, 6, 7) AND a.event_progress IN ('0', '1'))
                OR 
                (b.etype_id NOT IN (5, 6, 7) AND a.event_progress = '1')
            )
            AND a.priest_assigned_id IS NULL
        GROUP BY a.event_id
        ORDER BY a.date ASC");
        $arrayEvent  =[];
        foreach ($getNoPriestEvent as $priesEvent) {
            $arrayEvent[]=[
                "all" => $priesEvent,
                "title" => $priesEvent['name'] ?? 'N/A',
                "service" => $priesEvent['event_name'],
                "date" => $priesEvent['date'],
                "time_from" => $priesEvent['time_from'],
                "time_to" => $priesEvent['time_to'],
                "venue" => $priesEvent['venue_name'],
            ];     
        }

        echo json_encode(array("Status"=>"Success","data"=>$arrayEvent));
     
      }
      public function httpPost($payload)
      {
      
        $data = json_encode($payload);
        $arr = json_decode($data, true);
         
        
      
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