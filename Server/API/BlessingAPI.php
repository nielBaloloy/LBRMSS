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
        
        $Event =$arr['eventData'];//Event Data
        $eventData = Array(
          "E_ID" => null,
          "EventServiceID" => $Event['EventServiceID'],
          "Client" => $Event['Client'],
          "Service" => $Event['Service'],
          "Others" => $Event['Others'],
          "TypeofMass" => $Event['TypeofMass'],
          "Type" => 'Special',
          "TimeTo" => $Event['TimeTo'],
          "TimeFrom" => $Event['TimeFrom'],
          "Date" => $Event['Date'],  
          "Venue" =>  $Event['Event_Barangay']. " ".$Event['Event_City']. " ". $Event['Event_Province'] ,
          "Duration" => $Event['Duration'],
          "Days" => $Event['Days'],
          "Venue_type" => 'Outside',
          "Assigned_Priest" => $Event['Assigned_Priest'],
          "Contact_Number" => $Event['Contact_Number'],
          "Event_Region"=>$Event['Event_Region'],
          "Event_Province"=>$Event['Event_Province'],
          "Event_City"=>$Event['Event_City'],
          "Event_Brgy"=>$Event['Event_Barangay'],
          "CertificateFor" =>'NA',
          "EventProgress" =>'Pending',
          "RequirementStatus"=>'NA',
          "Description"=>$Event['Description']
          );

          $insert_EventInfo =$this->db->insert('eventstable', $eventData);
          if($insert_EventInfo){
            echo json_encode(array("Status"=>"Success"));
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