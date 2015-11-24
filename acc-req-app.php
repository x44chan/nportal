<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] != 'ACC'){
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
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
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
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
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
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
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
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
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
			<a  type = "button"class = "btn btn-primary" href = "accounting.php?ac=penot">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
				  <li><a href="#" id = "newundertime">Undertime Request Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
				</ul>
			</div>
			<a type = "button" class = "btn btn-primary" href = "acc-report.php" id = "showapproveda">Cutoff Summary</a>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>			
			<a  type = "button"class = "btn btn-primary  active"  href = "acc-req-app.php"> Approved Request</a>		
			<a type = "button"class = "btn btn-primary"  href = "acc-req-dapp.php">Dispproved Request</a>		
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
		$sql = "SELECT * FROM overtime,login where login.account_id = $accid and overtime.account_id = $accid and state like 'A%' ORDER BY datefile ASC";
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
				'<tr>
					<td>'.$newDate.'</td>
					<td>'. date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"].'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot']. ' / <strong>OT: '. $row['approvedothrs'].'</strong></td>
					<td>'.$row["officialworksched"].'</td>					
					<td><b>';
						if($row['state'] == 'UA'){
							echo 'Pending';
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}else if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Dispproved by HR</p>';
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Dispproved by Accounting</p>';
						}else if($row['state'] == 'DAAdmin'){
							echo '<p><font color = "red">Dispproved by Dep. Head</p>';
						}
					echo '<td></tr>';
		}
		?>
			</tbody>
		</table>
	</form>
<?php	}$conn->close();?>
</div>
<div id = "appob" style = "display: none;margin-top: -30px;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and state like 'A%' ORDER BY obdate ASC";
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
					<td>'. date("M j, Y", strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>	
					<td><b>';
						if($row['state'] == 'UA'){
							echo 'Pending';
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}else if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Dispproved by HR</p>';
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Dispproved by Accounting</p>';
						}else if($row['state'] == 'DAAdmin'){
							echo '<p><font color = "red">Dispproved by Dep. Head</p>';
						}
					echo '<td></tr>';
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
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid and state like 'A%'  ORDER BY datefile ASC";
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
					<td>'. date("M j, Y", strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>
					<td width = 250 height = 70>'.$row["reason"].'</td>
					<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
					<td>'.$row["numofhrs"].'</td>
					<td><b>';
						if($row['state'] == 'UA'){
							echo 'Pending';
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}else if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Dispproved by HR</p>';
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Dispproved by Accounting</p>';
						}else if($row['state'] == 'DAAdmin'){
							echo '<p><font color = "red">Dispproved by Dep. Head</p>';
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
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and state like 'A%' ORDER BY datefile ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	?>	
	<form role = "form" action = "approval.php"    method = "get">
			<table width = "100%" class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center><h2> Approved Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "160">Date File</th>
						<th>Name of Employee</th>
						<th width = "155">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width="200">Date of Leave (Fr - To)</th>
						<th ># of Day/s</th>
						<th width="150">Type of Leave</th>
						<th>Reason</th>
						<th >State</th>
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
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</p>';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by Dep. Head</p>';
						}else if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Dispproved by HR</p>';
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Dispproved by Accounting</p>';
						}else if($row['state'] == 'DAAdmin'){
							echo '<p><font color = "red">Dispproved by Dep. Head</p>';
						}
					echo '<td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
	?>
</div>
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