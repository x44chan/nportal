<?php session_start(); ?>
<?php  $title="Accounting Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
	include("conf.php");
?>
<?php	if($_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	}	?>
<script type="text/javascript" src="css/src/jquery.ptTimeSelect2.js"></script>
<link rel="stylesheet" type="text/css" href="css/src/jquery.ptTimeSelect2.css" />
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "?ac=penot">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
				  <li><a href="#" id = "newundertime">Undertime Request Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#penalty">Penalty Loan Form</a></li>
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
			<div class="btn-group btn-group-lg">
		        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
		        	<ul class="dropdown-menu" role="menu">
		        		<li><a href = "acc-report.php">Cut Off Summary</a></li>
		            	<li><a href="hr-emprof.php">Employee Profile</a></li>
		            	<li><a href = "acc-report.php?sumar=leasum">Employee Leave Summary</a></li>
		          	</ul>
		    </div>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?replenish">Petty Replenish Report</a></li>
				  <li class="divider"></li>
				  <li><a type = "button" href = "accounting-petty.php?pettydate"> Petty Date Summary </a></li>
				  <li><a type = "button" href = "accounting-petty.php?expenses"> Expenses </a></li>
				</ul>
			</div>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">My Request Status <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "req-all.php?appot">All Request</a></li>
				  <li><a href = "acc-req-app.php">My Approved Request</a></li>
				  <li><a href = "acc-req-dapp.php">My Disapproved Request</a></li>	
				</ul>
			</div>	
			<a type = "button" class = "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class="btn-group btn-group" role="group">
			<a role = "button"class = "btn btn-success"  href = "?ac=penot"> Overtime Request Status </a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penob"> Official Business Request Status</a>			
			<a role = "button"class = "btn btn-success"  href = "?ac=penlea"> Leave Request Status</a>		
			<a role = "button"class = "btn btn-success"  href = "?ac=penundr"> Undertime Request Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penpty"> Petty Request Status</a>
			<?php
				if($_SESSION['category'] == "Regular"){
					echo '
						<a role = "button"class = "btn btn-success"  href = "?ac=penca"> Cash Adv. Request Status</a>
						';
				}
			?>	
			<a role = "button"class = "btn btn-success"  href = "?ac=penloan"> Loan Request Status</a>
		</div>
	</div>
</div>
<div id = "needaproval" style = "margin-top: -30px;">
<?php 
	include 'caloan/petty.php'; 
	include 'caloan/editrequest.php';
	if(isset($_GET['ac']) && $_GET['ac'] == 'penca'){
		include("caloan/cashadv.php");
		}
?>
<?php 
	if(isset($_GET['loan']) && $_GET['loan'] > 0){
		include("caloan/loan.php");
		}
