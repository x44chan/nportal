<?php 
	session_start();
	$accid = $_SESSION['acc_id'];
	include("conf.php");
	if(!isset($_SESSION['acc_id'])){
		header("location: index.php");
	}
	date_default_timezone_set('Asia/Manila');
	include("header.php");	
?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#reqTb').DataTable({
		    "iDisplayLength": 15,
		    "order": [[ 0, "desc" ]]  	
		});
		$('.dataTables_length').hide();
	});
</script>
<div align = "center" style = "margin-bottom: 30px;">
	<div class="alert alert-success"><br>
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
			<?php if($_SESSION['level'] == 'ACC'){ ?>
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
			<?php } ?>
			<?php if($_SESSION['level'] == 'TECH'){ ?>
			<a  type = "button"class = "btn btn-primary"  href = "tech-sched.php" >Tech Scheduling</a>
			<?php } ?>
			<?php
				if((stristr($_SESSION['post'], 'sales') !== false) || stristr($_SESSION['post'], 'marketing') !== false){
					echo '<a href = "?expn" class="btn btn-primary"> Expenses </a>';
				}
			?>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">My Request Status <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "req-all.php?appot">All Request</a></li>
				  <li><a href = "req-app.php">My Approved Request</a></li>
				  <li><a href = "req-dapp.php">My Disapproved Request</a></li>	
				</ul>
			</div>		
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
		<br><br>
		<div class = "btn-group btn-group">
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?appot"> Overtime </a>
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?appob"> Official Business </a>
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?applea"> Leave  </a>			
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?appundr"> Undertime  </a>	
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?apppety"> Petty  </a>
			<?php if($_SESSION['category'] == "Regular"){?>
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?appca"> Cash Advance  </a>	
			<a style = "width: 150px;" type = "button"class = "btn btn-success" href = "?apploan"> Loan </a>
			<?php } ?>
		</div>
	</div>
</div>
<div id = "dash">
<?php 
	if(isset($_GET['lqdate'])){
		include 'aliquidate.php';	
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
				echo '<tr><td colspan = "2" align = "center"><button class = "btn btn-primary" type = "submit" name = "valcodesub">Complete Liquidate</button> <a href = "?apppety" class = "btn btn-danger">Back</a></td></tr>';
				echo '<input type = "hidden" name = "petyid" value = "' . $row['petty_id'] . '"/>';
			}	
			echo '</table></form>';
	}else{
		echo '<script type="text/javascript">window.location.replace("?apppety"); </script>';
	}
}
?>	
<?php 
	if(isset($_GET['loan']) && $_GET['loan'] > 0){
		include("caloan/loan.php");
		}
