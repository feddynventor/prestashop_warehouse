<?php

function fn_update_q($ean, $q) {
	global $conn;

	$query = "UPDATE ps_stock_available
		INNER JOIN ps_product ON ps_product.id_product = ps_stock_available.id_product
		SET ps_stock_available.quantity = ps_stock_available.quantity ".($q>0?"+".$q:$q).",
		ps_stock_available.physical_quantity = ps_stock_available.physical_quantity ".($q>0?"+".$q:$q)."
		WHERE ps_product.ean13='${ean}' OR ps_product.reference='${ean}'";

	if ($conn->query($query))
		echo "{\"ret\":1,\"found\":".$conn->affected_rows.",\"qta\":$q}";
	else
		echo "{\"ret\":0,\"query\":\"${query}\"}";
}
