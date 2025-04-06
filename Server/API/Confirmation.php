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
         
        // ===============Special TYpe Baptism====================//
        $Event =$arr['eventData'];//Event Data
        $ConfirmationData =$arr['ConfirmationData']; // Baptism data
        
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
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

          if (isset($ConfirmationData['EventProgress'])) {
            $status = strtolower($ConfirmationData['EventProgress']); // Convert to lowercase for consistency
        
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
          "requirement_status"  => $ConfirmationData['Status'],
          "created_at"          => $dty,
          "created_by"          => '1',
          "remark"              => '1'

        );
          
        $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);
        if($insert_EventInfo){

          $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
          $new_eventId= $eventId;

          $ConfirmationAssignment = array(
            "con_id" => '',
            "event_id" =>   $new_eventId,
            "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
            "remark" => '1'
        );
        // $getConf_id = $this->db->getMaxId('lbrmss_confirmation_main','con_id')+1;


          $ConfirmationDatas = array(
            "con_person_id" => '',
            "con_event_id" => $new_eventId,
            "con_lname" => $ConfirmationData['Last_Name'],
            "con_mname" => $ConfirmationData['Middle_Name'],
            "con_fname" => $ConfirmationData['First_Name'],
            "con_suffix" => $ConfirmationData['Suffix'],
            "con_gender" => $ConfirmationData['Gender'],
            "con_birthplace" => $ConfirmationData['Birth_Place'],
            "con_birth_date" => $ConfirmationData['Birth_Date'],
            "con_region" => $ConfirmationData['Region'],
            "con_province" => $ConfirmationData['Province'],
            "con_city" => $ConfirmationData['City'],
            "con_barangay" => $ConfirmationData['Barangay'],
            "con_mother" => $ConfirmationData['Mother_Name'],
            "con_father" => $ConfirmationData['Father_Name'],
            "con_legitimacy" => $ConfirmationData['Legitamacy'],
            "Nationality" => $ConfirmationData['Nationality'],
            "created_at" => date('Y-m-d H:i:s'),
            "created_by" => $loggedInUserId ?? '1',
            "updated_at" => $ConfirmationData['updated_at'] ?? '',
            "updated_by" => $ConfirmationData['updated_by'] ?? '',
            "remark" => '1'
        );

        $witnessLen = count($ConfirmationData["Witness"]);
        $multiInsertDataW = [];

        for ($x = 0; $x < $witnessLen; $x++) {
            $multiInsertDataW[] = array(
                "w_id" => '',
                "ServiceID" => $new_eventId,
                "Ninong" => $ConfirmationData['Witness'][$x]['Ninong'],
                "Ninong_Address" => $ConfirmationData['Witness'][$x]['Ninong_Address'],
                "Ninang" => $ConfirmationData['Witness'][$x]['Ninang_Testium'],
                "Ninang_Address" => $ConfirmationData['Witness'][$x]['Ninang_Address'],
            );
        }
        $tableNames = 'witness_testium_tbl';

         /** lbrmss_seminar */
         $SeminarLength = count($ConfirmationData["SeminarDetails"]);
         $multiInsertData = [];

         for ($s = 0; $s < $SeminarLength; $s++) {
             $multiInsertData[] = array(
                 "seminar_id" => '', // Auto-increment
                 "event_id" =>  $new_eventId,
                 "seminar_title" => $ConfirmationData['SeminarDetails'][$s]['SeminarTitle'],
                 "date_from" => $ConfirmationData['SeminarDetails'][$s]['Date'], 
                 "date_to" => $ConfirmationData['SeminarDetails'][$s]['Date'], // Adjust if necessary
                 "time_from" => $ConfirmationData['SeminarDetails'][$s]['timeStart'],
                 "time_to" => $ConfirmationData['SeminarDetails'][$s]['timeEnd'],
                 "status" => $ConfirmationData['Status'],
                 "duration" => $ConfirmationData['SeminarDetails'][$s]['duration'],
                 "days" => $ConfirmationData['SeminarDetails'][$s]['days'],
                 "seminar_Venue" => $ConfirmationData['SeminarDetails'][$s]['SeminarVenue'], 
                 "remark" => 1 // Default to show (1) as per image comment
             );
         }

         // Perform bulk insert using insertMulti
         $tableName = 'lbrmss_seminar'; // Replace with actual table name
         
         /** requirements */
         $RequirementsData = array(
          "req_id" => null, // Auto-increment
          "event_id" =>$new_eventId,
          "baptismal_certificate" => $ConfirmationData['Requirement']['Baptismal'] ?? 'no',
          "marriage_license" => $ConfirmationData['Requirement']['Marriage_License'] ?? 'no',
          "confirmation" => $ConfirmationData['Requirement']['Confirmation'] ?? 'no',
          "birth_certificate" => $ConfirmationData['Requirement']['LiveBirthCert'] ?? 'no',
          "cenomar" => $ConfirmationData['Requirement']['Cenomar'] ?? 'no',
          "interrogation" => $ConfirmationData['Requirement']['Interogation'] ?? 'no',
          "precana_seminar" => $ConfirmationData['Requirement']['PreCana'] ?? 'no',
          "confession" => $ConfirmationData['Requirement']['Confession'] ?? 'no',
          "remark" => 1 // Default to show (1) based on previous schema
      );

         // Fee per service
         $dateofEvent = $Event['Date'];
         $oneWeekBefore = date("Y-m-d", strtotime($dateofEvent . " -1 week"));
             $EventFeeData = array(
             "event_fee_id" => '', 
             "event_id" => $new_eventId, // Foreign key reference to event
             "service_id" =>  $Event['Service'],
             "reference_no" => "REFC-".$cleanDate.$new_eventId, // Unique reference number
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

         $priestAssigned = array(
          "sched_id" => '',
          "priest_id"=>$Event['Assigned_Priest']['priest_id'],
          "sched_event_id"=>$new_eventId,
          "date_from" =>$Event['Date'],
          "date_to" =>$Event['Date'],
          "sched_status"   =>'0',
          "time_from" =>$Event['TimeFrom'],
          "time_to"=>$Event['TimeTo'],
          "created_at" =>$dty,
          "remark"=>'1'
        );
        $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$priestAssigned);
        $insertFeeTemplate= $this->db->insert('lbrmss_event_fee',$EventFeeData);

          $insert_confirmation_data = $this->db->insert('lbrmss_confirmation_main', $ConfirmationAssignment);
          $insert_assignment_info = $this->db->insert('lbrmss_con_person', $ConfirmationDatas);
          $insertedWitness = $this->db->insertMulti($tableNames, $multiInsertDataW);
          $insertedIds = $this->db->insertMulti($tableName, $multiInsertData);
          $insertRequirement = $this->db->insert('lbrmss_m_requirements', $RequirementsData);
        
        if($insertPriestSchedule && $insert_confirmation_data && $insert_assignment_info && $insertedWitness && $insertedIds && $insertRequirement && $insertFeeTemplate){
            echo json_encode(array("Status" => "Success", "Message" => "Application Successfully Added"));
          } else{
            echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
          }


        }else{
          echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
        }
        } else{
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