?>
<?php 
	if(isset($_GET['apploan']) && !isset($_GET['loan'])){
		include("conf.php");
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid order by state ASC";
		$result = $conn->query($sql);		
	?>	
	<h2 align="center" style="margin-top: -25px;"> Loan Request </h2>
	<table id = "reqTb" class = "table table-hover" align = "center">
		<thead>
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
									echo '<a href = "?uploan='.$row['loan_id'].'&acc=penloan" class = "btn btn-success">Update Requested Amount</a> ';									
								}elseif($row['state'] == 'ARcvLoan'){
									echo '<a href = "petty-exec.php?loan='.$row['loan_id'].'&acc=penloan" class = "btn btn-success">Receive Loan</a> ';
									echo '<a href = "loan-exec.php?loanss='.$row['loan_id'].'&acc=penloan" class = "btn btn-danger">Decline</a>';
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
	$sql = "SELECT * FROM cashadv,login where login.account_id = $accid and cashadv.account_id = $accid order by cadate desc";
	$result = $conn->query($sql);
?>
	<h2 align="center" style="margin-top: -25px;"> Cash Advance Request </h2>
	<table id = "reqTb" class="table"  style="margin-top: -55px;">
		<thead>
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
					echo '<a href = "petty-exec.php?cashadv='.$row['cashadv_id'].'&acc=penca" class = "btn btn-success">Receive Cash Advance</a>';
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
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
	<h2 align="center" style="margin-top: -25px;"> Petty Request </h2>		
	<table id = "reqTb"  class = "table table-hover" align = "center">
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
						<td>'.$row['petty_id'].'</td>
						<td>'.$newDate.'</td>
						<td>'.$row['fname'] . ' '. $row['lname'].'</td>
						<td>'.$row['particular'].'</td>
						<td>'.$row['source'].'</td>
						<td>'.$transcode.'</td>
						<td>&#8369; '.$row['amount'].'</td>
						<td>';
							if($row['state'] == "CPetty"){
								echo '<b><font color = "red">Canceled Petty</font></b>';
							}elseif($row['state'] == "UAPetty"){
								echo '<b>Pending to Admin <br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'AAAPettyReceive'){
								echo '<a href = "petty-exec.php?o='.$row['petty_id'].'&acc=penpty" class = "btn btn-success">Receive Petty</a>';
							}elseif($row['state'] == 'DAPetty'){
								echo '<b><font color = "red">Disapproved request';
							}elseif($row['state'] == 'AAPettyReceived'){
								echo '<font color = "green"><b>Received ';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPetty'){
								echo '<font color = "green"><b>Pending to Accounting</font>';
							}elseif($row['state'] == 'UATransfer'){
								echo '<b> Pending for Processing</b><br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'TransProc'){
								echo '<b><font color = "green"> Proccessed by the Accounting </font></b><br>';
								echo '<a href = "?getcode='.$row['petty_id'].'" class = "btn btn-success">Get Code</a>';
							}elseif($row['state'] == 'TransProcCode'){
								echo '<font color = "green"><b>For Admin Verification & Releasing';
								echo '</font></br>Code: ' . $row['rcve_code'];
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
									echo '<b>Pending Completion</b><br>';
									echo '<a href = "?editliqdate='.$row['petty_id'].'" class = "btn btn-danger"> Edit Liquidation </a>';
								}
							}
				echo '</td></tr>';

		}
		
	}echo '</tbody></table></form>';$conn->close();
}
?> 
<?php if(isset($_GET['appot'])){ ?>
		<h2 style = "margin-top: -25px;" align="center"> Overtime Request </h2>
		<table id = "reqTb"  class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th width="9%">Date File</th>
					<th width="9%">Date of Overtime</th>
					<th width="12%">Name of Employee</th>
					<th width="30%">Reason</th>
					<th width="14%">From - To (Overtime)</th>
					<th width="11%">Offical Work Schedule</th>
					<th width="15%">State</th>
				</tr>
			</thead>
			<tbody>
<?php
		include("conf.php");		
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid order by datefile desc";		
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
			if($row['state'] == 'CheckedHR' || $row['state'] == 'AAdmin'){
				$explo = (explode(":",$row['approvedothrs']));
				if($explo[1] > 0){
					$explo2 = '.5';
				}else{
					$explo2 = '.0';
				}
				$xx = '<br><strong>' . $explo[0].$explo2;
			}else{
				$xx = "";
			}
			if($row['projtype'] != "" && $row['projtype'] != 'Others'){
				$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
			}else{
				$project = "";
			}
			if($row['projtype'] == 'Others'){
				$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
			}
			$originalDate = date($row['datefile']);
			$newDate = date("M j, Y", strtotime($originalDate));
			echo
				'<tr>
					<td>'.$newDate.'</td>
					<td>'.date("M j, Y", strtotime($row["dateofot"])).'</td>
					<td>'.$row["nameofemp"].'</td>
					<td width = 300 height = 70>'.$row["reason"]. $project.'</td>
					<td>'.$row["startofot"] . ' - ' . $row['endofot']. $xx .'</strong></td>
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
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</font></p> ';
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
							//echo '<a class = "btn btn-danger"href = "?appot&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
						}elseif($row['state'] == 'UAAdmin'){
							echo '<p>Waiting for Admin Approval</p>';
							//echo '<a class = "btn btn-danger"href = "?appot&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
						}
				echo '</td></tr>';
		}

	}
}

?>
	</tbody>
