<?php
	if(isset($_GET['promotion'])){
		include 'conf.php';
		$accid = mysql_escape_string($_GET['account_id']);
		if($_GET['promotion'] == 'a'){
			$sql = "UPDATE login set hrchange = '0' where account_id = '$accid' and hrchange != '0'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}elseif($_GET['promotion'] == 'd'){
			$stmts = "SELECT * FROM `login` where account_id = '$accid' and hrchange != '0'";
			$data = $conn->query($stmts)->fetch_assoc();
			$emcatergory = $data['oldpost'];
			if($data['empcatergory'] == 'Regular'){
				$datex = ', regdate = ""';
			}elseif($data['empcatergory'] == 'Probationary'){
				$datex = ', probidate = ""'; 
			}else{
				$datex = ', contractdate = ""';
			}
			$sql = "UPDATE login set empcatergory = '$emcatergory', hrchange = '0' $datex where account_id = '$accid' ";
			if ($conn->query($sql) === TRUE) {	 		
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		  	}
		}
	}


?>


<?php
if(isset($_POST['regsubmit'])){
		include('conf.php');
		$uname = mysqli_real_escape_string($conn,$_POST['reguname']);
		$pw = mysqli_real_escape_string($conn,$_POST['regpword']);
		$cpw = mysqli_real_escape_string($conn,$_POST['regcppword']);
		$level = mysqli_real_escape_string($conn,$_POST['level']);
		$sql = "SELECT * FROM `login` where `uname` = '$uname'";
		$result = $conn->query($sql);
		if($pw != $cpw){
			header('location: admin.php?suc=3');
		}else if($result->num_rows > 0){
			$error =  '<div class="alert alert-warning" align = "center">
						<a href="#"  class="close" data-dismiss="alert" aria-label="close" >&times;</a>
						<strong>Warning!</strong> Username already exists.
					</div>';
			header("location: admin.php?suc=0");
			unset($_POST['regsubmit']);
			$conn->close();
		}else{
			$error = '<div class="alert alert-success" align = "center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>Success!</strong> New user added.
						</div>';
			$stmt = $conn->prepare("INSERT into `login` (uname, pword, fname, lname, level, position, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssss", $uname, $pw, $regfname, $reglname, $level, $regpos, $regdep);
			$stmt->execute();			
			header("location: admin.php?suc=1");
			unset($_POST['regsubmit']);
			$conn->close();
	 
	 }

}

?>

<?php
if(isset($_POST['hreg'])){
		include('conf.php');
		session_start();
		$uname = mysqli_real_escape_string($conn,$_POST['reguname']);
		$pw = mysqli_real_escape_string($conn,$_POST['regpword']);
		$cpw = mysqli_real_escape_string($conn,$_POST['regcppword']);
		$level = mysqli_real_escape_string($conn,$_POST['level']);
		$sql = "SELECT * FROM `login` where `uname` = '$uname'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
        	echo '<script type="text/javascript">window.location.replace("hr.php"); </script>';
			$_SESSION['err'] = 'ex';
			$conn->close();
		}else{			
			$stmt = $conn->prepare("INSERT into `login` (uname, pword, level) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $uname, $pw, $level);
			$stmt->execute();	
			echo '<script type = "text/javascript">alert("Registration succesful")</script>';
       		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penot"); </script>';
			$conn->close();
	 
	 }

}
?>