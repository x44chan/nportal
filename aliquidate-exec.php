<?php
	include 'conf.php';
	session_start();
	if(isset($_POST['lsub'])){
		$liqstate = 'LIQDATE';
		$counter = $_POST['counter'];
		$date = date("Y-m-d");
		for($i = 0; $i <= $counter; $i++){
			$stmt = $conn->prepare("INSERT INTO `petty_liqdate` (petty_id, account_id, liqdate, liqtype, liqamount, liqinfo, liqstate, rcpt, liqothers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iisssssss", $_POST['pet_id'], $_SESSION['acc_id'], $date, $_POST['type'.$i], $_POST['amount'.$i], $_POST['trans'.$i], $liqstate, $_POST['wthrcpt'.$i], $_POST['others'.$i]);
			if($_POST['type'.$i]!= null){
				if($stmt->execute()){
					if($_SESSION['level'] == 'EMP'){
			    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
			    	}elseif ($_SESSION['level'] == 'ACC') {
			    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
			    	}elseif ($_SESSION['level'] == 'TECH') {
			    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
			    	}elseif ($_SESSION['level'] == 'HR') {
			    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
			    	}
				}else{
					$conn->error();
				}
			}
		}	
	}
?>