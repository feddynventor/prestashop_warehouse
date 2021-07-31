<?php

function fn_get($product){
	global $conn;

	$query = "INSERT INTO warehouse_sell(id_product) VALUES(".$product["data"]["id_product"].")";
	$result = $conn->query($query);

	if ($result) {
		echo "{\"ret\":1}";return;
	} else {
		echo "{\"ret\":0}";return;
	}
}
