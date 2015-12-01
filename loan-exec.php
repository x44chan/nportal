<?php
	include 'conf.php';
	session_start();
	if(isset($_POST['updteloan'])){
		$o = mysql_escape_string($_POST['cashadvid']);
		$accid = mysql_escape_string($_POST['accid']);
		$appamount = mysql_escape_string($_POST['uploan']);
		$sql ="UPDATE loan set 
	   		loanamount = '$appamount'
	    where loan_id = '$o' and account_id = '$accid' and state = 'ALoan' and loanamount != appamount"; 

	 	if ($conn->query($sql) === TRUE) {	 		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	} 
	}

?>
<?php
	if(isset($_GET['loanss'])){
		$o = mysql_escape_string($_GET['loanss']);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE loan set 
	   		state = 'DECLoan'
	    where loan_id = '$o' and account_id = '$accid'"; 

	 	if ($conn->query($sql) === TRUE) {	 		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	} 

	}
?>
<?php 
	//loan	
	if(isset($_POST['caapp'])){
		$o = mysql_escape_string($_POST['loanid']);
		$accid = mysql_escape_string($_POST['accid']);
		$appamount = mysql_escape_string($_POST['loanamount']);
		$sql ="UPDATE loan set 
	   		state = 'ARcvLoan', appamount = '$appamount', loanamount = '$appamount'
	    where loan_id = '$o' and account_id = '$accid' and state = 'UALoan'"; 
	    $cutoffdate = $_POST['cutoffyear'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
	    $cutamount = $_POST['loanamount'] / ($_POST['upduration'] * 2);
	    $duration = $_POST['upduration'] . ' Months';
	    $enddate = date("Y-m-d", strtotime($duration, strtotime($cutoffdate)));
	    
	 	if ($conn->query($sql) === TRUE) {	 
	 		$sql2 ="UPDATE loan_cutoff set 
	   		state = 'CutOffPaid', cutamount = '$cutamount', cutoffdate = '$cutoffdate', enddate = '$enddate', duration = '$duration'
			    where loan_id = '$o' and account_id = '$accid' and state = 'UALoanCut'"; 
			if($conn->query($sql2) === TRUE){
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	} 
	}
	if(isset($_GET['cashadv'])){
		$o = mysql_escape_string($_GET['cashadv']);
		$rcve_code = random_string(4);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE cashadv set 
	   		state = 'ARcvCash', rcve_code = '$rcve_code'
	    where cashadv_id = '$o' and account_id = '$accid' and state = 'ACash'";  
	    if ($conn->query($sql) === TRUE) {	 		
			echo '<script type="text/javascript">window.location.replace("employee.php?ac=penca"); </script>';
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	}
	if(isset($_POST['cashadvre'])){
		$pet_id = $_POST['pet_id'];
		$rcve_code = $_POST['rcve_code'];
		$query = "SELECT * FROM `cashadv` where cashadv_id = '$pet_id' and rcve_code = '$rcve_code'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE cashadv set 
		   		state = 'ACashReleased'
		    where cashadv_id = '$pet_id' and state = 'ARcvCash' and rcve_code = '$rcve_code'"; 
		 	if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		 		}else if($_SESSION['level'] == 'ACC'){
		 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		 		}
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}  
		}else{
			$_SESSION['err'] = 'Incorrect Code';
			if($_SESSION['level'] == 'Admin'){
				echo '<script type="text/javascript">window.location.replace("admin.php?release=1&petty_id='.$pet_id.'"); </script>';
			}else if($_SESSION['level'] == 'ACC'){
				echo '<script type="text/javascript">window.location.replace("accounting-petty.php?release=1&petty_id='.$pet_id.'"); </script>';
			}
		}
	}
?>

<?php
	if(isset($_GET['loan_cutoff'])){
		$loanid = mysql_escape_string($_GET['loan_id']);
		$cutoff = mysql_escape_string($_GET['cutoff_id']);
		if($_GET['loan_cutoff'] == 'a'){
			$sql = "UPDATE `loan_cutoff` set
						state = 'CutOffPaid'
					where loan_id = '$loanid' and cutoff_id = '$cutoff'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}
		if($_GET['loan_cutoff'] == 'd'){
			$sql = "UPDATE `loan_cutoff` set
						state = 'DALoan'
					where loan_id = '$loanid' and cutoff_id = '$cutoff'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}
	}

?>

<?php
	if(isset($_GET['cashadvact']) && $_GET['cashadvact'] == 'd'){
		$id = mysql_escape_string($_GET['cashadv_id']);
		$sql = "UPDATE `cashadv` set
					state = 'DACA'
			where cashadv_id = '$id'";
		if ($conn->query($sql) === TRUE) {
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		}
	}

	if(isset($_GET['loadact']) && $_GET['loadact'] == 'd'){
		$id = mysql_escape_string($_GET['loan_id']);
		$sql = "UPDATE `loan` set
					state = 'DALoan'
			where loan_id = '$id'";
		$sql2 = "UPDATE `loan_cutoff` set
					state = 'DALoan'
			where loan_id = '$id'";
		if ($conn->query($sql) === TRUE) {
			if ($conn->query($sql2) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}
	}

?>