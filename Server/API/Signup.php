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
       $account = $this->db->rawQuery("SELECT a.* ,b.* ,c.*, b.pos_id as mainPosid, CONCAT(b.fname,' ',b.mname,' ',b.lname)as Fullname FROM useraccounts as a 
                            LEFT JOIN lbrmss_account_person AS b ON a.UID = b.account_id AND a.remark ='1' 
                            LEFT JOIN lbrmss_position AS c  ON c.pos_id = b.pos_id 
                            WHERE b.remark ='1' AND a.remark = '1' ");
        $accountInfo =[];
        if(count($account) > 0){
          foreach($account as $details) 
          {
            $accountInfo[]=[
              "all"=>$details,
              "pid"=>$details['UID'],
              "position"=>$details['pos_name'],
              "fullname"=>$details['Fullname'],
              "username"=>$details['Username'],
              "status"=>($details['isActive'] == 1) ? 'Active' : 'Inactive',
            ];
          }
            echo json_encode(array("Status"=>"Success", "data"=>$accountInfo));
        }else{
          echo json_encode(array("Status"=>"Failed", "data"=>[]));
        }

      }
      public function httpPost($payload)
      {
      
        $data = json_encode($payload);
        $AccountInfo = json_decode($data, true);
        
        $account = $AccountInfo['account'];
        $acc_ids = $this->db->getMaxId('useraccounts','UID') +1;
      $data1 = array(
        "pos_id"     => $account['posid'],               // maps to pos_id
        "account_id" => $acc_ids,                 // maps to uid from account
        "lname"      => $account['lnamme'],              // typo fixed: probably meant "lname"
        "mname"      => $account['mname'],
        "fname"      => $account['fname'],
        "contact"    => $account['contact'],
        "created_at" => date('Y-m-d H:i:s'),             // current timestamp
        "created_by" => 1,                               // adjust as needed
        "updated_at" => null,                            // or current timestamp if needed
        "updated_by" => null,
        "remark"     => 1                                // static or logic-based
    );
      
      $insert1 = $this->db->insert('lbrmss_account_person', $data1);
      $acc_idss = $this->db->getMaxId('lbrmss_account_person','pid');
        $data2 = array(
        "UID"        => '',
        "person_id"  => $acc_idss,
        "AccessLvl"  => $account['posid'],
        "Username"   =>  $account['username'],
        "Password"   => $account['password'],
        "isActive"   => $account['status'],
        "remark"     =>'1',
    );

     $insert2 = $this->db->insert('useraccounts', $data2);
        if($account['posid'] == '1'){
          $acc_id = $this->db->getMaxId('lbrmss_account_person','pid');
          $data3 = array(
            "priest_id"       => '',
            "acc_id"          => $acc_id,
            "lname"           => $account['lnamme'],
            "mname"           => $account['mname'],
            "fname"           => $account['fname'],
            "suffix_name"     => $account['suffix_name'],
            "status"          => $account['status'],
            "contact_number"  => $account['contact'],
            "position"        => $account['posid'],
            "created_at"      => date('Y-m-d H:i:s'),
            "created_by"      => '1',
            "remark"          => '1'
        );
        
        $insert3 = $this->db->insert('lbrmss_priest_main', $data3);

        }
        if($insert1 == true && $insert2 == true){
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