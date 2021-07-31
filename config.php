<?php
if ($_GET['token']!="pj7X8bwp3Xm9COef"){
	header("HTTP/1.1 401 Unauthorized");
	die();
}

$servername = "localhost";
$username = "root";
$password = "madgirirdu64";
$dbname = "prestashop";

// Create connection
global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
