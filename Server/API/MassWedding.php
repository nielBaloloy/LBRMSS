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
        $req = json_decode($datas, true);
       
        $event       = $req['eventData'];
        $mass_excel  = $req['excel'];
        $Seminar     = $req['Seminar'];

        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        $dtyOne = $dt->format('Y-m-d');
        $cleanDate = str_replace("-", "", $dtyOne);
        // client
        $ClientData = Array(
          "cid" => '',
          "name" => $event['Client'],
          "contact_no" => $event['Contact_Number'],
          "created_at" => $dty,
          "created_by" => '1',//to be changed later 
          "remark" => '1',
        );
       
        $insertClient = $this->db->insert('lbrmss_client_list',$ClientData);

          if($insertClient){
            
            $clientId = $this->db->getMaxId('lbrmss_client_list','cid');
            $new_cid= $clientId;

          $type = (isset($event['Type']) && strtolower($event['Type']) === "special") ? 2 : 1;

              //set event 
            $eventData = Array(
              "event_id"            => '',
              "service_id"          => $event['Service'],
              "client"              => $new_cid,
              "date"                =>  $event['Date'],
              "date_to"             =>  $event['Date'],
              "time_from"           => $event['TimeFrom'],
              "time_to"             => $event['TimeTo'],
              "venue_name"          => $event['Venue'],
              "duration"            => $event['Duration'],
              "type"                => $type,
              "days"                => $event['Days'],
              "venue_type"          => $event['Venue_type'],
              "priest_assigned_id"  => (isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
              ? $event['Assigned_Priest']['priest_id'] 
              : null),
              "event_progress"      => '1',
              "requirement_status"  => '1',
              "created_at"          => $dty,
              "created_by"          => '1',
              "remark"              => '1'

            );

              //INSERT EVENT
             
              $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');

           
              $new_eventId= $eventId;
             

























              //insert seminar
             $insert_eventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);
             if($insert_eventInfo){

                    $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
                    $new_eventId= $eventId;
                     $marriageAssignment = array(
                            "mid" => '',
                            "event_id" =>   $new_eventId,
                            "assigned_priest" => (isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
                                                  ? $event['Assigned_Priest']['priest_id'] 
                                                  : null),
                            "remark" => '1'
                        );




                     $marriageGroomData=[];
                      $mass = count($mass_excel);
                      echo $mass;
                     //groom
                      for ($a = 0; $a < $mass; $a++) {
                          $dateFormat = $this->db->excelDateToDate($mass_excel[$a]['Groom_Birthdate']);
                   
                          $marriageGroomData[] = array(
                              "g_id" => '', // Assuming this is auto-incremented
                              "g_event_id" => $new_eventId, // You may need to assign the event ID separately
                              "groom_lname" => $mass_excel[$a]['Groom_Last_Name'] ?? '',
                              "groom_mname" => !empty($mass_excel[$a]['Groom_Middle_Name']) ? $mass_excel[$a]['Groom_Middle_Name'] : '',
                              "groom_fname" => $mass_excel[$a]['Groom_First_Name'] ?? '',
                              "groom_suffix" => !empty($mass_excel[$a]['Groom_Suffix']) ? $mass_excel[$a]['Groom_Suffix'] : '',
                              "age" => $mass_excel[$a]['Grooms_Age'] ?? '',
                              "dob" =>$dateFormat,
                              "g_region" => $mass_excel[$a]['Groom_Region'] ?? '',
                              "g_province" => $mass_excel[$a]['Groom_Province'] ?? '',
                              "g_city" => $mass_excel[$a]['Groom_City'] ?? '',
                              "g_brgy" => $mass_excel[$a]['Groom_Barangay'] ?? '',
                              "g_civil_status" => strtolower(trim($mass_excel[$a]['Groom_Status'] ?? '')) === 'single' ? 1 : 0,
                              "g_father" => $mass_excel[$a]['Groom_Father'] ?? '',
                              "g_mother" => $mass_excel[$a]['Groom_Mother'] ?? '',
                              "created_at" => date('Y-m-d H:i:s'), // Current timestamp
                              "created_by" => '1', // accountid
                              "updated_at" => '',
                              "updated_by" => '',
                              "remark" => '1' // Assuming default value is '1' (show)
                          );
                            


                      }
                        $GroomTable = 'lbrmss_m_groom';
                        $insertedGroomIds = $this->db->insertMulti($GroomTable, $marriageGroomData);
                // bride


                    $marriageBrideData = [];
                      for ($b = 0; $b < $mass; $b++) {
                          $dateFormat = $this->db->excelDateToDate($mass_excel[$b]['Bride_Birthdate']);
                          $marriageBrideData[] = array(
                              "b_id" => '', // Assuming this is auto-incremented
                              "b_event_id" => $new_eventId,
                              "bride_lname" => $mass_excel[$b]['Bride_Last_Name'] ?? '',
                              "bride_mname" => !empty($mass_excel[$b]['Bride_Middle_Name']) ? $mass_excel[$b]['Bride_Middle_Name'] : '',
                              "bride_fname" => $mass_excel[$b]['Bride_First_Name'] ?? '',
                              "age" => $mass_excel[$b]['Brides_Age'] ?? '',
                              "dob" => $dateFormat,
                              "b_region" => $mass_excel[$b]['Bride_Region'] ?? '',
                              "b_province" => $mass_excel[$b]['Bride_Province'] ?? '',
                              "b_city" => $mass_excel[$b]['Bride_City'] ?? '',
                              "b_brgy" => $mass_excel[$b]['Bride_Barangay'] ?? '',
                              "b_civil_status" => strtolower(trim($mass_excel[$a]['Bride_Status'] ?? '')) === 'single' ? 1 : 0,
                              "b_father" => $mass_excel[$b]['Bride_Father'] ?? '',
                              "b_mother" => $mass_excel[$b]['Bride_Mother'] ?? '',
                              "created_at" => date('Y-m-d H:i:s'),
                              "created_by" => '1', // Change as needed
                              "updated_at" => '',
                              "updated_by" => '',
                              "remark" => '1'
                          );
                      }

              $BrideTable = 'lbrmss_m_bride';
              $insertedBrideIds = $this->db->insertMulti($BrideTable, $marriageBrideData);

                    $SeminarLength = count($Seminar);
                    $multiInsertData = [];

                    for ($s = 0; $s < $SeminarLength; $s++) {
                        $multiInsertData[] = array(
                            "seminar_id" => null, // Auto-increment
                            "event_id" =>  $new_eventId,
                            "seminar_title" => $Seminar[$s]['SeminarTitle'],
                            "date_from" => $Seminar[$s]['Date'], 
                            "date_to" => $Seminar[$s]['Date'], // Adjust if necessary
                            "time_from" => $Seminar[$s]['timeStart'],
                            "time_to" => $Seminar[$s]['timeEnd'],
                            "status" => '2',
                            "duration" => $Seminar[$s]['duration'],
                            "days" => $Seminar[$s]['days'],
                            "seminar_Venue" => $Seminar[$s]['SeminarVenue'], 
                            "remark" => 1 // Default to show (1) as per image comment
                        );
                    }

                    // Perform bulk insert using insertMulti
                    $tableName = 'lbrmss_seminar'; // Replace with actual table name
                     $insertedIds = $this->db->insertMulti($tableName, $multiInsertData);

                  $witnessLen = count($mass_excel);
                  $multiInsertDataW = [];

                  for ($x = 0; $x < $witnessLen; $x++) {
                      $multiInsertDataW[] = array(
                          "w_id" => '',
                          "ServiceID" => $new_eventId,
                          "Ninong" => $mass_excel[$x]['Groom_Witness'],
                          "Ninong_Address" => $mass_excel[$x]['GroomWitness_Address'],
                          "Ninang" => $mass_excel[$x]['Bride_Witness'],
                          "Ninang_Address" => $mass_excel[$x]['BrideWitness_Address'],
                      );
                  }
                  $tableNames = 'witness_testium_tbl';
                  $insertedWitnessIds = $this->db->insertMulti($tableNames, $multiInsertDataW);


                  $requirementsLen = count($mass_excel);
                    $multiInsertDataR = [];

                    for ($r = 0; $r < $requirementsLen; $r++) {

                    
                       $multiInsertDataR[] = array(
                          "req_id" => null, // Auto-increment
                          "event_id" => $new_eventId,

                          "baptismal_certificate"  => strtolower(trim($mass_excel[$r]['Baptismal'] ?? '')) === 'yes' ? 'true' : 'no',
                          "marriage_license"       => strtolower(trim($mass_excel[$r]['Marriage_License'] ?? '')) === 'yes' ? 'true' : 'no',
                          "confirmation"           => strtolower(trim($mass_excel[$r]['Confirmation'] ?? '')) === 'yes' ? 'true' : 'no',
                          "birth_certificate"      => strtolower(trim($mass_excel[$r]['LiveBirthCert'] ?? '')) === 'yes' ? 'true' : 'no',
                          "cenomar"                => strtolower(trim($mass_excel[$r]['Cenomar'] ?? '')) === 'yes' ? 'true' : 'no',
                          "interrogation"          => strtolower(trim($mass_excel[$r]['Interogation'] ?? '')) === 'yes' ? 'true' : 'no',
                          "precana_seminar"        => strtolower(trim($mass_excel[$r]['PreCana'] ?? '')) === 'yes' ? 'true' : 'no',
                          "confession"             => strtolower(trim($mass_excel[$r]['Confession'] ?? '')) === 'yes' ? 'true' : 'no',

                          "remark" => 1 // default: show
                          );

                    }

                    $requirementsTable = 'lbrmss_m_requirements';
                    $insertedRequirementIds = $this->db->insertMulti($requirementsTable, $multiInsertDataR);
                    

                      $priestAssigned = array(
                        "sched_id" => '',
                        "priest_id"=>(isset($event['Assigned_Priest']) && isset($event['Assigned_Priest']['priest_id']) 
                        ? $event['Assigned_Priest']['priest_id'] 
                        : null),
                        "sched_event_id"=>$new_eventId,
                        "date_from" =>$event['Date'],
                        "date_to" =>$event['Date'],
                        "sched_status"   =>'1',
                        "time_from" =>$event['TimeFrom'],
                        "time_to"=>$event['TimeTo'],
                        "created_at" =>$dty,
                        "remark"=>'1'
                      );

                      $insertPriestSchedule= $this->db->insert('lbrmss_priest_schedule',$priestAssigned);
             }
            
       
            }

       
                      if (
                          $insert_eventInfo &&
                          $insertedGroomIds &&
                          $insertedBrideIds &&
                          $insertedIds &&
                          $insertedWitnessIds &&
                          $insertedRequirementIds &&
                          $insertPriestSchedule
                      ) {
                          echo json_encode(["Status" => "Success"]);
                      } else {
                          echo json_encode(["Status" => "Failed"]);
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