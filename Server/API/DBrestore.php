<?php
// === CONFIGURE DATABASE CREDENTIALS ===
$host = 'localhost';
$username = 'root';
$password = ''; // Your MySQL password
$database = 'restore'; // Target database name

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
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
        echo "<h3>Database restored successfully!</h3>";
    } else {
        echo "<h3>Database restored with $errorCount errors.</h3>";
    }
} else {
    echo "No file uploaded.";
}
?>
