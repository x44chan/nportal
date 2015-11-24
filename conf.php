<?php
	$host = "127.0.0.1";
	$uname = "root";
	$pword = "";
	$db = "new";
	
	$conn = new mysqli($host, $uname, $pword, $db);
	
	if($conn->connect_error){
		die("Connection error:". $conn->connect_error);
	}

?>
