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
      
        $data = json_encode($payload);
        $arr = json_decode($data, true);
         
        $priestid = $arr['priestId'];
        $event = $arr['event'];
        $eventID = $event['event_id'];

        $updateSchedulePriest = $this->db->rawQuery("UPDATE lbrmss_priest_schedule SET priest_id = '$priestid' ,sched_status = '1' WHERE remark = '1' AND sched_event_id = '$eventID';");
        $update_event_table_main = $this->db->rawQuery("UPDATE lbrmss_event_table_main SET priest_assigned_id = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
        
        $checkifmianExists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_confirmation_main WHERE event_id ='$eventID' AND remark = '1'");
        $checkifBaptExists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_baptism_main WHERE event_id ='$eventID' AND remark = '1'");
        $checkifannointingExists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_annointing WHERE event_id ='$eventID' AND remark = '1'");
        $checkifblessingExists =  $this->db->rawQuery("SELECT 1 as exist FROM blessing_requests WHERE event_id ='$eventID' AND remark = '1'");
        $checkifburialExists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_burial WHERE event_id ='$eventID' AND remark = '1'");
        $checkifmarriageExists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_mariage_main WHERE event_id ='$eventID' AND remark = '1'");
        $checkifmass_schedule_Exists =  $this->db->rawQuery("SELECT 1 as exist FROM lbrmss_mass_schedules WHERE mass_event_id ='$eventID' AND remark = '1'");
       
                
            if (!empty($checkifmianExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_confirmation_main SET assigned_priest = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifBaptExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_baptism_main SET assigned_priest = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifannointingExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_annointing SET assigned_priest = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                $this->db->rawQuery("UPDATE lbrmss_event_table_main SET event_progress = '1' WHERE remark = '1' AND event_id = '$eventID';");
        
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifblessingExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE blessing_requests SET priest_id = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                $this->db->rawQuery("UPDATE lbrmss_event_table_main SET event_progress = '1' WHERE remark = '1' AND event_id = '$eventID';");
        
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifburialExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_burial SET assigned_priest = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifmarriageExists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_mariage_main SET assigned_priest = '$priestid' WHERE remark = '1' AND event_id = '$eventID';");
                echo json_encode(array("msg" =>""));
            }
            if (!empty($checkifmass_schedule_Exists[0]['exist'])) {
                $this->db->rawQuery("UPDATE lbrmss_mass_schedules SET priest_id = '$priestid'  WHERE remark = '1' AND mass_event_id = '$eventID';");
                $this->db->rawQuery("UPDATE lbrmss_event_table_main SET event_progress = '1' WHERE remark = '1' AND event_id = '$eventID';");
        
                echo json_encode(array("msg" =>""));
            }

           
            echo json_encode(array("msg" =>""));
         
       
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