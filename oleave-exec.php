<?php
	session_start();
	include('conf.php');	
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['leasubmit'])){
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$datefile = date("Y-m-d");
		$twodaysred = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
		$nameofemployee = $_POST['nameofemployee'];
		$datehired = $_SESSION['datehired'];
		$deprt = $_POST['deprt'];
		$posttile = $_POST['posttile'];
		$dateofleavfr = $_POST['dateofleavfr'];
		$dateofleavto = $_POST['dateofleavto'];
		$numdays = $_POST['numdays'];
		if($_POST['typeoflea'] == 'Others'){
			$typeoflea = $_POST['typeoflea'];
			$othersl = $_POST['othersl'];
		}else{
			$typeoflea = $_POST['typeoflea'];
			$othersl = '';
		}
		$restric = 0;
		if($typeoflea == 'Vacation Leave'){
			if(date("Y-m-d", strtotime("+7 days", strtotime($datefile))) > date("Y-m-d", strtotime($dateofleavfr)) ){
				$restric = 1;
			}
		}
		$reason = $_POST['leareason'];
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else if($post == "service technician"){
			$state = 'UATech';	
		}else{
			$state = 'UA';	
		}

		$stmt = $conn->prepare("INSERT into `nleave` (account_id, datefile, nameofemployee, datehired, deprt, posttile, dateofleavfr, dateofleavto, numdays, typeoflea, othersl, reason, twodaysred, state) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isssssssssssss", $accid, $datefile, $nameofemployee, $datehired, $deprt, $posttile, $dateofleavfr, $dateofleavto, $numdays, $typeoflea, $othersl, $reason, $twodaysred, $state);
		if($restric == 0){
			$stmt->execute();
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penlea"); </script>';
	    	}
			$conn->close();	
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("employee.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("accounting.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("techsupervisor.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("hr.php?ac=penlea"); </script>';
	    	}
		}
	}
	if(isset($_GET['adleave'])){
		$balid = mysql_escape_string($_GET['leavebal_id']);
		if($_GET['adleave'] == 'a'){
			$sql = "UPDATE nleave_bal set state = 'AAdmin' where leavebal_id = '$balid' and state = 'UA'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}elseif($_GET['adleave'] == 'd'){

		}
	}
?>