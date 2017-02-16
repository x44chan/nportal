<?php 
	include('conf.php');
		if(empty($_GET['rep'])){
			$_GET['rep'] = 'all';
			$title = 'Over All Report';
		}
		if($_SESSION['level'] == 'Admin'){
			$lk = 'admin-emprof.php?rep';
		}elseif($_SESSION['level'] == 'ACC'){
			$lk = 'acc-report.php?rep';
		}elseif($_SESSION['level'] == 'HR'){
			$lk = 'hr-emprof.php?export';
		}else{
			$lk = 'index.php';
		}
		if(isset($_SESSION['date'])){
			$date1 = $_SESSION['date'];
			$date2 = $_SESSION['date0'];
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));			
		}elseif(date("d") >= 28){
			$date1 = date("Y-m-23");
			$date2 = date("Y-m-07", strtotime("+1 month"));
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}elseif(date("d") <= 13){
			$date1 = date("Y-m-23", strtotime("-1 month"));
			$date2 = date("Y-m-07");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}elseif(date("d") > 13 && date("d") < 28){
			$date1 = date("Y-m-08");
			$date2 = date("Y-m-22");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}
		if(isset($_POST['repfilter'])){
			$_SESSION['date'] = $_POST['repfr'];
			$_SESSION['date0'] = $_POST['repto'];
			echo '<script type = "text/javascript">window.location.replace("'.$lk.'='.$_POST['reptype'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['date']);
			unset($_SESSION['date0']);
			echo '<script type = "text/javascript">window.location.replace("'.$lk.'");</script>';
		}

	$date3 = date("Y-m-d", strtotime($date2));
	$date2 = date("Y-m-d 23:59:59 ", strtotime($date2));
	if(!isset($_GET['report'])){
?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -30px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Report Filtering <?php	if($_SESSION['level'] == 'ACC'){ ?><a href = "?sumar=leasum" class="btn btn-success pull-right"> Employee Leave Summary </a><?php } ?></i></u></h4><br>				
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-3" align="center">
				<label>Type of Reports</label>
				<select class="form-control input-sm" name ="reptype">
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'all'){ echo ' selected '; } ?> value="all">Overall Reports</option>				
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'lea'){ echo ' selected '; } ?> value="lea">Leave Reports</option>					
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ot'){ echo ' selected '; } ?> value="ot">Overtime Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'undr'){ echo ' selected '; } ?> value="undr">Undertime Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'hol'){ echo ' selected '; } ?> value="hol">Holiday Reports</option>					
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ob'){ echo ' selected '; } ?> value="ob">Official Business Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'ca'){ echo ' selected '; } ?> value="ca">Cash Advance Reports</option>
					<option <?php if(isset($_GET['rep']) && $_GET['rep'] == 'loan'){ echo ' selected '; } ?> value="loan">Loan Reports</option>	
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date'] . '" '; } else { echo ' value = "'. $date1 . '"';}?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date0'] . '" '; } else { echo ' value = "'. date("Y-m-d", strtotime($date2)) . '"';}?> />
			</div>
			<div class="col-xs-5">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="submit" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
					<a type="submit" class="btn btn-warning btn-sm" href = "export.php?exlea&date1=<?php echo $date1.'&date2=' .$date2;?>"><span class="glyphicon glyphicon-download-alt"></span> Export OT & Leave </a>
					<a type="submit" class="btn btn-warning btn-sm" href = "export.php?exob&date1=<?php echo $date1.'&date2=' .$date2;?>"><span class="glyphicon glyphicon-download-alt"></span> Export OB </a>
				</div>
			</div>
		</div>
	</div>
