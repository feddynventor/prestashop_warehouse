<?php

function fn_wish_check($product, $add){
	global $conn;

//	print_r($product["data"]["name"]);

	$res = $conn->query("SELECT url FROM warehouse_knownurls WHERE id_product=".$product["data"]["id_product"]);
	if ($res->num_rows == 1){
		$url = $res->fetch_assoc()["url"];
//		echo "{\"ret\":1}"; non stamparlo subito, fall fare al prox metodo
		if ($add==1) fn_wish_add($product);
		else echo "{\"ret\":1}";
	} else if ($res->num_rows == 0){
		echo "{\"ret\":0}";
	}

//	if ( !$conn->query("INSERT INTO warehouse(id,nome,url) VALUES()") )
//		echo "{\"ret\":\"-1\"}";
//	else
//		echo "{\"ret\":\"1\"}";

}

function fn_wish_add($product){
	global $conn;
	$reference = $product["data"]["reference"];
	$ean = $product["data"]["ean13"];
	$res = ($ean==""?$reference:$ean);

	if ($conn->query("INSERT INTO warehouse(nome,reference,id_product) VALUES('".$product["data"]["name"]."','".$res."',".$product["data"]["id_product"].")"))
		echo "{\"ret\":1}";
	else
		echo "{\"ret\":0,\"query\":\"${query}\"}";
}

function fn_wish_newknown($id_product, $url){
	global $conn;

	if ($conn->query("INSERT INTO warehouse_knownurls(id_product,url) VALUES(".$id_product.",'".$url."')")){
		echo "{\"ret\":1}";
//		fn_wish_add("",$id_product); //QUI HAI SOLO L'ID, MA LA FUN RICHIEDE IL JSON
	}else
		echo "{\"ret\":0,\"query\":\"${query}\"}";
}
