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
        
       $dt = new DateTime();
       $dty = $dt->format('Y-m-d H:i:s');
       $dtyOne = $dt->format('Y-m-d');
       $cleanDate = str_replace("-", "", $dtyOne);

       if (isset($arr['event_id'])) {
                        $purposeId = 0;
                        $id =$arr['event_id'];
                        $purpose =$arr['purpose'];
                      if ($purpose === 'Affidavit of Change Records') {
                            $purposeId = 9;
                        } elseif ($purpose === 'For Good Moral') {
                            $purposeId = 10;
                        } elseif ($purpose === 'For Change of Records') {
                            $purposeId = 11;
                        } else {
                            $purposeId = 0; // Default or unknown
                        }

           $getEvent =  $this->db->rawQuery("SELECT * FROM lbrmss_event_table_main WHERE event_id = '$id' and remark ='1' ");
       
            $EventFeeOtherData = array(
                        "event_fee_id" => '', 
                        "event_id" => $getEvent[0]['event_id'], // Foreign key reference to event
                        "service_id" => $purposeId,
                        "fee_type" => '1',
                        "reference_no" => "REFDOC-".$cleanDate.$getEvent[0]['event_id'], // Unique reference number
                        "payment_type" =>'1',
                        "amount_total" =>'', // Total amount for the event
                        "payment" => '', // Initial payment
                        "balance" => '', // Remaining balance
                        "due_date" => $dty, // Payment due date 1 week before event
                        "status" => '1', // 1 = Pending, 2 = Partially Paid, 3 = Paid
                        "created_at" => $dty, // Timestamp of creation
                        "updated_at" => '', // Timestamp of last update
                        "created_by" =>'1', // User who created the record
                        "updated_by" => '', // User who last updated the record
                        "remark" => '1' // 1 = Show, 0 = Hide
                    );
                    $insertFeeTemplate= $this->db->insert('lbrmss_event_fee',$EventFeeOtherData);
                    if($insertFeeTemplate){
                        echo json_encode(array("Status" => "Success", "Message" => "Payment Successfully Set"));
                    }else{
                        echo json_encode(array("Status" => "Failed", "Message" => ""));
                    }
       }else{
           $cert = $arr['x']['all'];
      
            $EventFeeData = array(
                        "event_fee_id" => '', 
                        "event_id" => $cert['event_id'], // Foreign key reference to event
                        "service_id" =>  $cert['service_id'],
                        "fee_type" => '1',
                        "reference_no" => "REFCERT-".$cleanDate.$cert['event_id'], // Unique reference number
                        "payment_type" =>'0',
                        "amount_total" =>'', // Total amount for the event
                        "payment" => '', // Initial payment
                        "balance" => '', // Remaining balance
                        "due_date" => $dty, // Payment due date 1 week before event
                        "status" => '1', // 1 = Pending, 2 = Partially Paid, 3 = Paid
                        "created_at" => $dty, // Timestamp of creation
                        "updated_at" => '', // Timestamp of last update
                        "created_by" =>'1', // User who created the record
                        "updated_by" => '', // User who last updated the record
                        "remark" => '1' // 1 = Show, 0 = Hide
                    );
                    $insertFeeTemplate= $this->db->insert('lbrmss_event_fee',$EventFeeData);
                    if($insertFeeTemplate){
                        echo json_encode(array("Status" => "Success", "Message" => "Payment Successfully Set"));
                    }else{
                        echo json_encode(array("Status" => "Failed", "Message" => ""));
                    }

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