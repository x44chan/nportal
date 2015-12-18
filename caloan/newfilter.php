<?php 
	include('conf.php');
		if(isset($_SESSION['date'])){
			$date1 = $_SESSION['date'];
			$date2 = $_SESSION['date0'];
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
			
		}elseif(date("d") < 16){
			$date1 = date("Y-m-01");
			$date2 = date("Y-m-15");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}else{
			$date1 = date("Y-m-16");
			$date2 = date("Y-m-t");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}
		if(isset($_POST['repfilter'])){
			$_SESSION['date'] = $_POST['repfr'];
			$_SESSION['date0'] = $_POST['repto'];
			echo '<script type = "text/javascript">window.location.replace("acc-report.php?rep='.$_POST['reptype'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['date']);
			unset($_SESSION['date0']);
			echo '<script type = "text/javascript">window.location.replace("acc-report.php");</script>';
		}
	if(!isset($_GET['report'])){
		

?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -30px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Report Filtering <a href = "?sumar=leasum" class="btn btn-success pull-right"> Employee Leave Summary </a></i></u></h4><br>				
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-3 col-xs-offset-1" align="center">
				<label>Type of Reports</label>
				<select class="form-control input-sm" name ="reptype">
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'all'){ echo ' selected '; } ?> value="all">Overall Reports</option>				
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'lea'){ echo ' selected '; } ?> value="lea">Leave Reports</option>					
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ot'){ echo ' selected '; } ?> value="ot">Overtime Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'undr'){ echo ' selected '; } ?> value="undr">Undertime Reports</option>					
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ob'){ echo ' selected '; } ?> value="ob">Official Business Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ca'){ echo ' selected '; } ?> value="ca">Cash Advance Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'loan'){ echo ' selected '; } ?> value="loan">Loan Reports</option>	
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date'] . '" '; }elseif(date("d") < 16){ echo ' value = "'. date("Y-m-01") . '"';} else { echo ' value = "'. date("Y-m-16") . '"';}?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date0'] . '" '; }elseif(date("d") < 16){ echo ' value = "'. date("Y-m-15") . '"';} else { echo ' value = "'. date("Y-m-t") . '"';}?> />
			</div>
			<div class="col-xs-4">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="submit" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="container-fluid" style="margin-top: -10px;">
	<div class="row">
		<div class="col-xs-12">
			<hr>
		</div>
	</div>
