<?php
//require("config.php");

function fn_get($ean){
	global $conn;

	$query = "SELECT ps_product.id_product, ps_product_lang.name, ps_product.reference, ps_product.ean13, ps_stock_available.quantity, ps_image.id_image
		FROM ps_product
		INNER JOIN ps_product_lang ON ps_product.id_product=ps_product_lang.id_product
		INNER JOIN ps_stock_available ON ps_product.id_product=ps_stock_available.id_product
		INNER JOIN ps_image ON ps_product.id_product=ps_image.id_product
		WHERE reference='".$ean."' OR ean13='".$ean."' LIMIT 1";

	$result = $conn->query($query);

	error_log($query);

	if ($result->num_rows == 1) {
		while($row = $result->fetch_assoc()) {
			print(json_encode(array("ret"=>1,"found"=>1,"data"=>$row))); return;
//			print(json_encode($row));
		}
	} else if ($result->num_rows == 0) {
		echo "{\"ret\":1,\"found\":0}";return;
	} else {
		echo "{\"ret\":1,\"found\":0}";return;
	}
	echo "{\"ret\":0}";
//	$conn->close();
}
