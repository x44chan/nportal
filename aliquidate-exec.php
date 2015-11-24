<?php
	include 'conf.php';
	session_start();
	if(isset($_POST['lsub'])){
		$liqstate = 'LIQDATE';
		$counter = $_POST['counter'];
		for($i = 0; $i <= $counter; $i++){
			$stmt = $conn->prepare("INSERT INTO `petty_liqdate` (petty_id, account_id, liqdate, liqtype, liqamount, liqinfo, liqstate) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iisssss", $_POST['pet_id'], $_SESSION['acc_id'], date("Y-m-d"), $_POST['type'.$i], $_POST['amount'.$i], $_POST['trans'.$i], $liqstate);
			if($_POST['type'.$i]!= null){
				if($stmt->execute()){
					echo '<script type = "text/javascript">window.location.replace("employee.php?ac=penpty");</script>';
				}else{
					$conn->error();
				}
			}
		}	
	}
?>