<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] != 'TECH'){
			header("location: index.php");
		}
	}else{
		header("location: index.php");
	}
	date_default_timezone_set('Asia/Manila');
	include("header.php");	
?>
<div align = "center" style = "margin-bottom: 30px; ">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong><br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br>	<br>	
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary" href = "techsupervisor.php?ac=penot">Home</a>	
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
				  <li><a href="#" id = "newundertime">Undertime Request Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
				  <?php
				  	if($_SESSION['category'] == "Regular"){
				  ?>
				  	<li class="divider"></li>
				  	<li><a href="#"  data-toggle="modal" data-target="#cashadv">Cash Advance Form</a></li>
				  	<li><a href="#"  data-toggle="modal" data-target="#loan">Loan Form</a></li>
				  <?php
				  	}
				  ?>
				</ul>
			</div>
			<a  type = "button"class = "btn btn-primary"  href = "tech-sched.php" >Tech Scheduling</a>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">My Request Status <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "req-all.php?appot">All Request</a></li>
				  <li><a href = "techsupervisor-app.php">My Approved Request</a></li>
				  <li><a href = "techsupervisor-dapp.php">My Approved Request</a></li>	
				</ul>
			</div>	
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
		<br><br>
		<div class = "btn-group btn-group">
			<a  type = "button"class = "btn btn-success" href = "?appot"> Approved Overtime </a>
			<a  type = "button"class = "btn btn-success" href = "?appob"> Approved Official Business </a>
			<a  type = "button"class = "btn btn-success" href = "?applea"> Approved Leave  </a>			
			<a  type = "button"class = "btn btn-success" href = "?appundr"> Approved Undertime  </a>	
			<a  type = "button"class = "btn btn-success" href = "?apppety"> Approved Petty  </a>
			<?php if($_SESSION['category'] == "Regular"){?>
			<a  type = "button"class = "btn btn-success" href = "?appca"> Approved Cash Advance  </a>	
			<a  type = "button"class = "btn btn-success" href = "?apploan"> Approved Loan </a>
			<?php } ?>
		</div>
	</div>
</div>
<?php 
	if(isset($_GET['loan']) && $_GET['loan'] > 0){
		include("caloan/loan.php");
		}
?>
<?php 
	if(isset($_GET['apploan']) && !isset($_GET['loan'])){
		include("conf.php");
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid and state = 'ALoan' order by state ASC";
		$result = $conn->query($sql);		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 8 align = center><h2> Loan Request Status </h2></td>
					</tr>
					<tr>
						<th>Loan #</th>
						<th>Date File</th>
						<th>Amount</th>
						<th>Start Date</th>
						<th>Approved Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$loan_id = $row['loan_id'];
				$stmts = "SELECT sum(cutamount) as cutamount,loan_id,cutoffdate,enddate FROM `loan_cutoff` where loan_id = '$loan_id'";
				$data = $conn->query($stmts)->fetch_assoc();
				if(date("Y-m-d") > $data['enddate']){
					$comp = '<b><font color = "green">Completed</font></b>';
				}if($row['appamount'] != null && $row['appamount'] == $row['loanamount'] && $row['loan_id'] == $data['loan_id']){

				}else{
					continue;
				}
				echo	'<tr>';
					echo	'<td>' . $row['loan_id'].'</td>';
					echo	'<td>' . date("M j, Y", strtotime($row['loandate'])).'</td>';
					echo	'<td>&#8369; ' . number_format($row['loanamount'])  .'</td>';
					echo	'<td>' . date("M j, Y", strtotime($data['cutoffdate'])) . '</td>';
					echo	'<td>&#8369; '.number_format($row['appamount']).'</td>';
					echo	'<td style = "width: 300px;">';
								if($row['state'] == 'UALoan'){
									echo '<b>Pending to Admin</b>';
								}elseif($row['state'] == 'DALoan'){
									echo '<b><font color = "red">Dispproved by the Admin</font></b>';
								}elseif($row['state'] == 'DECLoan'){
									echo '<b><font color = "red">Declined</font></b>';
								}elseif($row['appamount'] != null && $row['appamount'] != $row['loanamount']){
									echo '<a href = "?uploan='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Update Requested Amount</a> ';									
								}elseif($row['state'] == 'ARcvLoan'){
									echo '<a href = "petty-exec.php?loan='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Loan</a> ';
									echo '<a href = "loan-exec.php?loanss='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-danger">Decline</a>';
								}elseif($row['state'] == 'ARcvCashCode'){
									echo '<font color = "green"><b>Received ';
									echo '</font></br>Code: ' . $row['rcve_code'];
								}elseif($row['state'] == 'ALoan' && date("Y-m-d") > $data['enddate']){
									echo '<b><font color = "green">Completed</font></b>';
								}elseif($row['appamount'] != null && $row['appamount'] == $row['loanamount'] && $row['loan_id'] == $data['loan_id']){
									echo '<a href = "?loan='.$row['loan_id'].'&apploan" class = "btn btn-success">View Request</a>';
								}
					echo	'</td>';
				echo '</tr>';
			}
		}
		echo '</tbody></table>';
	}
