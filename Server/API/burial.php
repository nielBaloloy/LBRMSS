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
        $BurialData =$arr['BurialData']; // Baptism data
        $Requiement = $arr['BurialData']['Requirements'];
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
            "EventProgress" =>$BurialData['EventProgress'],
            "RequirementStatus"=>$BurialData['Status'],
            "Description"=>$Event['Description']
            );

            $insert_EventInfo =$this->db->insert('eventstable', $eventData);


            // Baptism Personal Details
            $burialData = array(
              "BU_ID" => $BurialData['BU_ID'],
              "First_Name" => $BurialData['First_Name'],
              "Middle_Name" => $BurialData['Middle_Name'],
              "Last_Name" => $BurialData['Last_Name'],
              "Suffix" => $BurialData['Suffix'],
              "Gender" => $BurialData['Gender'],
              "Age" => $BurialData['Age'],
              "Status" => $BurialData['Status'],
              "Birth_Date" => $BurialData['Birth_Date'],
              "Date_of_Death" => $BurialData['Date_of_Death'],
              "Date_of_Burial" => $BurialData['Date_of_Burial'],
              "Nationality" => $BurialData['Nationality'],
              "Father_Name" => $BurialData['Father_Name'],
              "Mother_Name" => $BurialData['Mother_Name'],
              "Burial_Arrangement"=>$BurialData['Burial_Arrangement'],
              "Spouse" => $BurialData['Spouse'],
              "Sacrament" => $BurialData['Sacrament'],
              "Cause_of_Death" => $BurialData['Cause_of_Death'],
              "Place_of_Interment" => $BurialData['Place_of_Interment'],
              "Assigned_Priest" => $BurialData['Assigned_Priest'],
              "Remarks" => $BurialData['Remarks'],
              "EventScheduleID" => $BurialData['EventScheduleID'],
              "Region" => $BurialData['Region'],
              "Province" => $BurialData['Province'],
              "City" => $BurialData['City'],
              "Barangay" => $BurialData['Barangay'],
          );
          
            $insert_BurialData = $this->db->insert('burial', $burialData);

            //requirement
            $requirementData = array(
                "ID" => null,
                "ServiceID"=> $Event['EventServiceID'],
                "Marriage_License" => 'no',
                "Confirmation" => 'no',
                "LiveBirthCert" =>$Requiement['LiveBirthCert'],
                "Cenomar" => 'no',
                "Baptismal" => $Requiement['Baptismal'],
                "Interogation" => 'no',
                "PreCana" => 'no',
                "Confession" => 'no',
                "PermissionLetter" => 'no',
                "Burial_Permit" =>  $Requiement['Burial_Permit'],
                "Death_Certificate" => $Requiement['Death_Certificate'],
                "Family_Consent"=>$Requiement['Family_Consent'],
                "Cremation_Authorization"=>$Requiement['Cremation_Authorization'],
            );
                $insertRequirement = $this->db->insert('requirements_tbl',$requirementData);
          

                if($insert_EventInfo == true && $insert_BurialData == true && $insertRequirement == true){
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