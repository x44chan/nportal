<?php
	include 'conf.php';
	session_start();
	$accid = $_SESSION['acc_id'];
	if(isset($_GET['canpetty'])){
		$petid = mysqli_real_escape_string($conn, $_GET['canpetty']);
		$stmt = "UPDATE `petty` set 
				state = 'CPetty'
			where account_id = '$accid' and state = 'UAPetty' and petty_id = '$petid'";
		if ($conn->query($stmt) === TRUE) {
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	}
?>