</div>
<div class="container-fluid" id = "reports" style="text-align: center;">
	<table class="table table-hover" id = "myTable" align="center">
		<thead>
			<th>Employee Name</th>
			<th>Action</th>
		</thead>
		<tbody>
	<?php
		$stmt = "SELECT * FROM `login` where level != 'Admin' and (active != '0' or active is null)";
		$result = $conn->query($stmt);
		if($result->num_rows > 0){
			$acounts = 0;
			while ($row = $result->fetch_assoc()) {	
				$accidd = $row['account_id'];
						$ssql1 = "SELECT count(account_id) as otcount FROM overtime where overtime.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and dateofot BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
						$ssql2 = "SELECT count(account_id) as obcount  FROM officialbusiness where officialbusiness.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and obdate BETWEEN '$date1' and '$date2' ORDER BY obdate ASC";
						$ssql3 = "SELECT count(account_id) as leacount  FROM nleave where nleave.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and dateofleavfr BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
						$ssql4 = "SELECT count(account_id) as undrcount  FROM undertime where undertime.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and dateofundrtime BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
						$ssql5 = "SELECT count(account_id) as cashadv  FROM cashadv where cashadv.account_id = $accidd and state = 'ACashReleased' and cadate BETWEEN '$date1' and '$date2' ORDER BY cadate ASC";
						$ssql6 = "SELECT count(account_id) as loanc  FROM loan_cutoff where loan_cutoff.account_id = $accidd and state = 'CutOffPaid' and '$date1' BETWEEN cutoffdate and enddate and enddate >= '$date2' ORDER BY cutoffdate ASC";
						
						if($_GET['rep'] == 'all'){
							$data1 = $conn->query($ssql1)->fetch_assoc();
							$data2 = $conn->query($ssql2)->fetch_assoc();
							$data3 = $conn->query($ssql3)->fetch_assoc();
							$data4 = $conn->query($ssql4)->fetch_assoc();
							$data5 = $conn->query($ssql5)->fetch_assoc();
							$data6 = $conn->query($ssql6)->fetch_assoc();
							$acounts =  $data1['otcount'] + $data2['obcount'] + $data3['leacount'] + $data4['undrcount'] + $data5['cashadv'] + $data6['loanc'];
							$title = "Overall Report";
						}elseif($_GET['rep'] == 'ot'){
							$data1 = $conn->query($ssql1)->fetch_assoc();
							$acounts = $data1['otcount'];
							$title = "Overtime Report";
						}elseif($_GET['rep'] == 'ob'){
							$data2 = $conn->query($ssql2)->fetch_assoc();
							$acounts = $data2['obcount'];
							$title = "Official Business Report";
						}elseif($_GET['rep'] == 'lea'){
							$data3 = $conn->query($ssql3)->fetch_assoc();
							$acounts = $data3['leacount'];
							$title = "Leave of Absense Report";
						}elseif($_GET['rep'] == 'undr'){
							$data4 = $conn->query($ssql4)->fetch_assoc();
							$acounts = $data4['undrcount'];
							$title = "Undertime Report";
						}elseif($_GET['rep'] == 'ca'){
							$data5 = $conn->query($ssql5)->fetch_assoc();
							$acounts = $data5['cashadv'];
							$title = "Cash Advance Report";
						}elseif($_GET['rep'] == 'loan'){
							$data6 = $conn->query($ssql6)->fetch_assoc();
							$acounts = $data6['loanc'];
							$title = "Loan Report";
						}
						$_SESSION['acounts'] = $acounts;
						if($acounts > 0 ){
							echo '<tr>';	
						}else{
							continue;
						}
						echo '
								<td>'.$row['fname'].' '.$row['lname'] .'</td>
								<td>
									<a style = "width: 250px;" target = "_blank" type = "button" class = "btn btn-primary" href = "?report='.$_GET['rep'] .'&accid='.$row['account_id'] .'"name = "submit"><span class="glyphicon glyphicon-file"></span> '.$title.'
										<span class="badge" style = "color: black; font-size: 13px; margin-left: 7px;">'.$acounts.'</span>
									</a>
								</td>
							</tr>';
				
			}
		}



	?>
		</tbody>
	</table>
