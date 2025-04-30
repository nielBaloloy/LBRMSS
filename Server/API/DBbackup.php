<?php
function backupDatabase($host, $username, $password, $database, $backupFile = null) {
    // Default filename
    if (!$backupFile) {
        $backupFile = $database . '_' . date('Y-m-d_H-i-s') . '.sql';
    }

    // Connect to MySQL
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get all table names
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }

    $sqlScript = "-- Backup of `$database`\n-- Created on " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($tables as $table) {
        // Table structure
        $createTable = $conn->query("SHOW CREATE TABLE `$table`")->fetch_assoc();
        $sqlScript .= "-- Table structure for `$table`\n";
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlScript .= $createTable['Create Table'] . ";\n\n";

        // Table data
        $rows = $conn->query("SELECT * FROM `$table`");
        if ($rows->num_rows > 0) {
            $sqlScript .= "-- Data for `$table`\n";
            while ($row = $rows->fetch_assoc()) {
                $values = array_map(function($value) use ($conn) {
                    return $value === null ? 'NULL' : "'" . $conn->real_escape_string($value) . "'";
                }, array_values($row));
                $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
            }
            $sqlScript .= "\n";
        }
    }

    // Write to file
    file_put_contents($backupFile, $sqlScript);

    $conn->close();

    return $backupFile;
}

// === CONFIGURE THESE ===
$host = 'localhost';
$username = 'root';
$password = ''; // your MySQL password
$database = 'lbrmss_db'; // replace with your database name

$backupFile = backupDatabase($host, $username, $password, $database);

// === TRIGGER DOWNLOAD ===
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($backupFile));
readfile($backupFile);

// Optionally delete the file from the server after download
unlink($backupFile);
exit;
?>
