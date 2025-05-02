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
        $pendingResult = $this->db->query("SELECT COUNT(event_id) AS count FROM lbrmss_event_table_main WHERE event_progress = '0' AND DATE_FORMAT(NOW(), '%Y-%m') = DATE_FORMAT(date_to, '%Y-%m') AND remark = '1'");
            $scheduledResult = $this->db->query("SELECT COUNT(event_id) AS count FROM lbrmss_event_table_main WHERE event_progress = '1' AND DATE_FORMAT(NOW(), '%Y-%m') = DATE_FORMAT(date_to, '%Y-%m') AND remark = '1'");
            $completeResult = $this->db->query("SELECT COUNT(event_id) AS count FROM lbrmss_event_table_main WHERE event_progress = '2' AND DATE_FORMAT(NOW(), '%Y-%m') = DATE_FORMAT(date_to, '%Y-%m') AND remark = '1'");

            // Extract the count values safely
            $pending = isset($pendingResult[0]['count']) ? (int)$pendingResult[0]['count'] : 0;
            $scheduled = isset($scheduledResult[0]['count']) ? (int)$scheduledResult[0]['count'] : 0;
            $complete = isset($completeResult[0]['count']) ? (int)$completeResult[0]['count'] : 0;

            // Output JSON
            echo json_encode([
                "Status" => "Success",
                "pending" => $pending,
                "scheduled" => $scheduled,
                "complete" => $complete
            ]);
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