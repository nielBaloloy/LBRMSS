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
        if(isset($arr['clientSchedule'])){
            $timeFrom   =  date("H:i:s", strtotime($arr['clientSchedule']['timeFrom']));
            $timeTo     =   date("H:i:s", strtotime($arr['clientSchedule']['timeTo']));
            $date       =  $arr['clientSchedule']['date'];
            $venuetype     =   $arr['clientSchedule']['venuetype'];
        
        }else{
            $timeFrom   =  date("H:i:s");
            $timeTo     =  date("H:i:s");
            $date       =  date("Y-m-d");
            $venuetype     =   '';
        }

    
         $getAvailablePriest = $this->db->rawQuery("SELECT DISTINCT
        b.*,
        pos.*,
        e.*,
        evt.venue_type
    FROM 
        lbrmss_priest_main AS b
    LEFT JOIN 
        lbrmss_position AS pos ON b.position = pos.pos_id
    LEFT JOIN 
        lbrmss_priest_schedule AS a ON a.priest_id = b.priest_id
    LEFT JOIN 
        lbrmss_account_person AS e ON e.pid = b.acc_id
    LEFT JOIN 
        lbrmss_event_table_main AS evt 
            ON evt.priest_assigned_id = a.priest_id
            AND a.remark = '1'
    WHERE 
        (a.priest_id IS NULL OR a.sched_status = '0')
        AND b.remark = '1'
        AND NOT EXISTS (
            SELECT 1 FROM lbrmss_priest_schedule AS a
            WHERE a.priest_id = b.priest_id
            AND a.remark = '1' AND a.sched_status = '1'
            AND '$date' BETWEEN a.date_from AND a.date_to
            AND (
                '$timeFrom' BETWEEN a.time_from AND a.time_to OR
                '$timeTo' BETWEEN a.time_from AND a.time_to OR
                a.time_from BETWEEN '$timeFrom' AND '$timeTo' OR
                a.time_to BETWEEN '$timeFrom' AND '$timeTo'
            )
        )
    GROUP BY b.priest_id

                    ");


                if(count($getAvailablePriest) > 0){
                    foreach($getAvailablePriest as $priest){
                            $AvailablePriest[] = [
                            "priest_id" => $priest['priest_id'],
                            "priest_name" => $priest['pos_prefix']." " .$priest['fname']." ".substr($priest['mname'],0,1)." ".$priest['lname'],
                            
                            ];
                    }
                     echo json_encode(array("Status"=>"Success", "availablePriest"=>$AvailablePriest ,"message"=>""));
                }else{
                        echo json_encode(array("Status"=>"Failed", "message"=>"No Priest available"));
                }

      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $LoginCredentials = json_decode($datas, true);
         
        $this->db->where ("Username", $LoginCredentials['username']);
        $account = $this->db->getOne("useraccounts");
        if($account < 1){
          echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
          }
          else{
            echo json_encode(array("Status"=>"Success", "loginData"=>$account ));
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