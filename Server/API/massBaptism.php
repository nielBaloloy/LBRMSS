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




                     $BaptismData=[];
                      $mass = count($mass_excel);
                      echo $mass;
                     //groom
                      for ($a = 0; $a < $mass; $a++) {
                          $dateFormat = $this->db->excelDateToDate($mass_excel[$a]['bapt_dob']);
                   
                          $BaptismData[] = array(
                               "bapt_person_id" => '', // Assuming this is auto-incremented
                                "bapt_event_id" => $new_eventId, // Link to event ID
                                "bapt_lname" => $mass_excel[$a]['bapt_lname'] ?? '',
                                "bapt_mname" => $mass_excel[$a]['bapt_mname'] ?? '',
                                "bapt_fname" => $mass_excel[$a]['bapt_fname'] ?? '',
                                "bapt_suffix" => $mass_excel[$a]['bapt_suffix'] ?? '',
                                "bapt_age" => $mass_excel[$a]['bapt_age'] ?? '',
                                "bapt_dob" => $dateFormat,
                                "bapt_birthplace" => $mass_excel['bapt_birthplace'] ?? '',
                                "bapt_gender" => strtolower(trim($mass_excel[$a]['bapt_gender'] ?? '')) === 'male' ? '1' : '2',
                                "bapt_father" => $mass_excel[$a]['bapt_father'] ?? '',
                                "bapt_mother" => $mass_excel[$a]['bapt_mother'] ?? '',
                                "bapt_legitimacy" => $mass_excel[$a]['bapt_legitimacy'] ?? '', // 1 = illegal, 2 = legal
                                "bapt_region" => $mass_excel[$a]['bapt_region'] ?? '',
                                "bapt_province" => $mass_excel[$a]['bapt_province'] ?? '',
                                "bapt_City" => $mass_excel[$a]['bapt_City'] ?? '',
                                "bapt_Barangay" => $mass_excel[$a]['bapt_Barangay'] ?? '',
                                "created_at" => date('Y-m-d H:i:s'),
                                "created_by" =>  '1', // Dynamically assign user ID
                                "updated_by" => '',
                                "remark" => '1' // 1 = show, 0 = hide
                          );
                            


                      }
                        $Bapt_table = 'lbrmss_bapt_person';
                        $insertedGroomIds = $this->db->insertMulti($Bapt_table, $BaptismData);
                // bride


                  

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
                          "Ninong" => $mass_excel[$x]['Ninong'],
                          "Ninong_Address" => $mass_excel[$x]['Ninong_Adress'],
                          "Ninang" => $mass_excel[$x]['Ninang'],
                          "Ninang_Address" => $mass_excel[$x]['Ninang_Adress'],
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