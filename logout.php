<?php
	session_start();
	include 'conf.php';
	$datetime = date("M j, Y g:i:s A");
	$in = "out";
	$stmt = $conn->prepare("INSERT INTO `login_log` (account_id, `datetime`, logintype) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $_SESSION['acc_id'], $datetime, $in);
	if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'TECH'){		
		$stmt->execute();
	}
	session_destroy();
?>	
	
	<script type="text/javascript"> 
		window.location.replace("index.php");
		alert("You have been successfully logged out");
	</script>	
	