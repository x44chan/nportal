<?php session_start(); ?>
<?php  $title="H.R. Page";
	include('header.php');	
	include('conf.php');
	date_default_timezone_set('Asia/Manila');
	$accid = $_SESSION['acc_id'];
	if(date("D") == 'Mon'){
		$forque1 = date('Y-m-d', strtotime("-3 days"));
		$endque1 = date('Y-m-d');
		$tforque1 = date('Y-m-d', strtotime("-3 days"));
		$tendque1 = date('Y-m-d');
	}else{
		$forque1 = date('Y-m-d', strtotime("-1 days"));
		$endque1 = date('Y-m-d');
		$tforque1 = date('Y-m-d', strtotime("-2 days"));
		$tendque1 = date('Y-m-d');
	}
?>
<?php if($_SESSION['level'] != 'HR'){
	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
	
	<?php
	}
	if(isset($_SESSION['err']) && $_SESSION['err'] == 'ex'){
	echo "<script type=\"text/javascript\">
			$(document).ready(function(){	      
				$('#newAcc').modal({
				backdrop: 'static',
				keyboard: false
				});
			});
			</script>";
	}
	$accid = $_SESSION['acc_id'];
	if($_SESSION['level'] == 'HR'){
		$state = 'AHR';
	}
?>
<script type="text/javascript">
	 $(document).ready( function () {
    	$('#myTable').DataTable({
		    "iDisplayLength": 25 ,
		    "order": [[ 0, "desc" ]]  	
		});
	});
</script>

<script type="text/javascript" src="css/src/jquery.ptTimeSelect2.js"></script>
<link rel="stylesheet" type="text/css" href="css/src/jquery.ptTimeSelect2.css" />
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "hr.php?ac=penot">Home</a>
			<?php if($_SESSION['acc_id'] == '3'){ ?>
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
			<?php } ?>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a data-toggle="modal" data-target="#newAcc">Add User</a></li>
				  <!--<li><a href = "tech-sched.php">Tech Scheduling</a></li>-->
				  <li><a href = "hr-emprof.php">Employee Profile</a></li>
				  <li><a href = "hr-emprof.php?correctionrep">Correction Reports</a></li>				  
				  <li><a href = "hr-emprof.php?export">O.T. & O.B Exporting</a></li>
				  <!--<li><a href = "hr-timecheck.php">In/Out Reference</a></li>-->
				</ul>
			</div>		
			<a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda"> Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda"> Dispproved Request</a>
			<a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class="btn-group btn-group" role="group">
			<a role = "button"class = "btn btn-success"  href = "?ac=penot"> Overtime Req. Status </a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penob"> Official B. Req. Status</a>			
			<a role = "button"class = "btn btn-success"  href = "?ac=penlea"> Leave Req. Status</a>		
			<a role = "button"class = "btn btn-success"  href = "?ac=penundr"> Undertime Req. Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penhol"> Holiday Req. Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penpty"> Petty Req. Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penca"> Cash Adv. Req. Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penloan"> Loan Req. Status</a>
		</div>
	</div>