</div>
<?php } ?>
<div id = "reportg">
<?php 
	if(isset($_GET['report'])){	
		
		//unset($_SESSION['date']);
		//unset($_SESSION['date0']);
		$accids = mysql_escape_string($_GET['accid']);
		$sql1 = "SELECT * FROM login where login.account_id = $accids limit 1";
		$result1 = $conn->query($sql1);
		$res123 = $result1->fetch_assoc();
		$name123 = $res123['fname'] . ' ' . $res123['lname'];	
		$position = $res123['position'];	
		$department = $res123['department'];
		$empcatergorys = $res123['empcatergory'];
		$leave
			
	?>	
	<h4 style = "margin-left: 10px;">Period: <i><strong><?php echo $cutoffdate11;?></strong></i></h4>
	<h4 style = "margin-left: 10px;">Name: <b><i><?php echo $name123;?></i></b></h4>
	<h4 style = "margin-left: 10px;">Position: <b><i><?php echo $position;?></i></b></h4>
	<h4 style = "margin-left: 10px;">Department: <b><i><?php echo $department;?></i></b></h4>
	<h4 style = "margin-left: 10px;">Category: <b><i><?php echo $empcatergorys;?></i></b></h4>
	<hr>
<?php if($_GET['report'] == 'all' || $_GET['report'] == 'ot'){
		$sql = "SELECT * FROM overtime where overtime.account_id = $accids and (state = 'AAdmin' or state = 'CheckedHR') and dateofot BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
		?> 
<div id = "otrep">
	
	<h4 style="margin-left: 20px;"><i><u> Overtime Report </u></i></h4>
	<hr>
		<table width = "100%"class = "table table-hover " align = "center">
			<thead>				
				<tr>
					<th width="12%">Date File</th>			
					<th width="10%">Date of O.T.</th>
					<th width="10%">From - To</th>
					<th width="15%">Checked by HR</th>
					<th width="5%">OT</th>
					<th width="30%">Reason</th>
					<th width="10%">Official Work Schedule</th>
				</tr>
			</thead>
			<tbody>
	<?php
		$cutofftime2 = 0;	
		while($row = $result->fetch_assoc()){
			$date17 = date("d");
			$dated = date("m");
			$datey = date("Y");		
			$explo = (explode(":",$row['approvedothrs']));
			if($row['oldot'] != null && ($row['state'] == 'AAdmin' || $row['state'] == 'CheckedHR')){
					$oldot = '<b><font color = "red">'. $row['oldot'] . '</font>';
					$hrot = '<b><i>';
					$hrclose = '<br>Based On: <i><font color = "green">'.$row['dareason']. "</font></i>";
			}else{
				$oldot = $row["startofot"] . ' - ' . $row['endofot'];
				$hrot = '<b><i>';
				$hrclose = '';
			}
			if($explo[1] > 0){
				$explo2 = '.5';
			}else{
				$explo2 = '.0';
			}
			if($row['otbreak'] != null){
				$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
			}else{
				$otbreak = "";
			}
			
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));			
			echo
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td style = "text-align:left;">'.  $oldot . $otbreak .'</td>	
					<td>'.$hrot .  $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . $otbreak  .'</td>
					<td><strong>'.$explo[0].$explo2.'</strong></td>
					<td>'.$row["reason"].'</td>
					<td>'.$row["officialworksched"].'</td>					
					</tr>';
		}

		?>

