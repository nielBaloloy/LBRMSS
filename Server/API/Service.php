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
        $apiParameter = (isset($arr['type']['status']) ? $arr['type']['status'] : "0" );
        
        $filter  = (isset($arr['type']) ? $arr['type'] :"");
       
        $filterRange = "";
        if (!empty($filter['dateFrom']) && !empty($filter['dateTo'])) {
          $dateFrom = $filter['dateFrom'];
          $dateTo = $filter['dateTo'];
          $filterRange = " AND (DATE(a.created_at) BETWEEN '$dateFrom' AND '$dateTo')";
      } else {
          $filterRange = "";
      }



        $Display_Pending = $this->db->rawQuery("SELECT * FROM lbrmss_event_table_main a 
                                                LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
                                                LEFT JOIN lbrmss_priest_main c ON c.priest_id = a.priest_assigned_id 
                                                LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
                                                LEFT JOIN lbrmss_client_list e ON e.cid = a.client 
                                                WHERE a.remark = '1' AND event_progress = '$apiParameter' $filterRange 
                                                ORDER BY a.created_at");

      if(count($Display_Pending) > 0){

        foreach ($Display_Pending as $event) {
          if ($event['event_progress'] == '0') {
            $icon = "hourglass_empty"; // ⏳ Pending
            $color = "-yellow-8"; 
        } elseif ($event['event_progress'] == '1') {
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
            "EventProgress" => ($event['event_progress'] == '0') ? "Pending" :(($event['event_progress'] == '1') ? "Scheduled" :"Done"),
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
        $arr = json_decode($datas, true);
        
        $Event =$arr['eventData'];//Event Data

        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');

        $ClientData = Array(
          "cid" => '',
          "name" => $Event['Client'],
          "contact_no" => $Event['Contact_Number'],
          "created_at" => $dty,
          "created_by" => '1',//to be changed later 
          "remark" => '1',
        );
        $insertClient = $this->db->insert('lbrmss_client_list',$ClientData);
        
        if($insertClient){
          $clientId = $this->db->getMaxId('lbrmss_client_list','cid');
          $new_cid= $clientId;

     
        $type = (isset($Event['Type']) && strtolower($Event['Type']) === "special") ? 2 : 1;


          $eventData = Array(
            "event_id" => '',
            "service_id" => $Event['Service'],
            "client" => $new_cid,
            "date" =>  $Event['Date'],
            "date_to" =>  $Event['Date'],
            "time_from"           => $Event['TimeTo'],
            "time_to"             => $Event['TimeFrom'],
            "venue_name"            => $Event['Venue'],
            "duration"            => $Event['Duration'],
            "type"                => $type,
            "days"                => $Event['Days'],
            "venue_type"          => $Event['Venue_type'],
            "priest_assigned_id"  => $Event['Assigned_Priest']['priest_id'],
            "event_progress"      => 1,
            "requirement_status"  => 1,
            "created_at"          => $dty,
            "created_by"          => '1',
            "remark"              => '1'

          );

           $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);
            if ($insert_EventInfo){
              $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
              $new_eventId= $eventId;
              $AnnointingData = array(
                "a_id" => '',
                "event_id" =>   $new_eventId,
                "region"   =>   $Event['Event_Region'],
                "province" =>    $Event['Event_Province'],
                "city"     =>  $Event['Event_City'],
                "brgy"    =>  $Event['Event_Barangay'],
                "description"  =>"",
                "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
                "created_at"    => $dty,
                "created_by" => '1',
                "remark" => '1'
            );
            
              $insertAnnointing = $this->db->insert('lbrmss_annointing',$AnnointingData );
              if($insertAnnointing){
                
                $ScheduleData = array(
                  "sched_id" => '',
                  "priest_id" => $Event['Assigned_Priest']['priest_id'],
                  "sched_event_id" => $new_eventId,
                  "date_from" => $Event['Date'],
                  "date_to" =>$Event['Date'],
                  "time_from" => $Event['TimeTo'],
                  "time_to"       => $Event['TimeFrom'],
                  "created_at" => $dty,
                  "remark" => '1' // 1 = show, 0 = hide
              );
              $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$ScheduleData);
                echo json_encode(array("Status" => "Success", "Message" => "Application Successfully Added"));
                //logs here
              }
            }else{
              echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
            }
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