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
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $dtyOne = $dt->format('Y-m-d');
        $cleanDate = str_replace("-", "", $dtyOne);
        $event =$arr['eventData'];//Event Data


        $ClientData = Array(
          "cid" => '',
          "name" => $event['Client' ],
          "contact_no" => $event['Contact_Number'],
          "created_at" => $dty,
          "created_by" => '1',//to be changed later 
          "remark" => '1',
        );
        $insert =$this->db->insert('lbrmss_client_list', $ClientData);
        $clientId = $this->db->getMaxId('lbrmss_client_list','cid');
        
        $BlessingEvent = Array(
          "event_id"            => '',
           "service_id"          => $event['Service'],
           "client"              => $clientId,
           "date"                => $event['Date'],
           "date_to"             => $event['Date'],
           "time_from"           => $event['TimeFrom'],
           "time_to"             => $event['TimeTo'],
           "venue_name"          => 'Outside',
           "duration"            => $event['Duration'] ?? '', // If not present
           "type"                => 2,
           "days"                => $event['Days'],
           "venue_type"          => 3,
           "priest_assigned_id"  => (isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
           ? $event['Assigned_Priest']['priest_id'] 
           : null),
           "event_progress"      => 1,
           "requirement_status"  => 1 ,
           "created_at"          => $dty,
           "created_by"          => 1,
           "remark"              => '1'
         );

          $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $BlessingEvent);
          if($insert_EventInfo){
            $eve_id = $this->db->getMaxId('lbrmss_event_table_main','event_id');
            $BlessingReqEvent = array(
              "event_id"            => $eve_id,
              "requester_name"      => $event['Client'] ?? '',
              "contact_number"      => $event['Contact_Number'] ?? '',
              "notes"               => $event['Description'] ?? '',
              "preferred_date"      => $event['Date'] ?? '',
              "preferred_time_from" => $event['TimeFrom'] ?? '',
              "preferred_time_to"   => $event['TimeTo'] ?? '',
              "location"            => $event['Event_Region']. " ".$event['Event_Province']."".$event['Event_City']."".$event['Event_Barangay'],
              "has_guests"          => $event['has_guests'] ?? 0,
              "guest_count"         => $event['guest_count'] ?? 0,
              "needs_certificate"   => $event['needs_certificate'] ?? 0,
              "donation_amount"     => $event['donation_amount'] ?? 0.00,
              "created_at"          => $dty,
              "priest_id"           =>(isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
              ? $event['Assigned_Priest']['priest_id'] 
              : null),
              "remark"              => '1'
            );
            $insert_ReqEventInfo =$this->db->insert('blessing_requests', $BlessingReqEvent);

              //payment for blessing but this is (optional)
            $EventFeeData = array(
              "event_fee_id" => '', 
              "event_id" => $eve_id, // Foreign key reference to event
              "service_id" =>  $event['Service'],
              "reference_no" => "REFBL-".$cleanDate.$eve_id, // Unique reference number
              "payment_type" =>'0',
              "amount_total" =>'', // Total amount for the event
              "payment" => '', // Initial payment
              "balance" => '', // Remaining balance
              "due_date" => '', // Payment due date 1 week before event
              "status" => '1', // 1 = Pending, 2 = Partially Paid, 3 = Paid
              "created_at" => $dty, // Timestamp of creation
              "updated_at" => '', // Timestamp of last update
              "created_by" =>'1', // User who created the record
              "updated_by" => '', // User who last updated the record
              "remark" => '1' // 1 = Show, 0 = Hide
          );
          $insertFeeTemplate= $this->db->insert('lbrmss_event_fee',$EventFeeData);

          $priestAssigned = array(
            "sched_id" => '',
            "priest_id"=> (isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
            ? $event['Assigned_Priest']['priest_id'] 
            : null),
            "sched_event_id"=>$eve_id,
            "date_from" =>$event['Date'],
            "date_to" =>$event['Date'],
            "sched_status"   =>'0',
            "time_from" =>$event['TimeFrom'],
            "time_to"=>$event['TimeTo'],
            "created_at" =>$dty,
            "remark"=>'1'
          );
        $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$priestAssigned);



            echo json_encode(array("Status"=>"Success"));
          }else{
            echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
          }
    }
 
     
      public function httpPut($payload)
      {
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);

        $event = $arr['blessingList'];
        $blessing = $arr['blessingList'];
        $event_id = $event[0]['all']['maineventid'];
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        // echo var_dump($event);
        // echo var_dump($event_id);
        $blessing_id = $blessing[0]['blessing']['blessing_id'];
        $Event = Array(
          
           "service_id"          => $event[0]['all']['mainid'],
           "client"              => $event[0]['all']['cid'],
           "date"                => $event[0]['Date'],
           "date_to"             => $event[0]['Date'],
           "time_from"           => $event[0]['Time_to'],
           "time_to"             => $event[0]['Time_from'],
           "venue_name"          => 'Outside',
           "duration"            => $event[0]['duration'] ?? '', // If not present
           "type"                => 2,
           "days"                => $event[0]['all']['days'],
           "venue_type"          => 3,
           "priest_assigned_id"  => $event[0]['priest_assigned_id'],
           "event_progress"      => 1,
           "requirement_status"  => 1 ,
           "created_at"          => $dty,
           "created_by"          => 1,
           "remark"              => '1'
         );

         $BlessingEvent = array(
          "event_id"            => $blessing[0]['blessing']['event_id'],
          "requester_name"      => $event[0]['client'] ?? '',
          "contact_number"      => $blessing[0]['blessing']['contact_number'] ?? '',
          "notes"               => $blessing[0]['blessing']['notes'] ?? '',
          "preferred_date"      => $event[0]['Date'] ?? '',
          "preferred_time_from" => $event[0]['Time_to'],
          "preferred_time_to"   => $event[0]['Time_from'] ?? '',
          "location"            => $blessing[0]['blessing']['location'],
          "has_guests"          => $blessing[0]['blessing']['has_guests'] ?? 0,
          "guest_count"         => $blessing[0]['blessing']['guest_count'] ?? 0,
          "needs_certificate"   => $blessing[0]['blessing']['needs_certificate'] ?? 0,
          "donation_amount"     => $blessing[0]['blessing']['donation_amount'] ?? 0.00,
          "created_at"          => $dty,
          "priest_id"           =>$event[0]['priest_assigned_id'],
          "remark"              => '1'
        );
          $this->db->where("event_id",$event_id);
          $updateEvent = $this->db->update("lbrmss_event_table_main",$Event);

    
          if($updateEvent ){
            $this->db->where("blessing_id",$blessing_id);
            $updateBlessing = $this->db->update("blessing_requests",$BlessingEvent);
            if($updateBlessing ){
              echo json_encode(array("Status"=>"Success","message"=>"Insert both Successful"));
            }else{
              echo json_encode(array("Status"=>"Failed","message"=>"Error inserting event"));
            }

          }else{
            echo json_encode(array("Status"=>"Failed","message"=>"Error inserting event"));
          }

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