</form>
<?php if($_SESSION['level'] == 'HR'){
	echo '</div><div style = "display: none;">';
}
?>
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
						$ssql2 = "SELECT count(account_id) as obcount  FROM officialbusiness where officialbusiness.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and obdatereq BETWEEN '$date1' and '$date2'  ORDER BY obdate ASC";
						$ssql3 = "SELECT count(account_id) as leacount  FROM nleave where nleave.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR' or state = 'CLea' or state = 'ReqCLea' or state = 'ReqCLeaHR') and (dateofleavfr BETWEEN '$date1' and '$date2' or dateofleavto BETWEEN '$date1' and '$date2') ORDER BY datefile ASC";
						$ssql4 = "SELECT count(account_id) as undrcount  FROM undertime where undertime.account_id = $accidd and (state = 'AAdmin' or state = 'CheckedHR') and dateofundrtime BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
						$ssql5 = "SELECT count(account_id) as cashadv  FROM cashadv where cashadv.account_id = $accidd and state = 'ACashReleased' and cadate BETWEEN '$date1' and '$date2' ORDER BY cadate ASC";
						$ssql6 = "SELECT count(loan_cutoff.account_id) as loanc,loan_cutoff.state,loan_cutoff.loan_id,loan_cutoff.enddate,loan_cutoff.full,loan.*,loan_cutoff.account_id  FROM loan_cutoff,loan where loan_cutoff.loan_id = loan.loan_id and loan.account_id = $accidd and loan_cutoff.account_id = $accidd and loan.state = 'ALoan' and (loan_cutoff.enddate between '$date1' and '$date2' or loan_cutoff.full BETWEEN '$date1' and '$date2') order by loandate desc limit 1";
						$ssql8 = "SELECT count(account_id) as holcount FROM holidayre where holidayre.account_id = $accidd and state = '2' and holiday BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
						
						if($_GET['rep'] == 'all'){
							$data1 = $conn->query($ssql1)->fetch_assoc();
							$data2 = $conn->query($ssql2)->fetch_assoc();
							$data3 = $conn->query($ssql3)->fetch_assoc();
							$data4 = $conn->query($ssql4)->fetch_assoc();
							$data5 = $conn->query($ssql5)->fetch_assoc();
							$data6 = $conn->query($ssql6)->fetch_assoc();
							$data8 = $conn->query($ssql8)->fetch_assoc();
							if($data6['loanc'] > 0){
								$data6['loanc'] = 1;
							}
							$acounts =  $data1['otcount'] + $data2['obcount'] + $data3['leacount'] + $data4['undrcount'] + $data5['cashadv'] + $data6['loanc'] + $data8['holcount'];
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
							if($data6['loanc'] > 0){
								$data6['loanc'] = 1;
							}
							$acounts = $data6['loanc'];
							$title = "Loan Report";
						}elseif($_GET['rep'] == 'hol'){
							$data8 = $conn->query($ssql8)->fetch_assoc();
							$acounts = $data8['holcount'];
							$title = "Holiday Report";
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
			if($row['dateofot'] < $date1 && date("Y-m-d", strtotime($row['datehr'])) == date("Y-m-d", strtotime($date1))){
				$adjust = '<a id = "backs" onclick = "setTimeout(\'window.location.href=window.location.href\', 100); return confirm(\'Are you sure?\');"target = "_blank" href = "exec.php?overtime_id=' . $row['overtime_id'] . '&account_id='.$_GET['accid'].'" class = "btn btn-sm btn-danger" onclick = "return confirm(\'Are you sure?\');"> Adjust </a> ';
			}else{
				$adjust = '';
			}
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
			if($row['projtype'] != ""){
				$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
			}else{
				$project = "";
			}
			if($row['project'] == ""){
				$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
			}
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));			
			echo
				'<tr>
					<td>'.$adjust. ' ' . $newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td style = "text-align:left;">'.  $oldot . $otbreak .'</td>	
					<td>'.$hrot .  $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . $otbreak  .'</td>
					<td><strong>'.$explo[0].$explo2.'</strong></td>
					<td>'.$row["reason"]. $project.'</td>
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
	$sql = "SELECT * FROM officialbusiness where officialbusiness.account_id = $accids and (state = 'AAdmin' or state = 'CheckedHR') and obdatereq BETWEEN '$date1' and '$date2' ORDER BY obdate ASC";
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
			if($row['obdatereq'] < $date1 && date("Y-m-d", strtotime($row['datehr'])) == date("Y-m-d", strtotime($date1))){
				$adjust = '<a id = "backs"  onclick = "setTimeout(\'window.location.href=window.location.href\', 100); return confirm(\'Are you sure?\');"target = "_blank" href = "exec.php?officialbusiness_id=' . $row['officialbusiness_id'] . '&account_id='.$_GET['accid'].'" class = "btn btn-sm btn-danger" onclick = "return confirm(\'Are you sure?\');"> Adjust </a> ';
			}else{
				$adjust = '';
			}
			//end of computation
			$date17 = date("d");
			$dated = date("F");
			$datey = date("Y");	
			$originalDate = date($row['obdate']);
			$newDate = date("M j, Y", strtotime($originalDate));
			$hr = $row['obtimein'] . ' - ' . $row['obtimeout'];
			if($row['nxtday'] == 1){
				$nxtday = "<br>(next day out)";
			} else {
				$nxtday = "";
			}
			echo
				'<tr>
					<td width = 100>'.$adjust .$newDate.'</td>
					<td>'.date("M j, Y",strtotime($row['obdatereq'])).'</td>	
					<td><i><b>'.$hr.'</b></td>
					<td>'.$row["officialworksched"]. $nxtday.'</td>				
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
		$sql = "SELECT * FROM nleave where nleave.account_id = $accids and (state = 'AAdmin' or state = 'CheckedHR' or state = 'CLea' or state = 'ReqCLea' or state = 'ReqCLeaHR') and (dateofleavfr BETWEEN '$date1' and '$date2' or dateofleavto BETWEEN '$date1' and '$date2') ORDER BY datefile ASC";
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
				if($row['state'] == 'ReqCLea'){
					$pay = $pay .'<br> <b><font color = "red"> Pending Cancelation Request<br> to H.R.</font></b>';
				}elseif($row['state'] == 'ReqCLeaHR'){
					$pay = $pay .'<br> <b><font color = "red"> Pending Cancelation Request<br> to Admin </font></b>';
				}elseif($row['state'] == 'CLea'){
					$pay = $pay .'<br> <b><font color = "red"> Approved Leave Cancelation Request </font></b>';
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
			if($row['source'] != "" && $_GET['report'] != 'all'){
				$source = "( " . $row['source'] . " )";
			}else{
				$source = "";
			}
			echo 
				'<tr>
					<td>'.$newDate.'<br>'.$source .'</td>						
					<td>₱ '.number_format($row["caamount"]).'</td>
					<td>'.$row["careason"].'</td>
				</tr>';
		}
		?>
		</tbody>
	</table>