?>
<?php
	if(isset($_GET['validate'])){
		$petida = mysql_escape_string($_GET['validate']);
		$sql = "SELECT * FROM `petty_liqdate` where petty_id = '$petida' and liqstate = 'EmpVal' group by petty_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<div align = "center" style = "margin-top: 50px;"><i><h3> Validate Code for Completion</h3></i></div>';
			echo '<form action = "petty-exec.php" method = "post"><table class = "table" style="width: 50%;" align = "center">';
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td><label>Enter Code</td><td><input required type = "text" class = "form-control" name = "valcode" placeholder="Enter Code"/></td></tr>';
				echo '<tr><td colspan = "2" align = "center"><button class = "btn btn-primary" type = "submit" name = "valcodesub">Complete Liquidate</button> <a href = "?ac=penpty" class = "btn btn-danger">Back</a></td></tr>';
				echo '<input type = "hidden" name = "petyid" value = "' . $row['petty_id'] . '"/>';
			}	
			echo '</table></form>';
	}else{
		echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';
	}
}
?>	
<?php 
	if(isset($_GET['lqdate'])){
		include 'aliquidate.php';	
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['upob'])){
		$oid = mysql_escape_string($_GET['upob']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and officialbusiness_id = '$oid' and state = 'UA'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<div ><form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table" style = "width: 50%;" align = "center">';
			while($row = $result->fetch_assoc()){
			?>
				<tr>
					<td colspan = 2 align = center>
						<h2> Edit Official Business Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date('F j, Y', strtotime($row['obdate']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['obename'];?></td>
				</tr>
				<tr>
					<td>ID No: </td>
					<td><?php echo $_SESSION['acc_id'];?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $_SESSION['post'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $_SESSION['dept'];?></td>
				</tr>
				<tr>
					<td>Date Of Official Business: </td>
					<td><?php echo date('M j, Y', strtotime($row['obdatereq']));?></td>
				</tr>				
				<tr>
					<td width="25%">Description of Work Order: </td>
					<td width="25%"><?php echo $row['obreason'];?></td>					
				</tr>
				<tr>
					<td> Official Work Schedule</td>
					<td> <?php echo $row['officialworksched'];?></td>
				</tr>
				<div class = "ui-widget-content" style = "border: none;">
				<tr>
					<td>Time In: </td>
					<td>
						<input class = "form-control" value = "<?php echo $row['obtimein'];?>" name = "obtimein" id = "obtimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>Time Out: </td>
					<td><input class = "form-control" value = "<?php echo $row['obtimeout'];?>" name = "obtimeout" id = "obtimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>				
				
				<tr id = "warning" style="display: none;">
					<td></td>
					<td>
						<div class="alert alert-danger fade in">
						  <strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b>
						</div>
					</td>
				</tr>
				<script type="text/javascript">
					$(document).ready(function(){	
						$('#obtimein').click(function() {
							$("#warning").hide();
						});
						$("#submituped").click(function(){						
							if($("#obtimein").val() == "" && $("#obtimeout").val() == "" ){
								$("#obtimein").attr("required", true);
								$("#obtimeout").attr("required", true);
							}else{
								$("#obtimein").attr("required", false);
								$("#obtimeout").attr("required", false);
							}
						});
					});
				</script>
				<script type="text/javascript">
					$(document).ready(function(){
						$('input[name="obtimein"]').ptTimeSelect2();
						$('input[name="upoffr"]').ptTimeSelect2();
						$('input[name="upoffto"]').ptTimeSelect2();
						$('input[name="obtimeout"]').ptTimeSelect2();
					});
				</script>
				</div>
				<input value = "<?php echo $row['obtimein'];?>" type = "hidden" name = "oldobtimein"/>
				<input value = "<?php echo $row['obtimeout'];?>" type = "hidden" name = "oldobtimeout"/>
				<input value = "<?php echo $row['account_id'];?>" type = "hidden" name = "accid"/>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" id = "submituped"name = "hrupobsubmit" onclick = "return confirm('Are you sure?.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
		<?php

			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form></div>';
	}
	
?>	
<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penloan'){
		include("conf.php");
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid order by state ASC";
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
						<th>Type</th>
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
				if($row['penalty'] == 1){
					$row['penalty'] = '<b><font color = "red"> Penalty Loan </font></b>';
				}else{
					$row['penalty'] = '<b> Salary Loan </b>';
				}
				$loan_id = $row['loan_id'];
				$stmts = "SELECT sum(cutamount) as cutamount,loan_id,cutoffdate,enddate,state FROM `loan_cutoff` where loan_id = '$loan_id' order by cutoff_id desc";
				$data = $conn->query($stmts)->fetch_assoc();
				$stmtsx = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'Full'";
				$datax = $conn->query($stmtsx)->fetch_assoc();
				echo	'<tr>';
					echo	'<td>' . $row['loan_id'].'</td>';
					echo	'<td>' . date("M j, Y", strtotime($row['loandate'])).'</td>';
					echo	'<td>' . $row['penalty'] . '</td>';
					echo	'<td>&#8369; ' . number_format($row['loanamount'])  .'</td>';
					echo	'<td>' . date("M j, Y", strtotime($row['startdate'])). '</td>';
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
									if($row['penalty'] ==  '<b> Salary Loan </b>'){
										echo '<a href = "loan-exec.php?loanss='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-danger">Decline</a>';
									}
								}elseif($row['state'] == 'ARcvCashCode'){
									echo '<font color = "green"><b>Received ';
									echo '</font></br>Code: ' . $row['rcve_code'];
								}elseif($row['state'] == 'ALoan' && (date("Y-m-d") >= date('Y-m-d', strtotime('+'.$row['duration'], strtotime($row['startdate'])))) || $datax['state'] == 'Full') {
									echo '<b><font color = "green">Completed</font></b><br>';
									echo '<a href = "?loan='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">View Request</a>';
								}elseif($row['appamount'] != null && $row['appamount'] == $row['loanamount'] && $row['loan_id'] == $data['loan_id']){
									echo '<a href = "?loan='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">View Request</a>';
								}
					echo	'</td>';
				echo '</tr>';
			}
		}
		echo '</tbody></table>';
	}
?>


<?php 
	$date17 = date("d");
	$dated = date("m");
	$datey = date("Y");
	if($date17 >= 17){
		$forque = date('Y-m-16');
		$endque = date('Y-m-31');
	}else{
		$forque = date('Y-m-01');
		$endque = date('Y-m-16');
	}
	if(date("d") < 2){
		$forque = date('Y-m-16', strtotime("previous month"));
		$endque = date('Y-m-d');
	}
	if(isset($_GET['ac']) && $_GET['ac'] == 'penot'){

		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and datefile BETWEEN '$forque' and '$endque' ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		
	?>
	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 7 align = center><h2> Overtime Application Status </h2></td>
					</tr>
					<tr>
						<th>Date File</th>						
						<th>Name of Employee</th>
						<th>Date of Overtime</th>
						<th>From - To (Overtime)</th>
						<th>Reason</th>
						<th>Offical Work Schedule</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
	<?php
			//'F j, Y - hA'
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$datetoday = date("Y-m-d");
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$newDate2 = date("M j, Y", strtotime($row['dateofot']));
					
				if($datetoday >= $row['2daysred'] && $row['state'] == 'UA'){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				if($row['oldot'] != null && ($row['state'] == 'AHR' || $row['state'] == 'UA')){
					$oldot = '</b><br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
					$hrot = '<b>App. OT: <i><font color = "green">';
					$hrclose = "</font></i>";
				}else if($row['oldot'] != null && $row['state'] == 'AAdmin'){
					$oldot = '<br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
					$hrot = '<b>App. OT: <i><font color = "green">';//( '.$row['approvedothrs'] . ' ) ';
					$hrclose = "</font></i>";
				}else{
					$oldot = "";
					$hrot = '';
					$hrclose ='';
				}
				if($row['otbreak'] != null){
					$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
				}else{
					$otbreak = "";
				}
				if($row['csrnum'] != ""){
					$row['csrnum'] = '<b>CSR Number: '.$row['csrnum'] .'</b><br>';
				}
				if($row['otlate'] != null){
					$otlate =  '<b><font color = "red"><i>Approved Late Filing <br>by the Dep. Head</i></font></b><br>';
				}else{
					$otlate = "";
				}
				$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo 
					'
						<td>'.$newDate .'</td>						
						<td>'.$row["nameofemp"].'</td>
						<td>'.$newDate2.'</td>
						<td style = "text-align:left;">'.$row['csrnum']. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>							
						<td width = 300 height = 70>'.$data1['reason'].'</td>
						<td>'.$row["officialworksched"].'</td>				
						<td><b>';
							if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
								echo 'Pending for Time Checking <br>';
							}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){								
								echo 'Pending for Time Checking HR<br>';
							}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to Tech Supervisor<br>';
							}else if($row['state'] == 'CheckedHR'){
								echo '<p><font color = "green">Checked by HR</font></p> ';
							}else if($row['state'] == 'AACC'){
								echo '<p><font color = "green">Approved by Accounting</font></p> ';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAHR'){
								echo '<p><font color = "red">Dispproved by HR</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAACC'){
								echo '<p><font color = "red">Dispproved by Accounting</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DATECH'){
								echo '<p><font color = "red">Disapproved by Technician Supervisor</font></p>'.$row['dareason'];
							}elseif($row['state'] == 'UALate'){
								echo '<p><i><font color = "red">Late Filing</font></i><br>Waiting for Admin Approval</p>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
							}elseif($row['state'] == 'UAAdmin'){
								echo '<p>Waiting for Admin Approval</p>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
							}
						echo '<td></tr>';
			}
			
		}echo '</tbody></table></form>';
}
?>

