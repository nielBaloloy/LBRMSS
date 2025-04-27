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

                    
        $getSchedule = $this->db->rawQuery("SELECT a.*, b.lname, b.mname, b.fname
                                            FROM lbrmss_mass_schedules a
                                            LEFT JOIN lbrmss_priest_main b ON b.acc_id = a.priest_id
                                            WHERE a.remark = '1';");
     $data = [];

     if ($getSchedule) {
         foreach ($getSchedule as $row) {
          $maseveid = $row['mass_event_id'];
            $getMasintentions = $this->db->rawQuery("SELECT * FROM lbrmss_mass_intentions WHERE mass_id ='$maseveid' AND  remark = '1';");
            

             $data[] = [
                 'mass_id' => $row['mass_id'],
                 'mass_event_id' => $row['mass_event_id'],
                 'mass_title' => $row['mass_title'],
                 'date' => $row['date'],
                 'time_from' => $row['time_from'],
                 'time_to' => $row['time_to'],
                 'VenueType' => $row['VenueType'],
                 'venue' => $row['venue'],
                 'priest_id' => $row['priest_id'],
                 'language' => $row['language'],
                 'created_at' => $row['created_at'],
                 'created_by' => $row['created_by'],
                 'remark' => $row['remark'],
                 'lname' => $row['lname'],
                 'mname' => $row['mname'],
                 'fname' => $row['fname'],
                 'details'   => $getMasintentions
             ];
         }
     
         if (count($data) > 0) {
             // Output the result as a single JSON response
             echo json_encode([
                 'Status' => 'Success',
                 'data' => $data
             ]);
         } else {
             echo json_encode([
                 'Status' => 'Failed',
                 'data' => []
             ]);
         }
     } else {
         echo json_encode([
             'Status' => 'Failed',
             'data' => []
         ]);
     }
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
       
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $c = count($arr);
  
        for($i = 0; $i < $c; $i++){
            $priestId = isset($arr[$i]['assigned_priest']['priest_id']) ? $arr[$i]['assigned_priest']['priest_id'] : null;

            


        $eventData = Array(
            "event_id" => '',
            "service_id" => '7',
            "client" => '',
            "date"                =>  $arr[$i]['date'],
            "date_to"             =>  $arr[$i]['date'],
            "time_from"           => $arr[$i]['time_from'],
            "time_to"             => $arr[$i]['time_to'],
            "venue_name"            => $arr[$i]['venue'],
            "duration"            => 60,
            "type"                => 2,
            "days"                => 1,
            "venue_type"          => $arr[$i]['venue_type'],
            "priest_assigned_id"  => $priestId,
            "event_progress"      => $priestId ? 1 : 0,
            "requirement_status"  => 1,
            "created_at"          => $dty,
            "created_by"          => '1',
            "remark"              => '1'

          );

           $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);
           $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');

           $massSchedule = array(
                "mass_id" => null,
                "mass_event_id"     => $eventId,
                "mass_title"        => $arr[$i]['mass_title'],
                "date"              => $arr[$i]['date'],
                "time_from"         => $arr[$i]['time_from'],
                "time_to"           => $arr[$i]['time_to'],
                "language"          => $arr[$i]['language'],
                "VenueType"         =>$arr[$i]['venue_type'],
                 "venue"            =>$arr[$i]['venue'],
                 "priest_id"        =>$priestId,
                "created_at"        => $dty ,
                "created_by"        =>1,
                "remark"            => "1"
            );
           
            $insertMass = $this->db->insert('lbrmss_mass_schedules', $massSchedule);
            //schedule for priest

                $ScheduleData = array(
                    "sched_id" => '',
                    "priest_id"         => $priestId,
                    "sched_event_id"    => $eventId,
                    "date_from"         => $arr[$i]['date'],
                    "date_to"           => $arr[$i]['date'],
                    "time_from"         => $arr[$i]['time_to'],
                    "time_to"           => $arr[$i]['time_from'],
                    "created_at"        => $dty,
                    "remark"            => '1' // 1 = show, 0 = hide
                );
                $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$ScheduleData);
            
           
          
        }
       
            echo json_encode(array("Status"=>"Success"));
       

       

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