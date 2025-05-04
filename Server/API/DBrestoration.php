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
        $host = 'localhost';
        $username = 'root';
        $password = ''; // Your MySQL password
        $database = 'lbrmss_dbs'; // Target database name
        
        $file = $_FILES['sql_file']['tmp_name'];
        if ($_FILES['sql_file']['type'] !== 'application/octet-stream' && pathinfo($_FILES['sql_file']['name'], PATHINFO_EXTENSION) !== 'sql') {
            die("Invalid file type. Please upload a .sql file.");
        }
    
        // Connect to MySQL
        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        // Read SQL file
        $sql = file_get_contents($file);
    
        // Split into individual statements
        $queries = explode(";\n", $sql);
    
        $errorCount = 0;
    
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if (!$conn->query($query)) {
                    $errorCount++;
                    echo "Error on query: " . $conn->error . "<br>";
                }
            }
        }
    
        $conn->close();
    
        if ($errorCount === 0) {
            echo json_encode(array("Status"=>"Succes"));
        } else {
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