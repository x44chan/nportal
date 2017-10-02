<?php	
	date_default_timezone_set('Asia/Manila');
	session_start();
	include("conf.php");
	include("savelogs.php");
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
		$xxxss = "SELECT * FROM overtime where overtime_id = '$id'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		
		if($_SESSION['level'] == 'HR' && ($state == 'AHR' || $state = 'DAHR')){
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
				if($state == 'AHR'){
					savelogs("Approve Overtime", $xxxsss['nameofemp'] . ' Date: ' . $xxxsss['dateofot'] . ' From: ' . $xxxsss['startofot'] . ' To: ' . $xxxsss['endofot']);	
				}else{
					savelogs("Disapprove Overtime", $xxxsss['nameofemp'] . ' Date: ' . $xxxsss['dateofot'] . ' From: ' . $xxxsss['startofot'] . ' To: ' . $xxxsss['endofot'] . ' Reason: ' . $xxxsss['dareason']);	
				}
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
				$states = "(state = 'UAAdmin' or state = 'UALate' or state = 'AHR')";
				$link = "";
			}
			$otlate = "";
			if(isset($_GET['late'])){
				$states = "state = 'UALate'";
				$otlate = ', otlate = "1"';
				if(isset($_GET['post'])){
					$state = 'UA';
				}else{
					$state = 'UA';
				}
				if(isset($_GET['level'])){
					$state = 'UA';
				}
			}
			if($state == 'DAAdmin'){
				$dareason = "dareason = '$dareason',";
				savelogs("Disapprove Overtime", $xxxsss['nameofemp'] . ' Date: ' . $xxxsss['dateofot'] . ' From: ' . $xxxsss['startofot'] . ' To: ' . $xxxsss['endofot'] . ' Reason: ' . $xxxsss['dareason']);	
			}else{
				$dareason = "";
			}
			if($state == 'AAdmin'){
				$state = 'AAdmin';
				savelogs("Approve Overtime", $xxxsss['nameofemp'] . ' Date: ' . $xxxsss['dateofot'] . ' From: ' . $xxxsss['startofot'] . ' To: ' . $xxxsss['endofot']);	
			}
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE overtime set state = '$state', $dareason datehr = '$date' $otlate where overtime_id = $id and $states";
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
	if(isset($_GET['hol'])){	
		$id = mysqli_real_escape_string($conn, $_GET['hol']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$xxxss = "SELECT * FROM holidayre,login where holidayre_id = '$id' and holidayre.account_id = login.account_id";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = null;
		}
		
		if($_SESSION['level'] == 'HR' && ($state == 'AHR' || $state = 'DAHR')){
			$date = date('Y-m-d h:i A');
			if($state == 'AHR'){
				$state = 1;
				savelogs("Approve Holiday", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . ' Date: ' . $xxxsss['holiday'] . ' Type: ' . $xxxsss['type']);	
			}else{
				$state = -1;
				savelogs("Disapprove Holiday", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . ' Date: ' . $xxxsss['holiday'] . ' Type: ' . $xxxsss['type'] . ' Reason: ' . $dareason);
			}
			unset($_SESSION['bypass']);
			$sql = "UPDATE holidayre set state = '$state',datehr = '$date',dareason = '$dareason' where holidayre_id = $id and state = 0";			
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['ac'].'"); </script>';	
			}else{
				die("Connection error:". $conn->connect_error);
			}
		}elseif($_SESSION['level'] == 'Admin' && ($state == 'AAdmin' || $state == 'DAAdmin')){
			if($state == 'DAAdmin'){
				$dareason = $_GET['dareason'];
			}else{
				$dareason = null;
			}
			if($state == 'AAdmin'){
				$state = 2;
				savelogs("Approve Holiday", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . ' Date: ' . $xxxsss['holiday'] . ' Type: ' . $xxxsss['type']);	
			}else{
				$state = -2;
				savelogs("Disapprove Holiday", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . ' Date: ' . $xxxsss['holiday'] . ' Type: ' . $xxxsss['type'] . ' Reason: ' . $dareason);
			}
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE holidayre set state = '$state',datehr = '$date',dareason = '$dareason' where holidayre_id = $id and state = 1";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
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
		if(isset($_POST['accadmin']) && $_POST['accadmin'] > 0){
			$upstate = 'AAdmin';
		}
		$oid = mysql_escape_string($_POST['leave_id']);
		$date = date('Y-m-d h:i A');
		$xxxss = "SELECT * FROM nleave where leave_id = '$oid'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		$sql = "UPDATE nleave set 
					state = '$upstate', leapay = '$leapay', ftowork = '$ftowork', datehr = '$date'
				where leave_id = '$oid' and state = '$state'";
		if($conn->query($sql) == TRUE){
			echo '<script type="text/javascript">window.location.replace("hr.php?ac=penlea"); </script>';
			savelogs("Approve Leave", $xxxsss['nameofemployee'] . ' Date From: ' . $xxxsss['dateofleavfr'] . ' To: ' . $xxxsss['dateofleavto']. ' Payment: ' . $leapay);	
		}else{
			die("Connection error:". $conn->connect_error);
		}	
	}