<?php
}

}
if($_GET['report'] == 'all' || $_GET['report'] == 'hol'){
	$sql = "SELECT * FROM holidayre where holidayre.account_id = $accids and state = 2 and holiday BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>	
<h4 style="margin-left: 20px;"><i><u> Holiday Report </u></i></h4>
	<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th width="10%">Date File</th>
					<th width="10%">Date of Request</th>
					<!--<th width="17%">Time</th>-->
					<th width="17%">Type</th>
					<th width="20%">Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){	
			/*if($row['oldtime'] != ""){
				$hrcheck = 'App Time: <i><font color = "green">' . $row['timein'] . ' - ' .$row['timeout'] . '</i></font><br>';
				$hrcheck .= 'Based On: <i><font color = "red">' . $row['dareason'] . '</font></i><br> Filed Time: <i><font color = "red">' . $row['oldtime'] . '</font></i>';
			}else{
				$hrcheck = $row['timein'] . ' - ' . $row['timeout'];
			}*/
			echo 
				'<tr>
					<td>'.date("M j, Y", strtotime($row['datefile'])).'</td>						
					<td>'.date("M j, Y", strtotime($row['holiday'])).'</td>
					<!--<td><b></td>-->
					<td>'.$row['type'].'</td>
					<td>'.$row['reason'].'</td>
				</tr>';
		}
		?>
		</tbody>
	</table>
