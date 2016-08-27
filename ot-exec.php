<?php
	session_start();
	include('conf.php');
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['otsubmit']) || isset($_POST['lateotsub'])){				
		//hrs:minutes computation
		function gettimediff($dtime,$atime){ 
		 $nextday = $dtime > $atime?1:0;
		 $dep = explode(':',$dtime);
		 $arr = explode(':',$atime);
		 $diff = abs(mktime($dep[0], $dep[1], 0, date('n'), date('j'), date('y')) - mktime($arr[0], $arr[1], 0, date('n'), date('j') + $nextday, date('y')));
		 $hours = floor($diff / (60*60));
		 $mins = floor(($diff - ($hours*60*60))/(60));
		 $secs = floor(($diff - (($hours*60*60)+($mins*60))));
		 if(strlen($hours) < 2){
		 	$hours = $hours;
		 }
		 if(strlen($mins) < 2){
		 	$mins =  $mins;
		 }
		 if(strlen($secs) < 2){
		 	$secs = "0" . $secs;
		 }
		 return $hours . ':' . $mins;
		}
		$time1 = date('H:i', strtotime($_POST['startofot']));
		$time2 = date('H:i', strtotime($_POST['endofot']));
		$approvedothrs = gettimediff($time1,$time2);	
	
		if(substr($approvedothrs,0,2) >= 8){
			$approvedothrs = date("G:i", strtotime("-1 hour", strtotime($approvedothrs)));
		}			
		//ot break on ot exec
		if(isset($_POST['otbreak']) && $_POST['otbreak'] != null){
			if($_POST['otbreak'] == '30 Mins'){
				$approvedothrs = date("G:i", strtotime("-30 min", strtotime($approvedothrs)));
				$otbreak = '-30 Minutes';
			}elseif ($_POST['otbreak'] == '1 Hour') {
				$approvedothrs = date("G:i", strtotime("-1 Hour", strtotime($approvedothrs)));
				$otbreak = '-1 Hour';
			}else{
				$otbreak = null;
			}					
		}else{
			$otbreak = null;
		}
		
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$datefile = date('Y-m-d H:i:s');
		$dateofot = $_POST['dateofot'];
		$nameofemployee = $_SESSION['name'];
		$startofot = $_POST['startofot'];
		$endofot = $_POST['endofot'];
		if(isset($_POST['restday']) || isset($_POST['oncall']) || isset($_POST['sw']) || isset($_POST['lg'])){
			if(isset($_POST['restday'])){
				$officialworksched = 'Restday<br>' . $_POST['officialworkschedfr']. ' - ' . $_POST['officialworkschedto'];
			}elseif(isset($_POST['oncall'])){
				$ex = explode(":", $approvedothrs);
				if($ex[1] > 0){
					$ex[0] .= '.5';
				}else{
					$ex[0] .= '.0';
				}
				if($ex[0] <= 4){
					$approvedothrs = '4:0';
				}elseif($ex[0] > 4){
					$approvedothrs = '8:0';
				}
				$officialworksched = 'Oncall<br>' . $_POST['officialworkschedfr']. ' - ' . $_POST['officialworkschedto'];
			}elseif(isset($_POST['sw'])){
				$officialworksched = 'Special N-W Holliday<br>' . $_POST['officialworkschedfr']. ' - ' . $_POST['officialworkschedto'];
			}elseif(isset($_POST['lg'])){
				$officialworksched = 'Legal Holliday<br>' . $_POST['officialworkschedfr']. ' - ' . $_POST['officialworkschedto'];
			}
			
		}else{
			$officialworksched = $_POST['officialworkschedfr']. ' - ' . $_POST['officialworkschedto'];
		}
		$twodaysred = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));;
		$reason = $_POST['reason'];
		$state = 'UA';
		$restric = 0;
		if(date("D") == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		if(date("Y-m-d", strtotime($minus, strtotime($datefile))) > date("Y-m-d", strtotime($dateofot)) || date("Y-m-d", strtotime($datefile)) < date("Y-m-d", strtotime($dateofot))){
			$restric = 1;	
		}
		if(isset($_POST['ottype'])){
			if($_POST['ottype'] == 'Project' || $_POST['ottype'] == 'Support'){
				$_POST['project'] = $_POST['otproject'];
			}elseif($_POST['ottype'] == 'P.M.'){
				$_POST['project'] = $_POST['otpm'];
			}elseif($_POST['ottype'] == 'Internet'){
				$_POST['project'] = $_POST['otinternet'];
			}elseif($_POST['ottype'] == 'Oncall'){
				$_POST['project'] = $_POST['otoncall'];
			}elseif($_POST['ottype'] == 'Corporate'){
				$_POST['project'] = $_POST['otcorpo'];
			}else{
				$project = null;
				$_POST['project'] = null;
			}
		}
		if($_POST['ottype'] == ""){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
			break;
		}
		if($_POST['onleave'] != ""){
			$reason .= '<br><b><i>(' . $_POST['onleave'].')</i></b>';
			$restric = 0;
		}
		$project = mysqli_real_escape_string($conn, $_POST['project']);
		$projtype = mysqli_real_escape_string($conn, $_POST['ottype']);
		$stmt = $conn->prepare("INSERT into `overtime` (project, projtype, account_id, datefile, 2daysred, dateofot, nameofemp, startofot, endofot, officialworksched, reason, state, approvedothrs, otbreak, csrnum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssissssssssssss",$project, $projtype, $accid, $datefile, $twodaysred, $dateofot, $nameofemployee, $startofot, $endofot, $officialworksched, $reason, $state, $approvedothrs, $otbreak, $_POST['csrnum']);	
		if($restric == 0){
			$stmt->execute();
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penot"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penot"); </script>';
	    	}
			$conn->close();
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Wrong date."); window.location.replace("employee.php?ac=penot"); </script>';
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