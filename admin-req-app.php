<?php 
	session_start();
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] != 'Admin'){
			header("location: index.php");
		}
	}else{
		header("location: index.php");
	}
	date_default_timezone_set('Asia/Manila');
	include("header.php");	
?>
<script type = "text/javascript">
	$(document).ready( function () {
    	$('#myTable').DataTable({
		    "iDisplayLength": 25,
		    "order": [[ 0, "desc" ]]  	
		});	
	});
</script>
<div align = "center" style = "margin-bottom: 30px;">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong><br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br>	<br>	
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary" href = "admin.php">Home</a>
			<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>
			<a href = "admin-emprof.php" type = "button"class = "btn btn-primary"  id = "newuserbtn">Employee Profile</a>	
			<a href = "?login_log" type = "button"class = "btn btn-primary">Login Log</a>		
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>
			<a type = "button"class = "btn btn-primary"  href = "tech-sched.php">Tech Schedule</a>
			<a  type = "button"class = "btn btn-primary  active"  href = "admin-req-app.php"> Approved Request</a>		
			<a type = "button"class = "btn btn-primary"  href = "admin-req-dapp.php">Dispproved Request</a>		
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
		<br><br>
		<div class = "btn-group btn-group">
			<a  type = "button"class = "btn btn-success" href = "?appot"> Approved Overtime </a>
			<a  type = "button"class = "btn btn-success" href = "?appob"> Approved Official Business </a>
			<a  type = "button"class = "btn btn-success" href = "?applea"> Approved Leave  </a>			
			<a  type = "button"class = "btn btn-success" href = "?appundr"> Approved Undertime  </a>	
			<a  type = "button"class = "btn btn-success" href = "?apppety"> Approved Petty  </a>
		</div>
	</div>
</div>
	<?php

		if(isset($_GET['login_log'])){
			include 'login_log.php';
			echo '</div><div style = "display: none;">';
		}
	?>
<?php 
	if(isset($_GET['apploan']) && !isset($_GET['loan'])){
		include("conf.php");
		$sql = "SELECT * FROM loan,login where login.account_id = loan.account_id and state = 'ALoan' order by state ASC";
		$result = $conn->query($sql);		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table id = "myTable" class = "table table-hover" align = "center">
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
	$sql = "SELECT * FROM cashadv,login where login.account_id = cashadv.account_id and cashadv.state = 'ACashReleased' order by cadate desc";
	$result = $conn->query($sql);
?>
	<table id = "myTable" class="table">
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
		$sql = "SELECT * FROM petty,login where login.account_id = petty.account_id and state = 'AAPettyRep' order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
			<h2 align="center"> Approved Petty Request </h2>
			<table id = "myTable" class = "table table-hover" align = "center" >
				<thead>
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
				$newDate = date("M j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				$petid = $row['petty_id'];
				if($row['state'] == 'AAPettyRep'){
					$transcode = $row['transfer_id'];
				}else{
					$transcode = "";
				}
				echo 
					'<tr>						
						<td>'.$newDate.'</td>
						<td>'.$row['petty_id'].'</td>
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
								echo '<font color = "green"><b>Completed</font>';
							}
				echo '</td></tr>';

		}
		
	}echo '</tbody></table>';$conn->close();
}
?> 
	<?php 
	if(isset($_GET['appot'])){

	?>
		<h2 align="center"> Approved Overtime Request</h2>
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>
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
		$cutoffdate = date("Y-m-d");
		$sql = "SELECT * FROM overtime,login where overtime.account_id = login.account_id and (state = 'AAdmin' or state = 'CheckedHR' or state = 'UA') order by datefile desc";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
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

	}echo '</tbody>
		</table>';$conn->close();
}

?>

	<?php 
	if(isset($_GET['appob'])){
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state =  'AAdmin' order by obdate desc";
		$result = $conn->query($sql);
		
			
	?>
		<h2 align="center"> Approved Official Business Request </h2>
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>
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
		if($result->num_rows > 0){
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
		
	}echo '</tbody></table>';$conn->close();
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
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id and state =  'AAdmin'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		
	?>
	<h2 align="center"> Approved Undertime Request </h2>
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>	
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
		if($result->num_rows > 0){
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
			
		}echo '</tbody></table>';
	}
	?>
	<?php 		
	if(isset($_GET['applea'])){
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and state =  'AAdmin' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	
	?>	
		<h2 align="center"> Approved Leave Request </h2>
			<table id = "myTable" class = "table table-hover" align = "center">
				<thead>
					<tr>
						<th width = "170">Date File</th>
						<th width = "120">Name</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "250">Date of Leave (From - To)</th>
						<th width = "100"># of Day/s</th>
						<th width = "170">Type of Leave</th>
						<th>Reason</th>
						<th>State</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
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
		
	}echo '</tbody></table>';$conn->close();
	}?>

<div id = "newuser" class = "form-group" style = "display: none;">
	<form role = "form" action = "newuser-exec.php" method = "post">
		<table id = "myTable" align = "center" width = "450">
			<tr>
				<td colspan = 5 align = "center"><h2>New Account</h2></td>
			</tr>
			<tr>
				<td colspan = 5><h3><font color = "red">Do not use your personal password</font></h3></td>
			</tr>
			<tr>
				<td>Username: </td>
				<td><input placeholder = "Enter Username" pattern=".{4,}" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input placeholder = "Enter Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regpword"/></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input placeholder = "Enter Confirm Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regcppword"/></td>
			</tr>
			<tr>
				<td>Account Level:</td>
				<td>
					<select name = "level" class ="form-control">
						<option value = "">------------
						<option value = "HR">HR
						<option value = "ACC">Accounting
						<option value = "TECH">Technician Supervisor
						<option value = "Admin">Admin
					</select>
				</td>
			</tr>			
			<tr>
				<td colspan = 2 align = center><input class = "btn btn-default" type = "submit" name = "regsubmit" value = "Submit"/></td>
			</tr>
		</table>
	</form>
</div>
<?php include('footer.php');?>