<?php
}

}
if($_GET['report'] == 'all' || $_GET['report'] == 'loan'){
	$sql = "SELECT * FROM loan,login,loan_cutoff where login.account_id = $accids  and loan_cutoff.account_id = $accids and loan_cutoff.loan_id = loan.loan_id and loan.state = 'ALoan' and (loan_cutoff.enddate between '$date1' and '$date2' or loan_cutoff.full BETWEEN '$date1' and '$date2') order by loandate desc limit 1";
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
			$loanamount = $row['appamount'];
			$stmtss = "SELECT count(account_id) as asd FROM `loan_cutoff` where loan_id = '$loan_id'";
			$datas = $conn->query($stmtss)->fetch_assoc();
			$len = $datas['asd'];	
			if($row['penalty'] == '1'){
				$row['penalty'] = '<b><font color = "red"> Penalty Loan </font></b><br>';
			}else{
				$row['penalty'] = '<b> Salary Loan </b><br>';
			}
			if($row['source'] != "" && $_GET['report'] != 'all'){
				$source = "( " . $row['source'] . " )";
			}else{
				$source = "";
			}
?>	
			<tr>
				<td rowspan="<?php echo $len+1;?>" style = "border-right: 1px solid #ddd; border-left: 1px solid #ddd; vertical-align: middle; text-align: center;">
					<i><p style="margin-left: 10px;"><?php echo  $row['penalty']. date("M j, Y", strtotime($row['loandate'])); ?><br><?php echo $source; ?> </p></i>
				</td>
				<td rowspan="<?php echo $len+1;?>" style = "border-right: 1px solid #ddd;vertical-align: middle; text-align: center;">
					<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['loanamount']); ?></p></i>
				</td>
			</tr>
<?php				
	}
	$sql = "SELECT * FROM loan_cutoff where loan_id = '$loan_id'";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			$lineto = "";
			$row['cutamount'] = str_replace(",", "", $row['cutamount']);
			if(date("Y-m-d") >= $row['enddate'] && $row['state'] != 'Cancel'){ 
				$loanamount -= $row['cutamount'];
				$ech = '₱ '.number_format($loanamount,2); 
			} else {
				$ech = ' - ';
			}
			if($row['state'] == 'Advance'){
				$stat = '<b><p id = "gree"><font color = "green"> Advance: '. date("M j, Y", strtotime($row['full'])).' </font></p></b>';
				$loanamount -= $row['cutamount'];
				$ech = '₱ '.number_format($loanamount,2); 	
			}elseif(date("Y-m-d") >= $row['enddate'] && $row['state'] == 'CutOffPaid'){ 
				$stat = '<b><p id = "green"><font color = "green">Deducted</font></p></b>'; 
			}elseif(date("Y-m-d") < $row['enddate'] && $row['state'] == 'CutOffPaid'){
				$stat = '<b><p id = "red"><font color = "red"> Pending </font></p></b>';
			}elseif($row['state'] == 'Cancel'){
				$stat = '<b><p id = "red"><font color = "red"> Moved </font></p></b>';
				$lineto = " style = 'text-decoration: line-through;'";
			}elseif($row['state'] == 'Full'){
				$stat = '<b><p id = "gree"><font color = "green"> Fully Paid as of '. date("M j, Y", strtotime($row['full'])).' </font></p></b>';
				$ech = '₱ '. 0;
			}
			echo '<tr '.$lineto.' style = "border-right: 1px solid #ddd; border-left: 1px solid #ddd;">';
				echo '<td>'.date("M j, Y", strtotime($row['cutoffdate'])) . ' - ' . date("M j, Y", strtotime($row['enddate'])).'</td>';
				echo '<td>'. '₱ ' . number_format($row['cutamount'],2).'</td>';
				echo '<td>'.$ech.'</td>';
				echo '<td>'.$stat.'</td>';
			echo '</tr>';
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