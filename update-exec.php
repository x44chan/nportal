<?php
	session_start();
	include('conf.php');
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}
	date_default_timezone_set('Asia/Manila');
	if(isset($_POST['upotsubmit']) || isset($_POST['lateotupsub'])){		
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
		 	$mins = $mins;
		 }
		 if(strlen($secs) < 2){
		 	$secs = "0" . $secs;
		 }
		 return $hours . ':' . $mins;
		}		
		$time1 = date('H:i', strtotime($_POST['uptimein']));
		$time2 = date('H:i', strtotime($_POST['uptimeout']));
		$approvedothrs = gettimediff($time1,$time2);
		//end of computation		
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
		
		$accid = $_SESSION['acc_id'];		
		$start = mysql_escape_string($_POST['uptimein']);
		$end = mysql_escape_string($_POST['uptimeout']);
		$post = strtolower($_SESSION['post']);
		$reason = mysql_escape_string($_POST['reason']);
		$csrnum = mysql_escape_string($_POST['csrnum']);
		
		if(isset($_POST['updateofot'])){
			$date = $_POST['updateofot'];
		}
		if(isset($_POST['uprestday']) || isset($_POST['uponcall'])){
			if(isset($_POST['uprestday'])){
				$officialworksched = 'Restday<br>' . mysqli_real_escape_string($conn, $_POST['upoffr']) . ' - ' . mysqli_real_escape_string($conn, $_POST['upoffto']);
			}elseif(isset($_POST['uponcall'])){
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
				$officialworksched = 'Oncall<br>' . mysqli_real_escape_string($conn, $_POST['upoffr']) . ' - ' . mysqli_real_escape_string($conn, $_POST['upoffto']);
			}
			
		}		
		if($_SESSION['level'] == "HR"){
			$state = 'state = "UAAdmin"';	
		}else{
			$state = '(state = "UAAdmin" or state = "UALate")';	
		}
		$stmts2x = "SELECT * FROM `overtime` where overtime_id = '$_SESSION[otid]' and  account_id = '$accid'";
  		$dxata = $conn->query($stmts2x)->fetch_assoc();
		if(date("D", strtotime($dxata['datefile'])) == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		$restric = 0;
		
		if(date("Y-m-d", strtotime($minus, strtotime($dxata['datefile']))) > date("Y-m-d", strtotime($date))){
				$restric = 0;
				$uplate = ', state = "UALate" ';		
		}else{
				$uplate = ', state = "UAAdmin" ';
		}
		if(isset($_POST['ottype'])){
			if($_POST['ottype'] == 'Project'){
				$_POST['project'] = $_POST['otproject'];
			}elseif($_POST['ottype'] == 'P.M.'){
				$_POST['project'] = $_POST['otpm'];
			}elseif($_POST['ottype'] == 'Internet'){
				$_POST['project'] = $_POST['otinternet'];
			}elseif($_POST['ottype'] == 'Others'){
				$project = null;
			}
		}
		if($_POST['ottype'] == ""){
			if($_SESSION['level'] == 'EMP'){
	    	//	echo '<script type="text/javascript">alert("Empty");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Empty");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
			break;
		}
		if($_POST['ottype'] == 'Others'){
			$_POST['project'] = null;
		}
		$project = mysqli_real_escape_string($conn, $_POST['project']);
		$projtype = mysqli_real_escape_string($conn, $_POST['ottype']);
		$stmt = "UPDATE `overtime` set 
			projtype = '$projtype', project = '$project', csrnum = '$csrnum', otbreak = '$otbreak', approvedothrs = '$approvedothrs', officialworksched = '$officialworksched', startofot = '$start', endofot = '$end', reason = '$reason', otbreak = '$otbreak', dateofot = '$date' $uplate
			where account_id = '$accid' and $state and overtime_id = '$_SESSION[otid]'";
		if($restric == 0){
			if ($conn->query($stmt) === TRUE) {
				if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">window.location.replace("employee.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}
		  	}else {
		    	echo "Error updating record: " . $conn->error;
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

	if(isset($_POST['upobsubmit'])){		
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];
		//$obtimein = mysql_escape_string($_POST['obtimein']);
		//$obtimeout = mysql_escape_string($_POST['obtimeout']);
		$obreason = mysql_escape_string($_POST['obreason']);
		if(isset($_POST['updateofob'])){
			$date = mysql_escape_string($_POST['updateofob']);
		}
		if(isset($_POST['uprestday']) && $_POST['uprestday'] == 'restday'){
			$officialworksched = "Restday<br>" .mysql_escape_string($_POST['upoffr']). ' - ' . mysql_escape_string($_POST['upoffto']);;
		}else{
			$officialworksched = mysql_escape_string($_POST['upoffr']). ' - ' . mysql_escape_string($_POST['upoffto']);
		}
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else{
			$state = '(state = "UAAdmin" or state = "UALate")';	
		}
		$stmts2xx = "SELECT * FROM `officialbusiness` where officialbusiness_id = '$_SESSION[otid]' and  account_id = '$accid'";
  		$dxatax = $conn->query($stmts2xx)->fetch_assoc();
		$restric = 0;
		if(date("D", strtotime($dxatax['obdate'])) == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		if(date("Y-m-d", strtotime($minus, strtotime($dxatax['obdate']))) > date("Y-m-d", strtotime($date))){
			$uplate = ',oblate = 1';		
		}else{
			$uplate = ',oblate = null';	
		}
		$stmt = "UPDATE `officialbusiness` set 
			obreason = '$obreason', officialworksched = '$officialworksched', obdatereq = '$date' $uplate
			where account_id = '$accid' and (state = 'UALate' or state = 'UAAdmin' or state = 'UA') and officialbusiness_id = '$_SESSION[otid]'";
		if($restric == 0){
			if ($conn->query($stmt) === TRUE) {
				if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">window.location.replace("employee.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_SESSION['acc'].'"); </script>';
		    	}
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}
			$conn->close();
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("employee.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("accounting.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("techsupervisor.php?ac=penob"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Wrong date"); window.location.replace("hr.php?ac=penob"); </script>';
	    	}
		}
	}

	if(isset($_POST['upleasubmit'])){		
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];
		$dateofleavfr = $_POST['dateofleavfr'];
		$dateofleavto = $_POST['dateofleavto'];
		$numdays = $_POST['numdays'];
		$reason = $_POST['leareason'];
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else if($post == "service technician"){
			$state = 'UATech';	
		}else{
			$state = 'UA';	
		}
		if($_SESSION['level'] == 'HR'){
			$state = 'AHR';
		}
		$stmt = "UPDATE `nleave` set 
			dateofleavfr = '$dateofleavfr', dateofleavto = '$dateofleavto', numdays = '$numdays', reason = '$reason'
			where account_id = '$accid' and (state = '$state' or (state = 'UA' and accadmin is null) or state = 'UAAdmin') and leave_id = '$_SESSION[otid]'";
		if ($conn->query($stmt) === TRUE) {
	    	if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
		$conn->close();		
	}

	if(isset($_POST['upunsubmit'])){	
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$undatereq = mysql_escape_string($_POST['undatereq']);
		$undertimefr = mysql_escape_string($_POST['untimefr']);
		$undertimeto = mysql_escape_string($_POST['untimeto']);
		$unreason = mysql_escape_string($_POST['unreason']);
		
		$unumofhrs = mysql_escape_string($_POST['unumofhrs']);
		
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else{
			$state = ' (state = "UAAdmin" or state = "UALate")';	
		}
		$stmts2x = "SELECT * FROM `undertime` where undertime_id = '$_SESSION[otid]' and  account_id = '$accid'";
  		$dxata = $conn->query($stmts2x)->fetch_assoc();
		if(date("D", strtotime($dxata['datefile'])) == 'Mon'){
			$minus = '-3 days';
		}else{
			$minus = '-1 days';
		}
		$restric = 0;
		
		if(date("Y-m-d", strtotime($minus, strtotime($dxata['datefile']))) > date("Y-m-d", strtotime($undatereq))) {
			$restric = 0;
			$uplate = ', state = "UALate" ';		
		}else{
			$uplate = ', state = "UAAdmin" ';	
		}
		$stmt = "UPDATE `undertime` set 
			dateofundrtime = '$undatereq', undertimefr = '$undertimefr', undertimeto = '$undertimeto', reason = '$unreason', numofhrs = '$unumofhrs' $uplate
			where account_id = '$accid' and $state and undertime_id = '$_SESSION[otid]'";
		if ($conn->query($stmt) === TRUE) {
	    	if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$_SESSION['acc'].'"); </script>';
	    	}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
		$conn->close();

	}
	if(isset($_POST['hrupdatetime'])){	
			$oldotstrt = $_POST['oldotstrt'];
			$oldotend = $_POST['oldotend'];
			$hruptimein = $_POST['hruptimein'];
			$hruptimeout = $_POST['hruptimeout'];
			$dareason = $_POST['dareason'];
			$overtime = $_POST['overtime'];
			$approve = $_POST['approve'];
			$ac = $_POST['ac'];
			$accid = $_POST['accid'];
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
			$time1 = date('H:i', strtotime($hruptimein));
			$time2 = date('H:i', strtotime($hruptimeout));
			$newappot = gettimediff($time1,$time2);
			$time3 = date('H:i', strtotime($oldotstrt));
			$time4 = date("H:i", strtotime($oldotend));
			$oldappot = gettimediff($time3,$time4);
			$hrrestric = 0;
			$explo = (explode(":", $newappot));
			$explo2 = (explode(":", $oldappot));
			if($explo[1] > 0){
				$explo[0] .= '.5';
			}else{
				$explo[0] .= '.0';
			}
			if($explo2[1] > 0){
				$explo2[0] .= '.5';
			}else{
				$explo2[0] .= '.0';
			}
			if($explo[0] > $explo2[0]){
				$hrrestric += 1;
			}
			$oldot = $oldotstrt . ' - ' . $oldotend;
			if(substr($newappot,0,2) >= 8){
				$newappot = date("G:i", strtotime("-1 hour", strtotime($newappot)));
			}	
			$date = date('Y-m-d h:i A');
			//ot break on update app
			if(isset($_POST['otbreak']) && $_POST['otbreak'] != null){
				if($_POST['otbreak'] == '30 Minutes'){
					$newappot = date("G:i", strtotime("-30 min", strtotime($newappot)));
					$otbreak = '-30 Minutes';
				}elseif ($_POST['otbreak'] == '1 Hour') {
					$newappot = date("G:i", strtotime("-1 Hour", strtotime($newappot)));
					$otbreak = '-1 Hour';
				}else{
					$otbreak = null;
				}					
			}else{
				$otbreak = null;
			}
			if(isset($_POST['onrestday'])){
				if($_POST['onrestday'] == 'Oncall'){
					$ex = explode(":", $newappot);
					if($ex[1] > 0){
						$ex[0] .= '.5';
					}else{
						$ex[0] .= '.0';
					}
					echo $ex[0];
					if($ex[0] >= 2 && $ex[0] < 4){
						$newappot = '4:0';
					}elseif($ex[0] >= 4){
						$newappot = '8:0';
					}
				}
			}
			$datex = date('Y-m-d h:i A');
		if($_SESSION['level'] == 'HR'){
			$upstate = 'CheckedHR';
			$state = 'UA';
			if(isset($_SESSION['bypass'])){
				$xstate = '(state = "UA"  or state = "UATech")';
			}else{
				$xstate = ' state = "UA" ';
			}
			$redirec = 'hr.php?ac='.$ac;
			$dates = "datehr = '$datex',";
		}elseif($_SESSION['level'] == 'TECH'){
			$upstate = 'CheckedHR';
			$xstate = 'state = "UATech"';
			$dates = "dateacc = '$datex',";
			$redirec = 'techsupervisor.php?ac='.$ac;
		}
		$upstate = 'CheckedHR';
		$stmt = "UPDATE `overtime` set 
			startofot = '$hruptimein', endofot = '$hruptimeout', $dates dareason = '$dareason',  oldot = '$oldot', state = '$upstate', approvedothrs = '$newappot'
			where account_id = '$accid' and state = 'UA' and overtime_id = '$overtime'";
		if($hrrestric == 0){
			if ($conn->query($stmt) === TRUE) {
		    	echo '<script type="text/javascript">window.location.replace("'.$redirec.'"); </script>';
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}
			$conn->close();
		}else{
			echo '<script type="text/javascript">alert("Restricted to add O.T");window.location.replace("'.$redirec.'"); </script>';
		}
	}

	if(isset($_POST['hrupobsubmit'])){	
		$obtimein = mysql_escape_string($_POST['obtimein']);
		$obtimeout = mysql_escape_string($_POST['obtimeout']);			
		$accid = mysql_escape_string($_POST['accid']);
		$obid = $_SESSION['otid'];
		$date = date('Y-m-d h:i A');
		$upstate = 'AHR';
		if($_SESSION['level'] == 'ACC'){
			$acc = ', dateacc = 1';
		}else{
			$acc = "";
		}
		$edithr = mysql_escape_string($_POST['oldobtimein']) . ' - ' . mysql_escape_string($_POST['oldobtimeout']);
		$stmt = "UPDATE `officialbusiness` set 
				obtimein = '$obtimein', obtimeout = '$obtimeout', state = 'CheckedHR', edithr = '$edithr', datehr = '$date' $acc
			where account_id = '$accid' and state = 'UA' and officialbusiness_id = '$obid'";
		if ($conn->query($stmt) === TRUE) {
	    	if($_SESSION['level'] == 'ACC'){
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penob"); </script>';
			}else{
				echo '<script type="text/javascript">window.location.replace("hr.php?ac=penob"); </script>';
			}
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
		$conn->close();

	}

	if(isset($_POST['hrupunsubmit'])){	
		$obtimein = mysql_escape_string($_POST['untimefr']);
		$obtimeout = mysql_escape_string($_POST['untimeto']);			
		$accid = mysql_escape_string($_SESSION['acc']);
		$numofhrs = mysql_escape_string($_POST['unumofhrs']);
		$obid = $_SESSION['otid'];
		$date = date('Y-m-d h:i A');
		$upstate = 'CheckedHR';
		$edithr = $_SESSION['oldunr'];
		$stmt = "UPDATE `undertime` set 
				undertimefr = '$obtimein', undertimeto = '$obtimeout', state = '$upstate', edithr = '$edithr', datehr = '$date', numofhrs = '$numofhrs'
			where account_id = '$accid' and state = 'UA' and undertime_id = '$obid'";
		if ($conn->query($stmt) === TRUE) {
	    	echo '<script type="text/javascript">window.location.replace("hr.php?ac=penundr"); </script>';
			
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
		$conn->close();

	}

	if(isset($_POST['disapprovetime'])){	
			$dareason = $_POST['dareason'];
			$overtime = $_POST['overtime'];
			$approve = $_POST['approve'];
			$ac = $_POST['ac'];
			$accid = $_POST['accid'];

		$stmt = "UPDATE `overtime` set 
			 state = '$approve', dareason = '$dareason'
			where account_id = '$accid' and state = 'UA' and overtime_id = '$overtime'";
		if ($conn->query($stmt) === TRUE) {
	    	echo '<script type="text/javascript">window.location.replace("hr.php?ac='.$ac.'"); </script>';
			
	  	}else {
	    	echo "Error updating record: " . $conn->error;
	  	}
		$conn->close();

	}
?>