</div>
<div id = "needaproval" style = "margin-top: -30px;">	
<?php 
	include 'caloan/petty.php';
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
	if(isset($_GET['lqdate'])){
		include 'aliquidate.php';	
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['upundr'])){
		$oid = mysql_escape_string($_GET['upundr']);
		$_SESSION['otid'] = $oid;
		
		
		$sql = "SELECT * FROM undertime,login where undertime.account_id = login.account_id and undertime_id = '$oid' and state = 'UA'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 70%;"align = "center">';
			while($row = $result->fetch_assoc()){
				$_SESSION['acc'] = $row['account_id'];
				$_SESSION['oldunr'] = $row['undertimefr'] . ' - ' . $row['undertimeto'];
				?>	
				<tr>
					<td colspan = 2 align = center>
						<h2> Edit Undertime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date("F j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['name'];?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $row['department'];?></td>
				</tr>
				<tr>
					<td>Date Of Undertime: </td>
					<td><?php echo date("M j, Y", strtotime($row['dateofundrtime']));?></td>						
				</tr>				
				<?php
					$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
				?>					
				<tr>
					<td width="40%">Reason: </td>
					<td width="60%"><?php echo $data1['reason'];?></td>
				</tr>							
				<div class = "ui-widget-content" style = "border: none;">		
					<tr class = "form-inline">
						<td>Time of Undertime: </td>
						<td>
							<label for = "fr"> From: </label><input value = "<?php echo $row['undertimefr'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "to" class = "form-control"  name = "untimefr"/>
							<label for = "to"> To:  </label><input value = "<?php echo $row['undertimeto'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "fr" class = "form-control" name = "untimeto"/>
						</td>	
					</tr>			
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="untimeto"]').ptTimeSelect();
							$('input[name="untimefr"]').ptTimeSelect();
						});
					</script>
				</div>	
				
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "hrupunsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
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
	</form>';
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
					<td><?php echo $row['account_id'];?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $row['department'];?></td>
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
				<tr>
					<td colspan="2"><label><input name = "nxtday" value = "nxtday" type = "checkbox" <?php if($row['nxtday'] == 1){ echo ' checked '; } ?>/> For next day out (ex. - 12am * and beyond) </td>
				</tr>				
				<tr>
					<td align = "left" colspan = "2"> <label> Employee Error <input type = "checkbox" name = "correction"/> </td>
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
				$stmts = "SELECT sum(cutamount) as cutamount,loan_id,cutoffdate,enddate,state FROM `loan_cutoff` where loan_id = '$loan_id' order by cutoff_id desc";
				$data = $conn->query($stmts)->fetch_assoc();
				$stmtsx = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'Full'";
				$datax = $conn->query($stmtsx)->fetch_assoc();
				echo	'<tr>';
					echo	'<td>' . $row['loan_id'].'</td>';
					echo	'<td>' . date("M j, Y", strtotime($row['loandate'])).'</td>';
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
									echo '<a href = "loan-exec.php?loanss='.$row['loan_id'].'&acc='.$_GET['ac'].'" class = "btn btn-danger">Decline</a>';
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
	if(isset($_GET['leaapprove'])){
		include("conf.php");
		$oid = mysql_escape_string($_GET['leave']);
		$state = 'UA';
		$accids = mysql_escape_string($_GET['leaapprove']);
		$sql = "SELECT * from `login` where account_id = '$accids' and empcatergory = 'Regular'";
		$result = $conn->query($sql);
		$datey = date("Y");
		$availsick = 0;
		$totavailvac = 0;
		if($result->num_rows > 0){		
			while($row = $result->fetch_assoc()){
				$cstatus = $row['ecstatus'];
				$accidd = $row['account_id'];
				$egender = $row['egender'];
				$cate = $row['empcatergory'];
				if(date("Y") == 2015){	
					$sl = $row['sickleave'] - $row['usedsl'];
					$vl = $row['vacleave'] - $row['usedvl'];
					$usedsl = $row['usedsl'];
					$usedvl = $row['usedvl'];
				}else{				
					$leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and CURDATE() BETWEEN startdate and enddate and state = 'AAdmin'";
					$datalea = $conn->query($leaveexec)->fetch_assoc();
					$sl = $datalea['sleave'];
					$vl = $datalea['vleave'];
					$solo = $datalea['solleave'];
					$usedsl = 0;
					$usedvl = 0;
				}
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Sick Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$availsick = $sl - $row1['count'];
						$scount = $row1['count'];						
						}
				}		
				if($scount == null){
					$scount = " - ";
				}			
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Vacation Leave'  and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$availvac = $vl - $row1['count'];
						$count = $row1['count'];
						}
				}		
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Other%' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$totavailvac = $availvac - $row1['count'];
						$count = $row1['count'];
						}
				}
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Paternity Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$patternity = 7 - $row1['count'];
						$count = $row1['count'];
						}
				}
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Wedding Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$wedding = 7 -  $row1['count'];
						$count = $row1['count'];
						}
				}				
				$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Solo Parent Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
				$result1 = $conn->query($sql1);
				if($result1->num_rows > 0){
					while($row1 = $result1->fetch_assoc()){
						$solleave = $solo -  $row1['count'];
						$count = $row1['count'];
						}
				}				
			}
		}
	if($cate == 'Regular'){
			$quarterdate = array();
			$date1=date_create($datalea['startdate']);
			$date2=date_create($datalea['enddate']);
			$diff=date_diff($date1,$date2);
			$months = $diff->format("%m");
			$quarter = 4;
			if($months > 9 && $months <= 12){
				$months = number_format($vl / 4,2);
				$quarter = 4;
			}elseif($months > 6 && $months <= 9){
				$months = number_format($vl / 3,2);
				$quarter = 3;
			}elseif($months > 3 && $months <= 6) {
				$months = number_format($vl / 2,2);
				$quarter = 2;
			}elseif($months > 0 && $months <= 3){
				$months = $vl;
				$quarter = 1;
			}
			$plus = 0;
			for($i = 1; $i <= $quarter; $i++){
				if($i > 1){
					$plus += 3;
				}else{
					$plus = 0;
				}
				$quarterdate[] = date("Y-m-d",strtotime('+'.$plus.' month', strtotime($datalea['startdate'])));
			}
			$xcount = array();
			$chan = array();
			$quar = array();
			for($i = 0; $i < $quarter; $i++){
				if($i == ($quarter - 1)){
					$two = date("Y-12-31");
				}else{
					$plus1 = $i+1;
					$two = date("Y-m-t",strtotime("-1 month",strtotime($quarterdate[$plus1])));
				}
				$one = $quarterdate[$i];
				if(date("Y-m-d") > $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accids' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					if($counter['count'] == ""){
						$months += ($months-1);
					}elseif($counter['count'] < $months){
						$months += ($months-$counter['count']);
					}
				}
				if(date("Y-m-d") >= $one && date("Y-m-d") <= $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accids' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					$xcount[] = $counter['count'];
				}else{
					continue;
				}
			}
			for($i = 0; $i < $quarter; $i++){
				if(!isset($xcount[$i])){
					continue;
				}				
				if($xcount[$i] > $months) {
					$wthpay = 'withoutpay';
				}elseif(($months - $xcount[$i]) <= 0){
					$wthpay = 'withoutpay';
				}else{
					$wthpay = null;
				}
				if(stristr($sql, '2016-12-31') == true){
					$wthpay = null;
				}

			}
		}
		$year = date("Y");
		$sqlbereave = "SELECT sum(numdays) as count,leapay from nleave where leave_id = '$oid' and account_id = '$accids' and typeoflea = 'Bereavement Leave' and ( (leapay = 'wthpay' and state = 'AAdmin') or state = 'UA' or state = 'AHR') and YEAR(dateofleavfr) = '$year'";
		$counterbereave = $conn->query($sqlbereave)->fetch_assoc();
		if($counterbereave['leapay'] != ""){
			$wthpay = 'withoutpay';
		}else{
			$wthpay = "";
		}
		$sql = "SELECT * FROM nleave,login where login.account_id = $accids and leave_id = '$oid' and state = 'UA'";
		$result = $conn->query($sql);

		if($result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {
?>
<form action="approval.php" method="post">
	<div align="center" style="margin-top: 40px;"><h3><i><u>Leave Approval</u></i></h3></div>
		<table class="table" style="width: 50%" align="center">
			<tr><td width="30%"><b>Date File:</td><td width="30%"><?php echo date("M j, Y", strtotime($row['datefile'])); ?></td></tr>
			<tr><td width="30%"><b>Name:</td><td width="30%"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td></tr>
			<tr><td width="30%"><b>Date Hired:</td><td width="30%"><?php echo date("M j, Y", strtotime($row['edatehired'])); ?></td></tr> 
			<tr><td width="30%"><b>Type of Leave:</td><td width="30%"><?php echo $row['typeoflea']; if($row['typeoflea'] == "Others"){echo '<br> ( '. $row['othersl'] . ' )';}?></td></tr> 
			<?php if(isset($cate) && $cate == 'Regular'){ ?>
			<tr><td width="30%"><b>Balance:</td><td width="30%"><?php if($row['typeoflea'] == 'Sick Leave'){ echo $availsick; }elseif($row['typeoflea'] == 'Solo Parent Leave'){ echo $solleave; } else { echo $totavailvac; }	?></td></tr> 
			<?php if($row['typeoflea'] != 'Sick Leave' && $row['typeoflea'] != 'Solo Parent Leave'){ ?>
			<tr><td width="30%"><b>Balance for this Quarter:</td><td width="30%"><?php if($totavailvac >= $months){ echo $months-$xcount[0]; $remainvac = $months-$xcount[0];}elseif(isset($xcount[0]) && $months-$xcount[0] <= 0){echo  $months-$xcount[0];  $remainvac =$months-$xcount[0];}else{ echo $totavailvac;  $remainvac=$totavailvac;}?></td></tr> 
			<?php }} ?>
			<?php  ?>
			<tr><td width="30%"><b>Date of Leave (From - To):</td><td width="30%"><?php echo date("M j", strtotime($row['dateofleavfr'])) . ' - ' . date("M j, Y", strtotime($row['dateofleavto'])); ?></td></tr> 
			<tr><td width="30%"><b>Number of Days: </td><td width="30%"><?php echo $row['numdays']; ?></td></tr> 
			<tr><td width="30%"><b>Reason: </td><td width="30%"><?php $query1 = "SELECT * FROM `nleave` where leave_id = '$row[leave_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();echo $data1['reason'];?></td></tr>
		<?php
			if($row['typeoflea'] == "Sick Leave"){
				echo "<tr><td width='30%''><b>Fit To Work </td><td width='30%'>
				<select class = 'form-control' name = 'fitowork' required>
					<option value = ''> ------- </option>
					<option value = 'Fit to Work'> Fit To Work </option>
				</select>
				</td></tr>";
			}
		?>
		<tr>
			<td width="30%"><b>Payment </td><td width="30%">
				<select class="form-control" name = "payment" required>
					<option value="">------</option>
					<option value="wthoutpay">Without Pay</option>
					<?php if(($row['empcatergory'] == 'Regular' && $row['regdate'] <= date('Y-m-d') && $row['leapay'] == null && (($availsick >= $row['numdays'] && $row['typeoflea'] == 'Sick Leave') || ($remainvac >= $row['numdays'] && $row['typeoflea'] != 'Sick Leave')))) { ?>
						<option value="wthpay">With Pay</option>
					<?php }elseif($wthpay == "" && ($row['typeoflea'] == 'Bereavement Leave' || $row['typeoflea'] == 'Solo Parent Leave')){ ?>
						<option value="wthpay">With Pay</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<input type = "hidden" value = "<?php echo $row['leave_id'];?>" name = "leave_id"/>
		<input type = "hidden" value = "<?php if(isset($_GET['accadmin'])){ echo 1; }else{echo 0;};?>" name = "accadmin"/>
		<tr>
			<td colspan="2">
				<button class="btn btn-primary" name = "leaveapp">Approve Leave</button>
				<a href = "?ac=penlea" class="btn btn-danger"> Back </a>
			</td>
		</tr>
		</table>
	</form>
<?php
			}
		}
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
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penot'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and overtime_id = '$oid' and state = 'UAAdmin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 50%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = "2" align = "center">
						<h2> Edit Overtime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date("M j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['nameofemp']?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $row['department'];?></td>
				</tr>
				<tr>
					<td>Date Of Overtime: </td>
					<td><input value = "<?php echo $row['dateofot'];?>" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "updateofot"/></td>
				</tr>	
				<tr>
					<td>CSR #: </td>
					<td><input class = "form-control" type = "text" value = "<?php echo $row['csrnum'];?>" placeholder = "Enter CSR Number" name = "csrnum"/></td>
				</tr>			
				<tr>
					<td>Reason (Work to be done): </td>
					<td><textarea required name = "reason"class = "form-control"><?php $query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();echo $data1['reason'];?></textarea></td>	
				</tr>
			<div class = "ui-widget-content" style = "border: none;">
				<tr>
					<td>Start of OT: </td>
					<td>
						<input id = "timein" onkeydown="return false;" value = "<?php echo $row['startofot'];?>" required class = "form-control" name = "uptimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>End of OT: </td>
					<td><input  value = "<?php echo $row['endofot'];?>" onkeydown="return false;"required class = "form-control" name = "uptimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>
				<tr>
					<td>OT Break ( if applicable ):  </td>
					<td>
						<select class = "form-control" name = "otbreak" id = "otbreak">
							<option value ="">--------</option>
							<option <?php if($row['otbreak'] == "-30 Minutes"){ echo ' selected ';}?> value = "30 Mins">30 Mins</option>
							<option <?php if($row['otbreak'] == "-1 Hour"){ echo ' selected ';}?> value = "1 Hour">1 Hour</option>
						</select>
					</td>					
				</tr>
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>
					<td colspan = 2 style="float: center;">
						<label for="restday" style="font-size: 15px; width: 500px; margin-left: -200px;"><input type="checkbox" <?php if($row['officialworksched'] == "Restday"){ echo "checked";}?> value = "restday" name="uprestday" id="restday"/> Rest Day</label>
					</td>
				</tr>	
				<tr id = "rday" class = "form-inline" <?php if($row['officialworksched'] == "Restday"){ echo "style = 'display: none;'";}?>>
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input onkeydown="return false;"name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;"name = "upoffto"value = "<?php echo $ex2;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  />
					</td>					
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upotsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="uptimein"]').ptTimeSelect();
							$('input[name="uptimeout"]').ptTimeSelect();
							$('input[name="upoffr"]').ptTimeSelect();							
							$('input[name="upoffto"]').ptTimeSelect();
						});
					</script>
			</div>
	<?php
			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penundr'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and undertime_id = '$oid' and state = 'AHR'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 70%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 2 align = center>
						<h2> Edit Undertime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date("M j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['name'];?></td>
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
					<td>Date Of Undertime: </td>
					<td>
						<input required class = "form-control" type = "date" value = "<?php echo $row['dateofundrtime'];?>" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "click to set date"min = "<?php echo date('m/d/Y'); ?>" name = "undatereq"/>
					</td>						
				</tr>									
				<div class = "ui-widget-content" style = "border: none;">		
					<tr class = "form-inline">
						<td>Time of Undertime: </td>
						<td>
							<label for = "fr"> From: </label><input value = "<?php echo $row['undertimefr'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "to" class = "form-control"  name = "untimefr"/>
							<label for = "to"> To:  </label><input value = "<?php echo $row['undertimeto'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "fr" class = "form-control" name = "untimeto"/>
							<label for = "numhrs">Num. of Hrs/Mins </label><input required placeholder = "_hrs : __mins" value = "<?php echo $row['numofhrs'];?>" id = "numhrs" class = "form-control" style = "width: 200px" name = "unumofhrs"/>
						</td>	
					</tr>			
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="untimeto"]').ptTimeSelect();
							$('input[name="untimefr"]').ptTimeSelect();
						});
					</script>
				</div>						
				<tr>
					<td>Reason: </td>
					<td><textarea required name = "unreason"class = "form-control"><?php echo $row['reason'];?></textarea></td>
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upunsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
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
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penlea'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$sql = "SELECT * FROM nleave,login where nleave.account_id = $accid and login.account_id = $accid and leave_id = '$oid' and state = 'AHR'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 60%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 3 align = center>
						<h2> Edit Leave Request </h2>
					</td>
				</tr>
				<tr>
					<td colspan = 3 align = center>
						<h5><p style = "font-style: italic; color: red;">For scheduled leave, submit Leave request to Human Resources Department seven(7) days prior to leave date. </h5>
					</td>
				</tr>		
				<tr class = "form-inline" >
					<td>Type of Leave</td>
					<td align = "left">
						<?php if($row['typeoflea'] == 'Others'){ echo $row['typeoflea'] . ': '.$row['othersl'];}else{echo $row['typeoflea'];}?>
					</td>
				</tr>	
				<div style = "display: none;">
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['nameofemployee'];?></td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date('F j, Y', strtotime($row['datefile']));?></td>
				</tr>				
				<tr>
					<td>Date Hired: </td>
					<td><?php echo date('F j, Y', strtotime($_SESSION['datehired'])); ?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $_SESSION['dept'];?></td>
				</tr>
				<tr>
					<td>Position Title: </td>
					<td><?php echo $_SESSION['post'];?></td>
				</tr>
				<tr>
					<td colspan = 3 align = "center">
						<h3>LEAVE DETAILS</h3>
				</tr>
				<tr class = "form-inline">
					<td>Inclusive Dates: </td>
					<td style="float:left;">
						From: <input required class = "form-control" type = "date" placeholder = "Click to set date" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['dateofleavfr']; ?>" name = "dateofleavfr"/>
						To: <input required class = "form-control" type = "date" placeholder = "Click to set date" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['dateofleavto']; ?>" n name = "dateofleavto"/>
						Number of Days: <input value = "<?php echo $row['numdays'];?>" maxlength = "3" style = "width: 90px;"type = "text" pattern = '[0-9.]+' required name = "numdays"class = "form-control"/>
					</td>
				</tr>					

				<tr>
					<td>Reason: </td>
					<td><textarea class = "form-control" name = "leareason"required><?php echo $row['reason'];?></textarea></td>
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upleasubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
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
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penob'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$state = 'UAAdmin';
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and officialbusiness_id = '$oid' and state = '$state'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<div ><form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 50%;" align = "center">';
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
					<td><input value = "<?php echo $row['obdatereq'];?>" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "updateofob"/></td>
				</tr>				
				<tr>
					<td>Description of Work Order: </td>
					<td><textarea required name = "obreason" class = "form-control col-sm-10"><?php echo $row['obreason'];?></textarea></td>
					
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
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>
					<td colspan = 2 style="float: center;">
						<label for="restday" style="font-size: 15px; width: 500px; margin-left: -200px;"><input type="checkbox" <?php if($row['officialworksched'] == "Restday"){ echo "checked";}?> value = "restday" name="uprestday" id="restday"/> Rest Day</label>
					</td>
				</tr>	
				<tr id = "rday" class = "form-inline" <?php if($row['officialworksched'] == "Restday"){ echo "style = 'display: none;'";}?>>
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input onkeydown="return false;"name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;"name = "upoffto"value = "<?php echo $ex2;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  />
					</td>					
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
								$("#warning").show();
								return false;
							}else{
								$("#warning").hide();
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
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" id = "submituped"name = "upobsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
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
	if(isset($_GET['ac']) && $_GET['ac'] == 'penhol'){
		include("conf.php");
		$sql = "SELECT * FROM holidayre,login where login.account_id = holidayre.account_id and state = '0' order by state ASC";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 9 align = center><h2> Holiday Request Status </h2></td>
					</tr>
					<tr>
						<th width="12%">Date File</th>
						<th width="12%">Name</th>
						<th width="11%">Date of Request</th>
						<th width="20%">Type</th>
						<th width="30%">Reason</th>
						<th width="15%">Status</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
?>
			<tr>
				<td><?php echo date("M j, Y h:i A", strtotime($row['datefile'])); ?></td>
				<td><?php echo $row['fname'] . ' '. $row['lname']; ?></td>
				<td><?php echo date("M j, Y", strtotime($row['holiday'])); ?></td>
				<td><?php echo $row['type']; ?></td>
				<td><?php echo $row['reason']; ?></td>
				<td><b>
				<?php
					if($row['state'] == 0){
						echo '<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&hol='.$row['holidayre_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-check"></span> Ok</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dhol='.$row['holidayre_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" style = "margin-top: 2px; role="button"><span class="glyphicon glyphicon-remove-sign"></span> Disapprove</a>';
					}
				?>
				</b></td>
<?php
		}
		
	}echo '</tbody></table></form>';$conn->close();
}
?>
	<?php 
		if(isset($_GET['ac']) && $_GET['ac'] == 'penot'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
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
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and state = 'UA' and position != 'service technician' ORDER BY datefile ASC";
		$result = $conn->query($sql);
			
	?><h2 align="center" style="margin-top: 35px;"> Pending Overtime Request </h2>
	<form role = "form" action = "approval.php"    method = "get">
			<table id = "myTable" class = "table table-hover" align = "center">
			<?php 
				
				/*if(isset($_GET['bypass'])){
					$_SESSION['bypass'] = 1;
					echo '<a href = "?ac=' . $_GET['ac'] . '"class="btn btn-primary pull-right" style="margin-bottom: -50px; margin-top: 20px; margin-right: 20px;"> Un-Bypass Tech Approval </a>';
				}else{					
					unset($_SESSION['bypass']);
					echo '<a href = "?ac=' . $_GET['ac'] . '&bypass"class="btn btn-primary pull-right" style="margin-bottom: -50px; margin-top: 20px; margin-right: 20px;"> Bypass Tech Approval </a>';
				}*/
			?>
				<thead>	
					<tr >
						<th width="10%">Date File</th>
						<th width="10%">Date of O.T.</th>
						<th width="10%">Name</th>
						<th width="25%">Reason</th>
						<th width="20%">From - To (Overtime)</th>
						<th width="15%">Offical Work Schedule</th>
						<th width="10%">Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
			$error = 0;
			if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));

				$datetoday = date("Y-m-d");
				if($datetoday >= $row['2daysred'] ){
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
				$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				if($row['otlate'] != null){
					$otlate = '';
				}else{
					$otlate = "";
				}
				if($row['projtype'] != "" && $row['projtype'] != 'Others'){
					$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
				}else{
					$project = "";
				}
				if($row['project'] == ""){
					$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
				}
				$approvedothrs = str_replace(":", ".", $row['approvedothrs']);
				$approvedothrs2 = str_replace("30", "5", $approvedothrs);
				//$approvedothrs2 = '<b># of hrs: '. $approvedothrs2.'<br>';
				$approvedothrs2 = "";
				echo 
					'	<td width = 180>'.$newDate.'</td>
						<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
						<td>'.$row["nameofemp"].'</td>
						<td width = 250 height = 70>'.$data1["reason"]. $project.'<br><font color = "green"><i>'.$row['comtype'].'</i></font></td>
						<td style = "text-align:left;">'. $approvedothrs2.$row['csrnum']. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>							
						<td>'.$row["officialworksched"].'</td>';
				if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}elseif($row['state'] == 'UATech' && !isset($_GET['bypass'])){
						echo '<td><b>Pending to Tech. Supervisor</b></td></tr>';
				}else{
					echo '<td width = "250">'.$otlate.'
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-check"></span> Ok</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&upovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit"></span> Edit</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" style = "margin-top: 2px; role="button"><span class="glyphicon glyphicon-remove-sign"></span> Disapprove</a>
						</td>
					</tr>';
				}
			}
		}

		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and (state = 'UA' or state = 'UATech') and position = 'service technician ' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				if($row['datefile'] < date("2015-12-10")){
					continue;
				}
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['2daysred'] ){
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
					$otlate = '<b><font color = "green"><i>Approved Late Filing by the Dep. Head</i></font></b><br>';
				}else{
					$otlate = "";
				}
				if($row['projtype'] != "" && $row['projtype'] != 'Others'){
					$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
				}else{
					$project = "";
				}
				if($row['project'] == ""){
					$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
				}
				$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo 
					'	<td width = 180>'.$newDate.'</td>
						<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
						<td>'.$row["nameofemp"].'</td>
						<td width = 250 height = 70>'.$data1["reason"]. $project.'<br><font color = "green"><i>'.$row['comtype'].'</i></font></td>
						<td style = "text-align:left;">'.$row['csrnum']. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>							
						<td>'.$row["officialworksched"].'</td>';
				if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}elseif($row['state'] == 'UATech' && !isset($_GET['bypass'])){
						echo '<td><b>Pending to Tech. Supervisor</b></td></tr>';
				}else{
					echo '<td width = "250">'.$otlate.'
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-check"></span> Ok</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&upovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit"></span> Edit</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" style = "margin-top: 2px; role="button"><span class="glyphicon glyphicon-remove-sign"></span> Disapprove</a>
						</td>
					</tr>';
				}
			}
		}

		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and dateofot BETWEEN '$forque' and '$endque' ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$datetoday = date("Y-m-d");
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				$newDate2 = date("M j, Y", strtotime($row['dateofot']));
				if($row['state'] == 'UA'){
					continue;
				}	
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
				if($row['projtype'] != "" && $row['projtype'] != 'Others'){
					$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
				}else{
					$project = "";
				}
				if($row['projtype'] == 'Others'){
					$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
				}
				$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo 
					'
						<td>'.$newDate .'</td>			
						<td>'.$newDate2.'</td>			
						<td>'.$row["nameofemp"].'</td><td width = 300 height = 70>'.$data1['reason']. $project.'<br><font color = "green"><i>'.$row['comtype'].'</i></font></td>
						<td style = "text-align:left;">'.$row['csrnum']. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>							
						<td>'.$row["officialworksched"].'</td>				
						<td><b>';
							if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
								echo 'Pending for Time Checking <br>';
							}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
								echo $otlate;
								echo 'Pending for Time Checking HR<br>';
							}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
								echo $otlate;
								echo 'Pending to Tech Supervisor<br>';
								if($row['otlate'] == null){
									echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';								}
							}else if($row['state'] == 'CheckedHR'){
								echo $otlate;
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
						echo '</td></tr>';
			}
		}else{
			$error += 1;
		}
		if($error > 1){
			echo '<tr><td colspan = 7><h2>No record found</h2></td></tr>';
		}
		echo '</tbody></table></form>';
	}
