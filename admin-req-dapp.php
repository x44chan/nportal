<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
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
	$(function(){
			$("#dappot").hide();
			$("#fordappot").on("click", function(){
				$("#dappot").show();
				$("#newuser").hide();
				$("#dappob").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#formhidden").hide();
				$("#disappleave").hide();
				$('#disappundr').hide();
			});
			
			$("#disappundr").hide();
			$("#fordappundr").on("click", function(){
				$("#disappundr").show();
				$("#dappot").hide();
				$("#undertime").hide();
				$("#dappob").hide();
				$("#formhidden").hide();
				$("#newuser").hide();
				$("#disappleave").hide();
			});
			
			$("#disappleave").hide();
			$("#fordisappleave").on("click", function(){
				$("#disappleave").show();
				$("#disappundr").hide();
				$("#dappot").hide();
				$("#undertime").hide();
				$("#dappob").hide();
				$("#formhidden").hide();
				$("#newuser").hide();
			});
			
			$("#dappob").hide();
			$("#fordappob").on("click", function(){	
				$("#disappundr").hide();
				$("#dappob").show();
				$("#dappot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#disappleave").hide();
				$("#formhidden").hide();
				$("#newuser").hide();
			});
			$("#newuserbtn").on("click", function(){
				$("#newuser").show();
				$("#dash").hide();
				$("#dappot").hide();
				$("#dappob").hide();
				$("#undertime").hide();
				$("#disappundr").hide();
				$("#disappleave").hide();
			});
	});
</script>
<div align = "center" style = "margin-bottom: 30px; ">
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
			<a  type = "button"class = "btn btn-primary"  href = "admin-req-app.php" >Approved Request</a>		
			<a  type = "button"class = "btn btn-primary  active"  href = "admin-req-dapp.php">Dispproved Request</a>		
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
		<br><br>
		<div class = "btn-group btn-group">
			<button  type = "button"class = "btn btn-success" id = "fordappot"> Disapproved Overtime Request </button>
			<button  type = "button"class = "btn btn-success" id = "fordappob"> Disapproved Official Business Request </button>			
			<button  type = "button"class = "btn btn-success" id = "fordisappleave"> Disapproved Leave Request </button>		
			<button  type = "button"class = "btn btn-success" id = "fordappundr"> Disapproved Undertime Request </button>	
		</div>
	</div>
</div>
<?php

if(isset($_GET['login_log'])){
		include 'login_log.php';
		echo '</div><div style = "display: none;">';
	}?>
<div id = "dappot" style = "margin-top: -30px; display: none;">
	<?php 
	if(isset($_GET['login_log'])){
		include 'login_log.php';
		echo '</div><div style = "display: none;">';
	}
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and state = 'DAAdmin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> Disapproved Overtime Request </h2></td>
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
		while($row = $result->fetch_assoc()){
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));
			echo
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.date('M j, Y', strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"].'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot'].'</td>
					<td>'.$row["officialworksched"].'</td>					
					<td><strong><font color = "red">Disapproved</font></approved></td>
				</tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
?>
</div>
<div id = "dappob" style = "margin-top: -30px; display: none;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'DAAdmin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Disapproved Official Business Request </h2></td>
				</tr>
				<tr>
					<th width = "105">Date File</th>
					<th>Name of Employee</th>
					<th>Position</th>
					<th>Department</th>
					<th>Date of Request</th>
					<th width = "150">Time In - Time Out</th>
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
					<td>'.date('M j, Y', strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>	
					<td><b><p><font color = "red">Disapproved</p><td>
				</tr>';
		}
		echo '</tbody></table></form></div>';
	}$conn->close();
	?>
</div>

<div id = "disappundr" style = "display: none; margin-top: -30px;">
	<?php 
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
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id and state = 'DAAdmin'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> Dispproved Undertime Request </h2></td>
				</tr>
				<tr >
					<th>Date File</th>
					<th>Date of Undertime</th>
					<th>Name of Employee</th>					
					<th>From - To (Overtime)</th>
					<th>Number of Hrs/Minutes</th>
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
			echo 
				'<tr>
					<td width = 105>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>					
					<td width = 200>'.date("M j, Y", strtotime($row["undertimefr"])). ' - ' . date("M j, Y", strtotime($row['undertimeto'])).'</td>
					<td>'.$row["numofhrs"].'</td>
					<td>'.$row["reason"].'</td>
					<td><b>';
						if($row['state'] == 'UA'){
							echo 'Pending';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved</p>';
						}else{
							echo '<p><font color = "red">Disapproved</p>';
						}
				echo '<td>
			</tr>';
			}
			echo '</tbody></table></form></div>';
		}
?>
</div>

<div id = "disappleave" style = "display: none; margin-top: -30px;">
	<?php 
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and state = 'DAAdmin' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	?>	
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center><h2> Approved Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "105">Date File</th>
						<th >Name of Employee</th>
						<th width = "105">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "200">Date of Leave (From - To)</th>
						<th width = "105">No. of Day/s</th>
						<th width = "150">Type of Leave</th>
						<th >Reason</th>
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
					<td>'.date("M j, Y", strtotime($row["dateofleavfr"])) .' - '. date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					<td >'.$row["reason"].'</td>	
					<td><b>';
							if($row['state'] == 'UA'){
								echo 'Pending';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved</p>';
							}else{
								echo '<p><font color = "red">Disapproved</p>';
							}
						echo '<td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
	?>
</div>


<div id = "newuser" class = "form-group" style = "display: none;">
	<form role = "form" action = "newuser-exec.php" method = "post">
		<table align = "center" width = "450">
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
<?php include('req-form.php');?>
<?php include('footer.php');?>