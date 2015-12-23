<?php
	include 'conf.php';
	session_start();
	$accid = $_SESSION['acc_id'];
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}
	if(isset($_GET['fullpay']) && $_SESSION['level'] == 'Admin'){
		$cutid = mysqli_real_escape_string($conn, $_GET['fullpay']);
		$accid = mysqli_real_escape_string($conn, $_GET['accid']);
		$loanid = mysqli_real_escape_string($conn, $_GET['loanid']);
		$full = date("Y-m-d");
		$stmt = "UPDATE `loan_cutoff` set 
				 state = 'Full', full = '$full'
			where account_id = '$accid'  and cutoff_id = '$cutid' and loan_id = '$loanid' and state = 'CutOffPaid'";
		if ($conn->query($stmt) === TRUE) {
			$stmtss = "SELECT * FROM `loan_cutoff` where loan_id = '$loanid' and cutoff_id = '$cutid'";
			$datas = $conn->query($stmtss)->fetch_assoc();
			$cutoffdate = $datas['cutoffdate'];
			$enddate = $datas['enddate'];
			$stmtx = "DELETE FROM `loan_cutoff` where account_id = '$accid' and loan_id = '$loanid' and '$enddate' BETWEEN '$cutoffdate' and enddate and state = 'CutOffPaid'";
			if ($conn->query($stmtx) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin-emprof.php?loan='.$loanid.'&accid='.$accid.'"); </script>';
			}
			
		}
	}
	if(isset($_GET['canloan']) && $_SESSION['level'] == 'Admin'){
		$cutid = mysqli_real_escape_string($conn, $_GET['canloan']);
		$accid = mysqli_real_escape_string($conn, $_GET['accid']);
		$loanid = mysqli_real_escape_string($conn, $_GET['loanid']);
		$stmt = "UPDATE `loan_cutoff` set 
				state = 'Cancel'
			where account_id = '$accid'  and cutoff_id = '$cutid'";
		if ($conn->query($stmt) === TRUE) {
			$stmtss = "SELECT * FROM `loan_cutoff` where loan_id = '$loanid' ORDER BY cutoff_id desc limit 1";
			$datas = $conn->query($stmtss)->fetch_assoc();
			$day = substr($datas['cutoffdate'], 8, 10);
			$datex = substr($datas['cutoffdate'], 5, 2);
			$cuts = 15;
			$fif = 15;
			if($datex == '02'){
				$cuts -= 2;
				$fif -= 2;
			}			
			if($day < '16'){
				$day = '16';
				$end = 't';
			}else{
				$day = '01';
				$end = '15';
			}
			$state = 'CutOffPaid';
			$date = date("Y-m-".$day, strtotime('+'.$cuts.' days', strtotime($datas['enddate'])));
			$enddate = date("Y-m-".$end, strtotime('+'.$cuts.' days', strtotime($datas['enddate'])));
			$stmt = $conn->prepare("INSERT INTO `loan_cutoff` (loan_id, account_id, cutamount, cutoffdate, state, duration, enddate) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iisssss", $loanid, $accid, $datas['cutamount'], $date, $state, $duration, $enddate);
			$stmt->execute();
			echo '<script type="text/javascript">window.location.replace("admin-emprof.php?loan='.$loanid.'&accid='.$accid.'"); </script>';
		}
	}
	if(isset($_GET['canpetty'])){
		$petid = mysqli_real_escape_string($conn, $_GET['canpetty']);
		$stmt = "UPDATE `petty` set 
				state = 'CPetty'
			where account_id = '$accid' and (state = 'UAPetty' or state = 'UATransfer') and petty_id = '$petid'";
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