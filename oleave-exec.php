<?php
	session_start();
	include('conf.php');	
	if(!isset($_SESSION['acc_id'])){
		echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
	}elseif(isset($_POST['leasubmit'])){
		$post = strtolower($_SESSION['post']);
		$accid = $_SESSION['acc_id'];		
		$datefile = date('Y-m-d H:i:s');
		$twodaysred = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
		$nameofemployee = $_POST['nameofemployee'];
		$datehired = $_SESSION['datehired'];
		$deprt = $_POST['deprt'];
		$posttile = $_POST['posttile'];
		$dateofleavfr = $_POST['dateofleavfr'];
		$dateofleavto = $_POST['dateofleavto'];
		$numdays = $_POST['numdays'];
		if($_POST['typeoflea'] == 'Others'){
			$typeoflea = $_POST['typeoflea'];
			$othersl = $_POST['othersl'];
		}else{
			$typeoflea = $_POST['typeoflea'];
			$othersl = '';
		}
		$restric = 0;
		if($typeoflea == 'Vacation Leave'){
			if(date("Y-m-d", strtotime("+9 days", strtotime($datefile))) > date("Y-m-d", strtotime($dateofleavfr))){
				$restric = 1;
			}
		}
		$reason = $_POST['leareason'];
		if($_SESSION['level'] == "HR"){
			$state = 'AHR';	
		}else{
			$state = 'UA';	
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
					$leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and state = 'AAdmin' and startdate like '$datey%'";					
					$datalea = $conn->query($leaveexec)->fetch_assoc();
					$sl = $datalea['sleave'];
					$vl = $datalea['vleave'];
					$usedsl = 0;
					$usedvl = 0;
				}
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Sick Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$availsick = $sl - $row1['count'];
						$scount = $row1['count'];						
						}
				}		
				if($scount == null){
					$scount = " - ";
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
		if(($typeoflea == 'Vacation Leave' || $typeoflea == 'Others') && $_SESSION['category'] == 'Regular'){
			$quarterdate = array();
			$date1=date_create($datalea['startdate']);
			$date2=date_create($datalea['enddate']);
			$diff=date_diff($date1,$date2);
			$months = $diff->format("%m");
			$quarter = 4;
			if($months > 9 && $months <= 12){
				$months = number_format($vl / 4,2);
				$quarter = 4;
			}elseif($months > 6 && $months <= 9){
				$months = number_format($vl / 3,2);
				$quarter = 3;
			}elseif($months > 3 && $months <= 6) {
				$months = number_format($vl / 2,2);
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
			$chan = array();
			$quar = array();
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
				if(date("Y-m-d") >= $one && date("Y-m-d") <= $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accid' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					$xcount[] = $counter['count'];
				}else{
					continue;
				}
			}
			for($i = 0; $i < $quarter; $i++){
				if(!isset($xcount[$i])){
					continue;
				}				
				if($xcount[$i] > $months) {
					$wthpay = 'withoutpay';
				}elseif(($months - $xcount[$i]) <= 0){
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
		if($typeoflea == 'Vacation Leave' && $_SESSION['category'] == 'Regular' && ($totavailvac >= $_POST['numdays'])){
			$state = 'UA';
		}
		if($typeoflea == 'Vacation Leave' && $_SESSION['category'] == 'Regular' && ($totavailvac < $_POST['numdays'])){
			$restric = 3;
		}
		if(($typeoflea == 'Vacation Leave' || $typeoflea == 'Others') && $_SESSION['category'] == 'Regular' && (($months-$xcount[0]) < $_POST['numdays'] && ($months-$xcount[0]) > 0)){
			$restric = 5;
		}
		if(($typeoflea == 'Sick Leave') && $_SESSION['category'] == 'Regular' && ($availsick < $_POST['numdays'])  && $availsick != 0){
			$restric = 6;
			$wthpay = 'withoutpay';
		}elseif(($typeoflea == 'Sick Leave') && $_SESSION['category'] == 'Regular' && ($availsick < $_POST['numdays']) && $availsick <= 0){
			$wthpay = 'withoutpay';
		}
		$stmt = $conn->prepare("INSERT into `nleave` (account_id, datefile, nameofemployee, datehired, deprt, posttile, dateofleavfr, dateofleavto, numdays, typeoflea, othersl, reason, twodaysred, state, leapay) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("issssssssssssss", $accid, $datefile, $nameofemployee, $datehired, $deprt, $posttile, $dateofleavfr, $dateofleavto, $numdays, $typeoflea, $othersl, $reason, $twodaysred, $state , $wthpay);
		if($restric == 0 || $restric == 3){
			$stmt->execute();
			if($wthpay != null && $restric == 0){
				if($typeoflea == 'Vacation Leave' || $typeoflea == 'Others'){
					$quarter = 'quarter';
				}elseif($typeoflea == 'Sick Leave'){
					$quarter = 'year';
				}
				$al = "alert('You already used your allowed ".$typeoflea." for this ".$quarter.", your request is automatically flaged as without pay.');";
			}else{
				$al = "";
			}
			if($restric == 3){
				$al = "alert('No more Vacation Leave Balance, your leave is w/o pay.');";
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
		}else{
			if($restric == 3){
				$alert = "No more Vacation Leave Balance.";
			}elseif($restric == 4){
				$alert = 'You can only request ' . $months . ' day/s per quarter ';
			}elseif($restric == 5){
				$alert = "Make it 2 request. 1.) ". ($months-$xcount[0]) ." day/s for with pay 2.) " . ($_POST['numdays'] - (($months-$xcount[0]))) . " day/s";
			}elseif($restric == 6){
				$alert = "Make it 2 request. 1.) ". ($availsick) ." day/s for with pay 2.) " . ($_POST['numdays'] - $availsick) . " day/s without pay";
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
	}
	if(isset($_GET['adleave'])){
		$balid = mysql_escape_string($_GET['leavebal_id']);
		if($_GET['adleave'] == 'a'){
			$sql = "UPDATE nleave_bal set state = 'AAdmin' where leavebal_id = '$balid' and state = 'UA'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}elseif($_GET['adleave'] == 'd'){			
			$sql = "UPDATE nleave_bal set state = 'DAAdmin' where leavebal_id = '$balid' and state = 'UA'";
			if ($conn->query($sql) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
			}
		}
	}
?>