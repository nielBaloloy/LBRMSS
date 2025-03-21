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
                                                LEFT JOIN lbrmss_priest_main c ON c.priest_id = a.priest_assigned_id 
                                                LEFT JOIN lbrmss_position d ON d.pos_id = c.position 
                                                LEFT JOIN lbrmss_mariage_main mm ON mm.event_id =a.event_id
                                                LEFT JOIN lbrmss_m_groom mg ON mg.g_event_id = mm.event_id 
                                                LEFT JOIN lbrmss_m_bride mb ON mb.b_event_id = mm.event_id
                                                LEFT JOIN witness_testium_tbl mw ON mw.ServiceID = mm.event_id
                                                WHERE a.remark = '1' AND a.service_id = 1 GROUP BY a.event_id
                                                ORDER BY a.created_at");

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
              "time_from"           => $Event['TimeTo'],
              "time_to"             => $Event['TimeFrom'],
              "venue_name"          => $Event['Venue'],
              "duration"            => $Event['Duration'],
              "type"                => $type,
              "days"                => $Event['Days'],
              "venue_type"          => $Event['Venue_type'],
              "priest_assigned_id"  => $Event['Assigned_Priest']['priest_id'],
              "event_progress"      => $eventProgress,
              "requirement_status"  => $MarriageData['Status'],
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
                    "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
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
                      "reference_no" => "REF-".$cleanDate.$new_eventId, // Unique reference number
                      "amount_total" =>'', // Total amount for the event
                      "down_payment" => '', // Initial payment
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
                  $insert_Groom_info = $this->db->insert('lbrmss_m_groom', $marriageGroomData);
                  $insert_Bride_info = $this->db->insert('lbrmss_m_bride', $marriageBrideData);
                  $insert_assignment_info = $this->db->insert('lbrmss_mariage_main', $marriageAssignment);
                  $insertedWitness = $this->db->insertMulti($tableNames, $multiInsertDataW);
                  $insertedIds = $this->db->insertMulti($tableName, $multiInsertData);
                  $insertRequirement = $this->db->insert('lbrmss_m_requirements', $marriageRequirementsData);
                  
                      if($insertFeeTemplate && $insert_Groom_info && $insert_Bride_info && $insert_assignment_info && $insertedWitness && $insertedIds && $insertRequirement){
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