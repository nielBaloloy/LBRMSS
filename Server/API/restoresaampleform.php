<!DOCTYPE html>
<html>
<head>
    <title>Restore Database</title>
</head>
<body>
    <h2>Import Database from SQL File</h2>
    <form action="DBrestore.php" method="post" enctype="multipart/form-data">
        <label>Select .sql File:</label>
        <input type="file" name="sql_file" accept=".sql" required><br><br>
        <input type="submit" name="submit" value="Restore Database">
    </form>
</body>
</html>