<?php	
	$sql = "SELECT * FROM overtime where overtime.account_id = $accids and (state = 'AAdmin' or state = 'CheckedHR') and dateofot BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$cutofftime2 = 0;	
		$hours12 = 0;
		$minutes12 = 0;
		$seconds1 = 0;
		while($row = $result->fetch_assoc()){
		//hrs:minutes computation
		$time1 = substr($row['startofot'],0,4);
		$time2 = substr($row['endofot'],0,4);
		list($hours, $minutes) = explode(':', $time1);
		$startTimestamp = mktime($hours, $minutes);
		list($hours, $minutes) = explode(':', $time2);
		$endTimestamp = mktime($hours, $minutes);
		$seconds = $endTimestamp - $startTimestamp;
		$minutes = ($seconds / 60) % 60;
		$hours = floor($seconds / (60 * 60));
		$dated = date("F");
		$cutoffs = date("Y-m-16");
		
		if(($row['state'] == 'AAdmin' || $row['state'] == 'CheckedHR') && $row['dateofot'] >= $cutoffs){	
			$cutoffdate = '16 - 30/31';				
			$hrs1 = $row['approvedothrs'];
			$min1 = $row['approvedothrs'];
			list($hours1, $minutes1) = explode(':', $hrs1);
			$startTimestamp1 = mktime($hours1, $minutes1);
			list($hours1, $minutes1) = explode(':', $min1);
			$endTimestamp1 = mktime($hours1, $minutes1);
			$seconds1 =$seconds1 + $endTimestamp1 - $startTimestamp1;
			$minutes1 =$minutes1 + ($seconds1 / 60) % 60;
			$hours1 = $hours1 +floor($seconds1 / (60 * 60));
			$hours12 += $hours1;
			$minutes12 += $minutes1;
		}else if(($row['state'] == 'AAdmin' || $row['state'] == 'CheckedHR') && $row['dateofot'] < $cutoffs){
			$cutoffdate = '1 - 15';
			$hrs1 = $row['approvedothrs'];
			$min1 = $row['approvedothrs'];
			list($hours1, $minutes1) = explode(':', $hrs1);
			$startTimestamp1 = mktime($hours1, $minutes1);
			list($hours1, $minutes1) = explode(':', $min1);
			$endTimestamp1 = mktime($hours1, $minutes1);
			$seconds1 =$seconds1 + $endTimestamp1 - $startTimestamp1;
			$minutes1 =$minutes1 + ($seconds1 / 60) % 60;
			$hours1 = $hours1 +floor($seconds1 / (60 * 60));
				
			$hours12 += $hours1;
			$minutes12 += $minutes1;
			}
		}
		$date17 = date("d");
		if($date17 == 1){
			$date17 = 16;
			$dateda = date("Y-m-d");
			$datade = date("F", strtotime("previous month"));
		}else{
			$datade = date("F") ;
		}
		$hours12 = $hours12;
		$minutetosec = $minutes12;
		$totalmin = $hours12 + $minutes12;
		$totalothrs = date('H:i', mktime(0,$minutes12));
		if(substr($totalothrs,3,5) == 30){
			$point5 = '.5';
		}else{
			$point5 = '';
		}
		echo '<tr ><td colspan = 5 style = "text-align: right; font-size: 16px;"><i id = "totss">Total OT: <u><strong>'. ($hours12 + substr($totalothrs,0,2)) .$point5. ' Hour/s</strong></i></u></td><td colspan = 3></td></tbody></table></form>';
		}
		echo '</tbody></table></div>';
	}
?>

<?php
	}
if($_GET['report'] == 'all' || $_GET['report'] == 'ob'){
	$sql = "SELECT * FROM officialbusiness where officialbusiness.account_id = $accids and (state = 'AAdmin' or state = 'CheckedHR') and obdate BETWEEN '$date1' and '$date2' ORDER BY obdate ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
<h4 style="margin-left: 20px;"><i><u> Official Business Report </u></i></h4>
	<hr>
	<form role = "form" action = "approval.php" method = "get">
		<table width = "100%" class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th width="12%">Date File</th>
					<th width="12%">Date of Request</th>	
					<th width="20%">Check by HR (In - Out)</th>
					<th width="26%">Official Work Schedule</th>
					<th width="30%">Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php
		$cutofftime2 = 0;	
		while($row = $result->fetch_assoc()){
			//end of computation
			$date17 = date("d");
			$dated = date("F");
			$datey = date("Y");	
			$originalDate = date($row['obdate']);
			$newDate = date("M j, Y", strtotime($originalDate));

			$hr = $row['obtimein'] . ' - ' . $row['obtimeout'];			
			echo
				'<tr>
					<td width = 100>'.$newDate.'</td>
					<td>'.date("M j, Y",strtotime($row['obdatereq'])).'</td>	
					<td><i><b>'.$hr.'</b></td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>';
					echo '</tr>';
		}
		?>
		</tbody>
	</table>
</form>
<?php	
		
		}
	}
