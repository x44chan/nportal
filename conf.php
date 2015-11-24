<?php
	$host = "127.0.0.1";
	$uname = "intersta_emp";
	$pword = "MaxcaspeR2015#";
	$db = "intersta_emp";
	
	$conn = new mysqli($host, $uname, $pword, $db);
	
	if($conn->connect_error){
		die("Connection error:". $conn->connect_error);
	}

?>
