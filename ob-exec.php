<?php
	session_start();
	include('conf.php');	
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['submit'])){
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$obdate = date('Y-m-d H:i:s');
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
		$nxtday = 0;
		if(isset($_POST['nxtday']) && $_POST['nxtday'] == 'nxtday'){
			$nxtday = 1;
		}
		if($_POST['obofficialworkschedfr'] == "" && $_POST['obofficialworkschedto'] == ""){
			$restric = 1;
		}
		$restric = 0;
		if(date("D") == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		if(date("Y-m-d", strtotime($minus, strtotime($obdate))) > date("Y-m-d", strtotime($obdatereq))){
			$oblate = 1;
			$restric = 1;		
		}else{
			$oblate = null;
		}
		if($_POST['onleave'] != ""){
			$obreason .= '<br><b><i>(' . $_POST['onleave'].')</i></b>';
			$restric = 0;
		}
		$sql = "SELECT * FROM officialbusiness where state != 'DAAdmin' and obdatereq = '$obdatereq' and account_id = '$accid'";
		$xx = $conn->query($sql);
		if($xx->num_rows > 0){
			$restric = 2;
		}
		$stmt = $conn->prepare("INSERT into `officialbusiness` (account_id, twodaysred, obdate, obename, obpost, obdept, obdatereq, obreason, officialworksched, state, oblate, nxtday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("issssssssssi",$accid, $twodaysred, $obdate, $obename, $obpost, $obdept, $obdatereq, $obreason, $officialworksched, $state, $oblate, $nxtday);
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
			if($restric == 1){
				$alert = "Wrong Date";
			}else{
				$alert = "You already filed " . date("M j, Y",strtotime($obdatereq)) .'.'; 
			}
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("employee.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("accounting.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("techsupervisor.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("hr.php?ac=penob"); </script>';
	    	}
		}
	}

?>