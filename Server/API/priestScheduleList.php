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
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
    
        $getPriestSchedule  = $this->db->rawQuery("
            SELECT * FROM lbrmss_priest_main as a 
            LEFT JOIN lbrmss_position as b ON a.position = b.pos_id
            WHERE a.remark = '1' AND a.status = '1'
        ");
    
        $data = []; // initialize array to collect all priest records
    
        foreach ($getPriestSchedule as $priest) {
                $id =  $priest['acc_id'];
                
                //             $this->db->where('remark','1');
                //             $this->db->where('sched_status','1');
                //             $this->db->where('priest_id',$id);
                // $schedules  =$this->db->get('lbrmss_priest_schedule');

                $schedules =$this->db->rawQuery("SELECT 
                                                    b.*, 
                                                    a.event_progress
                                                FROM lbrmss_event_table_main AS a
                                                INNER JOIN lbrmss_priest_schedule AS b 
                                                    ON a.priest_assigned_id = b.priest_id 
                                                        AND a.created_at = b.created_at
                                                WHERE 
                                                    b.priest_id = '$id'
                                                    AND b.sched_status = '1'
                                                    AND b.remark = '1'
                                                    AND a.event_progress = 1
                                                GROUP BY b.sched_id;");


            $data[] = array(
                "priest_id"    => $priest['priest_id'],
                "acc_id"    => $priest['acc_id'],
                "priest_name"  => $priest['fname'] . " " . substr($priest['mname'], 0, 1) . ". " . $priest['lname'],
                "position"     => $priest['pos_prefix'],
                "schedule"     => $schedules,
            );
        }
    
        echo json_encode($data); // output all collected data as JSON
      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
       
      


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