?>

<?php 
		if(isset($_GET['ac']) && $_GET['ac'] == 'penlea'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 > 16){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 16;
		}
		include("conf.php");
		$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and (state = 'UA' or state = 'ReqCLea') and YEAR(datefile) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php"    method = "get">
			<table width = "100%"class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 10 align = center><h2> Pending Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "160">Date File</th>
						<th width = "170">Name of Employee</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "200">Date of Leave</th>
						<th width = "120"># of Day/s</th>
						<th width = "170">Type of Leave</th>
						<th width = "180">Reason</th>
						<th width = "240">Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		$error = 0;
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));

				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] ){
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
					 <td>'.date("M d, Y", strtotime($row["edatehired"])).'</td>
					 <td >'.$row["deprt"].'</td>
					 <td>'.$row['posttile'].'</td>					
					 <td> Fr: '.date("M d, Y", strtotime($row["dateofleavfr"])) .'<br>To: '.date("M d, Y", strtotime($row["dateofleavto"])).'</td>
					 <td>'.$row["numdays"].'</td>					
					 <td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					 <td >'.$data1["reason"].'</td>';
					 if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
					}elseif($row['state'] == 'ReqCLea'){
						echo '<td width = "200"> <a class = "btn btn-danger" onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?hrclea='.$row['leave_id'].'"> Approve Cancelation Req. </a></td>';
					}else{
						echo '<td width = "200">'.$lates;
						if($row['accadmin'] > 0){ echo '<font color = "green"><b>App. Scheduled Leave<br>'; $accadmin = "&accadmin";}else{ $accadmin = "";}
						echo'	
							<a href = "?leaapprove='.$row['account_id'].'&leave='.$row['leave_id'].'&acc='.$_GET['ac'].  $accadmin .'"';?><?php echo'" class="btn btn-info" role="button">Checked</a>';
							if($row['accadmin'] < 1){ echo ' <a href = "?approve=DA'.$_SESSION['level'].'&dleave='.$row['leave_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button">Disapprove</a>';}
						echo '</td>
						</tr>';
					}
		}
	}else{
		$error += 1;
	}
		$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and YEAR(dateofleavfr) = $datey ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
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
					<td >'.$row["typeoflea"]. ' ' . $row['othersl']. '</td>	
					<td >'.$data1["reason"].'</td>	
					<td width = "200"><b>'.$lates;
							if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}elseif($row['state'] == 'CLea'){
								echo '<font color = "red"> Leave Canceled  </font>';
							}elseif($row['state'] == 'ReqCLea'){
								echo '<font color = "red"> Pending Cancelation Request </font>';
							}elseif($row['state'] == 'ReqCLeaHR'){
								echo '<font color = "red"> Pending Cancelation Request </font>';
							}
						echo '<td></tr>';
		}	
	}else{
		$error += 1;
	}
	if($error > 1){
		echo '<tr><td colspan = 10><h2>No record found</h2></td></tr>';
	}