?>
<?php 
	if(isset($_GET['appca'])){

	include 'conf.php';
	$sql = "SELECT * FROM cashadv,login where login.account_id = $accid and cashadv.account_id = $accid and cashadv.state = 'ACashReleased' order by cadate desc";
	$result = $conn->query($sql);
?>
	<table class="table">
		<thead>
			<tr>
				<td colspan = 8 align = center><h2> Cash Advance Request Status </h2></td>
			</tr>
			<tr>
				<th width="20%">Date File</th>
				<th width="20%">Amount</th>
				<th width="40%">Reason</th>
				<th width="20%">State</th>
			</tr>
		</thead>
		<tbody>
<?php
	if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			echo '<tr><td>' . date("M j, Y", strtotime($row['cadate'])) . '</td>';
			echo '<td>₱ ' . number_format($row['caamount']) . '</td>';
			echo '<td>' . $row['careason'] . '</td>';
			echo '<td><b>';
				if($row['state'] == 'UACA'){
					echo 'Pending to Admin';
				}elseif($row['state'] == 'DACA'){
					echo '<font color = "red">Disapproved by the Admin</font>';
				}elseif($row['state'] == 'ACash'){
					echo '<a href = "petty-exec.php?cashadv='.$row['cashadv_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Cash Advance</a>';
				}elseif($row['state'] == 'ARcvCash'){
					echo '<font color = "green">Received</font><br>Code: '.$row['rcve_code'];
				}elseif($row['state'] == 'ACashReleased'){
					echo '<font color = "green">Completed</font>';
				}
			echo '</b></td>';
			echo '</tr>';
		}
	}
?>
		</tbody>
	</table>

