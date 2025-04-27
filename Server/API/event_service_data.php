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
       
        $Display_Schedule = $this->db->rawQuery("SELECT a.*,b.event_name,c.lname,c.mname,c.fname,
                                                d.pos_name,d.pos_prefix,
                                                e.name
                                                FROM lbrmss_event_table_main a 
                                                LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
                                                LEFT JOIN lbrmss_priest_main c ON c.acc_id = a.priest_assigned_id 
                                                LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
                                                LEFT JOIN lbrmss_client_list e ON e.cid = a.client 
                                                WHERE a.remark = '1' AND a.event_progress ='1' AND a.priest_assigned_id IS NOT NULL
                                                group by a.event_id
                                                
                                                ORDER BY a.created_at");
     
     if(count($Display_Schedule) > 0){
       
        foreach ($Display_Schedule as $event) {
          if ($event['event_progress'] == '0') {
            $icon = "hourglass_empty"; // ⏳ Pending
            $color = "-yellow-8"; 
        } elseif ($event['event_progress'] == '1') {
            $icon = "event"; // 📅 Scheduled
            $color = "blue";
        } else {
            $icon = "check_circle"; // ✅ Done
            $color = "yellow";
        }
        $ev_id = $event['event_id'];
        /** get seminar */
        $this->db->where('remark', '1');
        $this->db->where('event_id', $ev_id);
        $seminarDetails  = $this->db->get('lbrmss_seminar');
        /** get payment  info */
        $this->db->where('remark', '1');
        $this->db->where('event_id', $ev_id);
        $paymentdedtails  = $this->db->get('lbrmss_event_fee');
        
          $pendingEvents[] = [
            "all" => $event,
            "title"=> $event['event_name'],
            "start"=>$event['date']."T".$event['time_from'],
            "end" =>$event['date_to']."T".$event['time_to'],
            "backgroundColor"=> '#0000',
            "seminar" => !empty($seminarDetails) ? $seminarDetails : [],
            "payment" => !empty($paymentdedtails) ? $paymentdedtails : []
              ];
        
        }
       
        
        echo json_encode(array("Status"=>"Success", "data"=>$pendingEvents));
      }else{
          echo json_encode(array("Status"=>"Failed", "data"=>[]));
        }
            
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $LoginCredentials = json_decode($datas, true);
         
       
      
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