echo '</tbody></table></form>';
}
?>
<?php 
		if(isset($_GET['ac']) && $_GET['ac'] == 'penundr'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
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
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id and state = 'UA' and dateofundrtime > '2016-02-05' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 7 align = center><h2> Pending Undertime Request </h2></td>
					</tr>
					<tr >
						<th>Date File</th>
						<th>Date of Undertime</th>
						<th>Name of Employee</th>
						<th>Reason</th>
						<th>Fr - To (Undertime)</th>
						<th>Number of Hrs/Minutes</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		$error = 0;
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				
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
					<td>'.$row["numofhrs"].'</td>	';
					 if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				echo'				
					<td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-check"></span> Ok</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&upundr='.$row['undertime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit"></span> Edit</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dundertime='.$row['undertime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button">Disapprove</a>
					</td>
				</tr>';
			}
		}
			
		}else{
			$error += 1;
		}
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and datefile BETWEEN '$forque' and '$endque' ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				
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
						if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['undertime_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}
					echo '<td></tr>';
			}
		}else{
			$error += 1;
		}

		if($error > 1){
			echo '<tr><td colspan = 7><h2>No record found</h2></td></tr>';
		}
	echo '</tbody></table></form>';
}
?>
<?php
	if(isset($_GET['ac']) && $_GET['ac'] == 'penob'){
	$date17 = date("d");
	$dated = date("m");
	$datey = date("Y");
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
	include("conf.php");
	$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'UA' and obdate > '2015-12-03' and position != 'service technician' ORDER BY obdate ASC";
	$result = $conn->query($sql);	
?>

	<form role = "form" action = "approval.php"    method = "get">
	<?php 
		
		/*if(isset($_GET['bypass'])){
			$_SESSION['bypass'] = 1;
			echo '<a href = "?ac=' . $_GET['ac'] . '"class="btn btn-primary pull-right" style="margin-bottom: -50px; margin-top: 20px; margin-right: 20px;"> Un-Bypass Tech Approval </a>';
		}else{					
			unset($_SESSION['bypass']);
			echo '<a href = "?ac=' . $_GET['ac'] . '&bypass"class="btn btn-primary pull-right" style="margin-bottom: -50px; margin-top: 20px; margin-right: 20px;"> Bypass Tech Approval </a>';
		}*/
	?>
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Pending Official Business Request </h2></td>
				</tr>
				<tr>
					<th width="10%">Date File</th>
					<th width="10%">Name of Employee</th>
					<th width="8%">Position</th>
					<th width="8%">Department</th>
					<th width="8%">Date of Request</th>
					<th width="10%">Time In - Time Out</th>
					<th width="10%">Offical Work Schedule</th>
					<th width="21%">Reason</th>
					<th width="15%">Action</th>
				</tr>
			</thead>
			<tbody>
<?php
		$error = 0;
		if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){			
			$originalDate = date($row['obdate']);
			$newDate = date("M j, Y h:i A", strtotime($originalDate));
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
				echo '<tr style = "color: red">';
			}else{
				echo '<tr>';
			}		
			if($row['nxtday'] == 1){
				$nxtday = "<br>(next day out)";
			}else{
				$nxtday = "";
			}
			echo 
					'<td>'.$newDate.'</td>
					<td>'.$row["obename"].'</td>
					<td>'.$row["obpost"].'</td>
					<td >'.$row["obdept"].'</td>
					<td>'.date("M d, Y", strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"] . $nxtday .'</td>				
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
							<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button" id = "DAHR">Disapprove</a>
						
						</td>
					</tr>';
					}
		}
	}

	$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and obdate > '2015-12-03' and (state = 'UA' or state = 'UATech') and position = 'service technician' ORDER BY obdate ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['obdate']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}	
				if($row['nxtday'] == 1){
					$nxtday = "<br>(next day out)";
				}else{
					$nxtday = "";
				}	
				echo 
						'<td>'.$newDate.'</td>
						<td>'.$row["obename"].'</td>
						<td>'.$row["obpost"].'</td>
						<td >'.$row["obdept"].'</td>
						<td>'.date("M d, Y", strtotime($row['obdatereq'])).'</td>					
						<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
						<td>'.$row["officialworksched"].$nxtday.'</td>				
						<td >'.$row["obreason"].'</td>	';
						if($row['state'] == 'UAACCAdmin'){
							echo '<td><strong>Pending to Admin<strong></td>';
						}elseif($row['state'] == 'UATech' && !isset($_GET['bypass'])){
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
									<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button" id = "DAHR">Disapprove</a>
							</td>
							</tr>';
							
						}
			}
		}
	$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and obdate BETWEEN '$forque' and '$endque' ORDER BY state ASC,obdate ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$originalDate = date($row['obdate']);
				$newDate = date("M j, Y h:i A", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}		
				if($row["obtimein"] != null && $row['obtimeout'] != null){
					$split = " - ";
				}else{
					$split = "";
				}
				if($row['state'] == 'UA'){
					continue;
				}
				echo 
					'	<td>'.$newDate.'</td>
						<td>'.$row["obename"].'</td>
						<td>'.$row["obpost"].'</td>
						<td >'.$row["obdept"].'</td>
						<td>'.date("M j, Y", strtotime($row['obdatereq'])).'</td>					
						<td>'.$row["obtimein"] . $split . $row['obtimeout'].'</td>
						<td>'.$row["officialworksched"].'</td>				
						<td >'.$row["obreason"].'</td>	
					<td><b>';
							if($row['state'] == 'UAAdmin' || $row['state'] == 'UALate' || $row['state'] == 'UA'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'CheckedHR'){
								if($row['dateacc'] == 1){
									$chck = 'ACC';
								}else{
									$chck = 'HR';
								}
								echo '<p><font color = "green">Checked by '.$chck.'</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}else if($row['state'] == 'AHR'){
								echo '<p><font color = "green">Pending to Dep. Head</font></p> '.$row['dareason'];
							}
						echo '<td></tr>';
		}
	}else{
		$error += 1;
	}
	if($error > 1){
		echo '<tr><td colspan = 9><h2>No record found</h2></td></tr>';
	}
	$conn->close();
	echo '</tbody></table></form>';
}
?>


