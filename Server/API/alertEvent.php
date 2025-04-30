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
  require_once('../model/insertEvent.php');
 
  class API
  {
      public function __construct()
      {
       $this->db = new MysqlIDB('localhost', 'root', '', 'lbrmss_db');
       
      }


      public function httpGet($payload)
      {
       //alert events 1 hour before 
       $events = new EventModel();
       $alert = $this->db->rawQuery("SELECT a.*, b.*, c.*, d.*
                                        FROM lbrmss_event_table_main AS a
                                        LEFT JOIN lbrmss_client_list AS b ON a.client = b.cid 
                                        LEFT JOIN lbrmss_event_fee AS c ON c.event_id = a.event_id 
                                        LEFT JOIN lbrmss_event_services AS d ON d.etype_id = a.service_id 
                                        WHERE a.time_from BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 HOUR)
                                        AND a.remark = '1'
                                        AND d.etype_id != '7'
                                        GROUP BY a.event_id;
                                        ");

        foreach ($alert as $upcoming) {
        
                $res = $events->sendAlert($upcoming);

        }



        }
      public function httpPost($payload)
      {
      
       
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