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

        //  segregate data(Event data and Marriage Data)
        $Event =$arr['eventData'];//Event Data
        $MarriageData =$arr['MarriageData']; // Marriage data
        // event array
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
        "EventProgress" =>$MarriageData['EventProgress'],
        "RequirementStatus"=>$MarriageData['Status']

        );
        $insert_EventInfo =$this->db->insert('eventstable', $eventData);
        //marriage array
        $marriageData = array(
          "MID" => null,
          "Groom_First_Name" => $MarriageData['Groom_First_Name'],
          "Groom_Middle_Name" => $MarriageData['Groom_Middle_Name'],
          "Groom_Last_Name" => $MarriageData['Groom_Last_Name'],
          "Groom_Suffix" => $MarriageData['Groom_Suffix'],
          "Groom_Status" => $MarriageData['Groom_Status'],
          "Groom_BirthDate" => $MarriageData['Groom_BirthDate'],
          "Groom_Region" => $MarriageData['Groom_Region'],
          "Groom_Province" => $MarriageData['Groom_Province'],
          "Groom_City" => $MarriageData['Groom_City'],
          "Groom_Barangay" => $MarriageData['Groom_Barangay'],
          "Grooms_Age" => $MarriageData['Grooms_Age'],
          "Groom_Father" => $MarriageData['Groom_Father'],
          "Groom_Mother" => $MarriageData['Groom_Mother'],
          "Bride_First_Name" => $MarriageData['Bride_First_Name'],
          "Bride_Middle_Name" => $MarriageData['Bride_Middle_Name'],
          "Bride_Last_Name" => $MarriageData['Bride_Last_Name'],
          "Bride_BirthDate" => $MarriageData['Bride_BirthDate'],
          "Bride_Status" => $MarriageData['Bride_Status'],
          "Bride_Age" => $MarriageData['Bride_Age'],
          "Bride_Father" => $MarriageData['Bride_Father'],
          "Bride_Mother" => $MarriageData['Bride_Mother'],
          "Bride_Region" => $MarriageData['Bride_Region'],
          "Bride_Province" => $MarriageData['Bride_Province'],
          "Bride_City" => $MarriageData['Bride_City'],
          "Bride_Barangay" => $MarriageData['Bride_Barangay'],
          "EventProgress" => $MarriageData['EventProgress'],
          "Status" => $MarriageData['Status'],
          "ContactNumber" => $MarriageData['ContactNumber'],
          "Contact_Person" => $MarriageData['Contact_Person'],
          "EventServiceID"=> $Event['EventServiceID'],
          
      );
      $insert_MarriageData = $this->db->insert('marriagetb',$marriageData);

      //Witness/Sponsor Section
      $witnessLen = count($MarriageData["Witness"]);
      for ($x = 0; $x<$witnessLen; $x++){
        $sponsorData = array(
          "ID" =>null,
          "ServiceID" => $Event['EventServiceID'],
          "Ninong" => $MarriageData['Witness'][$x]['Groom_Testium'],
          "Ninong_Address" => $MarriageData['Witness'][$x]['G_Address'],
          "Ninang" => $MarriageData['Witness'][$x]['Bride_Testium'],
          "Ninang_Address" => $MarriageData['Witness'][$x]['B_Address'],
      );
      // insert sponsor here (Query)
      $insertWitness = $this->db->insert('witness_testium_tbl',$sponsorData);
      }

      // Seminar Section
      $SeminarLength = count($MarriageData["SeminarDetails"]);
      for ($s = 0; $s<$SeminarLength; $s++){
        $seminarData = array(
          "E_ID" => null,
          "EventServiceID" => $Event['EventServiceID'],
          "Client" => $Event['Client'],
          "Service" => $MarriageData['SeminarDetails'][$s]['SeminarTitle'],
          "Others" => null,
          "TypeofMass" => null,
          "Type" => "Seminar",
          "TimeTo" => $MarriageData['SeminarDetails'][$s]['timeStart'],
          "TimeFrom" => $MarriageData['SeminarDetails'][$s]['timeEnd'],
          "Date" => $MarriageData['SeminarDetails'][$s]['Date'], 
          "Venue" => $MarriageData['SeminarDetails'][$s]['SeminarVenue'], 
          "Duration" => $MarriageData['SeminarDetails'][$s]['duration'],
          "Days" => $MarriageData['SeminarDetails'][$s]['days'],
          "Venue_type" => "Church",
          "Assigned_Priest" => null,
          "Contact_Number" => null,
          "CertificateFor" =>null,
          "EventProgress" =>$MarriageData['EventProgress'],
          "RequirementStatus"=>$MarriageData['Status']
      );
      // insert Seminar Details here (Query)
      $insertSeminar = $this->db->insert('eventstable',$seminarData);
      }
    $requirementData = array(
      "ID" => null,
      "ServiceID"=> $Event['EventServiceID'],
      "Baptismal" => $MarriageData['Requirement']['Baptismal'],
      "Marriage_License" => $MarriageData['Requirement']['Marriage_License'],
      "Confirmation" => $MarriageData['Requirement']['Confirmation'],
      "LiveBirthCert" => $MarriageData['Requirement']['LiveBirthCert'],
      "Cenomar" => $MarriageData['Requirement']['Cenomar'],
      "Interogation" => $MarriageData['Requirement']['Interogation'],
      "PreCana" => $MarriageData['Requirement']['PreCana'],
      "Confession" => $MarriageData['Requirement']['Confession'],
      "PermissionLetter" => $MarriageData['Requirement']['PermissionLetter']
  );
      $insertRequirement = $this->db->insert('requirements_tbl',$requirementData);
  
    
        if($insert_EventInfo == true && $insert_MarriageData == true &&
        $insertWitness == true && $insertRequirement ==true){
          // fetch the marriage data to be displayed on peending table
          $Display_Pending = $this->db->where('EventProgress', "Pending")
                                      ->get('eventstable');
          echo json_encode(array("Status"=>"Success", "Pending"=>$Display_Pending));
        }else{
          echo json_encode(array("Status" => "Failed" . $this->db->getLastError()));
        }
          
            
       
         


















        //save witness/ god parents to witness db 
        // save requirements to req_table
        // all with serviceID Foreign KEY

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