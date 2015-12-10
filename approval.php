<?php	
	date_default_timezone_set('Asia/Manila');
	session_start();
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		if($_SESSION['level'] == 'EMP'){
			header("location: index.php");
		}
	}else{
			header("location: index.php");
	
	}
	
	include('conf.php');
	if(isset($_GET['overtime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['overtime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		
		if($_SESSION['level'] == 'HR' && ($state == 'AHR' || $state == 'DAHR')){
			$date = date('Y-m-d h:i A');
			if(isset($_SESSION['bypass'])){
				$xstate = '(state = "UA"  or state = "UATech")';
			}else{
				$xstate = ' state = "UA" ';
			}
			unset($_SESSION['bypass']);
			$sql = "UPDATE overtime set state = '$state',datehr = '$date',dareason = '$dareason' where overtime_id = $id and $xstate";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';	
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else if($_SESSION['level'] == 'TECH' && ($state == 'UA' || $state == 'DATECH')){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE overtime set state = '$state',dateacc = '$date',dareason = '$dareason' where overtime_id = $id and state = 'UATech'";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['ac'].'"); </script>';		
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}elseif($_SESSION['level'] == 'Admin' && ($state == 'AAdmin' || $state == 'DAAdmin')){
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state = 'UA' or state = 'UATech')";
				$link = "?bypass";
			}else{
				$states = "(state  = 'AHR' or state = 'UALate')";
				$link = "";
			}
			$otlate = "";
			if(isset($_GET['late'])){
				$states = "state = 'UALate'";
				$otlate = ', otlate = "1"';
				if(isset($_GET['post'])){
					$state = 'UATech';
				}else{
					$state = 'UA';
				}
				if(isset($_GET['level'])){
					$state = 'AAdmin';
				}
			}
			$sql = "UPDATE overtime set state = '$state' $otlate where overtime_id = $id and $states";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php'.$link.'"); </script>';
			}else{
				die("Connection error:". $conn->connect_error);
			}		
		}else{
			if($_SESSION['level'] == 'Admin'){
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}elseif ($_SESSION['level'] == 'TECH') {
				echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['ac'].'"); </script>';
			}elseif ($_SESSION['level'] == 'HR') {
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';
			}
		}
	}
?>
<?php
	if(isset($_POST['leaveapp'])){
		$leapay = mysql_escape_string($_POST['payment']);
		if(isset($_POST['fitowork'])){
			$ftowork = mysql_escape_string($_POST['fitowork']);
		}else{
			$ftowork = "";
		}
		if($_SESSION['level'] == 'HR'){
			$upstate = 'AHR';
			$state = 'UA';
		}elseif($_SESSION['level'] == "TECH"){
			$upstate = 'UA';
			$state = 'UATech';
		}
		$oid = mysql_escape_string($_POST['leave_id']);
		$date = date('Y-m-d h:i A');
		$sql = "UPDATE nleave set 
					state = '$upstate', leapay = '$leapay', ftowork = '$ftowork', datehr = '$date'
				where leave_id = '$oid' and state = '$state'";
		if($conn->query($sql) == TRUE){
			echo '<script type="text/javascript">window.location.replace("hr.php?ac=penlea"); </script>';
		}else{
			die("Connection error:". $conn->connect_error);
		}	
	}

?>
<?php
	include('conf.php');
	if(isset($_GET['officialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['officialbusiness_id']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);		
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if($_SESSION['level'] == 'HR' && ($state == 'AHR' || $state == 'DAHR')){
			$date = date('Y-m-d h:i A');
			if(isset($_SESSION['bypass'])){
				$xstate = '(state = "UA"  or state = "UATech")';
			}else{
				$xstate = ' state = "UA" ';
			}
			unset($_SESSION['bypass']);
			$sql = "UPDATE officialbusiness set state = '$state',datehr = '$date',dareason = '$dareason'  where officialbusiness_id = $id and $xstate";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else if($_SESSION['level'] == 'TECH' && ($state == 'UA' || $state == 'DATECH')){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE officialbusiness set state = '$state',dateacc = '$date',dareason = '$dareason'  where officialbusiness_id = $id and state = 'UATech'";			
			if($conn->query($sql) == TRUE){				
				echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['ac'].'"); </script>';
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else{
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			$sql = "UPDATE officialbusiness set state = '$state' where officialbusiness_id = $id and $states";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php'.$link.'"); </script>';
			}else{
			die("Connection error:". $conn->connect_error);
		}		
	}		
	}
?>


<?php
	include('conf.php');
	if(isset($_GET['undertime'])){
		$id = mysqli_real_escape_string($conn, $_GET['undertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if($_SESSION['level'] == 'HR'){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE undertime set state = '$state',datehr = '$date',dareason = '$dareason'  where undertime_id = $id and state = 'UA'";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';	
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else if($_SESSION['level'] == 'TECH'){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE undertime set state = '$state',dateacc = '$date',dareason = '$dareason'  where undertime_id = $id and state = 'UATech'";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['ac'].'"); </script>';
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else{
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			$sql = "UPDATE undertime set state = '$state' where undertime_id = $id and $states";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php'.$link.'"); </script>';
			}else{
			die("Connection error:". $conn->connect_error);
		}		
	}
}
?>


<?php
	include('conf.php');
	if(isset($_GET['leave'])){
		$id = mysqli_real_escape_string($conn, $_GET['leave']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if($_SESSION['level'] == 'HR'){
			$date = date('F d, Y h:i A');
			$sql = "UPDATE nleave set state = '$state',datehr = '$date',dareason = '$dareason'  where leave_id = $id and state = 'UA'";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';		
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else if($_SESSION['level'] == 'TECH'){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE nleave set state = '$state',dateacc = '$date',dareason = '$dareason'  where leave_id = $id and state = 'UATech'";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['ac'].'"); </script>';		
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}else{
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			$sql = "UPDATE nleave set state = '$state' where leave_id = $id and $states";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php'.$link.'"); </script>';
			}else{
			die("Connection error:". $conn->connect_error);
		}		
	}			
	}
?>