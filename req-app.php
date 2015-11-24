<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] != 'EMP'){
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
				$("#appundr").hide();
				$("#appob").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#formhidden").hide();
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
				$("#formhidden").hide();
			});
			
			$("#appob").hide();
			$("#forappob").on("click", function(){
				$("#appob").show();
				$("#appleave").hide();
				$("#appundr").hide();
				$("#appot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#formhidden").hide();
			});
			
			$("#appleave").hide();
			$("#forappleave").on("click", function(){
				$("#appleave").show();
				$("#appob").hide();
				$("#appundr").hide();
				$("#appot").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#formhidden").hide();
			});
			
			$("#newovertime").on("click", function(){
				$("#appot").hide();
				$("#appundr").hide();
				$("#appob").hide();
				$("#undertime").hide();
				$("#offb").hide();
				$("#appleave").hide();
			});
			$("#newundertime").on("click", function(){
				$("#appot").hide();
				$("#appleave").hide();
				$("#appundr").hide();
				$("#appob").hide();
				$("#formhidden").hide();
				$("#offb").hide();
			});
			$("#newoffb").on("click", function(){
				$("#appot").hide();
				$("#appob").hide();
				$("#undertime").hide();
				$("#formhidden").hide();
				$("#appleave").hide();
			});
			
	});
</script>
<div align = "center" style = "margin-bottom: 30px;">
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
				</ul>
			</div>				
			<a  type = "button"class = "btn btn-primary  active"  href = "req-app.php">My Approved Request</a>		
			<a type = "button"class = "btn btn-primary"  href = "req-dapp.php">My Dispproved Request</a>		
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
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 >= 17){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 15;
		}
		if(date("d") < 2){
			$date17 = 16;
			$forque = 16;
			$endque = 32;
			$dated = date("m", strtotime("previous month"));
		}
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and DAY(dateofot) >= $forque and state = 'AAdmin' and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> My Approved Overtime Request </h2></td>
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
				echo '<td></tr>';
		}
		?>
	
			</tbody>
		</table>
		</form>
<?php	}$conn->close();
	?>
<?php
	include("conf.php");
	$date17 = date("d");
	$dated = date("m");
	$datey = date("Y");
	if($date17 >= 17){
		$forque = 16;
		$endque = 31;
		$cutoffdate = '16 - 30/31';	
	}else{
		$cutoffdate = '1 - 15';
		$forque = 1;
		$endque = 15;
	}
	if($date17 == 1){
		$date17 = 16;
		$forque = 16;
		$endque = 32;
		$dated = date("m", strtotime("previous month"));
	}
	$sql = "SELECT * FROM overtime where overtime.account_id = $accid and DAY(dateofot) >= $forque and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey ";
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

		$hours12 = $hours12;
		$minutetosec = $minutes12;
		$totalmin = $hours12 + $minutes12;
		$totalothrs = date('H:i', mktime(0,$minutes12));
		if(substr($totalothrs,3,5) == 30){
			$point5 = '.5';
		}else{
			$point5 = '';
		}
		echo '<div align = "center">Total OT: <strong>'. ($hours12 + substr($totalothrs,0,2)) .$point5. ' Hour/s </strong>for '.$datade. ' ' . $cutoffdate . ', ' . date("Y").'</strong></div>';
	}
?>
</div>
<div id = "appob" style = "display: none;margin-top: -30px;">
	<?php 
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and state =  'AAdmin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			
	?>
	<form role = "form" action = "approval.php"    method = "get">
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
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid and state =  'AAdmin'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
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
	?>
</div>
<div id = "appleave" style = "display: none; margin-top: -30px;">
	<?php 
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and state =  'AAdmin' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	?>	
	<form role = "form" action = "approval.php"    method = "get">
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
<?php } include('req-form.php');?>
<?php include('footer.php');?>