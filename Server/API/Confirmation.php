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
        
        //to insert eventData
        $eventData = Array(
            "E_ID" => null,
            "EventServiceID" => $Event['EventServiceID'],
            "Client" => $Event['Client'],
            "Service" => $Event['Service'],
            "Others" => $Event['Others'],
            "TypeofMass" => $Event['TypeofMass'],
            "Type" => $Event['Type'],
            "TimeTo" => $Event['TimeTo'],
            "TimeFrom" => $Event['TimeFrom'],
            "Date" => $Event['Date'],  
            "Venue" => $Event['Venue'],
            "Duration" => $Event['Duration'],
            "Days" => $Event['Days'],
            "Venue_type" => $Event['Venue_type'],
            "Assigned_Priest" => $Event['Assigned_Priest'],
            "Contact_Number" => $Event['Contact_Number'],
            "CertificateFor" => $Event['CertificateFor'],
            "EventProgress" =>$ConfirmationData['EventProgress'],
            "RequirementStatus"=>$ConfirmationData['Status'],
            "Description"=>$Event['Description']
    
            );

            $insert_EventInfo =$this->db->insert('eventstable', $eventData);


            // Confirmation Personal Details
            $ConfirmationDatas = array(
                "CID" => null,
                "First_Name" => $ConfirmationData['First_Name'],
                "Middle_Name" => $ConfirmationData['Middle_Name'],
                "Last_Name" => $ConfirmationData['Last_Name'],
                "Suffix" => $ConfirmationData['Suffix'],
                "Gender" => $ConfirmationData['Gender'],
                "Birth_Date" => $ConfirmationData['Birth_Date'],
                "Birth_Place" => $ConfirmationData['Birth_Place'],
                "Legitimacy" => $ConfirmationData['Legitamacy'],
                "Nationality" => $ConfirmationData['Nationality'],
                "Father_Name" => $ConfirmationData['Father_Name'],
                "Mother_Name" => $ConfirmationData['Mother_Name'],
                "EventProgress" => $ConfirmationData['EventProgress'],
                "Status" => $ConfirmationData['Status'],
                "ContactNumber" => $ConfirmationData['ContactNumber'],
                "Contact_Person" => $ConfirmationData['Contact_Person'],
                "EventScheduleID" => $ConfirmationData['EventScheduleID'],
                "Region" => $ConfirmationData['Region'],
                "Province" => $ConfirmationData['Province'],
                "City" => $ConfirmationData['City'],
                "Barangay" => $ConfirmationData['Barangay'],
            );
            $insert_BaptismData = $this->db->insert('confirmation', $ConfirmationDatas);


             //Witness/Sponsor Section
             $witnessLen = count($ConfirmationData['Witness']);
            for ($x = 0; $x<$witnessLen; $x++){
                $sponsorData = array(
                "ID" =>null,
                "ServiceID" => $Event['EventServiceID'],
                "Ninong" => $ConfirmationData['Witness'][$x]['Ninong'],
                "Ninong_Address" => $ConfirmationData['Witness'][$x]['Ninong_Address'],
                "Ninang" => $ConfirmationData['Witness'][$x]['Ninang_Testium'],
                "Ninang_Address" => $ConfirmationData['Witness'][$x]['Ninang_Address'],
            );
            // =======================================================//
            $insertWitness = $this->db->insert('witness_testium_tbl',$sponsorData);
            }
            // requirement
            $requirementData = array(
                "ID" => null,
                "ServiceID"=> $Event['EventServiceID'],
                "Marriage_License" => $ConfirmationData['Requirement']['Marriage_License'],
                "Confirmation" => $ConfirmationData['Requirement']['Confirmation'],
                "LiveBirthCert" => $ConfirmationData['Requirement']['LiveBirthCert'],
                "Cenomar" => 'no',
                "Baptismal" => 'no',
                "Interogation" => 'no',
                "PreCana" => 'no',
                "Confession" => 'no',
                "PermissionLetter" => 'no'
            );
                $insertRequirement = $this->db->insert('requirements_tbl',$requirementData);
             // Seminar Section
                $SeminarLength = count($ConfirmationData["SeminarDetails"]);
                for ($s = 0; $s<$SeminarLength; $s++){
                    $seminarData = array(
                    "E_ID" => null,
                    "EventServiceID" => $Event['EventServiceID'],
                    "Client" => $Event['Client'],
                    "Service" => $ConfirmationData['SeminarDetails'][$s]['SeminarTitle'],
                    "Others" => null,
                    "TypeofMass" => null,
                    "Type" => "Seminar",
                    "TimeTo" => $ConfirmationData['SeminarDetails'][$s]['timeStart'],
                    "TimeFrom" => $ConfirmationData['SeminarDetails'][$s]['timeEnd'],
                    "Date" => $ConfirmationData['SeminarDetails'][$s]['Date'], 
                    "Venue" => $ConfirmationData['SeminarDetails'][$s]['SeminarVenue'], 
                    "Duration" => $ConfirmationData['SeminarDetails'][$s]['duration'],
                    "Days" => $ConfirmationData['SeminarDetails'][$s]['days'],
                    "Venue_type" => "Church",
                    "Assigned_Priest" => null,
                    "Contact_Number" => null,
                    "CertificateFor" =>null,
                    "EventProgress" =>$ConfirmationData['EventProgress'],
                    "RequirementStatus"=>$ConfirmationData['Status']
                );
                // insert Seminar Details here (Query)
                $insertSeminar = $this->db->insert('eventstable',$seminarData);
                }

                if($insert_EventInfo == true && $ConfirmationDatas == true &&
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