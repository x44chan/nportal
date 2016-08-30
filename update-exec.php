<?php
	session_start();
	include('conf.php');
	include('savelogs.php');
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
		if(isset($_POST['uprestday']) || isset($_POST['uponcall']) || isset($_POST['sw']) || isset($_POST['lg'])){
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
			}elseif(isset($_POST['sw'])){
				$officialworksched = 'Special N-W Holliday<br>' . mysqli_real_escape_string($conn, $_POST['upoffr']) . ' - ' . mysqli_real_escape_string($conn, $_POST['upoffto']);
			}elseif(isset($_POST['lg'])){
				$officialworksched = 'Legal Holliday<br>' . mysqli_real_escape_string($conn, $_POST['upoffr']) . ' - ' . mysqli_real_escape_string($conn, $_POST['upoffto']);
			}
		}else{
			$officialworksched = $_POST['upoffr']. ' - ' . $_POST['upoffto'];
		}	
		if($_SESSION['level'] == "HR"){
			$state = 'state = "UAAdmin"';	
		}else{
			$state = '(state = "UA")';	
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
				$restric = 1;
				$uplate = ', state = "UA" ';		
		}else{
				$uplate = ', state = "UA" ';
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
		$project = mysqli_real_escape_string($conn, $_POST['project']);
		$projtype = mysqli_real_escape_string($conn, $_POST['ottype']);
		if($_POST['onleave'] != ""){
			$reason .= '<br><b><i>(' . $_POST['onleave'].')</i></b>';
			$restric = 0;
		}
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
		if($_POST['upoffr'] == "" && $_POST['upoffto'] == ""){
			$restric = 1;
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
			$restric = 1;	
		}else{
			$uplate = ',oblate = null';	
		}
		if($_POST['onleave'] != ""){
			$obreason .= '<br><b><i>(' . $_POST['onleave'].')</i></b>';
			$restric = 0;
		}
		$sql = "SELECT * FROM officialbusiness where state != 'DAAdmin' and obdatereq = '$date' and account_id = '$accid' and officialbusiness_id != '$_SESSION[otid]'";
		$xx = $conn->query($sql);
		if($xx->num_rows > 0){
			$restric = 2;
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
			if($restric == 1){
				$alert = "Wrong Date";
			}else{
				$alert = "You already filed " . date("M j, Y",strtotime($date)) .'.'; 
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
			$state = 'UA';	
		}else{
			$state = 'UA';	
		}
		$stmts2xx = "SELECT * FROM `nleave` where leave_id = '$_SESSION[otid]' and  account_id = '$accid'";
  		$dxatax = $conn->query($stmts2xx)->fetch_assoc();
  		$restric = 0;
		if($dxatax['typeoflea'] == 'Vacation Leave'){
			if(date("Y-m-d", strtotime("+9 days", strtotime($dxatax['datefile']))) > date("Y-m-d", strtotime($dateofleavfr))){
				$restric = 1;
			}
		}
		$accid = $_SESSION['acc_id'];
		$sql = "SELECT * from `login` where account_id = '$accid' and empcatergory = 'Regular'";
		$result = $conn->query($sql);
		$datey = date("Y");
		$availsick = 0;
		$totavailvac = 0;
		if($result->num_rows > 0){		
			while($row = $result->fetch_assoc()){
				$cstatus = $row['ecstatus'];
				$accidd = $row['account_id'];
				$egender = $row['egender'];
				if(date("Y") == 2015){	
					$sl = $row['sickleave'] - $row['usedsl'];
					$vl = $row['vacleave'] - $row['usedvl'];
					$usedsl = $row['usedsl'];
					$usedvl = $row['usedvl'];
				}else{				
					$leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and state = 'AAdmin'";
					$datalea = $conn->query($leaveexec)->fetch_assoc();
					$sl = $datalea['sleave'];
					$vl = $datalea['vleave'];
					$usedsl = 0;
					$usedvl = 0;
				}
						
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Vacation Leave'  and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$availvac = $vl - $row1['count'];
						$count = $row1['count'];
						}
				}		
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Other%' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$totavailvac = $availvac - $row1['count'];
						$count = $row1['count'];
						}
				}			
			}
		}
		if(($dxatax['typeoflea'] == 'Vacation Leave' || $dxatax['typeoflea'] == 'Others') && $_SESSION['category'] == 'Regular'){
			$quarterdate = array();
			$date1=date_create($datalea['startdate']);
			$date2=date_create($datalea['enddate']);
			$diff=date_diff($date1,$date2);
			$months = $diff->format("%m");
			if($months > 9 && $months <= 12){
				$months = ceil($vl / 4);
				$quarter = 4;
			}elseif($months > 6 && $months <= 9){
				$months = ceil($vl / 3);
				$quarter = 3;
			}elseif($months > 3 && $months <= 6) {
				$months = ceil($vl / 2);
				$quarter = 2;
			}elseif($months > 0 && $months <= 3){
				$months = $vl;
				$quarter = 1;
			}
			$plus = 0;
			for($i = 1; $i <= $quarter; $i++){
				if($i > 1){
					$plus += 3;
				}else{
					$plus = 0;
				}
				$quarterdate[] = date("Y-m-d",strtotime('+'.$plus.' month', strtotime($datalea['startdate'])));
			}
			$xcount = array();
			for($i = 0; $i < $quarter; $i++){
				if($i == ($quarter - 1)){
					$two = date("Y-12-31");
				}else{
					$plus1 = $i+1;
					$two = date("Y-m-t",strtotime("-1 month",strtotime($quarterdate[$plus1])));
				}
				$one = $quarterdate[$i];
				if(date("Y-m-d") > $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accid' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					if($counter['count'] == ""){
						$months += ($months-1);
					}elseif($counter['count'] < $months){
						$months += ($months-$counter['count']);
					}
				}
				if($dxatax['datefile'] >= $one && $dxatax['datefile'] <= $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accid' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					$xcount[] = $counter['count'];
				}else{
					continue;
				}
			}
			$wthpay = null;
			for($i = 0; $i < $quarter; $i++){
				if(!isset($xcount[$i])){
					continue;
				}				
				if($xcount[$i] >= $months) {
					$wthpay = 'withoutpay';
				}elseif(($months - $xcount[$i]) < $numdays){
					$wthpay = 'withoutpay';
				}else {
					$wthpay = null;
				}
				if(stristr($sql, '2016-12-31') == true){
					$wthpay = null;
				}
			}
		}
		if($dxatax['typeoflea'] == 'Vacation Leave' && $_SESSION['category'] == 'Regular' && ($totavailvac >= $numdays)){
			$state = 'UA';
		}
		if($dxatax['typeoflea'] == 'Vacation Leave' && $_SESSION['category'] == 'Regular' && ($totavailvac < $numdays)){
			$restric = 3;
		}
		if(($dxatax['typeoflea'] == 'Vacation Leave' || $dxatax['typeoflea'] == 'Others') && $_SESSION['category'] == 'Regular' && (($months-$xcount[0]) < $_POST['numdays'] && ($months-$xcount[0]) != 0)){
			$restric = 5;
		}
		$stmt = "UPDATE `nleave` set 
			dateofleavfr = '$dateofleavfr', dateofleavto = '$dateofleavto', numdays = '$numdays', reason = '$reason', state = '$state', leapay = '$wthpay'
			where account_id = '$accid' and (state = '$state' or (state = 'UA' and accadmin is null) or state = 'UAAdmin') and leave_id = '$_SESSION[otid]'";
		if($restric == 0){
			if ($conn->query($stmt) === TRUE) {
		    	if($wthpay != null){
					$al = "alert('You already used your allowed V.L. for this quarter, your request is automatically flaged as without pay.');";
				}else{
					$al = "";
				}
				if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">'.$al.'window.location.replace("employee.php?ac=penlea"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">'.$al.'window.location.replace("accounting.php?ac=penlea"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">'.$al.'window.location.replace("techsupervisor.php?ac=penlea"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">'.$al.'window.location.replace("hr.php?ac=penlea"); </script>';
		    	}
				$conn->close();	
		  	}
		}else{
			if($restric == 3){
				$alert = "No more Vacation Leave Balance.";
			}elseif($restric == 4){
				$alert = 'You can only request ' . $months . ' day/s per quarter ';
			}elseif($restric == 5){
				$alert = "Make it 2 request. 1.) ". ($months-$xcount[0]) ." day/s for with pay 2.) " . ($_POST['numdays'] - (($months-$xcount[0]))) . " day/s";
			}else{
				$alert = "Wrong Date";
			}
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("employee.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("accounting.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("techsupervisor.php?ac=penlea"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("'.$alert.'"); window.location.replace("hr.php?ac=penlea"); </script>';
	    	}
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
		
		//$unumofhrs = mysql_escape_string($_POST['unumofhrs']);
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
		// if($hours == 00  && $minutes != 00){
		// 	$hours += 24;	
		// }
		 return $hours . ':' . $mins;
		}
		$time1 = date('H:i', strtotime($undertimefr));
		$time2 = date('H:i', strtotime($undertimeto));
		$unumofhrs = gettimediff($time1,$time2);
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else{
			$state = ' state = "UA"';	
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
			$restric = 1;
			$uplate = ', state = "UA" ';		
		}else{
			$uplate = ', state = "UA" ';	
		}
		if($_POST['onleave'] != ""){
			$unreason .= '<br><b><i>(' . $_POST['onleave'].')</i></b>';
			$restric = 0;
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
			if(isset($_POST['oldotbreak']) && isset($_POST['otbreak']) && $_POST['oldotbreak'] != $_POST['otbreak']){
				$oldotbreak = '<br><font color = "#333"> Filed Break </font>' . $_POST['oldotbreak'];
			}else{
				$oldotbreak = "";
			}
			$oldot = $oldotstrt . ' - ' . $oldotend . $oldotbreak;
			if(substr($newappot,0,2) >= 8){
				$newappot = date("G:i", strtotime("-1 hour", strtotime($newappot)));
			}	
			$date = date('Y-m-d h:i A');
			//ot break on update app
			if(isset($_POST['otbreak']) && $_POST['otbreak'] != null){
				if($_POST['otbreak'] == '30 Mins'){
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
			$upstate = 'AHR';
			$state = 'UA';
			if(isset($_SESSION['bypass'])){
				$xstate = '(state = "UA"  or state = "UATech")';
			}else{
				$xstate = ' state = "UA" ';
			}
			$redirec = 'hr.php?ac='.$ac;
			$dates = "datehr = '$datex',";
		}elseif($_SESSION['level'] == 'TECH'){
			$upstate = 'AHR';
			$xstate = 'state = "UATech"';
			$dates = "dateacc = '$datex',";
			$redirec = 'techsupervisor.php?ac='.$ac;
		}
		if(isset($_POST['correction'])){
			$correcxq = " , correction = '1' ";
		}else{
			$correcxq = "";
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
		$project = mysqli_real_escape_string($conn, $_POST['project']);
		$projtype = mysqli_real_escape_string($conn, $_POST['ottype']);
		if(isset($_POST['oldprojtype']) && $_POST['oldprojtype'] != "" && isset($_POST['oldproject']) && $_POST['oldproject'] != ""){
		//	$oldot = $_POST['oldprojtype'] . ': ' . $_POST['oldproject'] . ' <br> ' . $oldot;
		}
		$upstate = 'AHR';
		$stmt = "UPDATE `overtime` set 
			project = '$project', projtype = '$projtype', startofot = '$hruptimein', endofot = '$hruptimeout', $dates dareason = '$dareason',  oldot = '$oldot', state = '$upstate', approvedothrs = '$newappot', otbreak = '$otbreak' $correcxq
			where account_id = '$accid' and state = 'UA' and overtime_id = '$overtime'";
		$xxxss = "SELECT * FROM login where account_id = '$accid'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();	
		
		if($hrrestric == 0){
			if ($conn->query($stmt) === TRUE) {
		    	echo '<script type="text/javascript">window.location.replace("'.$redirec.'"); </script>';
		    	savelogs("Update Overtime", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . " From: " . $oldot . " To: " . $hruptimein . ' - ' . $hruptimeout);
		  	}else {
		    	echo "Error updating record: " . $conn->error;
		  	}
			$conn->close();
		}else{
			echo '<script type="text/javascript">alert("Restricted to add O.T");window.location.replace("'.$redirec.'"); </script>';
			savelogs("Update Overtime (Restricted)", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . " From: " . $oldot . " To: " . $hruptimein . ' - ' . $hruptimeout);
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
		$xxxss = "SELECT * FROM login where account_id = '$accid'";
		$xxxsss = $conn->query($xxxss)->fetch_assoc();
		$stmt = "UPDATE `officialbusiness` set 
				obtimein = '$obtimein', obtimeout = '$obtimeout', state = 'CheckedHR', edithr = '$edithr', datehr = '$date' $acc
			where account_id = '$accid' and state = 'UA' and officialbusiness_id = '$obid'";
		if ($conn->query($stmt) === TRUE) {
			savelogs("Update Official Business", $xxxsss['fname'] . ' ' . $xxxsss['lname'] . " In: " . $obtimein . " Out: " . $obtimeout);
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

	if(isset($_POST['hruphol'])){	
		$obtimein = mysql_escape_string($_POST['hruptimein']);
		$obtimeout = mysql_escape_string($_POST['hruptimeout']);			
		$accid = mysql_escape_string($_POST['accid']);
		$obid = mysqli_real_escape_string($conn, $_POST['hol']);
		if(isset($_POST['dareason']) && $_POST['dareason'] == ""){
			$basedon = null;
		}else{
			$basedon = mysqli_real_escape_string($conn, $_POST['dareason']);
		}
		$date = date('Y-m-d h:i A');
		$edithr = mysql_escape_string($_POST['oldobtimein']) . ' - ' . mysql_escape_string($_POST['oldobtimeout']);
		$stmt = "UPDATE `holidayre` set 
				timein = '$obtimein', timeout = '$obtimeout', state = '1', oldtime = '$edithr', datehr = '$date' , dareason = '$basedon'
			where account_id = '$accid' and state = '0' and holidayre_id = '$obid'";
		if ($conn->query($stmt) === TRUE) {
	    	if($_SESSION['level'] == 'ACC'){
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penhol"); </script>';
			}else{
				echo '<script type="text/javascript">window.location.replace("hr.php?ac=penhol"); </script>';
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
		//$numofhrs = mysql_escape_string($_POST['unumofhrs']);
		$obid = $_SESSION['otid'];
		$date = date('Y-m-d h:i A');
		$upstate = 'CheckedHR';
		$edithr = $_SESSION['oldunr'];
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
		$time1 = date('H:i', strtotime($obtimein));
		$time2 = date('H:i', strtotime($obtimeout));
		$numofhrs = gettimediff($time1,$time2);;
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