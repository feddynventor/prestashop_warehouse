<?php

$servername = "localhost";
$username = "root";
$password = "madgirirdu64";
$dbname = "prestashop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
	global $conn;

//	$query = "SELECT warehouse.id AS ID, warehouse.reference AS EAN, warehouse.id_product, warehouse.nome AS Articolo, warehouse.click AS OK, COUNT(*) AS Qta, warehouse_knownurls.url AS URL FROM warehouse INNER JOIN warehouse_knownurls ON warehouse.id_product = warehouse_knownurls.id_product WHERE warehouse.date >= DATE_ADD(CURDATE(), INTERVAL -7 DAY) GROUP BY id_product ORDER BY ID DESC";
	$query = "SELECT warehouse.id AS ID, warehouse.reference AS EAN, warehouse.id_product, warehouse.nome AS Articolo, warehouse.click AS OK, 1 AS Qta, warehouse.date AS Data, warehouse_knownurls.url AS URL FROM warehouse INNER JOIN warehouse_knownurls ON warehouse.id_product = warehouse_knownurls.id_product WHERE warehouse.date >= DATE_ADD(CURDATE(), INTERVAL -7 DAY) ORDER BY ID DESC";

	$result = $conn->query($query);
	$products = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			array_push($products,$row);
//			print(json_encode($row));
		}
	}
	echo json_encode($products);

$conn->close();
?>
