<?php
include_once("config.php");

if (!isset($_GET["cmd"]))
	die("{\"ret\":\"no cmd\"}");

switch($_GET["cmd"]){
	case "get":
		include_once("fn_get.php");
		if (isset($_GET["ean"]))
			fn_get($_GET["ean"]);
		else
			echo "{\"ret\":\"0\"}";
		break;
//	case "sell":
//		include_once("fn_sell.php");
//		if (isset($_GET["product"]) && !empty($_GET["product"]))
//			fn_sell($_GET["product"]);
//		else
//			echo "{\"ret\":\"0\"}";
//		break;
	case "quant":
		include_once("fn_update_q.php");
		if (isset($_GET["ean"]) && !empty($_GET["ean"]) && isset($_GET["q"]))
			fn_update_q($_GET["ean"], $_GET["q"]);
		else
			echo "{\"ret\":\"0\"}";
		break;
	case "wish_uncheck":
		include_once("fn_wish_uncheck.php");
		if (isset($_GET["id"]) && !empty($_GET["id"]))
			fn_uncheck($_GET["id"]);
		else
			echo "{\"ret\":\"0\"}";
		break;
	case "wish_check":
		include_once("fn_wish_add.php");
		if (isset($_GET["product"]) && !empty($_GET["product"]))
			fn_wish_check($_GET["product"],$_GET["add"]);
		else
			echo "{\"ret\":\"0\"}";
		break;
	case "wish_newknown":
		include_once("fn_wish_add.php");
		if (isset($_GET["url"]) && !empty($_GET["url"]) && isset($_GET["product"]) && !empty($_GET["product"]))
			fn_wish_newknown($_GET["product"],$_GET["url"]);
		else
			echo "{\"ret\":\"0\"}";
		break;
	default:
		echo "{\"ret\":\"default\"}";

}
$conn->close();
//phpinfo();
?>
