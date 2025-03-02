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
        $BaptismData =$arr['BaptismData']; // Baptism data

            /**get current date and time*/
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
            
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
                    "requirement_status"  => $MarriageData['Status'],
                    "created_at"          => $dty,
                    "created_by"          => '1',
                    "remark"              => '1'

                );
                $insert_EventInfo =$this->db->insert('lbrmss_event_table_main', $eventData);

                if($insert_EventInfo){
                    $eventId = $this->db->getMaxId('lbrmss_event_table_main','event_id');
                    $new_eventId= $eventId;
                    
                    /** insert into lbrmss_baptism_main */
                    $marriageAssignment = array(
                        "mid" => '',
                        "event_id" =>   $new_eventId,
                        "assigned_priest" => $Event['Assigned_Priest']['priest_id'],
                        "remark" => '1'
                    );

                    /** insert into lbrmss_bapt_person */
                    $baptismData = array(
                        "bapt_person_id" => '', // Assuming this is auto-incremented
                        "bapt_event_id" => $baptEventId, // Link to event ID
                        "bapt_lname" => $baptismDetails['Last_Name'] ?? '',
                        "bapt_mname" => $baptismDetails['Middle_Name'] ?? '',
                        "bapt_fname" => $baptismDetails['First_Name'] ?? '',
                        "bapt_suffix" => $baptismDetails['Suffix'] ?? null,
                        "bapt_age" => $baptismDetails['Age'] ?? '',
                        "bapt_dob" => isset($baptismDetails['BirthDate']) && strtotime($baptismDetails['BirthDate']) 
                                        ? date('Y-m-d', strtotime($baptismDetails['BirthDate'])) 
                                        : null,
                        "bapt_birthplace" => $baptismDetails['Birthplace'] ?? '',
                        "bapt_gender" => $baptismDetails['Gender'] ?? '',
                        "bapt_father" => $baptismDetails['Father'] ?? '',
                        "bapt_mother" => $baptismDetails['Mother'] ?? '',
                        "bapt_legitimacy" => $baptismDetails['Legitimacy'] ?? '', // 1 = illegal, 2 = legal
                        "bapt_region" => $baptismDetails['Region'] ?? '',
                        "bapt_province" => $baptismDetails['Province'] ?? '',
                        "bapt_City" => $baptismDetails['City'] ?? '',
                        "bapt_Barangay" => $baptismDetails['Barangay'] ?? '',
                        "created_at" => date('Y-m-d H:i:s'),
                        "created_by" => $loggedInUserId ?? '1', // Dynamically assign user ID
                        "updated_by" => null,
                        "remark" => '1' // 1 = show, 0 = hide
                    );
                    
                }

    

            };
        

            // Baptism Personal Details
            $baptismData = array(
                "BID" => null,
                "First_Name" => $BaptismData['First_Name'],
                "Middle_Name" => $BaptismData['Middle_Name'],
                "Last_Name" => $BaptismData['Last_Name'],
                "Suffix" => $BaptismData['Suffix'],
                "Gender" => $BaptismData['Gender'],
                "Birth_Date" => $BaptismData['Birth_Date'],
                "Birth_Place" => $BaptismData['Birth_Place'],
                "Legitamacy" => $BaptismData['Legitamacy'],
                "Father_Name" => $BaptismData['Father_Name'],
                "Mother_Name" => $BaptismData['Mother_Name'],
                "EventProgress" => $BaptismData['EventProgress'],
                "Status" => $BaptismData['Status'],
                "ContactNumber" => $BaptismData['ContactNumber'],
                "Contact_Person" => $BaptismData['Contact_Person'],
                "EventScheduleID" => $BaptismData['EventScheduleID'],
                
                "Region" => $BaptismData['Region'],
                "Province" => $BaptismData['Province'],
                "City" => $BaptismData['City'],
                "Barangay" => $BaptismData['Barangay'],
            );
            $insert_BaptismData = $this->db->insert('baptism', $baptismData);


             //Witness/Sponsor Section
            $witnessLen = count($BaptismData["BaptismWitness"]);
            for ($x = 0; $x<$witnessLen; $x++){
                $sponsorData = array(
                "ID" =>null,
                "ServiceID" => $Event['EventServiceID'],
                "Ninong" => $BaptismData['BaptismWitness'][$x]['Ninong'],
                "Ninong_Address" => $BaptismData['BaptismWitness'][$x]['Ninong_Address'],
                "Ninang" => $BaptismData['BaptismWitness'][$x]['Ninang_Testium'],
                "Ninang_Address" => $BaptismData['BaptismWitness'][$x]['Ninang_Address'],
            );
            // =======================================================//
            $insertWitness = $this->db->insert('witness_testium_tbl',$sponsorData);
            }
            //requirement
            $requirementData = array(
                "ID" => null,
                "ServiceID"=> $Event['EventServiceID'],
                "Marriage_License" => $BaptismData['Requirement']['Marriage_License'],
                "Confirmation" => $BaptismData['Requirement']['Confirmation'],
                "LiveBirthCert" => $BaptismData['Requirement']['LiveBirthCert'],
                "Cenomar" => 'no',
                "Baptismal" => 'no',
                "Interogation" => 'no',
                "PreCana" => 'no',
                "Confession" => 'no',
                "PermissionLetter" => 'no'
            );
                $insertRequirement = $this->db->insert('requirements_tbl',$requirementData);
             // Seminar Section
                $SeminarLength = count($BaptismData["SeminarDetails"]);
                for ($s = 0; $s<$SeminarLength; $s++){
                    $seminarData = array(
                    "E_ID" => null,
                    "EventServiceID" => $Event['EventServiceID'],
                    "Client" => $Event['Client'],
                    "Service" => $BaptismData['SeminarDetails'][$s]['SeminarTitle'],
                    "Others" => null,
                    "TypeofMass" => null,
                    "Type" => "Seminar",
                    "TimeTo" => $BaptismData['SeminarDetails'][$s]['timeStart'],
                    "TimeFrom" => $BaptismData['SeminarDetails'][$s]['timeEnd'],
                    "Date" => $BaptismData['SeminarDetails'][$s]['Date'], 
                    "Venue" => $BaptismData['SeminarDetails'][$s]['SeminarVenue'], 
                    "Duration" => $BaptismData['SeminarDetails'][$s]['duration'],
                    "Days" => $BaptismData['SeminarDetails'][$s]['days'],
                    "Venue_type" => "Church",
                    "Assigned_Priest" => null,
                    "Contact_Number" => null,
                    "CertificateFor" =>null,
                    "EventProgress" =>$BaptismData['EventProgress'],
                    "RequirementStatus"=>$BaptismData['Status']
                );
                // insert Seminar Details here (Query)
                $insertSeminar = $this->db->insert('eventstable',$seminarData);
                }

                if($insert_EventInfo == true && $insert_BaptismData == true &&
                $insertWitness == true && $insertRequirement ==true){
                  // fetch the marriage data to be displayed on peending table
                  $Display_Pending = $this->db->where('EventProgress', "Pending")
                                              ->get('eventstable');
                  echo json_encode(array("Status"=>"Success", "Pending"=>$Display_Pending));
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