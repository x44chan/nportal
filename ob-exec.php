<?php
	session_start();
	include('conf.php');	
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['submit'])){
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$obdate = date("Y-m-d");
		$obename = $_POST['obename'];
		$obpost = $_POST['obpost'];
		$obdept = $_POST['obdept'];
		$obdatereq = $_POST['obdatereq'];
		$twodaysred = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
		$obreason = $_POST['obreason'];
		//$obtimein = $_POST['obtimein'];
		//$obtimeout = $_POST['obtimeout'];
		$state = 'UA';
		if(isset($_POST['restday']) && $_POST['restday'] == 'restday'){
			$officialworksched = "Restday<br>" . $_POST['obofficialworkschedfr']. ' - ' . $_POST['obofficialworkschedto'];
		}else{
			$officialworksched = $_POST['obofficialworkschedfr']. ' - ' . $_POST['obofficialworkschedto'];
		}
		if($_POST['obofficialworkschedfr'] == "" && $_POST['obofficialworkschedto'] == ""){
			$restric = 1;
		}
		$restric = 0;
		if(date("D") == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-3 days';
		}
		
		$restric = 0;
		if(date("Y-m-d", strtotime($minus, strtotime($obdate))) > date("Y-m-d", strtotime($obdatereq))){
			$oblate = 1;
			$restric = 1;		
		}else{
			$oblate = null;
		}
		$stmt = $conn->prepare("INSERT into `officialbusiness` (account_id, twodaysred, obdate, obename, obpost, obdept, obdatereq, obreason, officialworksched, state, oblate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("issssssssss",$accid, $twodaysred, $obdate, $obename, $obpost, $obdept, $obdatereq, $obreason, $officialworksched, $state, $oblate);
		if($restric == 0){
			$stmt->execute();
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penob"); </script>';
	    	}
			$conn->close();
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("employee.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("accounting.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("techsupervisor.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("hr.php?ac=penot"); </script>';
	    	}
		}
	}

?>