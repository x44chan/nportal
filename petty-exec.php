<?php
	include("conf.php");
	session_start();
	include('savelogs.php');
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}
	function random_string($length) {
	    $key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));

	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }

	    return $key;
	}
	if(isset($_GET['loan'])){
		$o = mysql_escape_string($_GET['loan']);
		$rcve_code = random_string(4);
		$accid = $_SESSION['acc_id'];
		$rcve_code = random_string(4);
		$sql ="UPDATE loan set 
	   		state = 'ARcvCashCode', rcve_code = '$rcve_code'
	    where loan_id = '$o' and account_id = '$accid'";  
	   	savelogs("Receive Loan", "Loan #: " . $o);
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
	  	$conn->close();
	}
	if(isset($_POST['submitpetty'])){		
		$pettyamount = $_POST['pettyamount'];
		$petty_id = $_POST['petty_id'];
		if(isset($_POST['appart']) && $_POST['appart'] != ''){
			$particular = $_POST['appart'];
		}
		if(isset($_POST['transct']) && $_POST['transct'] != ""){
			$trans = $_POST['transct'];
			$checkk = " Check #: " . $trans;
		}else{
			$trans = null;
			$checkk = ' ' . $_POST['appart'] . ' ';
		}
		if($_SESSION['level'] == 'Admin'){
			$source = $_POST['source'];
			if($source == 'Eliseo' || $source == 'Sharon'){
				$state = 'AAAPettyReceive';
			}else{
				$state = 'AAPetty';
			}
			if($state == 'AAPetty'){
				$checkk = "";
			}
			savelogs("Approve Petty", "Petty #: " . $petty_id . " Source: " . $source . $checkk);
			$appdate = ", appdate = now() ";
		}else if($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR'){
			$state = 'AAAPettyReceive';
			$source = 'Accounting';
			savelogs("Approve Petty", "Petty #: " . $petty_id . $checkk);
			$appdate = "";
		}
		$sql ="UPDATE petty set 
	   		amount = '$pettyamount', source = '$source', state = '$state', transfer_id = '$trans', particular = '$particular' $appdate
	    where petty_id = '$petty_id'"; 
	 	if ($conn->query($sql) === TRUE) {	 		
	    	if($_SESSION['level'] == 'Admin'){
	    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
	    	}elseif($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR'){
	    		echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	} 
	  	$conn->close();
	}
	if(isset($_GET['pettyac']) && $_GET['pettyac'] == 'd'){	
		$petty_id = $_GET['petty_id'];
		$sql ="UPDATE petty set 
			state = 'DAPetty'
	    where petty_id = '$petty_id'"; 
	    savelogs("Disapprove Petty", "Petty #: " . $petty_id);
	 	if ($conn->query($sql) === TRUE) {
	 		if($_SESSION['level'] == 'Admin'){
	    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
	 		}else if($_SESSION['level'] == 'ACC'){
	 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
	 		}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  
	  	$conn->close();
	}
	
?>
<?php 
	//cash advance
	if(isset($_POST['caapp'])){
		$o = mysql_escape_string($_POST['cashadvid']);
		$accid = mysql_escape_string($_POST['accid']);
		$source = mysqli_real_escape_string($conn, $_POST['loan_source']);
		$sql ="UPDATE cashadv set 
	   		state = 'ACash', source = '$source'
	    where cashadv_id = '$o' and account_id = '$accid' and state = 'UACA'"; 
	    savelogs("Approve Cash Advance", "Cash Advance #: " .$o . ", Source -> " . $source);
	 	if ($conn->query($sql) === TRUE) {	 		
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	} 
	  	$conn->close();
	}
	if(isset($_GET['cashadv'])){
		$o = mysql_escape_string($_GET['cashadv']);
		$rcve_code = random_string(4);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE cashadv set 
	   		state = 'ARcvCash', rcve_code = '$rcve_code'
	    where cashadv_id = '$o' and account_id = '$accid' and state = 'ACash'";  
	    savelogs("Receive Cash Advance", "Cash Advance #: " .$o);
	    if ($conn->query($sql) === TRUE) {	 		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penca"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
	  	$conn->close();
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
		    savelogs("Release Cash Advance", "Cash Advance #: " .$pet_id);
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
		$conn->close();
	}
?>
<?php
	//employee receive
	if(isset($_GET['o']) && isset($_GET['acc'])){
		
		$rcve_code = random_string(4);
		$o = mysql_escape_string($_GET['o']);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE petty set 
	   		state = 'AAPettyReceived', rcve_code = '$rcve_code'
	    where petty_id = '$o' and account_id = '$accid' and state = 'AAAPettyReceive'"; 
	    savelogs("Receive Petty", "Petty #: " .$o);
	 	if ($conn->query($sql) === TRUE) {	 		
		    if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac='.$_GET['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac='.$_GET['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_GET['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_GET['acc'].'"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  
	  	$conn->close();
	}

?>

<?php
	//petty done
	if(isset($_GET['pettydone']) && isset($_GET['petty_id'])){
		$o = mysql_escape_string($_GET['petty_id']);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE petty set 
	   		state = 'AAPettyRep'
	    where petty_id = '$o' and state = 'AAPettyReceived'"; 
	    savelogs("Done Petty", "Petty #: " .$o);
	 	if ($conn->query($sql) === TRUE) {	 		
	    	if($_SESSION['level'] == 'Admin'){
	    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
	 		}else if($_SESSION['level'] == 'ACC'){
	 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
	 		}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  
	  	$conn->close();
	}

?>

<?php
	//release with code
	if(isset($_POST['codesub'])){
		$accid = $_SESSION['acc_id'];
		$pet_id = $_POST['pet_id'];
		$rcve_code = $_POST['rcve_code'];
		$releasedate = date("Y-m-d");
		$query = "SELECT * FROM `petty` where petty_id = '$pet_id' and rcve_code = '$rcve_code'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE petty set 
		   		state = 'AAPettyRep', releasedate = '$releasedate'
		    where petty_id = '$pet_id' and state = 'AAPettyReceived' and rcve_code = '$rcve_code'"; 
		    savelogs("Release Petty", "Petty #: " . $pet_id);
		 	if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		 		}else if($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR'){
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
		$conn->close();
	}

?>

<?php
	//release with code
	if(isset($_POST['codelon'])){
		$accid = $_SESSION['acc_id'];
		$pet_id = $_POST['pet_id'];
		$rcve_code = $_POST['rcve_code'];
		$source = mysqli_real_escape_string($conn, $_POST['loan_source']);
		$query = "SELECT * FROM `loan` where loan_id = '$pet_id' and rcve_code = '$rcve_code'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE loan set 
		   		state = 'ALoan', source = '$source'
		    where loan_id = '$pet_id' and state = 'ARcvCashCode' and rcve_code = '$rcve_code'"; 
		    savelogs("Release Loan", "Loan #: " .$pet_id);
		 	if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		savelogs("Release Loan", "Loand ID -> " . $pet_id . ", Code -> " . $rcve_code . ", Source -> " . $source);
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
				echo '<script type="text/javascript">window.location.replace("admin.php?loanrelease=1&petty_id='.$pet_id.'"); </script>';
			}else if($_SESSION['level'] == 'ACC'){
				echo '<script type="text/javascript">window.location.replace("accounting-petty.php?release=1&petty_id='.$pet_id.'"); </script>';
			}
		}
		$conn->close();
	}

?>

<?php
	//release with code
	if(isset($_POST['liqsubmit']) || isset($_POST['admliqsubmit'])){
		$accid = $_SESSION['acc_id'];
		$pet_id = mysqli_real_escape_string($conn, $_POST['pet_id']);
		if(!empty($_POST['liqcode'])){
			$liqcode = mysqli_real_escape_string($conn, $_POST['liqcode']);	
		}
		$comdate = date("Y-m-d");
		$query = "SELECT * FROM `petty_liqdate` where petty_id = '$pet_id' and liqcode is NULL";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			if(isset($_POST['admliqsubmit'])){
				$sql ="UPDATE petty_liqdate set 
		   			liqstate = 'AdmnApp'
		    	where petty_id = '$pet_id' and liqstate = 'LIQDATE' and liqcode is NULL";
		    	savelogs("Approve Liquidation/Remove Restriction", "Petty #: " . $pet_id); 
			}elseif(isset($_POST['liqsubmit'])){
				$sql ="UPDATE petty_liqdate set 
		   			liqstate = 'EmpVal', liqcode = '$liqcode', completedate = '$comdate'
		    	where petty_id = '$pet_id' and (liqstate = 'LIQDATE' or liqstate = 'AdmnApp') and liqcode is NULL"; 
		    	savelogs("Complete Liquidation", "Petty #: " . $pet_id);
			}
			if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		echo '<script type="text/javascript">window.location.replace("admin-petty.php"); </script>';
		 		}else if($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR'){
		 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		 		}
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}  
		}else{
			$_SESSION['err'] = 'Incorrect Code';
			if($_SESSION['level'] == 'Admin'){
				echo '<script type="text/javascript">window.location.replace("admin-petty.php?complete=1&petty_id='.$pet_id.'"); </script>';
			}else if($_SESSION['level'] == 'ACC'){
				echo '<script type="text/javascript">window.location.replace("accounting-petty.php?complete=1&petty_id='.$pet_id.'"); </script>';
			}
		}
		$conn->close();
	}
?>


<?php
	//complete
	if(isset($_POST['valcodesub'])){
		$admincode = random_string(4);
		$pet_id = mysqli_real_escape_string($conn, $_POST['petyid']);
		$liqcode = mysqli_real_escape_string($conn, $_POST['valcode']);
		$valdate = date('Y-m-d');
		$query = "SELECT * FROM `petty_liqdate` where petty_id = '$pet_id' and liqcode = '$liqcode'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE petty_liqdate set 
		   		liqstate = 'CompleteLiqdate', liqcode = '$liqcode', admincode = '$admincode', valdate = '$valdate'
		    where petty_id = '$pet_id' and liqstate = 'EmpVal' and liqcode = '$liqcode'"; 
		    savelogs("Employee Validate Code", "Petty #: " . $pet_id);
		 	if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		 		}else if($_SESSION['level'] == 'ACC'){
		 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		 		}
		 		elseif($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
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
			}elseif($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
		}
		$conn->close();
	}

