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
       

      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $PaymentData= json_decode($datas, true);
        $ev_id = $PaymentData['event_id'];
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $dtyOne = $dt->format('Y-m-d');
        $event = array(
            
            "event_id" => $PaymentData['event_id'],
            "service_id" => $PaymentData['service_id'],
            "reference_no" =>$PaymentData['reference_no'],
            "payment_type" => $PaymentData['payment_type'], // Default 0 (Full Payment)
            "amount_total" => $PaymentData['amount_total'],
            "payment" => $PaymentData['payment'],
            "balance" =>  ($PaymentData['payment_type'] == '1') ? 0 : $PaymentData['balance'],
            "due_date" => $dtyOne,
            "status" => ($PaymentData['payment_type'] == '1') ? '3' : '2',
            "created_at" => null,
            "updated_at" =>  date("Y-m-d H:i:s"),
            "created_by" => null,
            "updated_by" => $PaymentData['created_by'],
            "remark" => '1'
        );
        $this->db->where('remark','1');
        $this->db->where('event_id', $ev_id);
        if ($this->db->update('lbrmss_event_fee', $event)){
          /** Validate id if the event id has a seminar and asssigned priest  */
          $getEventData = $this->db->rawQuery("SELECT a.event_id, a.date, a.date_to,a.time_from, a.time_to, a.priest_assigned_id, b.seminar_id
          FROM lbrmss_event_table_main AS a 
          LEFT JOIN lbrmss_seminar AS b ON a.event_id = b.event_id 
          WHERE a.event_id = '$ev_id' AND b.event_id ='$ev_id' AND a.remark = '1' AND b.remark ='1';");

          $this->db->where ("event_id", $ev_id);
          $hasSeminar = $this->db->getOne("lbrmss_seminar");
          if(!empty($hasSeminar)){
              /** If not empty update seminar  */
              foreach($getEventData as $seminar){
                $sid = $seminar['seminar_id'];
                  $updateSeminar = $this->db->rawQuery("UPDATE lbrmss_seminar SET status = '1' WHERE seminar_id ='$sid' AND remark = '1';");
              }
          }
          /** insert priest schedule */
          $priestAssigned = array(
            "sched_id" => $getEventData[0][''],
            "priest_id"=>$getEventData[0]['priest_assigned_id'],
            "sched_event_id"=>$getEventData[0]['event_id'],
            "date_from" =>$getEventData[0]['date'],
            "date_to" =>$getEventData[0]['date_to'],
            "time_from" =>$getEventData[0]['time_from'],
            "time_to"=>$getEventData[0]['time_to'],
            "created_at" =>$dty,
            "remark"=>'1'
          );
          $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$priestAssigned);
          $updateEventStatus = $this->db->rawQuery("UPDATE lbrmss_event_table_main SET event_progress = '1' WHERE event_id ='$ev_id' AND remark = '1'");
  
          echo json_encode(array("message"=>""));
        }
         else
               {
                echo json_encode(array("message"=>"Error" ));
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