<?php
	
	include('conf.php');
	if(isset($_GET['dovertime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['dovertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penot" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';
			
	}
	if(isset($_GET['dhol'])){	
		$id = mysqli_real_escape_string($conn, $_GET['dhol']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penhol" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "hol" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';
			
	}
	if(isset($_GET['uphol'])){
		$id = mysqli_real_escape_string($conn, $_GET['uphol']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$sql = "SELECT * FROM holidayre,login where login.account_id = holidayre.account_id and holidayre_id = '$id' and state = 0";
		$result = $conn->query($sql);		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<form action = "update-exec.php" method = "post" class = "form-group">
					<table class = "table table-hover" style = "width: 720px;" align = "center">
						<thead>
							<tr>
								<th colspan  = 2><h3> Update Holiday  </h3></th>
							</tr>
						</thead>';
				echo '<tbody>';
				echo '<tr><td>Name: </td><td>' . $row['fname'] . ' ' . $row['lname'] . '</td></tr>';
				echo '<tr><td>Time In / Out: </td><td>' . $row['timein'] . ' / ' . $row['timeout'] . '</td></tr>';
				echo '<tr><td>Reason: </td><td>' . $row['reason'] . '</td></tr>';
				echo '<tr><td>Holiday</td><td><b>'. date("M j, Y",strtotime($row['holiday'])) . '<br>' . $row['type'] . '</td></tr>';
				echo '<tr><td>New Time In</td><td><input type = "text" value = "' . $row['timein'] . '" class = "form-control" name = "hruptimein"/></tr>';
				echo '<tr><td>New Time Out</td><td><input type = "text" value = "' . $row['timeout'] . '" class = "form-control" name = "hruptimeout"/></td></tr>';
				echo '<tr><td>Based On</td><td><select class = "form-control" required name = "dareason" id = "dareason">
							<option value = "">-------</option>
							<option value = "Biometrics">Biometrics</option>
							<option value="C.S.R">C.S.R.</option>	
						</select></td></tr>';
				echo '<input value = "'. $row['account_id'] .'" type = "hidden" name = "accid"/>';
				echo '
					<input type = "hidden" name = "oldobtimein" value = "' . $row['timein'] . '"/>
					<input type = "hidden" name = "oldobtimeout" value = "' . $row['timeout'] . '"/>
				  	<tr>
						<td><input type = "hidden" name = "hol" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
				  	</tr>
				  	<tr>
						<td colspan = 2>
							<button onclick = "return confirm(\'Are you sure? (Edit Time)\');" type = "submit" class = "btn btn-primary" name = "hruphol" value = "Submit Edit"><span class="glyphicon glyphicon-ok-sign"></span> Submit</button>
							<a href = "?ac=penhol" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a>
						</td>
					</tr>
				</tbody> 
				</table>
			</form>';
			}
		}
		echo "
				<script type=\"text/javascript\">
					$(document).ready(function(){
						$('input[name=\"hruptimein\"]').ptTimeSelect();
						$('input[name=\"hruptimeout\"]').ptTimeSelect();
					});
				</script>";
	}
	if(isset($_GET['upovertime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['upovertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		if(isset($_SESSION['bypass'])){
			$xstate = '(state = "UA"  or state = "UATech")';
		}else{
			$xstate = ' state = "UA" ';
		}
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and overtime_id = '$id' and $xstate";
		$result = $conn->query($sql);		
		if($result->num_rows > 0){
			echo '<form action = "update-exec.php" method = "post" class = "form-group">
					<table class = "table table-hover" style = "width: 720px; border: none;" align = "center">
						<thead>
							<tr>
								<th colspan  = 3><h3> Update Time  </h3></th>
							</tr>
						</thead>';
			while($row = $result->fetch_assoc()){
				if($row['otlate'] != null){
					echo  '<tr><td colspan = "2"><b><font color = "green"><i>Approved Late Filing by the Dep. Head</i></font></b></td><tr>';
				}
				?>

				<tr>
					<td><b>Date File: </b></td>
					<td><?php echo date("M j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td><b>Name of Employee: </b></td>
					<td><?php echo $row['nameofemp']?></td>
				</tr>
				<tr>
					<td><b>Position: </b></td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td><b>Department: </b></td>
					<td><?php echo $row['department'];?></td>
				</tr>
				<tr>
					<td><b>Date Of Overtime: </b></td>
					<td><?php echo date("M j, Y", strtotime($row['dateofot']));?></td>
				</tr>	
				<tr>
					<td> <b>Type: </td>
					<td>
						<select required class="form-control" name = "ottype">
		          			<option value=""> Select ( P.M / Internet / Project / Luwas / Netlink)  </option>
		          			<option <?php if($row['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
		          			<option <?php if($row['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
		          			<option <?php if($row['projtype'] == 'Project'){ echo ' selected '; } ?> value="Project"> Project </option>
		          			<option <?php if($row['projtype'] == 'Support'){ echo ' selected '; } ?> value="Support"> Project Support </option>
		          			<option <?php if($row['projtype'] == 'Service'){ echo ' selected '; } ?> value="Service"> Service </option>
		          			<option <?php if($row['projtype'] == 'Email Hosting'){ echo ' selected '; } ?> value="Email Hosting"> Email Hosting </option>
		          			<option <?php if($row['projtype'] == 'Commission Base'){ echo ' selected '; } ?> value="Commission Base"> Commission Base </option>
		          			<option <?php if($row['projtype'] == 'Corporate'){ echo ' selected '; } ?> value="Corporate"> Corporate </option>
		          			<option <?php if($row['projtype'] == 'Luwas'){ echo ' selected '; } ?> value="Luwas"> Luwas </option>	
		          			<option <?php if($row['projtype'] == 'Netlink'){ echo ' selected '; } ?> value="Netlink"> Netlink </option>	
						</select>
					</td>
				</tr>
				<tr <?php if($row['projtype'] != 'Corporate'){ echo ' style = "display: none;" '; } ?> id = "otcorpo">
            		<td><label>Corporate <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otcorpo">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Corporate' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'Commission Base'){ echo ' style = "display: none;" '; } ?>  id = "otcomisiontype">
            		<td><label>Commission Base (Project/Bidding)<font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otcomisiontype">
		            		<option value = ""> - - - - - </option>
		            		<option value="Bidding" <?php if($row['comtype'] == 'Bidding') echo ' selected '; ?>>Bidding</option>
		            		<option value="Project" <?php if($row['comtype'] == 'Project') echo ' selected '; ?>>Project</option>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] == 'Commission Base' && $row['comtype'] == 'Project'){}else{ echo ' style = "display: none;" '; } ?> id = "otcomisionproj">
            		<td><label>Commission Base (Project)<font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otcomisionproj">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Commission Base' and comtype = 'Project' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] == 'Commission Base' && $row['comtype'] == 'Bidding'){}else{ echo ' style = "display: none;" '; } ?> id = "otcomisionbid">
            		<td><label>Commission Base (Bidding)<font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otcomisionbid">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Commission Base' and comtype = 'Bidding' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
				<tr <?php if($row['projtype'] != 'Email Hosting'){ echo ' style = "display: none;" '; } ?> id = "otehosting">
            		<td><label>Email Hosting <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otehosting">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Email Hosting' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'Support'){ echo ' style = "display: none;" '; } ?> id = "otsupport">
            		<td><label>Location <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "locx" onchange="showUser(this.value,'','sup')">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT loc FROM `project` where type = 'Support' and state = '1' group by loc order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					$xsql2 = "SELECT loc FROM `project` where type = 'Support' and name = '$row[project]'";
		            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
		            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
		            						$selecteds = ' selected ';		            						
		            						$loc = $xresult2['loc'];
		            					}else{
		            						$selecteds = "";
		            					}

		            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
				<tr <?php if($row['projtype'] != 'Service'){ echo ' style = "display: none;" '; } ?> id = "otoncall">
            		<td><label>Service </label></td>
            		<td>
            			<select class="form-control" name = "otoncall">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
				<tr <?php if($row['projtype'] != 'Project'){ echo ' style = "display: none;" '; } ?> id = "otproject">
            		<td><label>Project </label></td>
            		<td>
            			<select class="form-control" name = "loc" onchange="showUser(this.value,'proj','')">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(loc)";
		            			$xresult = $conn->query($xsql);		            			
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					$xsql2 = "SELECT loc FROM `project` where type = 'Project' and name = '$row[project]'";
		            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
		            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
		            						$selecteds = ' selected ';		            						
		            						$locx = $xresult2['loc'];
		            						$loc = "";
		            					}else{
		            						$selecteds = "";
		            					}

		            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>		        
		        <tr id = "loc" >
		        	<?php if($row['projtype'] == 'Project' || $row['projtype'] == 'Support'){ ?>
		        	<td><b>PO </td>
		        	<td>
		        		<select name = "otproject" class = "form-control">
		        			<?php
		            			$xsql = "SELECT * FROM `project` where type = '$row[projtype]' and state = '1' and (loc = '$loc' or loc = '$locx') order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		        		</select>
		        	</td>
		        	<?php } ?>
		        </tr>
		        <tr <?php if($row['projtype'] != 'P.M.'){ echo ' style = "display: none;" '; } ?> id = "otpm">
            		<td><label>P.M. </label></td>
            		<td>
            			<select class="form-control" name = "otpm">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'Internet'){ echo ' style = "display: none;" '; } ?> id = "otinternet">
            		<td><label>Internet</label></td>
            		<td>
            			<select class="form-control" name = "otinternet">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>	
				<tr>
					<td width="260px"><b>Reason (Work to be done): </b></td>
					<td width="460px"><?php if($row['projtype'] != "" && $row['projtype'] != 'Others'){
					$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
				}else{
					$project = "";
				}if($row['projtype'] == 'Others'){
						$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
					}$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
				$data1 = $conn->query($query1)->fetch_assoc();echo $data1['reason'] . $project;?></td>	
				</tr>
			<div class = "ui-widget-content" style = "border: none;">
				<tr>
					<td><b>Start of OT: </b></td>
					<td><?php echo $row['startofot'];?></td>
				</tr>				
				<tr>
					<td><b>End of OT: </b></td>
					<td><?php echo $row['endofot'];?></td>
				</tr>
				<?php
					if($row['otbreak'] != null){
				?>
				<tr>
					<td><b>OT Break: </b></td>
					<td><input type = "text" class="form-control" readonly name = "oldotbreak" value = "<?php echo substr($row['otbreak'], 1);?>"/></td>					
				</tr>
				<?php } ?>
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						if(stristr($row['officialworksched'], '<br>') !== FALSE){
							$explode1 = explode('<br>', $row['officialworksched']);
							$row['officialworksched'] = $explode1[1];
						}
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>					
					<td colspan="2">
						<label for="restday" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Restday'){ echo ' checked '; } ?> value = "restday" name="uprestday" id="restday"/> Rest Day </label>
						<label for="oncall" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Oncall'){ echo ' checked '; } ?> value = "oncall" name="uponcall" id = "oncall"/> Oncall </label>
						<label for="sw" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Special N-W Holliday'){ echo ' checked '; } ?> value = "sw" name="sw" id = "sw"/> Special N-W Holliday </label>
						<label for="lg" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Legal Holliday'){ echo ' checked '; } ?> value = "sw" name="lg" id = "lg"/> Legal Holliday </label>
					</td>
				</tr>	
				<tr>
					<td><b>OT Break ( if applicable ):  </td>
					<td>
						<select class = "form-control" name = "otbreak" id = "otbreak">
							<option value ="">--------</option>
							<option <?php if($row['otbreak'] == "-30 Minutes"){ echo ' selected ';}?> value = "30 Mins">30 Mins</option>
							<option <?php if($row['otbreak'] == "-1 Hour"){ echo ' selected ';}?> value = "1 Hour">1 Hour</option>
						</select>
					</td>					
				</tr>
				<tr id = "rday" class = "form-inline" >
					<td><b>Official Work Sched: </b></td>
					<td>
						<?php if(isset($explode1[0])){ echo '<b>'.$explode1[0].'</b><br>'; } ?>
						<label for = "fr">From:</label><input onkeydown="return false;" name = "oldofffr" value = "<?php echo $ex1;?>"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;" name = "oldoffto"value = "<?php echo $ex2;?>"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  />
					</td>			
				</tr>
				<tr>
					<td><b>CSR #</b></td>
					<td><?php echo $row['csrnum'] ?>
				</tr>
				<tr>
					<td><b>New Start of OT: </b></td>
					<td>
						<input id = "timein" value = "<?php echo $row['startofot'];?>" required class = "form-control" name = "hruptimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td><b>New End of OT: </b></td>
					<td><input  value = "<?php echo $row['endofot'];?>" required class = "form-control" name = "hruptimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>				
				<tr class = "form-inline" >
					<td><b>New Official Work Sched: </b></td>
					<td>
						<?php if(isset($explode1[0])){ echo '<b>'.$explode1[0].'</b><br>'; } ?>
						<label for = "fr">From:</label><input onkeydown="return false;" name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;" name = "upoffto" value = "<?php echo $ex2;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  />
					</td>			
				</tr>
				<tr id = "warning" style="display: none;">
					<td></td>
					<td>
						<div class="alert alert-danger fade in">
						  <strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b>
						</div>
					</td>
				</tr>
				<tr>
					<td align = "right"><label for = "dareason"> Based On </label></td>
					<td>
						<select class = "form-control" required name = "dareason" id = "dareason">
							<option value = "">-------</option>
							<option value = "Biometrics">Biometrics</option>
							<option value="C.S.R">C.S.R.</option>
							<option value="Manpower Allocation">Manpower Allocation</option>	
						</select>
					</td>
				</tr>
				<tr>
					<td align = "left" colspan = "2"> <label> Employee Error <input type = "checkbox" name = "correction"/> </td>
				</tr>
				<tr style="display:none;">
					<td>
						<input value = "<?php echo $row['startofot'];?>" type = "hidden" name = "oldotstrt"/>
						<input value = "<?php echo $row['endofot'];?>" type = "hidden" name = "oldotend"/>
						<input value = "<?php echo $row['projtype'];?>" type = "hidden" name = "oldprojtype"/>
						<input value = "<?php echo $row['project'];?>" type = "hidden" name = "oldproject"/>
						<input value = "<?php echo $row['account_id'];?>" type = "hidden" name = "accid"/>
						<input value = "<?php if(isset($explode1[0]) && $explode1[0] != ''){ echo $explode1[0]; } ?>" type = "hidden" name = "oldsched"/>
					</td>
				</tr>		
				<script type="text/javascript">
					$(document).ready(function(){	
						$('#obtimein').click(function() {
							$("#warning").hide();
						});
						$("#submits").click(function(){						
							if($("#obtimein").val() == "" && $("#obtimeout").val() == "" ){
								$("#warning").show();
								return false;
							}else{
								$("#warning").hide();
							}
						});
					});
				</script>
				<script type="text/javascript">
					$(document).ready(function(){
						$('input[name="hruptimein"]').ptTimeSelect();
						$('input[name="hruptimeout"]').ptTimeSelect();
						$('input[name="upoffr"]').ptTimeSelect();
						$('input[name="upoffto"]').ptTimeSelect();
					});
				</script>
		</div>
	<?php
			}
			echo '<tr>
					<td colspan = 2>
						<button onclick = "return confirm(\'Are you sure? (Edit Time)\');" type = "submit" class = "btn btn-primary" name = "hrupdatetime" value = "Submit Edit"><span class="glyphicon glyphicon-ok-sign"></span> Submit</button>
						<a href = "?ac=penot" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a>
					</td>
				</tr>
			  	</tr>
			  	<tr>
					<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
					<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
					<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
			  	</tr>
			</table>
		</form>';
		}
		
			
	}
?>
<?php
	include('conf.php');
	if(isset($_GET['dleave'])){
		$id = mysqli_real_escape_string($conn, $_GET['dleave']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$query1 = "SELECT * FROM `nleave`,`login` where nleave.account_id = login.account_id and leave_id = '$id' and accadmin is null";
		$data1 = $conn->query($query1)->fetch_assoc();
		if($data1['leave_id'] != null){
			echo '<form action = "approval.php" method = "get" class = "form-group">
					<table class = "table table-hover" align = "center">
						<thead>
							<tr>
								<th colspan  = 3><h3> Disapproval Reason </h3></th>
							</tr>
						</thead>
						<tr>
							<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
							<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
						</tr>
						<tr>
							<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penlea" class = "btn btn-danger">Back</a></td>
						</tr>
						<tr>
							<td><input type = "hidden" name = "leave" value = "'.$id.'"/></td>
							<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
							<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
						</tr>
					</table>
				</form>';	
			}		
	}
	if(isset($_GET['dofficialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['dofficialbusiness_id']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penob" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "officialbusiness_id" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';		
	}
	if(isset($_GET['dundertime'])){
		$id = mysqli_real_escape_string($conn, $_GET['dundertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penundr" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "undertime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';
		}
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