if($_GET['report'] == 'all' || $_GET['report'] == 'lea'){
		$sql = "SELECT * FROM nleave where nleave.account_id = $accids and state = 'AAdmin' and dateofleavfr BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
?>
<div id = "nleave">
	<h4 style="margin-left: 20px;"><i><u> Leave Report </u></i></h4>
	<hr>
	<table width = "100%" class = "table table-hover" align = "center">
			<thead>					
					<tr>
						<th width="15%">Date File</th>				
						<th width="15%">Date of Leave (Fr - To)</th>
						<th width="10%"># of Day/s</th>
						<th width="10%">Type</th>
						<th width="30%">Reason</th>
						<th width="20%">Checked by HR (Payment)</th>
					</tr>
				</thead>
				<tbody>
	<?php
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				if($row['othersl'] == null || $row['othersl'] == ""){
					$otherslea = "";
				}else{
					$otherslea = ': ' . $row['othersl'];
				}
				if($row['leapay'] == 'wthoutpay'){
					$pay = 'w/o Pay';
				}else{
					$pay = 'w/ Pay';
				}
				echo 
					'<td>'.$newDate.'</td>
															
					<td>Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])) .'<br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"].$otherslea . '</td>	
					<td >'.$row["reason"].'</td>
					<td>'. $pay.'</td></tr>';
		}
		?>
		</tbody>
	</table>
</div>
<?php
}
}
if($_GET['report'] == 'all' || $_GET['report'] == 'undr'){
	$sql = "SELECT * FROM undertime where undertime.account_id = $accids and  (state = 'AAdmin' or state = 'CheckedHR') and dateofundrtime BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
	<h4 style="margin-left: 20px;"><i><u> Undertime Report </u></i></h4>
	<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th width="12%">Date File</th>
					<th width="12%">Date of Undertime</th>					
					<th width="15%">Fr - To (Undertime)</th>
					<th width="15%">Checked by HR</th>
					<th width="11%"># of Hrs/Minutes</th>
					<th width="45%">Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));
	
			$datetoday = date("Y-m-d");
			if($row['edithr'] != ""){
				$oldundr = $row['edithr'];
				$hr = $row['undertimefr'] . ' - ' . $row['undertimeto'];
			}else{
				$oldundr = $row['undertimefr'] . ' - ' . $row['undertimeto'];
				$hr = $row['undertimefr'] . ' - ' . $row['undertimeto'];
			}
			echo 
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofundrtime"])).'</td>							
					<td>'.$oldundr.'</td>
					<td><b>'.$hr.'</td>
					<td>'.$row["numofhrs"].'</td>
					<td>'.$row["reason"].'</td></tr>';
		}
		?>
		</tbody>
	</table>

<?php
}
}
if($_GET['report'] == 'all' || $_GET['report'] == 'ca'){
	$sql = "SELECT * FROM cashadv where cashadv.account_id = $accids and state = 'ACashReleased' and cadate BETWEEN '$date1' and '$date2' ORDER BY cadate ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>	
<h4 style="margin-left: 20px;"><i><u> Cash Advance </u></i></h4>
	<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th>Date File</th>					
					<th>Amount</th>
					<th>Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['cadate']);
			$newDate = date("M j, Y", strtotime($originalDate));

			echo 
				'<tr>
					<td>'.$newDate.'</td>						
					<td>'.$row["caamount"].'</td>
					<td>'.$row["careason"].'</td></tr>';
		}
		?>
		</tbody>
	</table>
