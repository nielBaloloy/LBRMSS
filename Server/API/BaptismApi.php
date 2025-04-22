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

        $baptism = $this->db->rawQuery("SELECT * FROM lbrmss_event_table_main a 
        LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
        LEFT JOIN lbrmss_priest_main c ON c.priest_id = a.priest_assigned_id 
        LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
        LEFT JOIN lbrmss_baptism_main mm ON mm.event_id =a.event_id
        LEFT JOIN lbrmss_bapt_person mg ON mg.bapt_event_id = mm.event_id 
        LEFT JOIN witness_testium_tbl mw ON mw.ServiceID = mm.event_id
        LEFT JOIN lbrmss_event_fee ef ON ef.event_id = mm.event_id
        WHERE a.remark = '1' AND a.service_id = 2 AND a.event_progress = '2' GROUP BY a.event_id
        ORDER BY a.created_at");

          if(count($baptism) > 0){

          foreach ($baptism as $event) {
          $person = $event['bapt_lname']." ".$event['bapt_mname']." ".$event['bapt_fname'];
         
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
        $BaptismData =$arr['BaptismData']; // Baptism data

            /**get current date and time*/
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $dtyOne = $dt->format('Y-m-d');
        $cleanDate = str_replace("-", "", $dtyOne);
            // event array
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
    
                if (isset($BaptismData['EventProgress'])) {
                  $status = strtolower($BaptismData['EventProgress']); // Convert to lowercase for consistency
              
                  if ($status === "scheduled") {
                      $eventProgress = 1;
                  } elseif ($status === "pending") {
                      $eventProgress = 0;
                  }elseif ($status === "done") {
                    $eventProgress = 2;
                }
              }
              $type = (isset($Event['Type']) && strtolower($Event['Type']) === "special") ? 2 : 1;
              //to insert eventData
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
                    "requirement_status"  => $BaptismData['Status'],
                    "created_at"          => $dty,
                    "created_by"          => '1',
                    "remark"              => '1'

                );
                $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);

                if($insert_EventInfo){
                    $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
                    $new_eventId= $eventId;
                    
                    /** insert into lbrmss_baptism_main */
                    $BaptismAssignment = array(
                        "bapt_id" => '',
                        "event_id" =>   $new_eventId,
                        "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
                        "remark" => '1'
                    );
                    $getBapt_id = $this->db->getMaxId('lbrmss_baptism_main','bapt_id')+1;

                    /** insert into lbrmss_bapt_person */
                    $baptismData = array(
                        "bapt_person_id" => '', // Assuming this is auto-incremented
                        "bapt_event_id" => $new_eventId, // Link to event ID
                        "bapt_lname" => $BaptismData['Last_Name'] ?? '',
                        "bapt_mname" => $BaptismData['Middle_Name'] ?? '',
                        "bapt_fname" => $BaptismData['First_Name'] ?? '',
                        "bapt_suffix" => $BaptismData['Suffix'] ?? '',
                        "bapt_age" => $BaptismData['Age'] ?? '',
                        "bapt_dob" => isset($BaptismData['Birth_Date']) && strtotime($BaptismData['Birth_Date']) 
                                        ? date('Y-m-d', strtotime($BaptismData['Birth_Date'])) 
                                        : '',
                        "bapt_birthplace" => $BaptismData['Birth_Place'] ?? '',
                        "bapt_gender" => $BaptismData['Gender'] ?? '',
                        "bapt_father" => $BaptismData['Father_Name'] ?? '',
                        "bapt_mother" => $BaptismData['Mother_Name'] ?? '',
                        "bapt_legitimacy" => $BaptismData['Legitimacy'] ?? '', // 1 = illegal, 2 = legal
                        "bapt_region" => $BaptismData['Region'] ?? '',
                        "bapt_province" => $BaptismData['Province'] ?? '',
                        "bapt_City" => $BaptismData['City'] ?? '',
                        "bapt_Barangay" => $BaptismData['Barangay'] ?? '',
                        "created_at" => date('Y-m-d H:i:s'),
                        "created_by" => $loggedInUserId ?? '1', // Dynamically assign user ID
                        "updated_by" => '',
                        "remark" => '1' // 1 = show, 0 = hide
                    );
               
                        /** Insert into lbrmss_witness */
                        $witnessLen = count($BaptismData["BaptismWitness"]);
                        $multiInsertDataW = [];
      
                        for ($x = 0; $x < $witnessLen; $x++) {
                            $multiInsertDataW[] = array(
                                "w_id" => '',
                                "ServiceID" => $new_eventId,
                                "Ninong" => $BaptismData['BaptismWitness'][$x]['Ninong'],
                                "Ninong_Address" => $BaptismData['BaptismWitness'][$x]['Ninong_Address'],
                                "Ninang" => $BaptismData['BaptismWitness'][$x]['Ninang_Testium'],
                                "Ninang_Address" => $BaptismData['BaptismWitness'][$x]['Ninang_Address'],
                            );
                        }
                        $tableNames = 'witness_testium_tbl';

                        /** lbrmss_seminar */
                        $SeminarLength = count($BaptismData["SeminarDetails"]);
                        $multiInsertData = [];
    
                        for ($s = 0; $s < $SeminarLength; $s++) {
                            $multiInsertData[] = array(
                                "seminar_id" => '', // Auto-increment
                                "event_id" =>  $new_eventId,
                                "seminar_title" => $BaptismData['SeminarDetails'][$s]['SeminarTitle'],
                                "date_from" => $BaptismData['SeminarDetails'][$s]['Date'], 
                                "date_to" => $BaptismData['SeminarDetails'][$s]['Date'], // Adjust if necessary
                                "time_from" => $BaptismData['SeminarDetails'][$s]['timeStart'],
                                "time_to" => $BaptismData['SeminarDetails'][$s]['timeEnd'],
                                "status" => $BaptismData['Status'],
                                "duration" => $BaptismData['SeminarDetails'][$s]['duration'],
                                "days" => $BaptismData['SeminarDetails'][$s]['days'],
                                "seminar_Venue" => $BaptismData['SeminarDetails'][$s]['SeminarVenue'], 
                                "remark" => 1 // Default to show (1) as per image comment
                            );
                        }
    
                        // Perform bulk insert using insertMulti
                        $tableName = 'lbrmss_seminar'; // Replace with actual table name
                        
                        /** requirements */
                        $RequirementsData = array(
                            "req_id" => null, // Auto-increment
                            "event_id" =>$new_eventId,
                            "baptismal_certificate" => $BaptismData['Requirement']['Baptismal'] ?? 'no',
                            "marriage_license" => $BaptismData['Requirement']['Marriage_License'] ?? 'no',
                            "confirmation" => $BaptismData['Requirement']['Confirmation'] ?? 'no',
                            "birth_certificate" => $BaptismData['Requirement']['LiveBirthCert'] ?? 'no',
                            "cenomar" => $BaptismData['Requirement']['Cenomar'] ?? 'no',
                            "interrogation" => $BaptismData['Requirement']['Interogation'] ?? 'no',
                            "precana_seminar" => $BaptismData['Requirement']['PreCana'] ?? 'no',
                            "confession" => $BaptismData['Requirement']['Confession'] ?? 'no',
                            "remark" => 1 // Default to show (1) based on previous schema
                        );
                        
                            // Fee per service
                                $dateofEvent = $Event['Date'];
                                $oneWeekBefore = date("Y-m-d", strtotime($dateofEvent . " -1 week"));
                                    $EventFeeData = array(
                                    "event_fee_id" => '', 
                                    "event_id" => $new_eventId, // Foreign key reference to event
                                    "service_id" =>  $Event['Service'],
                                    "reference_no" => "REFBP-".$cleanDate.$new_eventId, // Unique reference number
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

                        $insert_baptism_data = $this->db->insert('lbrmss_baptism_main', $BaptismAssignment);
                        $insert_assignment_info = $this->db->insert('lbrmss_bapt_person', $baptismData);
                        $insertedWitness = $this->db->insertMulti($tableNames, $multiInsertDataW);
                        $insertedIds = $this->db->insertMulti($tableName, $multiInsertData);
                        $insertRequirement = $this->db->insert('lbrmss_m_requirements', $RequirementsData);
                       
                        if($insertPriestSchedule && $insert_baptism_data && $insert_assignment_info && $insertedWitness && $insertedIds && $insertRequirement && $insertFeeTemplate){
                            echo json_encode(array("Status" => "Success", "Message" => "Application Successfully Added"));
                          } else{
                            echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
                          }
                }else{
                    echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
                  }
    

            }
            else{
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
        $personalDetails   = $arr['payload']['event']['baptismperson'];
        $witness = $arr['payload']['event']['witness'];
        $requirements = $arr['payload']['requirements'];
        $seminar = $arr['payload']['seminar'];
        $requirementStatus = $arr['payload']['statusReq'];
        $event_id =$event['event_id'];
        
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
          "requirement_status"  => ($requirementStatus == "complete") ? 1 : 0 ,
          "created_at"          => $event['created_at'],
          "created_by"          => $event['created_by'],
          "remark"              => $event['remark']
        );
        $this->db->where('event_id',$event_id);
        $this->db->where('event_id',$event_id);
        $updateEvents = $this->db->update('lbrmss_event_table_main',$UpdateEvent);

        $updateCon_main =Array(
        
          "event_id" =>   $event['event_id'],
          "assigned_priest" => $event['priest_id'],
          "remark" => '1'
        );
        $this->db->where('event_id',$event_id);
        $updateConMain = $this->db->update('lbrmss_baptism_main',$updateCon_main);
        $updatePerson  =Array(
            "bapt_person_id"     => $personalDetails['bapt_person_id'],
            "bapt_event_id"     => $personalDetails['bapt_event_id'],
            "bapt_lname"        => $personalDetails['bapt_lname'],
            "bapt_mname"        => $personalDetails['bapt_mname'],
            "bapt_fname"        => $personalDetails['bapt_fname'],
            "bapt_suffix"       => $personalDetails['bapt_suffix'], // could be null
            "bapt_gender"       => $personalDetails['bapt_gender'],
            "bapt_birthplace"   => $personalDetails['bapt_birthplace'],
            "bapt_dob"          => $personalDetails['bapt_dob'],
            "bapt_age"          => $personalDetails['bapt_age'],
            "bapt_region"       => $personalDetails['bapt_region'],
            "bapt_province"     => $personalDetails['bapt_province'],
            "bapt_City"         => $personalDetails['bapt_City'],
            "bapt_Barangay"     => $personalDetails['bapt_Barangay'],
            "bapt_mother"       => $personalDetails['bapt_mother'],
            "bapt_father"       => $personalDetails['bapt_father'],
            "bapt_legitimacy"   => $personalDetails['bapt_legitimacy'],
            "created_at"        => $personalDetails['created_at'],
            "created_by"        => $personalDetails['created_by'],
            "updated_by"        => $personalDetails['updated_by'],
            "remark"            => $personalDetails['remark']
        );
        $this->db->where('bapt_event_id',$event_id);
        $updateConperson = $this->db->update('lbrmss_bapt_person',$updatePerson);
        

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