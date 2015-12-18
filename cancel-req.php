<?php
	include 'conf.php';
	session_start();
	$accid = $_SESSION['acc_id'];
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}
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

	if(isset($_GET['canlea'])){
		$petid = mysqli_real_escape_string($conn, $_GET['canlea']);
		if($_SESSION['level'] == 'HR'){
			$state = 'ReqCLeaHR';
		}else{
			$state = 'ReqCLea';
		}
		$stmt = "UPDATE `nleave` set 
				state = 'ReqCLea'
			where account_id = '$accid' and typeoflea != 'Sick Leave' and leave_id = '$petid'";
		if ($conn->query($stmt) === TRUE) {
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("req-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("acc-req-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr-req-app.php?applea"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	}

	if(isset($_GET['hrclea'])){
		$petid = mysqli_real_escape_string($conn, $_GET['hrclea']);
		$stmt = "UPDATE `nleave` set 
				state = 'ReqCLeaHR'
			where state = 'ReqCLea' and typeoflea != 'Sick Leave'  and leave_id = '$petid'";
		if ($conn->query($stmt) === TRUE) {
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("req-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("acc-req-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor-app.php?applea"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr-req-app.php?applea"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	}

	if(isset($_GET['adlea']) && $_SESSION['level'] == 'Admin'){
		$petid = mysqli_real_escape_string($conn, $_GET['adlea']);
		$stmt = "UPDATE `nleave` set 
				state = 'CLea'
			where state = 'ReqCLeaHR' and leave_id = '$petid'";
		if ($conn->query($stmt) === TRUE) {
	    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	}

?>