<?php
	include("conf.php");
	session_start();
	if(isset($_POST['submitpetty'])){		
		$pettyamount = $_POST['pettyamount'];
		$petty_id = $_POST['petty_id'];
		
		if(isset($_POST['transct'])){
			$trans = $_POST['transct'];
		}else{
			$trans = null;
		}
		if($_SESSION['level'] == 'Admin'){
			$source = $_POST['source'];
			if($source == 'Eli/Sha'){
				$state = 'AAAPettyReceive';
			}else{
				$state = 'AAPetty';
			}
		}else if($_SESSION['level'] == 'ACC'){
			$state = 'AAAPettyReceive';
			$source = 'Accounting';
		}
		$sql ="UPDATE petty set 
	   		amount = '$pettyamount', source = '$source', state = '$state', transfer_id = '$trans'
	    where petty_id = '$petty_id'"; 
	 	if ($conn->query($sql) === TRUE) {	 		
	    	if($_SESSION['level'] == 'Admin'){echo '<script type="text/javascript">window.location.replace("admin-petty.php"); </script>';}
	    	else if($_SESSION['level'] == 'ACC'){echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  
	}
	if(isset($_GET['pettyac']) && $_GET['pettyac'] == 'd'){	
		$petty_id = $_GET['petty_id'];
		$sql ="UPDATE petty set 
			state = 'DAPetty'
	    where petty_id = '$petty_id'"; 
	 	if ($conn->query($sql) === TRUE) {
	 		if($_SESSION['level'] == 'Admin'){
	    		echo '<script type="text/javascript">window.location.replace("admin-petty.php"); </script>';
	 		}else if($_SESSION['level'] == 'ACC'){
	 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
	 		}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  
	}
	
?>

<?php
	//employee receive
	if(isset($_GET['o']) && isset($_GET['acc'])){
		$o = mysql_escape_string($_GET['o']);
		$accid = $_SESSION['acc_id'];
		$sql ="UPDATE petty set 
	   		state = 'AAPettyReceived'
	    where petty_id = '$o' and account_id = '$accid' and state = 'AAAPettyReceive'"; 

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

	 	if ($conn->query($sql) === TRUE) {	 		
	    	if($_SESSION['level'] == 'Admin'){
	    		echo '<script type="text/javascript">window.location.replace("admin-petty.php"); </script>';
	 		}else if($_SESSION['level'] == 'ACC'){
	 			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
	 		}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}  

	}

?>