?>


<?php
	//complete
	if(isset($_POST['excesssubmit'])){
		$pet_id = $_POST['pet_id'];
		$liqcode = $_POST['avalcode'];
		$query = "SELECT * FROM `petty_liqdate` where petty_id = '$pet_id' and admincode = '$liqcode'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE petty_liqdate set 
		   		accval = 'ExcessComplete'
		    where petty_id = '$pet_id' and accval = 'AdminRcv' and admincode = '$liqcode'"; 
		    savelogs("Admin Validation Code", "Petty #: " . $pet_id);
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
				echo '<script type="text/javascript">window.location.replace("admin-petty.php?validate=1&petty_id='.$pet_id.'"); </script>';
			}else if($_SESSION['level'] == 'ACC'){
				echo '<script type="text/javascript">window.location.replace("accounting-petty.php?validate=1&petty_id='.$pet_id.'"); </script>';
			}
		}
		$conn->close();
	}

?>

<?php
	if(isset($_GET['excesscode']) && $_SESSION['level'] == 'Admin'){
		$pet_id = mysql_escape_string($_GET['excesscode']);
		$query = "SELECT * FROM `petty_liqdate` where petty_id = '$pet_id'";
		$result = $conn->query($query);
		if($result->num_rows > 0){
			$sql ="UPDATE petty_liqdate set 
		   		accval = 'AdminRcv'
		    where petty_id = '$pet_id' and accval IS NULL and liqstate = 'CompleteLiqdate'"; 
		    savelogs("Receive Change", "Petty #: " . $pet_id);
		 	if ($conn->query($sql) === TRUE) {	 		
		    	if($_SESSION['level'] == 'Admin'){
		    		echo '<script type="text/javascript">window.location.replace("admin-petty.php?liqdate='.$_GET['excesscode'].'&acc='.$_GET['acc'].'"); </script>';
		 		}else if($_SESSION['level'] == 'ACC'){
		 		//	echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		 		}
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}  
		}else{
			$_SESSION['err'] = 'Incorrect Code';
			if($_SESSION['level'] == 'Admin'){
				echo '<script type="text/javascript">window.location.replace("admin.php?release=1&petty_id='.$pet_id.'"); </script>';
			}else if($_SESSION['level'] == 'ACC'){
			//	echo '<script type="text/javascript">window.location.replace("accounting-petty.php?release=1&petty_id='.$pet_id.'"); </script>';
			}
		}
		$conn->close();
	}else{
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}


?>