<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penundr'){
		
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and datefile BETWEEN '$forque' and '$endque' ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 7 align = center><h2> Undertime Application Status </h2></td>
					</tr>
					<tr >
						<th>Date File</th>
						<th>Date of Undertime</th>
						<th>Name of Employee</th>
						<th>Reason</th>
						<th>From - To (Overtime)</th>
						<th>Number of Hrs/Minutes</th>
						<th>Action</th>
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
				$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo 
					'<td width = 180>'.$newDate.'</td>
					<td>'. date("M j, Y", strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>
					<td width = 250 height = 70>'.$data1["reason"].'</td>
					<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
					<td>'.$row["numofhrs"].'</td>
					<td><b>';
						if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
								echo 'Pending to HR for Checking<br>';								
							}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to HR for Checking';
							}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to Tech Supervisor<br>';								
							}else if($row['state'] == 'AHR'){
								echo '<p><font color = "green">Approved by HR</font></p> ';
							}else if($row['state'] == 'AACC'){
								echo '<p><font color = "green">Approved by Accounting</font></p> ';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAHR'){
								echo '<p><font color = "red">Dispproved by HR</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAACC'){
								echo '<p><font color = "red">Dispproved by Accounting</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DATECH'){
								echo '<p><font color = "red">Disapproved by Technician Supervisor</font></p>'.$row['dareason'];
							}elseif($row['state'] == 'UALate'){
								echo '<p><i><font color = "red">Late Filing</font></i><br>Waiting for Admin Approval</p>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['undertime_id'].'">Edit Application</a>';
							}elseif($row['state'] == 'UAAdmin'){
								echo '<p>Waiting for Admin Approval</p>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['undertime_id'].'">Edit Application</a>';
							}else if($row['state'] == 'CheckedHR'){
								echo '<p><font color = "green">Checked by HR</font></p> ';
							}
					echo '<td></tr>';
			}
			
		}echo '</tbody></table></form>';
	}
