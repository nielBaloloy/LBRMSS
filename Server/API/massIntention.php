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
        $datas = json_encode($payload);
        $request = json_decode($datas, true);
         $getFee="";
        $serviceRequest_ID = $request['serviceId'];
        $serviceRequest_Type = $request['type'];
        if($serviceRequest_ID == '4'){
         
          $this->db->where("remark", '1'); 
          $this->db->where("service_fee_id", $serviceRequest_ID); 
          $getFee = $this->db->get('lbrmss_event_fee_assignment');
        }else{
          
                $this->db->where("remark", '1'); 
                $this->db->where("service_fee_id", $serviceRequest_ID); 
                $getFee = $this->db->get('lbrmss_event_fee_assignment');
        }
         if($getFee){
            echo json_encode(array("Status"=>"Success", "fee"=>$getFee));     
         }else{
            echo json_encode(array("Status"=>"Failed", "fee"=>""));  
         }


      }
      public function httpPost($payload)
      {
        $datas = json_encode($payload);
        $req = json_decode($datas, true);
       
        $intention = $req['eventData'];
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
    
        $massIntention = Array(
            "mi_id" => '',
            "mass_id" => $intention['E_ID'],
            "intention_type" => $intention['TypeofMass'],
            "intention_desc" =>  $intention['Description'],
            "created_at" => $dty,
            "created_by" => '1',
            "remark" => '1',
          );
          $insertmassIntention = $this->db->insert('lbrmss_mass_intentions',$massIntention);
          
          if($insertmassIntention){
            echo json_encode(array("Status"=>"Success"));
          }else{
            echo json_encode(array("Status"=>"Failed"));
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