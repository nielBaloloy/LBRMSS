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
        $confirmation = $this->db->rawQuery("SELECT * FROM lbrmss_event_table_main a 
        LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
        LEFT JOIN lbrmss_priest_main c ON c.priest_id = a.priest_assigned_id 
        LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
        LEFT JOIN lbrmss_confirmation_main mm ON mm.event_id =a.event_id
        LEFT JOIN lbrmss_con_person mg ON mg.con_event_id = mm.event_id 
        LEFT JOIN witness_testium_tbl mw ON mw.ServiceID = mm.event_id
        LEFT JOIN lbrmss_event_fee ef ON ef.event_id = mm.event_id
        WHERE a.remark = '1' AND a.service_id = 3 AND a.event_progress = '2' GROUP BY a.event_id
        ORDER BY a.created_at");

          if(count($confirmation) > 0){

          foreach ($confirmation as $event) {
          $person = $event['con_lname']." ".$event['con_mname']." ".$event['con_fname'];
         
          $pendingEvents[] = [
          "all" => $event,
          "E_ID" => $event['event_id'],
          "EventServiceID" => $event['service_id'],
          "Service" => $event['event_name'],
          "Client" => $person,
          "Type" => ($event['type'] == '1') ? "Mass" : "Special",
          "Date" => $event['date'],
          "Venue" => $event['venue_name'], 
          'Assigned_Priest' =>$event['pos_prefix']." " .$event['fname']." ".substr($event['mname'],0,1)." ".$event['lname'],
          "Venue_type" => ($event['venue_type'] == '1') ? "Church" : (($event['venue_type'] == '2') ? "Pastoral Center" : "Outside"),

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
        
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);

        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        
        $event = $arr['payload']['event']['all'];
        $personalDetails   = $arr['payload']['event']['confirmation'];
        $witness = $arr['payload']['event']['witness'];
        $requirements = $arr['payload']['requirements'];
        $requirementStatus = $arr['payload']['statusReq'];
        $seminar = $arr['payload']['seminar'];

        $event_id =$event['event_id'];
        // echo var_dump("event",$Event);
        //update event
        // $UpdateEvent = Array (
         
        // );
        // $type = (isset($Event['Type']) && strtolower($Event['Type']) === "special") ? 2 : 1;

        $UpdateEvent = Array(
         "event_id"            => $event['event_id'],
          "service_id"          => $event['service_id'],
          "client"              => $event['client'],
          "date"                => $event['date'],
          "date_to"             => $event['date_to'],
          "time_from"           => $event['time_from'],
          "time_to"             => $event['time_to'],
          "venue_name"          => $event['venue_name'],
          "duration"            => $event['duration'] ?? '', // If not present
          "type"                => $event['type'],
          "days"                => $event['days'],
          "venue_type"          => $event['venue_type'],
          "priest_assigned_id"  => $event['priest_assigned_id'],
          "event_progress"      => $event['event_progress'],
          "requirement_status"  =>($requirementStatus == "complete") ? 1 : 0 ,
          "created_at"          => $event['created_at'],
          "created_by"          => $event['created_by'],
          "remark"              => $event['remark']
        );
        $this->db->where('event_id',$event_id);
        $updateEvents = $this->db->update('lbrmss_event_table_main',$UpdateEvent);

        $updateCon_main =Array(
          "con_id" => '',
          "event_id" =>   $event['event_id'],
          "assigned_priest" => $event['priest_id'],
          "remark" => '1'
        );
        $this->db->where('event_id',$event_id);
        $updateConMain = $this->db->update('lbrmss_confirmation_main',$updateCon_main);
        $updatePerson  =Array(
        "con_person_id"     => $personalDetails['con_person_id'],
        "con_event_id"      => $personalDetails['con_event_id'],
        "con_lname"         => $personalDetails['con_lname'],
        "con_mname"         => $personalDetails['con_mname'],
        "con_fname"         => $personalDetails['con_fname'],
        "con_suffix"        => $personalDetails['con_suffix'], // could be null
        "con_gender"        => $personalDetails['con_gender'],
        "con_birthplace"    => $personalDetails['con_birthplace'],
        "con_birth_date"    => $personalDetails['con_birth_date'],
        "con_region"        => $personalDetails['con_region'],
        "con_province"      => $personalDetails['con_province'],
        "con_city"          => $personalDetails['con_city'],
        "con_barangay"      => $personalDetails['con_barangay'],
        "con_mother"        => $personalDetails['con_mother'],
        "con_father"        => $personalDetails['con_father'],
        "con_legitimacy"    => $personalDetails['con_legitimacy'],
        "Nationality"       => $personalDetails['Nationality'],
        "created_at"        => $personalDetails['created_at'],
        "created_by"        => $personalDetails['created_by'],
        "updated_at"        => $personalDetails['updated_at'],
        "updated_by"        => $personalDetails['updated_by'],
        "remark"            => $personalDetails['remark']
        );
        $this->db->where('con_event_id',$event_id);
        $updateConperson = $this->db->update('lbrmss_con_person',$updatePerson);
        

            for ($x = 0; $x < count($witness); $x++) {
              $multiUpdateDataW = array(
                  "w_id" => $witness[$x]['w_id'],
                  "ServiceID" => $witness[$x]['ServiceID'],
                  "Ninong" => $witness[$x]['Ninong'],
                  "Ninong_Address" => $witness[$x]['Ninong_Address'],
                  "Ninang" => $witness[$x]['Ninang'],
                  "Ninang_Address" => $witness[$x]['Ninang_Address'],
              );
              $this->db->where('w_id', $witness[$x]['w_id']);
              $updateWitness = $this->db->update('witness_testium_tbl',$multiUpdateDataW);
          }
          

          for ($s = 0; $s < count($seminar); $s++) {
            $multiInsertData = array(
              "seminar_id"        => $seminar[$s]['field0'],
              "event_id"        => $event_id,
                "seminar_title"   => $seminar[$s]['field1'],       // Title
                "date_from"       => $seminar[$s]['field2'],       // Date
                "date_to"         => $seminar[$s]['field2'],       // Assuming same day
                "time_from"       => $seminar[$s]['field3'],       // Start Time
                "time_to"         => $seminar[$s]['field4'],       // End Time
                "status"          => $seminar[$s]['field5'],       // Status (1 = active?)
                "duration"        => $seminar[$s]['field6'],       // Duration in mins?
                "days"            => '1',                            // Can be derived if needed
                "seminar_Venue"   => $seminar[$s]['field7'],       // Venue
                "remark"          => '1'                             // Default
            );

            $this->db->where('seminar_id', $seminar[$s]['field0']);
            $updateSeminar= $this->db->update('lbrmss_seminar',$multiInsertData);
        }
      

          /** requirements */
         
          $RequirementsData = array(
            "req_id"                 => isset($requirements['req_id']) ? $requirements['req_id'] : null,
            "baptismal_certificate"  => (isset($requirements['Baptismal']) && $requirements['Baptismal'] === 'true') ? 'true' : 'no',
              "marriage_license"       => (isset($requirements['Marriage_License']) && $requirements['Marriage_License'] === 'true') ? 'true' : 'no',
              "confirmation"           => (isset($requirements['Confirmation']) && $requirements['Confirmation'] === 'true') ? 'true' : 'no',
              "birth_certificate"      => (isset($requirements['LiveBirthCert']) && $requirements['LiveBirthCert'] === 'true') ? 'true' : 'no',
              "cenomar"                => (isset($requirements['Cenomar']) && $requirements['Cenomar'] === 'true') ? 'true' : 'no',
              "interrogation"          => (isset($requirements['Interogation']) && $requirements['Interogation'] === 'true') ? 'true' : 'no',
              "precana_seminar"        => (isset($requirements['PreCana']) && $requirements['PreCana'] === 'true') ? 'true' : 'no',
              "confession"             => (isset($requirements['Confession']) && $requirements['Confession'] === 'true') ? 'true' : 'no',
              "family_consent"         => (isset($requirements['Family_Consent']) && $requirements['Family_Consent'] === 'true') ? 'true' : 'no',
              "cremation_authorization"=> (isset($requirements['Cremation_Authorization']) && $requirements['Cremation_Authorization'] === 'true') ? 'true' : 'no',
              "death_certificate"      => (isset($requirements['Death_Certificate']) && $requirements['Death_Certificate'] === 'true') ? 'true' : 'no',
              "remark"                 => 1 // Default show
                    
        );

        $req_id = $requirements['req_id'];
                
        echo var_dump($RequirementsData);
        $this->db->where('req_id',$req_id);
        $insertRequirement = $this->db->update('lbrmss_m_requirements', $RequirementsData);
        

     
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