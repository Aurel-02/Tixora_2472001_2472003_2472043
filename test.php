<?php
$conn = new mysqli('127.0.0.1', 'root', '');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$res = $conn->query("SHOW DATABASES");
$dbs = [];
while($row = $res->fetch_assoc()) {
    $dbs[] = $row['Database'];
}
file_put_contents('auth_output.json', json_encode($dbs));
echo "Databases written to auth_output.json\n";
