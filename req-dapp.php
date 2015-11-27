<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] !='EMP'){
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
			$("#disappundr").hide();
			$("#dappob").hide();
			$("#undertime").hide();
			$("#offb").hide();
			$("#formhidden").hide();
			$("#disappleave").hide();
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
		});
		
		$("#disappundr").hide();
		$("#fordappundr").on("click", function(){
			$("#disappundr").show();
			$("#dappot").hide();
			$("#undertime").hide();
			$("#dappob").hide();
			$("#formhidden").hide();
			$("#disappleave").hide();
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
		});
		
		$("#disappleave").hide();
		$("#fordisappleave").on("click", function(){
			$("#disappleave").show();
			$("#disappundr").hide();
			$("#dappot").hide();
			$("#undertime").hide();
			$("#dappob").hide();
			$("#formhidden").hide();
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
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
			$("#offb").hide();
			$("#undertime").hide();
			$("#formhidden").hide();
			$("#leave").hide();
		});
		
		$("#newovertime").on("click", function(){	
			$("#disappundr").hide();
			$("#dappot").hide();
			$("#dappob").hide();
			$("#undertime").hide();
			$("#disappleave").hide();
			$("#offb").hide();
		});
		$("#newundertime").on("click", function(){
			$("#disappundr").hide();
			$("#dappot").hide();
			$("#dappob").hide();
			$("#formhidden").hide();
			$("#offb").hide();
			$("#disappleave").hide();
		});
		$("#newoffb").on("click", function(){
			$("#dappot").hide();
			$("#dappob").hide();
			$("#undertime").hide();
			$("#disappundr").hide();
			$("#formhidden").hide();
			$("#disappleave").hide();
		});
	});
</script>
<div align = "center" style = "margin-bottom: 30px; ">
	<div class="alert alert-success">
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong><br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br>	<br>	
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary" href = "employee.php?ac=penot" id = "home">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absense Request</a></li>				  
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
			<a  type = "button"class = "btn btn-primary"  href = "req-app.php" >My Approved Request</a>		
			<a  type = "button"class = "btn btn-primary  active"  href = "req-dapp.php">My Dispproved Request</a>		
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

<div id = "dappot" style = "margin-top: -30px; display: none;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = $accid and overtime.account_id = $accid and state like 'DA%'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> My Disapproved Overtime Request </h2></td>
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
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"].'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot'].'</td>
					<td>'.$row["officialworksched"].'</td>					
					<td><b>';
						if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Disapproved by HR</font></p>'.$row['dareason'];
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Disapproved by Accounting</font></p>'.$row['dareason'];
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p>';
						}
				echo '<td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
?>
</div>
<div id = "dappob" style = "margin-top: -30px; display: none;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and state like 'DA%'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> My Disapproved Official Business Request </h2></td>
				</tr>
				<tr>
					<th width="105">Date File</th>
					<th>Name of Employee</th>
					<th>Position</th>
					<th>Department</th>
					<th width="105">Date of Request</th>
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
					<td><b>';
						if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Disapproved by HR</font></p>'.$row['dareason'];
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Disapproved by Accounting</font></p>'.$row['dareason'];
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p>';
						}
				echo '<td></tr>';
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
			$endque = 1;
		}
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid and state like 'DA%'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> My Dispproved Undertime Request </h2></td>
				</tr>
				<tr >
					<th>Date File</th>
					<th>Date of Undertime</th>
					<th>Name of Employee</th>
					<th>Reason</th>
					<th>From - To (Undertime)</th>
					<th>Number of Hrs/Minutes</th>
					<th>Action</th>
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
						if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Disapproved by HR</font></p>'.$row['dareason'];
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Disapproved by Accounting</font></p>'.$row['dareason'];
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p>';
						}
				echo '<td></tr>';
			}
			echo '</tbody></table></form></div>';
		}
?>
</div>

<div id = "disappleave" style = "display: none; margin-top: -30px;">
	<?php 
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and state like 'DA%' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	?>	
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center><h2> My Disapproved Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "105">Date File</th>
						<th >Name of Employee</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "200">Date of Leave (Fr - To)</th>
						<th width = "100"># of Day/s</th>
						<th width = "140">Type of Leave</th>
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
						if($row['state'] == 'DAHR'){
							echo '<p><font color = "red">Disapproved by HR</font></p>'.$row['dareason'];
						}else if($row['state'] == 'DAACC'){
							echo '<p><font color = "red">Disapproved by Accounting</font></p>'.$row['dareason'];
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p>';
						}
						echo '<td></tr>';
		}
		echo '</tbody></table></form>';
	}$conn->close();
	?>
</div>
<?php include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'default'){
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
<?php }include('req-form.php');?>
<?php include("footer.php");?>