<?php
	} 
	if(isset($_GET['apppety'])){

		include("conf.php");
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid and state = 'AAPettyRep' order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 8 align = center><h2> Pending Petty Request </h2></td>
					</tr>
					<tr>
						<th>Petty #</th>
						<th>Date File</th>
						<th>Name</th>
						<th>Particular</th>
						<th>Source</th>
						<th>Transfer Code</th>
						<th>Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['date']);
				$newDate = date("F j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				$petid = $row['petty_id'];
				if($row['state'] == 'AAPettyRep'){
					$transcode = $row['transfer_id'];
				}else{
					$transcode = "";
				}
				echo 
					'<tr>
						<td>'.$row['petty_id'].'</td>
						<td>'.$newDate.'</td>
						<td>'.$row['fname'] . ' '. $row['lname'].'</td>
						<td>'.$row['particular'].'</td>
						<td>'.$row['source'].'</td>
						<td>'.$transcode.'</td>
						<td>&#8369; '.$row['amount'].'</td>
						<td>';
							if($row['state'] == "UAPetty"){
								echo '<b>Pending to Admin';
							}elseif($row['state'] == 'AAAPettyReceive'){
								echo '<a href = "petty-exec.php?o='.$row['petty_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Petty</a>';
							}elseif($row['state'] == 'DAPetty'){
								echo 'Disapproved request';
							}elseif($row['state'] == 'AAPettyReceived'){
								echo '<font color = "green"><b>Received ';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPetty'){
								echo '<font color = "green"><b>Pending to Accounting</font>';
							}elseif($row['state'] == 'AAPettyRep'){
								$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
								$data = $conn->query($sql)->fetch_assoc();
								if($data['petty_id'] == null){
									echo '<a class = "btn btn-danger" href = "?lqdate=' . $row['petty_id'] . '"/> To Liquidate </a>';
								}elseif($data['liqstate'] == 'EmpVal'){
									echo '<font color = "green"><b>Liquidated</font><br>';
									echo '<a href = "?validate=' . $petid . '" class = "btn btn-success">Validate Code</a>';
								}elseif($data['liqstate'] == 'CompleteLiqdate'){
									echo '<font color = "green"><b>Completed</font>';
								}elseif($data['liqstate'] == 'LIQDATE'){
									echo '<b>Pending Completion</b>';
								}
							}
				echo '</td></tr>';

		}
		
	}echo '</tbody></table></form>';$conn->close();
}
?> 
<?php 
	if(isset($_GET['appot'])){
		if(isset($_GET['prev'])){
			echo '<a href = "?appot" class="pull-left btn btn-primary" style="margin-left: 10px; margin-top: -20px;"> Current Cutoff </a>';
		}else{
			echo '<a href = "?appot&prev" class="pull-left btn btn-primary" style="margin-left: 10px; margin-top: -20px;"> Previos Cutoff </a>';
		}
		if(isset($_GET['prev'])){
			$date17 = date("d");
			if($date17 >= 17){
				$forque = date('Y-m-01');
				$endque = date('Y-m-15');
			}else{
				$forque = date('Y-m-16');
				$endque = date('Y-m-31');
			}
		}
	?>
		<form role = "form" action = "approval.php" style="margin-top: -60px;" method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> My Approved Overtime Request <?php if(isset($_GET['prev'])){ echo '(<font color = "red"><i>Previos Cut-Off</i></font>)'; } ?></h2></td>
				</tr>
				<tr>
					<th>Date File</th>
					<th>Date of Overtime</th>
					<th>Name of Employee</th>
					<th>Reason</th>
					<th>From - To (Overtime)</th>
					<th>Offical Work Schedule</th>
					<th>State</th>
				</tr>
			</thead>
			<tbody>
<?php
		include("conf.php");
		if(isset($_GET['prev'])){
			$date17 = date("d");
			if($date17 >= 17){
				$forque = date('Y-m-01');
				$endque = date('Y-m-15');
			}else{
				$forque = date('Y-m-16');
				$endque = date('Y-m-31', strtotime("previous month"));
			}
			if(date("d") >= 2 && date("d") < 17){
				$forque = date('Y-m-16', strtotime("previous month"));
				$endque = date('Y-m-t', strtotime("previous month"));
			}
			$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and dateofot BETWEEN '$forque' and '$endque' and state = 'AAdmin'";
		}else{
			$date17 = date("d");
			if($date17 >= 17){
				$forque = date('Y-m-16');
				$endque = date('Y-m-31');
			}else{
				$forque = date('Y-m-01');
				$endque = date('Y-m-15');
			}
			if(date("d") < 2){
				$forque = date('Y-m-16', strtotime("previous month"));
				$endque = date('Y-m-d');
			}
			$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and dateofot BETWEEN '$forque' and '$endque' and state = 'AAdmin'";
		}
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<?php
		$cutofftime2 = 0;	
		while($row = $result->fetch_assoc()){
			//end of computation
			$date17 = date("d");
			$dated = date("F");
			$datey = date("Y");
			
			$explo = (explode(":",$row['approvedothrs']));
			if($explo[1] > 0){
				$explo2 = '.5';
			}else{
				$explo2 = '.0';
			}
			
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));
			echo
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"].'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot']. ' /<strong> OT: '. $explo[0].$explo2 .'</strong></td>
					<td>'.$row["officialworksched"].'</td>					
					<td><b>';
						if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else{
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}
				echo '</td></tr>';
		}

	}$conn->close();

?>
</tbody>
		</table>
		</form>
