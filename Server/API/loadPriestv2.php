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
        
        $date   =  date("H:i:s", strtotime($arr['date']));
        $timefrom   =  date("H:i:s", strtotime($arr['timefrom']));
        $timeto   =  date("H:i:s", strtotime($arr['timeto']));

   
         $getAvailablePriest = $this->db->rawQuery("SELECT b.*,
                        pos.*
                    FROM lbrmss_priest_main AS b
                    LEFT JOIN lbrmss_position AS pos ON b.position = pos_id
                    LEFT JOIN lbrmss_priest_schedule AS a 
                    ON a.priest_id = b.priest_id 
                    AND a.remark = '1'  
                    AND (
                        
                        ('$date' BETWEEN a.date_from AND a.date_to)
                        AND (
                            ('$timefrom' BETWEEN a.time_from AND a.time_to)  
                            OR ('$timeto' BETWEEN a.time_from AND a.time_to)  
                            OR (a.time_from BETWEEN '$timefrom' AND '$timeto')  
                            OR (a.time_to BETWEEN '$timefrom' AND '$timeto')  
                        )
                    )
                    WHERE a.priest_id IS NULL OR a.sched_status = '0'
                    AND b.remark = '1'; 
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