</table>
	<?php 
	if(isset($_GET['appob'])){
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid order by obdate desc";
		$result = $conn->query($sql);
		
			
	?>	
	<h2 align="center" style="margin-top: -25px;"> Official Business Request </h2>
	<table id = "reqTb"  class = "table table-hover" align = "center">
		<thead>
			<tr>
				<th width="10%">Date File</th>
				<th width="10%">Date of Request</th>
				<th width="12%">Time In - Time Out</th>
				<th width="12%">Offical Work Schedule</th>
				<th width="36%">Reason</th>
				<th width="20%">State</th>
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
					<td>'.date("M j, Y", strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>	
					<td><b>';
						if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
							echo 'Pending for Time Checking <br>';
						}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){								
							echo 'Pending for Time Checking HR<br>';
						}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
							echo 'Pending to Tech Supervisor<br>';
						}else if($row['state'] == 'CheckedHR'){
							if($row['dateacc'] == 1){
								$chck = 'ACC';
							}else{
								$chck = 'HR';
							}
							echo '<p><font color = "green">Checked by '.$chck.'</font></p> ';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</font></p> ';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</font></p> ';
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
							//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
						}elseif($row['state'] == 'UAAdmin'){
							echo '<p>Waiting for Admin Approval</p>';
							//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
						}
				echo '</td></tr>';
		}
		
	}echo '</tbody></table>';$conn->close();
}?>
	<?php 
	if(isset($_GET['appundr'])){
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid ORDER BY datefile DESC";
		$result = $conn->query($sql);
		
	?>
	<h2 align="center" style="margin-top: -25px;"> Undertime Request </h2>	
	<table id = "reqTb"  class = "table table-hover" align = "center">
		<thead>	
			<tr>
				<th>Date File</th>
				<th>Date of Undertime</th>
				<th>Name of Employee</th>
				<th>Reason</th>
				<th>From - To (Undertime)</th>
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
					if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
						echo 'Pending to HR<br>';
						if($row['accadmin'] == null){
						//	echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
						}								
					}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
						echo 'Pending to HR<br>';
					}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
						echo 'Pending to Tech Supervisor<br>';
						//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
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
						//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
					}
			
		}
	}echo '</tbody></table></form>';
}
	?>
	<?php 		
	if(isset($_GET['applea'])){
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid ORDER BY datefile DESC";
	$result = $conn->query($sql);
	
	?>	
	<h2 align="center" style="margin-top: -25px;"> Leave Request </h2>	
	<table id = "reqTb"  class = "table table-hover" align = "center">
		<thead>
			<tr>
				<td colspan = 10 align = center></td>
			</tr>
			<tr>
				<th width = "10%">Date File</th>
				<th width = "20">Date of Leave (From - To)</th>
				<th width = "10%">No. of Day/s</th>
				<th width = "10%">Type of Leave</th>
				<th width="30%">Reason</th>
				<th width="20%">State</th>
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
					<td>Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])).'<br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					<td >'.$row["reason"].'</td>	
					<td width = "150"><b>';
						if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
							echo 'Pending to HR<br>';
							if($row['accadmin'] == null){
							//	echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
							}								
						}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
							echo 'Pending to HR<br>';
						}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
							echo 'Pending to Tech Supervisor<br>';
							//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
						}else if($row['state'] == 'AHR'){
							echo '<p><font color = "green">Approved by HR</font></p> ';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "green">Approved by Accounting</font></p> ';
						}else if($row['state'] == 'AAdmin'){
							echo '<p><font color = "green">Approved by <br>Dep. Head</p>';
							if($row['typeoflea'] != 'Sick Leave' && date("Y-m-d") <= $row['dateofleavfr']){
							//	echo '<a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canlea='.$row['leave_id'].'" class = "btn btn-danger"> Cancel Leave </a>';
							}
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
							//echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
						}elseif($row['state'] == 'CLea'){
							echo '<font color = "red"> Leave Canceled  </font>';
						}elseif($row['state'] == 'ReqCLea'){
							echo '<font color = "red"> Pending Cancelation Request </font>';
						}elseif($row['state'] == 'ReqCLeaHR'){
							echo '<font color = "red"> Pending Cancelation Request </font>';
						}

				echo '</td></tr>';
		}
		
	}echo '</tbody></table></form>';$conn->close();
	}?>
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