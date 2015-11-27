<?php
	include 'conf.php';
	session_start();
	if(isset($_POST['wthrcpt'])){
		echo 1;
	}
	if(isset($_POST['lsub'])){
		$liqstate = 'LIQDATE';
		$counter = $_POST['counter'];
		for($i = 0; $i <= $counter; $i++){
			$stmt = $conn->prepare("INSERT INTO `petty_liqdate` (petty_id, account_id, liqdate, liqtype, liqamount, liqinfo, liqstate, rcpt, liqothers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iisssssss", $_POST['pet_id'], $_SESSION['acc_id'], date("Y-m-d"), $_POST['type'.$i], $_POST['amount'.$i], $_POST['trans'.$i], $liqstate, $_POST['wthrcpt'.$i], $_POST['others'.$i]);
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