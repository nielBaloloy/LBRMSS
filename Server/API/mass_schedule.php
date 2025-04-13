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
                       $this->db->where('remark','1');
        $getSchedule = $this->db->get('lbrmss_mass_schedules');
        if($getSchedule){
            echo json_encode(array("Status"=>"Success", "data"=>$getSchedule));
        }else{
            echo json_encode(array("Status"=>"Failed", "data"=>[]));
          }
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
       
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $eventData = Array(
            "event_id" => '',
            "service_id" => '7',
            "client" => '',
            "date" =>  $arr['date'],
            "date_to" =>  $arr['date'],
            "time_from"           => $arr['time_from'],
            "time_to"             => $arr['time_to'],
            "venue_name"            => $arr['Venue'],
            "duration"            => 60,
            "type"                => 2,
            "days"                => 1,
            "venue_type"          => $arr['Venue_type'],
            "priest_assigned_id"  => $arr['Assigned_Priest']['priest_id'],
            "event_progress"      => 1,
            "requirement_status"  => 1,
            "created_at"          => $dty,
            "created_by"          => '1',
            "remark"              => '1'

          );

           $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);
           $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
        $massSchedule = array(
            "mass_id" => null,
            "mass_title" => $arr['mass_title'],
            "date" => $arr['date'],
            "time_from" => $arr['time_from'],
            "time_to" => $arr['time_to'],
            "language" => $arr['language'],
            "VenueType" =>$arr['Venue_type'],
             "venue" =>$arr['Venue'],
             "priest_id" =>$arr['Assigned_Priest']['priest_id'],
            "created_at" => $dty ,
            "created_by" =>1,
            "remark" => "1"
        );
        
        if(isset($arr)){
            $insertMass = $this->db->insert('lbrmss_mass_schedules', $massSchedule);
            $ScheduleData = array(
                "sched_id" => '',
                "priest_id" => $arr['Assigned_Priest']['priest_id'],
                "sched_event_id" => $new_eventId,
                "date_from" => $Event['Date'],
                "date_to" =>$Event['Date'],
                "time_from" => $Event['TimeTo'],
                "time_to"       => $Event['TimeFrom'],
                "created_at" => $dty,
                "remark" => '1' // 1 = show, 0 = hide
            );


            $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$ScheduleData);
            if( $insertMass){
                echo json_encode(array("Status"=>"Success"));
            }else{
                echo json_encode(array("Status"=>"Failed"));
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