?>
<?php
	include('conf.php');
	if(isset($_GET['officialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['officialbusiness_id']);
		$xxxss = "SELECT * FROM officialbusiness where officialbusiness_id = '$id'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		$state = mysqli_real_escape_string($conn, $_GET['approve']);		
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if(($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'ACC') && ($state == 'AHR' || $state == 'DAHR')){
			$date = date('Y-m-d h:i A');
			if(isset($_SESSION['bypass'])){
				$xstate = '(state = "UA"  or state = "UATech")';
			}else{
				$xstate = ' state = "UA" ';
			}
			unset($_SESSION['bypass']);
			$sql = "UPDATE officialbusiness set state = '$state',datehr = '$date',dareason = '$dareason'  where officialbusiness_id = $id and $xstate";			
			if($conn->query($sql) == TRUE){
				if($state == 'DAHR'){
					savelogs("Disapprove Official Business", $xxxsss['obename'] . " Reason: " . $dareason);
				}
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
		}else if($_SESSION['level'] == 'Admin' && ($state == 'AAdmin' || $state == 'DAAdmin')){
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			if($state == 'DAAdmin'){
				$dareason = $_GET['dareason'];
			}else{
				$dareason = "";
			}
			if(isset($_GET['ua']) && $state = 'AAmin'){
				$state = 'UA';
			}
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE officialbusiness set state = '$state', dareason = '$dareason', datehr = '$date' where officialbusiness_id = $id and (state = 'UAAdmin' or state = 'UALate' or state = 'AHR')";
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
		$xxxss = "SELECT * FROM undertime where undertime_id = '$id'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if($_SESSION['level'] == 'HR' && ($state == 'AHR' || $state == 'DAHR')){
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE undertime set state = '$state',datehr = '$date',dareason = '$dareason'  where undertime_id = $id and state = 'UA'";			
			if($conn->query($sql) == TRUE){
				savelogs("Approve Undertime", $xxxsss['name'] . " ID: " . $id);
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
		}else if($_SESSION['level'] == 'Admin'){
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			if(isset($_GET['late'])){
				$undrlate = ', undlate = "1"';
			}else{
				$undrlate = "";
			}
			if($state == 'AAdmin'){
				$state = 'AAdmin';
			}
			if($state == 'DAAdmin'){
				$dareason = $_GET['dareason'];
			}else{
				$dareason = "";
			}
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE undertime set state = '$state', dareason = '$dareason', datehr = '$date' $undrlate where undertime_id = $id and (state = 'UAAdmin' or state = 'UALate' or state = 'AHR')";
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
		$xxxss = "SELECT * FROM nleave where leave_id = '$id'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		if(isset($_GET['dareason'])){
			$dareason = mysqli_real_escape_string($conn, $_GET['dareason']);
		}else{
			$dareason = "";
		}
		if($_SESSION['level'] == 'HR'){
			$date = date('Y-m-d h:i A');
			if($state == 'DAHR'){
				$accadmin = " and accadmin is null";
				savelogs("Disapprove Leave ", $xxxsss['nameofemployee'] . " Reason: " . $dareason);
			}else{
				$accadmin = "";
			}
			if(isset($_POST['accadmin']) && $_POST['accadmin'] > 0){
				$state = 'AAdmin';
			}
			$sql = "UPDATE nleave set state = '$state',datehr = '$date',dareason = '$dareason'  where leave_id = $id and state = 'UA' $accadmin";			
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
		}else if($_SESSION['level'] == 'Admin'){
			if(isset($_GET['bypass']) && $_GET['bypass'] == '1'){
				$states = "(state  = 'AHR' or state like 'UA%')";
				$link = "?bypass";
			}else{
				$states = "state  = 'AHR'";
				$link = "";
			}
			if(isset($_GET['sched'])){
				$states = 'state = "UAAdmin"';
				$state = 'UA';
				$apadmin = ', accadmin = "1"';
			}else{
				$apadmin = "";
			}
			if($state == 'DAAdmin'){
				$dareason = $_GET['dareason'];
			}else{
				$dareason = "";
			}
			savelogs("Approve Leave", $xxxsss['nameofemployee'] . ' Date From: ' . $xxxsss['dateofleavfr'] . ' To: ' . $xxxsss['dateofleavto']. ' Payment: ' . $xxxsss['leapay']);	
			$date = date('Y-m-d h:i A');
			$sql = "UPDATE nleave set state = '$state',dareason = '$dareason', datehr = '$date' $apadmin where leave_id = $id and (state = 'UAAdmin' or state = 'UALate' or state = 'AHR')";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php'.$link.'"); </script>';
			}else{
			die("Connection error:". $conn->connect_error);
		}		
	}			
	}
?>

<?php
	if(isset($_GET['proj_table']) && isset($_GET['project_id']) && $_SESSION['level'] == 'Admin'){
		if($_GET['proj_table'] == 'a'){
			$state = '1';
			$isAp = '1';
			$not = "Approved";
		}else{
			$state = '2';
			$isAp = '2';
			$not = "Disapproved";
		}
		$sql = "UPDATE project set state = '$state', isAp = '$isAp' where project_id = '" . mysqli_real_escape_string($conn, $_GET['project_id']) . "'";			
		if($conn->query($sql) == TRUE){
			echo '<script type="text/javascript"> alert("Project '.$not.'"); window.location.replace("admin.php"); </script>';		
		}else{
			die("Connection error:". $conn->connect_error);
		}
	}
?>