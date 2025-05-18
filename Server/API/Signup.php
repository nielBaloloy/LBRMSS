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
              "mode"=>1,
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
    public function httpPost()
{
    // Retrieve POST data directly
    $posid        = $_POST['posid'];
    $lname        = $_POST['lname'];
    $mname        = $_POST['mname'];
    $fname        = $_POST['fname'];
    $suffix_name  = $_POST['suffix_name'];
    $contact      = $_POST['contact'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];
    $status       = $_POST['status'];
    $posprefix    = $_POST['posprefix'];

    // Get next account ID
    $acc_ids = $this->db->getMaxId('useraccounts', 'UID') + 1;

    // Handle file upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/' . $imageName;
        }
    }

    // Insert into lbrmss_account_person
    $data1 = [
        "pos_id"      => $posid,
        "account_id"  => $acc_ids,
        "lname"       => $lname,
        "mname"       => $mname,
        "fname"       => $fname,
        "suffix_name" => $suffix_name,
        "contact"     => $contact,
        "photo"       => $imagePath, // if your table has a `photo` column
        "created_at"  => date('Y-m-d H:i:s'),
        "created_by"  => 1,
        "updated_at"  => null,
        "updated_by"  => null,
        "remark"      => 1
    ];

    $insert1 = $this->db->insert('lbrmss_account_person', $data1);
    $acc_idss = $this->db->getMaxId('lbrmss_account_person', 'pid');

    // Insert into useraccounts
    $data2 = [
        "UID"        => '',
        "person_id"  => $acc_idss,
        "AccessLvl"  => $posid,
        "Username"   => $username,
        "Password"   => $password,
        "isActive"   => $status,
        "remark"     => '1',
    ];

    $insert2 = $this->db->insert('useraccounts', $data2);

    // Insert into priest_main if position is 1
    if ($posid == '1') {
        $acc_id = $acc_idss; // already retrieved
        $data3 = [
            "priest_id"      => '',
            "acc_id"         => $acc_id,
            "lname"          => $lname,
            "mname"          => $mname,
            "fname"          => $fname,
            "suffix_name"    => $suffix_name,
            "status"         => $status,
            "contact_number" => $contact,
            "position"       => $posid,
            "created_at"     => date('Y-m-d H:i:s'),
            "created_by"     => '1',
            "remark"         => '1'
        ];

        $insert3 = $this->db->insert('lbrmss_priest_main', $data3);
    }

    // Final response
    if ($insert1 && $insert2) {
        echo json_encode(["Status" => "Success"]);
    } else {
        echo json_encode(["Status" => "Failed"]);
    }
}

 
     
      public function httpPut($payload)
      {
          $data = json_encode($payload);
          $AccountInfo = json_decode($data, true);
          $AccountInfo  =$AccountInfo['account'];
          $pid  = $AccountInfo['pid'];
          $pos_id  = $AccountInfo['posid'];
          $fname  = $AccountInfo['fname'];
          $mname  = $AccountInfo['mname'];
          $lname  = $AccountInfo['lnamme'];
          $pass  = $AccountInfo['password'];
          $username  = $AccountInfo['username'];
          $contact  = $AccountInfo['contact'];
           $status  = $AccountInfo['status'];
          $suffix  = $AccountInfo['suffix_name'] ?? null;
          $update = $this->db->rawQuery("UPDATE lbrmss_account_person SET pos_id = '$pos_id',
                                        fname = '$fname',
                                        mname ='$mname',
                                        lname ='$mname',
                                        suffix_name = '$suffix',
                                        contact = '$contact'
                                        WHERE pid  ='$pid'
                                        AND remark = '1';
                                          ");
          $this->db->rawQuery("UPDATE useraccounts SET 
                                                Username  ='$username', 
                                                Password = '$pass', 
                                                isActive = '$status'
                                                WHERE person_id  ='$pid' AND remark = '1';");
           
          echo json_encode(array("Status"=>"Success","data"=>$AccountInfo));
    

    }
      public function httpDelete($payload)
      {
          $data = json_encode($payload);
          $AccountInfo = json_decode($data, true);
          $delete_id  =$AccountInfo['delete'];

          $update = $this->db->rawQuery("UPDATE lbrmss_account_person SET remark ='0'
                                          WHERE pid  ='$delete_id'
                                          AND remark = '1';
                                            ");
            $this->db->rawQuery("UPDATE useraccounts SET remark ='0'
                                                  WHERE person_id  ='$delete_id' AND remark = '1';");
           
          echo json_encode(array("Status"=>"Success","data"=>$AccountInfo));
        
        
       
       
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