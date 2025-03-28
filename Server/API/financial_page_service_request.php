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
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        $apiParameter = (isset($arr['type']['status']) ? $arr['type']['status'] : "1" );
        
        $filter  = (isset($arr['type']) ? $arr['type'] :"");
       
        $filterRange = "";
        if (!empty($filter['dateFrom']) && !empty($filter['dateTo'])) {
          $dateFrom = $filter['dateFrom'];
          $dateTo = $filter['dateTo'];
          $filterRange = " AND (DATE(a.created_at) BETWEEN '$dateFrom' AND '$dateTo')";
      } else {
          $filterRange = "";
      }



        $Display_Pending = $this->db->rawQuery("SELECT ef.status as payStatus , a.*,b.*,c.*,d.*,e.*,ef.* 
                                                FROM lbrmss_event_table_main a  
                                                LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
                                                LEFT JOIN lbrmss_priest_main c ON c.priest_id = a.priest_assigned_id 
                                                LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
                                                LEFT JOIN lbrmss_client_list e ON e.cid = a.client
                                                 LEFT JOIN lbrmss_event_fee ef ON ef.event_id = a.event_id
                                                WHERE a.remark = '1' AND ef.status = '$apiParameter' $filterRange 
                                                ORDER BY ef.reference_no DESC");

      if(count($Display_Pending) > 0){

        foreach ($Display_Pending as $event) {
          if ($event['payStatus'] == '0') {
            $icon = "hourglass_empty"; // ⏳ Pending
            $color = "-yellow-8"; 
        } elseif ($event['payStatus'] == '1') {
            $icon = "event"; // 📅 Scheduled
            $color = "blue";
        } else {
            $icon = "check_circle"; // ✅ Done
            $color = "green";
        }
          $pendingEvents[] = [
            "all" => $event,
            "E_ID" => $event['event_id'],
            "EventServiceID" => $event['service_id'],
            "Service" => $event['event_name'],
            "Client" => $event['name'],
            "Type" => ($event['type'] == '1') ? "Mass" : "Special",
            "Date" => $event['date'],
            "Venue" => $event['venue_name'], 
            'Assigned_Priest' =>$event['pos_prefix']." " .$event['fname']." ".substr($event['mname'],0,1)." ".$event['lname'],
            "Venue_type" => ($event['venue_type'] == '1') ? "Church" : (($event['venue_type'] == '2') ? "Pastoral Center" : "Outside"),
            "EventProgress" => ($event['payStatus'] == '1') ? "Pending" :(($event['payStatus'] == '2') ? "Partial" :"Paid"),
            "RequirementStatus" => ($event['requirement_status']=='0') ? "Incomplete" : "Complete",
            "icon" =>$icon,
            "color" =>$color,
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