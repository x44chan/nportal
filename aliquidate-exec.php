<?php
	include 'conf.php';
	session_start();
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['lsub']) && $_SESSION['exec'] == 0){
		$liqstate = 'LIQDATE';
		$counter = $_POST['counter'];
		$date = date("Y-m-d");
		$petid = mysqli_real_escape_string($conn, $_POST['pet_id']);
		$sql = "SELECT * FROM `petty` where petty_id = '$petid' and account_id = '$_SESSION[acc_id]'";
		$data = $conn->query($sql)->fetch_assoc();
		$amount = 0;
		for($i = 0; $i <= $counter; $i++){
			if(isset($_POST['amount'.$i])){		
				$amount += $_POST['amount'.$i];	
			}else{

			}
		}
		if(str_replace(",","", number_format($amount,2)) - str_replace(",","", $data['amount']) > 0) {
			echo '<script type="text/javascript"> alert("Ooops. Huli ka!");// window.location.replace("index.php"); </script>';
		}else{
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
			$_SESSION['exec'] = 1;	
		}
	}else{
		if($_SESSION['level'] == 'EMP'){
    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'ACC') {
    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'TECH') {
    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'HR') {
    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
    	}
	}
?>