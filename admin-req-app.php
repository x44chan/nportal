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
			$("#appot").hide();
			$("#forappot").on("click", function(){
				$("#appot").show();
				$("#appob").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#newuser").hide();
				$("#appundr").hide();				
				$("#appleave").hide();
			});
			
			$("#appundr").hide();
			$("#forappundr").on("click", function(){
				$("#appundr").show();
				$("#appob").hide();
				$("#appleave").hide();
				$("#appot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#newuser").hide();
			});
			
			$("#appob").hide();
			$("#forappob").on("click", function(){
				$("#appob").show();
				$("#appleave").hide();
				$("#appundr").hide();
				$("#appot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#newuser").hide();
			});
			
			$("#appleave").hide();
			$("#forappleave").on("click", function(){
				$("#appleave").show();
				$("#appob").hide();
				$("#appundr").hide();
				$("#appot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#newuser").hide();
			});
			
			$("#newuserbtn").on("click", function(){
				$("#newuser").show();
				$("#appot").hide();
				$("#dash").hide();
				$("#appundr").hide();
				$("#appob").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#appleave").hide();
	
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
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>					
			<a  type = "button"class = "btn btn-primary  active"  href = "admin-req-app.php"> Approved Request</a>		
			<a type = "button"class = "btn btn-primary"  href = "admin-req-dapp.php">Dispproved Request</a>		
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
		<br><br>
		<div class = "btn-group btn-group">
			<button  type = "button"class = "btn btn-success" id = "forappot"> Approved Overtime Request </button>
			<button  type = "button"class = "btn btn-success" id = "forappob"> Approved Official Business Request </button>
			<button  type = "button"class = "btn btn-success" id = "forappleave"> Approved Leave Request </button>			
			<button  type = "button"class = "btn btn-success" id = "forappundr"> Approved Undertime Request </button>	
		</div>
	</div>
</div>

<div id = "appot" style = "display: none; margin-top: -30px;">
	<?php 
		include("conf.php");
		$cutoffdate = date("Y-m-d");
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and state = 'AAdmin' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> Approved Overtime Request </h2></td>
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
		$cutofftime2 = 0;	
		while($row = $result->fetch_assoc()){
			//end of computation
			$date17 = date("d");
			$dated = date("F");
			$datey = date("Y");
			
	
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));
			echo
				'			<tr>
					<td>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"].'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot']. ' / OT: '. $row['approvedothrs'].'</td>
					<td>'.$row["officialworksched"].'</td>					
					<td><strong><font color = "green">Approved</font></td>
				</tr>';
		}
		?>
	
			</tbody>
		</table>
		</form>
<?php	}$conn->close();
	?>

</div>
<div id = "appob" style = "display: none;margin-top: -30px;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'AAdmin' ORDER BY obdate ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Approved Official Business Request </h2></td>
				</tr>
				<tr>
					<th width="105">Date File</th>
					<th>Name of Employee</th>
					<th>Position</th>
					<th>Department</th>
					<th>Date of Request</th>
					<th width="150">Time In - Time Out</th>
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
					<td><b><p><font color = "green">Approved</p><td>
				</tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
	?>
</div>
<div id = "appundr" style = "display: none;margin-top: -30px;">
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
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id  and state = 'AAdmin'  ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> Approved Undertime Request </h2></td>
				</tr>
				<tr >
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
		}
?>
</div>
<div id = "appleave" style = "display: none; margin-top: -30px;">
	<?php 
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and state = 'AAdmin' ORDER BY datefile ASC";
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
						<th width = "105">Name of Employee</th>
						<th  width = "105">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "250">Date of Leave (From - To)</th>
						<th >No. of Day/s</th>
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
					<td>Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])) .'<br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
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
				<td>Username: </td>
				<td><input pattern=".{4,}" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regpword"/></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regcppword"/></td>
			</tr>
			<tr>
				<td>First Name: </td>
				<td><input required pattern="[a-zA-Z0-9\s]+"class ="form-control"type = "text" name = "regfname"/></td>
			</tr>
			<tr>
				<td>Last Name:</td>
				<td> <input required pattern="[a-zA-Z0-9\s]+" class ="form-control"type = "text" name = "reglname"/></td>
			</tr>
			<tr>
				<td>Postion:</td>
				<td> <input required pattern="[a-zA-Z\s]+" class ="form-control"type = "text" name = "regpos"/></td>
			</tr>
			<tr>
				<td>Department:</td>
				<td> <input required pattern="[a-zA-Z\s]+" class ="form-control"type = "text" name = "regdep"/></td>
			</tr>
			<tr>
				<td>Account Level:</td>
				<td>
					<select name = "level" class ="form-control">
						<option value = "EMP">Employee
						<option value = "HR">HR
						<option value = "ACC">Accounting
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