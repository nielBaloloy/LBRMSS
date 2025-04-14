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
       $getPriestList = $this->db->rawQuery("SELECT * FROM lbrmss_priest_main AS a 
                                                LEFT JOIN lbrmss_position AS b
                                                ON a.position = b.pos_id WHERE 
                                                a.remark = '1' 
                                                AND b.remark = '1'");

            
        if($getPriestList){
          $priests = [];

            foreach ($getPriestList as $row) {
                $priests[] = [
                    'priest_id' => $row['priest_id'],
                    'lname' => $row['lname'],
                    'fname' => $row['fname'],
                    'mname' => $row['mname'],
                    'suffix_name' => $row['suffix_name'],
                    'contact_number' => $row['contact_number'],
                    'status' => $row['status'] == 1 ? 'active' : 'inactive', // map numeric to string
                    'position' => $row['position'], // this is the pos_id
                    'fullName' => $row['fname'] . ' ' . substr($row['mname'], 0, 1) . '. ' . $row['lname'],
                    'pos_name' => $row['pos_name'],
                    'pos_prefix' => $row['pos_prefix'],
                    'remark' => $row['remark'],
                    'created_at' => $row['created_at'],
                    'created_by' => $row['created_by']
                ];
            }
            echo json_encode(array("Status"=>"Success","data"=>$priests));
        }else{
            echo json_encode(array("Status"=>"Failed","data"=>[]));
        }

      

      }
      public function httpPost($payload)
      {
      
        $datas = json_encode($payload);
        $arr = json_decode($datas, true);
        
       
        $dt = new DateTime();
        $dty = $dt->format('Y-m-d H:i:s');
        
        $priest = array(
          'priest_id' => '',
          'lname' => $arr['lname'] ,
          'fname' => $arr['fname'],
          'mname' => $arr['mname'] ,
          'suffix_name' => $arr['sufix_name'] ?? '', // typo in key? Make sure this matches your actual data
          'contact_number' => $arr['contact_number'] ?? '',
          'status' => $arr['status'],
          'position' => $arr['position'],
          'created_at'=>$dty,
          'created_by'=>'1',
          'remark'  =>'1'
        );
        $insertPriest = $this->db->insert('lbrmss_priest_main',$priest);
        if($insertPriest){
          echo json_encode(array("message"=>""));
        }else{
          echo json_encode(array("message"=>"Failed"));
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