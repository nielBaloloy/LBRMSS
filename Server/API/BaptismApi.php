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
       $this->db = new MysqlIDB('localhost', 'root', '', 'lbrmssdb');
       
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
            "EventProgress" =>$BaptismData['EventProgress'],
            "RequirementStatus"=>$BaptismData['Status']
    
            );

            $insert_EventInfo =$this->db->insert('eventstable', $eventData);


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