?>
<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penob'){
		if($date17 >= 17){
			$forque = date('Y-m-16');
			$endque = date('Y-m-31');
		}else{
			$forque = date('Y-m-01');
			$endque = date('Y-m-16');
		}
		if(date("d") < 2){
			$forque = date('Y-m-16', strtotime("previous month"));
			$endque = date('Y-m-d');
		}
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and obdate BETWEEN '$forque' and '$endque' ORDER BY state ASC,obdate ASC";
		$result = $conn->query($sql);
		
	?>	
		<div>
			<h2 align="center" style="margin-top:40px;"> Official Business Status </h2>
		<?php if(!isset($_GET['bypass'])) { ?>	
			<a href = "?ac=penob&bypass" class="btn btn-success pull-right" style="margin-right: 10px; margin-top: -30px;"> Bypass Approval </a>
		<?php }else { ?>
			<a href = "?ac=penob" class="btn btn-success pull-right" style="margin-right: 10px; margin-top: -30px;"> Un-Bypass Approval </a>
		<?php } ?>
		</div>
		<form role = "form" action = "approval.php"    method = "get">

			<table class = "table table-hover" align = "center">
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
		if(isset($_GET['bypass'])){
			$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'UA' and obdate > '2015-12-03' ORDER BY obdate ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){			
					$originalDate = date($row['obdate']);
					$newDate = date("M j, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}		
					echo 
							'<td>'.$newDate.'</td>
							<td>'.$row["obename"].'</td>
							<td>'.$row["obpost"].'</td>
							<td >'.$row["obdept"].'</td>
							<td>'.date("M d, Y", strtotime($row['obdatereq'])).'</td>					
							<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
							<td>'.$row["officialworksched"].'</td>				
							<td >'.$row["obreason"].'</td>	';
							if($row['state'] == 'UAACCAdmin'){
								echo '<td><strong>Pending to Admin<strong></td>';
							}elseif($row['state'] == 'UATech'){
								echo '<td><b>Pending to Tech. Supervisor</b></td></tr>';
							}else{
								if($row['oblate'] == 1){
									$late = "<b><font color = 'red'> Late Filed </font></b><br>";
								}else{
									$late = "";
								}
							echo'
								<td width = "200">'.$late.'
									<a href = "?approve=DA'.$_SESSION['level'].'&upob='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit"></span> Add Time In / Out</a>
								</td>
							</tr>';
							}
				}
			}
		}
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['obdate']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				
				$sched = $row["obtimein"] . ' - ' . $row['obtimeout'];
				if($row['oblate'] != ""){
					$late = "<b><font color = 'red'> Late Filed </font></b><br>";
				}else{
					$late = "";
				}
				echo 
					'	<td>'.$newDate.'</td>
						<td>'.$row["obename"].'</td>
						<td>'.$row["obpost"].'</td>
						<td >'.$row["obdept"].'</td>
						<td>'.date("M j, Y", strtotime($row['obdatereq'])).'</td>					
						<td>'.$sched.'</td>
						<td>'.$row["officialworksched"].'</td>				
						<td >'.$row["obreason"].'</td>	
						<td><b>';
							if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
								echo $late;
								echo 'Pending for Time Checking <br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
								echo $late;
								echo 'Pending for Time Checking <br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to Tech Supervisor<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AHR'){
								if($row['dateacc'] == 1){
									$chck = 'ACC';
								}else{
									$chck = 'HR';
								}
								echo '<p><font color = "green">Approved by '.$chck.'</font></p> ';
							}else if($row['state'] == 'AACC'){
								echo '<p><font color = "green">Approved by Accounting</font></p> ';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAHR'){
								echo '<p><font color = "red">Dispproved by HR</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAACC'){
								echo '<p><font color = "red">Dispproved by Accounting</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DATECH'){
								echo '<p><font color = "red">Disapproved by Technician Supervisor</font></p>'.$row['dareason'];
							}elseif($row['state'] == 'UAAdmin'){
								echo 'Waiting for Admin Approval<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}elseif($row['state'] == 'UALate'){
								echo '<p><i><font color = "red">Late Filing</font></i><br>Waiting for Admin Approval</p>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'&late">Edit Application</a>';
							}else if($row['state'] == 'CheckedHR'){
								echo '<p><font color = "green">Checked by HR</font></p> ';
							}
						echo '</td></tr>';
		}
		
	}
	
	echo '</tbody></table></form>';$conn->close();
}
?>

