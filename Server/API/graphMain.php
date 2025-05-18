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

                        $getCashier = $this->db->rawQuery("
                    SELECT 
                        service_id,
                        SUM(CASE WHEN fee_type = 0 THEN 1 ELSE 0 END) AS ceremonies,
                        SUM(CASE WHEN fee_type = 1 THEN 1 ELSE 0 END) AS certificates
                    FROM 
                        lbrmss_event_fee
                    WHERE 
                        remark = 1
                    GROUP BY 
                        service_id
                ");

                $ceremonies = [0, 0, 0, 0];   // marriage, baptism, confession, burial
                $certificates = [0, 0, 0, 0];

                // Map service_id to index (you can adjust IDs based on your DB)
                $serviceMap = [
                    1 => 0, // marriage
                    2 => 1, // baptism
                    3 => 2, // confession
                    4 => 3  // burial
                ];

                foreach ($getCashier as $row) {
                    $sid = $row['service_id'];
                    if (isset($serviceMap[$sid])) {
                        $index = $serviceMap[$sid];
                        $ceremonies[$index] = (int)$row['ceremonies'];
                        $certificates[$index] = (int)$row['certificates'];
                    }
                }

                $datasets = [
                    [
                        "label" => "Ceremonies",
                        "data" => $ceremonies,
                        "backgroundColor" => "rgba(54, 162, 235, 0.7)",
                        "borderColor" => "rgba(54, 162, 235, 1)",
                        "borderWidth" => 1,
                    ],
                    [
                        "label" => "Certificates",
                        "data" => $certificates,
                        "backgroundColor" => "rgba(255, 99, 132, 0.7)",
                        "borderColor" => "rgba(255, 99, 132, 1)",
                        "borderWidth" => 1,
                    ]
                ];

                echo json_encode(["datasets" => $datasets]);

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