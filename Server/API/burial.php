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
         
        $Event =$arr['eventData'];//Event Data
        $BurialData =$arr['BurialData']; // Baptism data
        
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
          /**get current date and time*/
  
        
          $dtyOne = $dt->format('Y-m-d');
          $cleanDate = str_replace("-", "", $dtyOne);
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

          if (isset($BurialData['EventProgress'])) {
            $status = strtolower($BurialData['EventProgress']); // Convert to lowercase for consistency
        
            if ($status === "scheduled") {
                $eventProgress = 1;
            } elseif ($status === "pending") {
                $eventProgress = 0;
            }elseif ($status === "done") {
              $eventProgress = 2;
          }
        }

        $type = (isset($Event['Type']) && strtolower($Event['Type']) === "special") ? 2 : 1;

        $eventData = Array(
          "event_id" => '',
          "service_id" => $Event['Service'],
          "client" => $new_cid,
          "date" =>  $Event['Date'],
          "date_to" =>  $Event['Date'],
          "time_from"           => $Event['TimeTo'],
          "time_to"             => $Event['TimeFrom'],
          "venue_name"          => $Event['Venue'],
          "duration"            => $Event['Duration'],
          "type"                => $type,
          "days"                => $Event['Days'],
          "venue_type"          => $Event['Venue_type'],
          "priest_assigned_id"  => $Event['Assigned_Priest']['priest_id'],
          "event_progress"      => $eventProgress,
          "requirement_status"  => $BurialData['Status'],
          "created_at"          => $dty,
          "created_by"          => '1',
          "remark"              => '1'

        );
        $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);

        if($insert_EventInfo){
          $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
          $new_eventId= $eventId;

          $BurialAssignment = array(
            "burial_id" => '',
            "event_id" =>   $new_eventId,
            "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
            "remark" => '1'
        );

        $getburial_id = $this->db->getMaxId('lbrmss_burial','burial_id')+1;

        $burialRecord = array(
          'bpid' => null, // Auto-increment ID (not provided in image)
          'event_id' => $new_eventId, 
          'bu_lname' => $BurialData['Last_Name'],
          'bu_mname' => $BurialData['Middle_Name'],
          'bu_fname' => $BurialData['First_Name'],
          'bu_suffix' => $BurialData['Suffix'], // Now mapped
          'bu_gender' => $BurialData['Gender'], // Assuming 1 = Male, 2 = Female
          'bu_age' => $BurialData['Age'],
          'bu_birthdate' => $BurialData['Birth_Date'],
          'bu_birthplace' => $BurialData['Birth_Place'],
          'bu_status' => $BurialData['Status'], // Now mapped
          'bu_father' => $BurialData['Father_Name'],
          'bu_mother' => $BurialData['Mother_Name'],
          'bu_spouse' => $BurialData['Spouse'], // Now mapped
          'bu_nationality' => $BurialData['Nationality'],
          'bu_reg' => $BurialData['Region'],
          'bu_prov' => $BurialData['Province'],
          'bu_city' => $BurialData['City'],
          'bu_brgy' => $BurialData['Barangay'],
          'date_of_death' => $BurialData['Date_of_Death'],
          'cause_of_death' => $BurialData['Cause_of_Death'],
          'burial_option' =>$BurialData['Burial_Arrangement'],
          'date_of_burial' => $BurialData['Date_of_Burial'],
          'place_of_interment' => $BurialData['Place_of_Interment'],
        );

         // Perform bulk insert using insertMulti
         $tableName = 'lbrmss_seminar'; // Replace with actual table name
         
         /** requirements */
         $RequirementsData = array(
          "req_id" => null, // Auto-increment
          "event_id" => $new_eventId,
          "baptismal_certificate" => $ConfirmationData['Requirements']['Baptismal'] ?? 'no',
          "marriage_license" => $ConfirmationData['Requirements']['Marriage_License'] ?? 'no',
          "confirmation" => $ConfirmationData['Requirements']['Confirmation'] ?? 'no',
          "birth_certificate" => $ConfirmationData['Requirements']['LiveBirthCert'] ?? 'no',
          "cenomar" => $ConfirmationData['Requirements']['Cenomar'] ?? 'no',
          "interrogation" => $ConfirmationData['Requirements']['Interogation'] ?? 'no',
          "precana_seminar" => $ConfirmationData['Requirements']['PreCana'] ?? 'no',
          "confession" => $ConfirmationData['Requirements']['Confession'] ?? 'no',
          "family_consent" => $ConfirmationData['Requirements']['Family_Consent'] ?? 'no',
          "cremation_authorization" => $ConfirmationData['Requirements']['Cremation_Authorization'] ?? 'no',
          "death_certificate" => $ConfirmationData['Requirements']['Death_Certificate'] ?? 'no',
          "remark" => 1 // Default to show (1) based on schema
      );

        // Fee per service
        $dateofEvent = $Event['Date'];
        $oneWeekBefore = date("Y-m-d", strtotime($dateofEvent . " -1 week"));
            $EventFeeData = array(
            "event_fee_id" => '', 
            "event_id" => $new_eventId, // Foreign key reference to event
            "service_id" =>  $Event['Service'],
            "reference_no" => "REFB-".$cleanDate.$new_eventId, // Unique reference number
            "payment_type" =>'0',
            "amount_total" =>'', // Total amount for the event
            "payment" => '', // Initial payment
            "balance" => '', // Remaining balance
            "due_date" => $oneWeekBefore, // Payment due date 1 week before event
            "status" => '1', // 1 = Pending, 2 = Partially Paid, 3 = Paid
            "created_at" => $dty, // Timestamp of creation
            "updated_at" => '', // Timestamp of last update
            "created_by" =>'1', // User who created the record
            "updated_by" => '', // User who last updated the record
            "remark" => '1' // 1 = Show, 0 = Hide
        );

       $insertFeeTemplate= $this->db->insert('lbrmss_event_fee',$EventFeeData);

      $insert_Burial_data = $this->db->insert('lbrmss_burial', $BurialAssignment);
      $insert_assignment_info = $this->db->insert('lbrmss_burial_person', $burialRecord);
      
      $insertRequirement = $this->db->insert('lbrmss_m_requirements', $RequirementsData);
      
      if($insert_Burial_data && $insert_assignment_info  && $insertRequirement &&  $insertFeeTemplate){
        echo json_encode(array("Status" => "Success", "Message" => "Application Successfully Added"));
      } else{
        echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
      }

        }else{
          echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
        }

        }else{
          echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
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