<?php
}

}
if($_GET['report'] == 'all' || $_GET['report'] == 'loan'){
	$sql = "SELECT * FROM loan,login,loan_cutoff where login.account_id = loan.account_id  and login.account_id = loan_cutoff.account_id and loan_cutoff.loan_id = loan.loan_id and loan.state = 'ALoan' and '$date1' BETWEEN loan_cutoff.cutoffdate and loan_cutoff.enddate and loan_cutoff.enddate >= '$date2' order by loandate desc";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		
?>
<div id = "loanrep">
<h4 style="margin-left: 20px;"><i><u> Loan Report </u></i></h4>
<table class="table table-hover" style="border-bottom: 1px solid #ddd; ">
		<thead>
			<th>Loan Date</th>
			<th>Loan Amount</th>		
			<th>Cut-Off</th>
			<th>Salary Deduction</th>
			<th>Loan Balance</th>
			<th>Status</th>	
		</thead>
		<tbody>
<?php
		while ($row = $result->fetch_assoc()) {
			$loan_id = $row['loan_id'];
			$stmtss = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'CutOffPaid' and '$date1' BETWEEN loan_cutoff.cutoffdate and loan_cutoff.enddate and loan_cutoff.enddate >= '$date2'";
			$datas = $conn->query($stmtss)->fetch_assoc();
			if($row['loan_id'] != $datas['loan_id']){
				continue;
			}
			$len = substr($datas['duration'],0,2) * 2;			
			$day = substr($datas['cutoffdate'], 8, 10);			
			$cuts = 0;
			$fif = 0;
			$comp = $row['loanamount'];
?>	
			<tr>
				<td rowspan="<?php echo $len+1;?>" style = "border-right: 1px solid #ddd; border-left: 1px solid #ddd; vertical-align: middle; text-align: center;">
					<i><p style="margin-left: 10px;"><?php echo date("M j, Y", strtotime($row['loandate'])); ?></p></i>
				</td>
				<td rowspan="<?php echo $len+1;?>" style = "border-right: 1px solid #ddd;vertical-align: middle; text-align: center;">
					<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['loanamount']); ?></p></i>
				</td>
			</tr>
			<?php
				for($i = 1; $i <= $len; $i++){
					$cuts = 15 * $i;
					$fif += 15;
					if($day == '16'){
						$day = '16';
						$end = 't';
					}else{
						$day = '01';
						$end = '15';
					}
					$comp -= $datas['cutamount'];
					$date1  =	date("Y-m-".$day, strtotime("+15 days", strtotime($datas['cutoffdate'])));
					$date22	=	date("Y-m-".$end."", strtotime('+'.$cuts.' days', strtotime($datas['cutoffdate'])));
					if($date22 <= $date2){
						$states = '<p style = "margin-left: 10px;"><b><font color = "green"> Deducted </font></b></p>';
						$balance = '₱ ' . ($comp);
					}else{
						$states = '<p style = "margin-left: 10px;"><b><font color = "red"> Pending </font></b></p>';
						$balance = ' - ';
					}

			?>
			<tr style="border-right: 1px solid #ddd;">
				<td>
					<i><?php echo '<p style="margin-left: 10px;">'.date("M ".$day.", Y", strtotime('+'.$fif.' days', strtotime($datas['cutoffdate']))) .' - ' . date("M ".$end.", Y", strtotime('+'.$cuts.' days', strtotime($datas['cutoffdate']))).'</p>';
					?></i>
				</td>
				<td>
					<i><p style="margin-left: 10px;">₱ <?php echo number_format($datas['cutamount']); ?></p></i>
				</td>
				<td>
					<i><p style="margin-left: 10px;"> <?php echo $balance; ?></p></i>
				</td>
				<td>
					<i><?php echo $states;?></i>
				</td>
			</tr>
<?php
				if($day == '16'){
					$day = '01';
					$end = '15';
				}else{
					$day = '16';
					$end = 't';
				}
				
			}
		}

?>				
			</tbody>
	</table>
</div>
<?php
	}
}
?>
	<div class="row" id = "backs">
		<div class="col-xs-12">
			<hr>
		</div>
	</div>
	<div class="row" style="margin-top: 30px;">
		<div class="col-xs-4">
			<hr style="border: 1px solid !important; margin-left: 20px; ">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4" align="center">
			<i><p style="margin-left: 20px"> <?php echo $name123;?></p></i>
		</div>
	</div>

<?php
		echo '<div align = "center"><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?report='.$_GET['report'].'&print&accid='.$_GET['accid'].'"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a>';
		echo '<a id = "backs" class = "btn btn-danger" href="javascript:window.open(\'\',\'_parent\',\'\');window.close();"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back</a></div>';
		echo '</div>';
		if(isset($_GET['print'])){
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?report='.$_GET['report'].'&accid='.$_GET['accid'].'";});</script>';
		}
}

?>