<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penlea'){
		include("conf.php");
		$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and YEAR(datefile) = $datey ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		
	?>	
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover " align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center><h2> Leave Application Status </h2></td>
					</tr>
					<tr>
						<th width = "170">Date File</th>
						<th width = "170">Name of Employee</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "250">Date of Leave (Fr - To)</th>
						<th width = "100"># of Day/s</th>
						<th width = "170">Type of Leave</th>
						<th width = "160">Reason</th>
						<th>State</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$newDate2 = date("M j, Y", strtotime($row["edatehired"]));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}	
				if($row['lealte'] == '1'){
					$lates = '<b><font color = "red"> Late Filed </font></b><br>';
				}else{
					$lates = "";
				}
				$query1 = "SELECT * FROM `nleave` where leave_id = '$row[leave_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo 
					'<td>'.$newDate.'</td>
					<td>'.$row["nameofemployee"].'</td>
					<td>'.$newDate2.'</td>
					<td >'.$row["deprt"].'</td>
					<td>'.$row['posttile'].'</td>					
					<td width = "300">Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])) .' <br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					<td >'.$data1["reason"].'</td>	
					<td width = "200"><b>' . $lates;
							if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
								echo 'Pending to HR<br>';
								if($row['accadmin'] == null){
									echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
								}								
							}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to HR<br>';
							}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
								echo 'Pending to Tech Supervisor<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AHR'){
								echo '<p><font color = "green">Approved by HR</font></p> ';
							}else if($row['state'] == 'AACC'){
								echo '<p><font color = "green">Approved by Accounting</font></p> ';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAHR'){
								echo '<p><font color = "red">Dispproved by HR</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAACC'){
								echo '<p><font color = "red">Dispproved by Accounting</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}else if($row['state'] == 'DATECH'){
								echo '<p><font color = "red">Disapproved by Technician Supervisor</font></p>'.$row['dareason'];
							}elseif($row['state'] == 'CLea'){
								echo '<font color = "red"> Leave Canceled  </font>';
							}elseif($row['state'] == 'ReqCLea'){
								echo '<font color = "red"> Pending Cancelation Request </font>';
							}elseif($row['state'] == 'ReqCLeaHR'){
								echo '<font color = "red"> Pending Cancelation Request </font>';
							}elseif($row['state'] == 'UAAdmin'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
							}
						echo '</td></tr>';
			}
		
	}echo '</tbody></table></form>';
	$conn->close();
}
?>
</div>
<?php include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'default'){
		include('up-pass.php');
	}else if($_SESSION['201date'] == null || $_SESSION['201date'] == '0000-00-00'){
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