<?php
	include("conf.php");
		if(isset($_GET['prev'])){
			$date17 = date("d");
			if($date17 >= 17){
				$forque = date('Y-m-01');
				$endque = date('Y-m-15');
			}else{
				$forque = date('Y-m-16');
				$endque = date('Y-m-31', strtotime("previous month"));
			}
			if(date("d") == 2){
				$forque = date('Y-m-16', strtotime("previous month"));
				$endque = date('Y-m-t', strtotime("previous month"));
			}
			$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and dateofot BETWEEN '$forque' and '$endque' and state = 'AAdmin'";
		}else{
			$date17 = date("d");
			if($date17 >= 17){
				$forque = date('Y-m-16');
				$endque = date('Y-m-31');
			}else{
				$forque = date('Y-m-01');
				$endque = date('Y-m-15');
			}
			if(date("d") < 2){
				$forque = date('Y-m-16', strtotime("previous month"));
				$endque = date('Y-m-d');
			}
			$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and dateofot BETWEEN '$forque' and '$endque' and state = 'AAdmin'";
		}
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
		
		if($row['state'] == 'AAdmin' && $row['dateofot'] >= $cutoffs){	
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
		}else if($row['state'] == 'AAdmin' && $row['dateofot'] < $cutoffs){
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
		$cutoffdate = date("M j, Y", strtotime($forque)) . ' - ' . date("M j, Y", strtotime($endque));
		$hours12 = $hours12;
		$minutetosec = $minutes12;
		$totalmin = $hours12 + $minutes12;
		$totalothrs = date('H:i', mktime(0,$minutes12));
		if(substr($totalothrs,3,5) == 30){
			$point5 = '.5';
		}else{
			$point5 = '';
		}
		echo '<div align = "center">Total OT: <strong>'. ($hours12 + substr($totalothrs,0,2)) .$point5. ' Hour/s </strong>for '.$cutoffdate . '</strong></div>';
	}
}
?>

	<?php 
	if(isset($_GET['appob'])){
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and state =  'AAdmin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			
	?>
	<form role = "form" action = "approval.php" style="margin-top: -50px;" method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> My Approved Official Business Request </h2></td>
				</tr>
				<tr>
					<th>Date File</th>
					<th>Name of Employee</th>
					<th>Position</th>
					<th>Department</th>
					<th>Date of Request</th>
					<th>Time In - Time Out</th>
					<th>Offical Work Schedule</th>
					<th>Reason</th>
					<th>State</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){
			$originalDate = date($row['obdate']);
			$newDate = date("M j, Y", strtotime($originalDate));
			echo 
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.$row["obename"].'</td>
					<td>'.$row["obpost"].'</td>
					<td >'.$row["obdept"].'</td>
					<td>'.date("M j, Y", strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>	
					<td><b>';
						if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else{
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}
				echo '</td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
}?>
	<?php 
	if(isset($_GET['appundr'])){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 >= 16){
			$forque = 16;
			$endque = 31;
			}else{
			$forque = 1;
			$endque = 15;
		}
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid and state =  'AAdmin'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php" style="margin-top: -50px;" method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> My Approved Undertime Request </h2></td>
				</tr>
				<tr>
					<th>Date File</th>
					<th>Date of Undertime</th>
					<th>Name of Employee</th>
					<th>Reason</th>
					<th>From - To (Overtime)</th>
					<th>Number of Hrs/Minutes</th>
					<th>State</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));

			$datetoday = date("Y-m-d");
			echo 
			'<tr>
				<td width = 180>'.$newDate.'</td>
				<td>'.date("M j, Y", strtotime($row["dateofundrtime"])).'</td>
				<td>'.$row["name"].'</td>
				<td width = 250 height = 70>'.$row["reason"].'</td>
				<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
				<td>'.$row["numofhrs"].'</td>
				<td><b>';
					if($row['state'] == 'AHR'){
						echo '<p><font color = "green">Approved by HR</p>';
					}else if($row['state'] == 'AACC'){
						echo '<p><font color = "green">Approved by Accounting</p>';
					}else{
						echo '<p><font color = "green">Approved by Dep. Head</p>';
					}
						echo '</td></tr>';
					}
			echo '</tbody></table></form>';
		}
	}
	?>
	<?php 		
	if(isset($_GET['applea'])){
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and state =  'AAdmin' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	?>	
	<form role = "form" action = "approval.php" style="margin-top: -50px;" method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center><h2> My Approved Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "170">Date File</th>
						<th width = "120">Name</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "250">Date of Leave (From - To)</th>
						<th width = "100">No. of Day/s</th>
						<th width = "170">Type of Leave</th>
						<th>Reason</th>
						<th>State</th>
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
				echo 
					'<td>'.$newDate.'</td>
					<td>'.$row["nameofemployee"].'</td>
					<td>'.date("M j, Y", strtotime($row["datehired"])).'</td>
					<td >'.$row["deprt"].'</td>
					<td>'.$row['posttile'].'</td>					
					<td>Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])).'<br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					<td >'.$row["reason"].'</td>	
					<td width = "150"><b>';
						if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else{
							echo '<p><font color = "green">Approved by <br>Dep. Head</p>';
						}
				echo '</td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
	}?>



<?php include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'defaultpass'){
		include('up-pass.php');
	}else if($_SESSION['201date'] == null){
	?>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
    backdrop: 'static',
    keyboard: false
  });
});
</script>
<?php } include("req-form.php");?>
<?php include("footer.php");?>