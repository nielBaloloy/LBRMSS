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
     

        $serviceRequest_ID = $request['serviceId'];

             
                  $this->db->where("remark", '1'); 
                  $this->db->where("service_fee_id", $serviceRequest_ID); 
         $getFee = $this->db->get('lbrmss_event_fee_assignment');
         $fee_data = [];
         foreach ($getFee as $row) {
            $fee_data[] = [
                'fee_ids' => $row['fee_ids'],
                'service_fee_id' => $row['service_fee_id'],
                'ServiceType' => $row['ServiceType'],
                'VenueType' => $row['VenueType'],
                'name' => $row['name'],
                'model' => $row['model'],
                'amount' => $row['amount'],
                'remark' => $row['remark']
            ];
        }
         if($getFee){
            echo json_encode(array("Status"=>"Success", "fee"=>$fee_data));     
         }else{
            echo json_encode(array("Status"=>"Failed", "fee"=>[]));  
         }


      }
      public function httpPost($payload) {
        $datas = json_encode($payload);
        $request = json_decode($datas, true);

        $fees  = $request['AssignedFee'];
        $words = $fees['name'];
        $model = str_replace(" ", "", $words);
        $fee_data=array(
            'fee_ids' => '',
            'service_fee_id' => $fees['service_type'],
            'ServiceType' => $fees['service_fee_id'],
            'VenueType' => $fees['VenueType'],
            'name' => $fees['name'],
            'model' => $model,
            'amount' => $fees['amount'],
            'remark' => '1'
        );
        $insertData = $this->db->insert('lbrmss_event_fee_assignment',$fee_data);
        if($insertData){
            echo json_encode(array("Status"=>"Success"));   
        }else{
            echo json_encode(array("Status"=>"Failed")); 
        }

      }
      public function httpPut($payload){}
      public function httpDelete($payload){}
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