<?php

function fn_uncheck($idd){
	global $conn;

	if ( !$conn->query("UPDATE warehouse SET click=1 WHERE id=".$idd) )
		echo "{\"ret\":\"-1\",\"query\":"."UPDATE warehouse SET click=1 WHERE id=".$idd."}";
	else
		echo "{\"ret\":\"1\"}";

}
