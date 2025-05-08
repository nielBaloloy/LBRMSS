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
      //   $apiParameter = (isset($arr['type']['status']) ? $arr['type']['status'] : "0" );
        
      //   $filter  = (isset($arr['type']) ? $arr['type'] :"");
       
      //   $filterRange = "";
      //   if (!empty($filter['dateFrom']) && !empty($filter['dateTo'])) {
      //     $dateFrom = $filter['dateFrom'];
      //     $dateTo = $filter['dateTo'];
      //     $filterRange = " AND (DATE(a.created_at) BETWEEN '$dateFrom' AND '$dateTo')";
      // } else {
      //     $filterRange = "";
      // }



        $Marriage = $this->db->rawQuery("SELECT * FROM lbrmss_event_table_main a 
                                                LEFT JOIN lbrmss_event_services b ON a.service_id = b.etype_id 
                                                LEFT JOIN lbrmss_priest_main c ON c.acc_id = a.priest_assigned_id 
                                                LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
                                                LEFT JOIN lbrmss_mariage_main mm ON mm.event_id =a.event_id
                                                LEFT JOIN lbrmss_m_groom mg ON mg.g_event_id = mm.event_id 
                                                LEFT JOIN lbrmss_m_bride mb ON mb.b_event_id = mm.event_id
                                                LEFT JOIN witness_testium_tbl mw ON mw.ServiceID = mm.event_id
                                                LEFT JOIN lbrmss_event_fee ef ON ef.event_id = mm.event_id
                                                WHERE a.remark = '1' AND a.service_id = 1   AND a.event_progress = '2' GROUP BY a.event_id
                                              
                                                ORDER BY a.created_at DESC");

      if(count($Marriage) > 0){

        foreach ($Marriage as $event) {
          $groom  = $event['groom_lname']." ".$event['groom_mname']." ".$event['groom_fname'];
          $bride  = $event['groom_lname']." ".$event['bride_mname']." ".$event['bride_fname'];
          $pendingEvents[] = [
            "all" => $event,
            "E_ID" => $event['event_id'],
            "EventServiceID" => $event['service_id'],
            "Service" => $event['event_name'],
            "Client" => $groom." and ".$bride,
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
        // segregate data(Event data and Marriage Data)
        $Event =$arr['eventData'];//Event Data
        $MarriageData =$arr['MarriageData']; // Marriage data

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

            if (isset($MarriageData['EventProgress'])) {
              $status = strtolower($MarriageData['EventProgress']); // Convert to lowercase for consistency
          
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
              "time_from"           => $Event['TimeFrom'],
              "time_to"             => $Event['TimeTo'],
              "venue_name"          => $Event['Venue'],
              "duration"            => $Event['Duration'],
              "type"                => $type,
              "days"                => $Event['Days'],
              "venue_type"          => $Event['Venue_type'],
              "priest_assigned_id"  => (isset($Event['Assigned_Priest']) && isset($Event['Assigned_Priest']['priest_id']) 
              ? $Event['Assigned_Priest']['priest_id'] 
              : null),
              "event_progress"      => $eventProgress,
              "requirement_status"  => ($MarriageData['Status'] == "Complete") ? '1' : '0',
              "created_at"          => $dty,
              "created_by"          => '1',
              "remark"              => '1'

            );

             $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);

                  if($insert_EventInfo){

                    $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
                    $new_eventId= $eventId;
                    //insert marriage data
                        // =========== groom data =================
                    $marriageGroomData = array(
                      "g_id" => '', // Assuming this is auto-incremented
                      "g_event_id" => $new_eventId, // You may need to assign the event ID separately
                      "groom_lname" => $MarriageData['Groom_Last_Name'] ?? '',
                      "groom_mname" => !empty($MarriageData['Groom_Middle_Name']) ? $MarriageData['Groom_Middle_Name'] : '',
                      "groom_fname" => $MarriageData['Groom_First_Name'] ?? '',
                      "groom_suffix" => !empty($MarriageData['Groom_Suffix']) ? $MarriageData['Groom_Suffix'] : '',
                      "age" => $MarriageData['Grooms_Age'] ?? '',
                      "dob" => !empty($MarriageData['Groom_BirthDate']) ? date('Y-m-d', strtotime($MarriageData['Groom_BirthDate'])) : '',
                      "g_region" => $MarriageData['Groom_Region'] ?? '',
                      "g_province" => $MarriageData['Groom_Province'] ?? '',
                      "g_city" => $MarriageData['Groom_City'] ?? '',
                      "g_brgy" => $MarriageData['Groom_Barangay'] ?? '',
                      "g_civil_status" => $MarriageData['Groom_Status'] ?? '',
                      "g_father" => $MarriageData['Groom_Father'] ?? '',
                      "g_mother" => $MarriageData['Groom_Mother'] ?? '',
                      "created_at" => date('Y-m-d H:i:s'), // Current timestamp
                      "created_by" => '1', // accountid
                      "updated_at" => '',
                      "updated_by" => '',
                      "remark" => '1' // Assuming default value is '1' (show)
                  );
                        // =========== bride data =================
                  $marriageBrideData = array(
                    "b_id" => '', // Assuming auto-increment
                    "b_event_id" => $new_eventId, // You may need to assign this separately
                    "bride_lname" => $MarriageData['Bride_Last_Name'] ?? '',
                    "bride_mname" => !empty($MarriageData['Bride_Middle_Name']) ? $MarriageData['Bride_Middle_Name'] : '',
                    "bride_fname" => $MarriageData['Bride_First_Name'] ?? '',
                    "age" => $MarriageData['Bride_Age'] ?? '',
                    "dob" => !empty($MarriageData['Bride_BirthDate']) ? date('Y-m-d', strtotime($MarriageData['Bride_BirthDate'])) : '',
                    "b_region" => $MarriageData['Bride_Region'] ?? '',
                    "b_province" => $MarriageData['Bride_Province'] ?? '',
                    "b_city" => $MarriageData['Bride_City'] ?? '',
                    "b_brgy" => $MarriageData['Bride_Barangay'] ?? '',
                    "b_civil_status" => $MarriageData['Bride_Status'] ?? '',
                    "b_father" => $MarriageData['Bride_Father'] ?? '',
                    "b_mother" => $MarriageData['Bride_Mother'] ?? '',
                    "created_at" => date('Y-m-d H:i:s'), // Current timestamp
                    "created_by" => '1', // Assign this separately
                    "updated_at" => '',
                    "updated_by" => '',
                    "remark" => '1' // Assuming default value is '1' (show)
                );
                  // =========== marraige main =================
                  $marriageAssignment = array(
                    "mid" => '',
                    "event_id" =>   $new_eventId,
                    "assigned_priest" => (isset($Event['Assigned_Priest']) && isset($Event['Assigned_Priest']['priest_id']) 
                    ? $Event['Assigned_Priest']['priest_id'] 
                    : null),
                    "remark" => '1'
                );
                
                    // =========== witness data =================
                  $witnessLen = count($MarriageData["Witness"]);
                  $multiInsertDataW = [];

                  for ($x = 0; $x < $witnessLen; $x++) {
                      $multiInsertDataW[] = array(
                          "w_id" => '',
                          "ServiceID" => $new_eventId,
                          "Ninong" => $MarriageData['Witness'][$x]['Groom_Testium'],
                          "Ninong_Address" => $MarriageData['Witness'][$x]['G_Address'],
                          "Ninang" => $MarriageData['Witness'][$x]['Bride_Testium'],
                          "Ninang_Address" => $MarriageData['Witness'][$x]['B_Address'],
                      );
                  }
                  $tableNames = 'witness_testium_tbl';
                  

                  //================== Seminar data =================
                  $SeminarLength = count($MarriageData["SeminarDetails"]);
                    $multiInsertData = [];

                    for ($s = 0; $s < $SeminarLength; $s++) {
                        $multiInsertData[] = array(
                            "seminar_id" => null, // Auto-increment
                            "event_id" =>  $new_eventId,
                            "seminar_title" => $MarriageData['SeminarDetails'][$s]['SeminarTitle'],
                            "date_from" => $MarriageData['SeminarDetails'][$s]['Date'], 
                            "date_to" => $MarriageData['SeminarDetails'][$s]['Date'], // Adjust if necessary
                            "time_from" => $MarriageData['SeminarDetails'][$s]['timeStart'],
                            "time_to" => $MarriageData['SeminarDetails'][$s]['timeEnd'],
                            "status" => $MarriageData['Status'],
                            "duration" => $MarriageData['SeminarDetails'][$s]['duration'],
                            "days" => $MarriageData['SeminarDetails'][$s]['days'],
                            "seminar_Venue" => $MarriageData['SeminarDetails'][$s]['SeminarVenue'], 
                            "remark" => 1 // Default to show (1) as per image comment
                        );
                    }

                    // Perform bulk insert using insertMulti
                    $tableName = 'lbrmss_seminar'; // Replace with actual table name
                  
                    //========= requirements =================================
                    
                    $marriageRequirementsData = array(
                      "req_id" => null, // Auto-increment
                      "event_id" =>$new_eventId,
                      "baptismal_certificate" => $MarriageData['Requirement']['Baptismal'] ?? 'no',
                      "marriage_license" => $MarriageData['Requirement']['Marriage_License'] ?? 'no',
                      "confirmation" => $MarriageData['Requirement']['Confirmation'] ?? 'no',
                      "birth_certificate" => $MarriageData['Requirement']['LiveBirthCert'] ?? 'no',
                      "cenomar" => $MarriageData['Requirement']['Cenomar'] ?? 'no',
                      "interrogation" => $MarriageData['Requirement']['Interogation'] ?? 'no',
                      "precana_seminar" => $MarriageData['Requirement']['PreCana'] ?? 'no',
                      "confession" => $MarriageData['Requirement']['Confession'] ?? 'no',
                      "remark" => 1 // Default to show (1) based on previous schema
                  );
                  
                  // Insert the data into the database
                  $dateofEvent = $Event['Date'];
                  $oneWeekBefore = date("Y-m-d", strtotime($dateofEvent . " -1 week"));
                    $EventFeeData = array(
                      "event_fee_id" => '', 
                      "event_id" => $new_eventId, // Foreign key reference to event
                      "service_id" =>  $Event['Service'],
                      "reference_no" => "REFM-".$cleanDate.$new_eventId, // Unique reference number
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
                    "priest_id"=>(isset($Event['Assigned_Priest']) && isset($Event['Assigned_Priest']['priest_id']) 
                    ? $Event['Assigned_Priest']['priest_id'] 
                    : null),
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
                  $insert_Groom_info = $this->db->insert('lbrmss_m_groom', $marriageGroomData);
                  $insert_Bride_info = $this->db->insert('lbrmss_m_bride', $marriageBrideData);
                  $insert_assignment_info = $this->db->insert('lbrmss_mariage_main', $marriageAssignment);
                  $insertedWitness = $this->db->insertMulti($tableNames, $multiInsertDataW);
                  $insertedIds = $this->db->insertMulti($tableName, $multiInsertData);
                  $insertRequirement = $this->db->insert('lbrmss_m_requirements', $marriageRequirementsData);
                  
                      if($insertPriestSchedule && $insertFeeTemplate && $insert_Groom_info && $insert_Bride_info && $insert_assignment_info && $insertedWitness && $insertedIds && $insertRequirement){
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
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);

        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $requirementStatus = $arr['payload']['statusReq'];
      
        $event = $arr['payload']['event']['all'];
        $events = $arr['payload']['event'];
        $groom = $arr['payload']['groom'];
        $bride = $arr['payload']['bride'];
        $priest = $arr['payload']['event']['Assigned_Priest']['priest_id'] ?? null;
     
        $witness = $arr['payload']['event']['witness'];
        $requirements = $arr['payload']['requirements'];
        $seminar = $arr['payload']['seminar'];
    
        $event_id =$event['event_id'];
    
        $UpdateEvent = Array(
         "event_id"            => $event['event_id'],
          "service_id"          => $event['service_id'],
          "client"              => $event['client'],
          "date"                => $event['date'],
          "date_to"             => $event['date_to'],
          "time_from"           => $events['Time_from'],
          "time_to"             => $events['Time_to'],
          "venue_name"          => $events['Venue'],
          "duration"            => $event['duration'] ?? '', // If not present
          "type"                => $event['type'],
          "days"                => $event['days'],
          "venue_type"          => ($events['Venue'] === 'St Raphael Church') ? '1' :
          (($events['Venue'] === 'Pastoral Center') ? '2' : '3'),
          "priest_assigned_id"  =>  $event['priest_assigned_id']['priest_id'] ?? $priest,
         
          "event_progress"      => $event['event_progress'],
          "requirement_status"  => ($requirementStatus == "complete") ? 1 : 0 ,
          "created_at"          => $event['created_at'],
          "created_by"          => $event['created_by'],
          "remark"              => $event['remark']
        );
        $this->db->where('event_id',$event_id);
        $updateEvents = $this->db->update('lbrmss_event_table_main',$UpdateEvent);

        $update_main =Array(
          
          "event_id" =>   $event['event_id'],
          "assigned_priest" => $event['priest_assigned_id']['priest_id'] ?? $priest,
          "remark" => '1'
        );
        $this->db->where('event_id',$event_id);
        $updateConMain = $this->db->update('lbrmss_mariage_main',$update_main);

        $updateGroom = array(
        
          "g_event_id"     => $groom['g_event_id'],
          "groom_lname"    => $groom['groom_lname'],
          "groom_mname"    => $groom['groom_mname'],
          "groom_fname"    => $groom['groom_fname'],
          "groom_suffix"   => $groom['groom_suffix'], // could be null
          "age"            => $groom['age'],
          "dob"            => $groom['dob'],
          "g_region"       => $groom['g_region'],
          "g_province"     => $groom['g_province'],
          "g_city"         => $groom['g_city'],
          "g_brgy"         => $groom['g_brgy'],
          "g_civil_status" => $groom['g_civil_status'],
          "g_father"       => $groom['g_father'],
          "g_mother"       => $groom['g_mother'],
          "created_at"     => $groom['created_at'],
          "created_by"     => $groom['created_by'],
          "updated_at"     => $groom['updated_at'],
          "updated_by"     => $groom['updated_by'],
          "remark"         => $groom['remark']
      );
      
        $this->db->where('g_event_id',$event_id);
        $grooms = $this->db->update('lbrmss_m_groom',$updateGroom);
        $updateBride = array(
       
          "b_event_id"     => $bride['b_event_id'],
          "bride_lname"    => $bride['bride_lname'],
          "bride_mname"    => $bride['bride_mname'],
          "bride_fname"    => $bride['bride_fname'],
          
          "age"            => $bride['age'],
          "dob"            => $bride['dob'],
          "b_region"       => $bride['b_region'],
          "b_province"     => $bride['b_province'],
          "b_city"         => $bride['b_city'],
          "b_brgy"         => $bride['b_brgy'],
          "b_civil_status" => $bride['b_civil_status'],
          "b_father"       => $bride['b_father'],
          "b_mother"       => $bride['b_mother'],
          "created_at"     => $bride['created_at'],
          "created_by"     => $bride['created_by'],
          "updated_at"     => $bride['updated_at'],
          "updated_by"     => $bride['updated_by'],
          "remark"         => $bride['remark']
      );
      
      
        $this->db->where('b_event_id',$event_id);
        $brides = $this->db->update('lbrmss_m_bride',$updateBride);

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
                
        // echo var_dump($RequirementsData);
        $this->db->where('req_id',$req_id);
        $insertRequirement = $this->db->update('lbrmss_m_requirements', $RequirementsData);
        
        echo json_encode(array("msg"=>""));
  

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