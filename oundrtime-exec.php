<?php
	session_start();
	include('conf.php');	
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['unsubmit'])){
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$undate = date("Y-m-d");
		$unname = $_POST['unename'];
		$unpost = $_POST['unpost'];
		$undept = $_POST['undept'];
		$undatereq = $_POST['undatereq'];
		$undertimefr = $_POST['untimefr'];
		$undertimeto = $_POST['untimeto'];
		$unreason = $_POST['unreason'];
		$unumofhrs = $_POST['unumofhrs'];

		$state = 'UAAdmin';
		if(date("D") == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		if(date("Y-m-d", strtotime($minus, strtotime($undate))) > date("Y-m-d", strtotime($undatereq)) || date("Y-m-d", strtotime($undate)) < date("Y-m-d", strtotime($undatereq))){
			$state = 'UALate';
			$restric = 0;	
		}
		$twodaysred = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
		$stmt = $conn->prepare("INSERT into `undertime` (account_id, twodaysred, datefile, name, position, department, dateofundrtime, undertimefr, undertimeto, reason, state, numofhrs) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isssssssssss",$accid, $twodaysred, $undate, $unname, $unpost, $undept, $undatereq, $undertimefr, $undertimeto, $unreason, $state, $unumofhrs);
		$stmt->execute();
		if($_SESSION['level'] == 'EMP'){
    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penundr"); </script>';
    	}elseif ($_SESSION['level'] == 'ACC') {
    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penundr"); </script>';
    	}elseif ($_SESSION['level'] == 'TECH') {
    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penundr"); </script>';
    	}elseif ($_SESSION['level'] == 'HR') {
    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penundr"); </script>';
    	}
		$conn->close();
	}

?>