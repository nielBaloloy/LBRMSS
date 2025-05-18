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
  require_once('../model/sessionGet.php');
 
 
  class API
  {
      public function __construct()
      {
       $this->db = new MysqlIDB('localhost', 'root', '', 'lbrmss_db');
       
      }

      public function httpGet($payload)
      {
        $upcomingEvent = $this->db->rawQuery("SELECT * FROM `lbrmss_event_table_main`as a 
                                                LEFT JOIN lbrmss_event_services as b ON a.service_id = b.etype_id  AND b.remark = '1'
                                                LEFT JOIN lbrmss_client_list as c ON c.cid = a.client AND c.remark = '1'
                                                LEFT JOIN lbrmss_priest_main as d ON d.acc_id  = a.priest_assigned_id AND d.remark = '1'
                                                LEFT JOIN lbrmss_position as e ON e.pos_id  = d.position AND e.remark = '1'
                                             
                                                WHERE a.date > CURDATE()
                                                AND a.remark ='1' AND a.requirement_status = '1'");
        $sched = [];
        foreach ($upcomingEvent as $row) {
                $eventSeminarId = $row['event_id'];
                $selectSeminar = $this->db->rawQuery("SELECT * FROM lbrmss_seminar WHERE event_id = '$eventSeminarId';");
                
                $sched []= [
                    "event_id" => $row['event_id'],
                    "Event" => $row['event_name'],
                    "Client" => $row['name'] ?? "   ",
                    "Date"  => $row['date'],
                    "Time_from"=> $row['time_from'],
                    "Time_to"  =>$row['time_to'],
                    "Venue_name" => $row['venue_name'],
                    "Priest_assigned" =>$row['pos_prefix']. " " .$row['fname']. " ". $row['lname'],
                    "seminar" =>  $selectSeminar 
                ];
        }
            
        if(count($sched) > 0){
            echo json_encode(array("Status"=>"Status","data"=>$sched));
        }else{
            echo json_encode(array("Status"=>"Status","data"=>[]));
        }
      }
      public function httpPost($payload)
      {
        
    }
 
     
      public function httpPut($payload)
      {
        
        // $upcomingEvents =$this->db->rawQuery("UPDATE `lbrmss_event_table_main`
        //                                         SET event_progress = '1'
        //                                         WHERE `date` > CURDATE()
        //                                         AND `remark` ='1' AND requirement_status = '1';
        //                                         ");

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