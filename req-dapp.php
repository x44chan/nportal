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
<div align = "center" style = "margin-bottom: 30px; ">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong><br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br>	<br>	
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary" href = "employee.php?ac=penot" id = "home">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<?php
				include 'caloan/reqbut.php';
			?>
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
			<a  type = "button"class = "btn btn-success" href = "?appot"> Disapproved Overtime </a>
			<a  type = "button"class = "btn btn-success" href = "?appob"> Disapproved Official Business </a>
			<a  type = "button"class = "btn btn-success" href = "?applea"> Disapproved Leave  </a>			
			<a  type = "button"class = "btn btn-success" href = "?appundr"> Disapproved Undertime  </a>	
			<a  type = "button"class = "btn btn-success" href = "?apppety"> Disapproved Petty  </a>
		</div>
	</div>
</div>

<?php 
	if(isset($_GET['apploan']) && !isset($_GET['loan'])){
		include("conf.php");
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid and state = 'DALoan' order by state ASC";
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
						<th>Status</th>
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
	$sql = "SELECT * FROM cashadv,login where login.account_id = $accid and cashadv.account_id = $accid and cashadv.state = 'DACA' order by cadate desc";
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
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid and state = 'DAPetty' order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get"  style="margin-top: -20px;">
			<table id = "myTable" class = "table table-hover" align = "center" >
				<thead>
					<tr>
						<td colspan = 8 align = center><h2> Disapproved Petty Request </h2></td>
					</tr>
					<tr>
						<th>Petty #</th>
						<th>Date File</th>
						<th>Name</th>
						<th>Particular</th>
						<th>Source</th>
						<th>Transfer Code</th>
						<th>Amount</th>
						<th>Status</th>
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
		
	}echo '</tbody></table></form>';$conn->close();
}
?> 
	<?php 
	if(isset($_GET['appot'])){

	?>
		<form role = "form" action = "approval.php" style="margin-top: -20px;" method = "get">
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center style="margin-top: -20px;"><h2> Disapproved Overtime Request</h2></td>
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
		$cutoffdate = date("Y-m-d");
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and state = 'DAAdmin' order by datefile desc";
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
							echo '<p><font color = "red">Disapproved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "red">Disapproved by Accounting</p>';
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p></font>' . $row['dareason'];
						}
				echo '</td></tr>';
		}

	}echo '</tbody>
		</table>
		</form>';$conn->close();
}

?>

	<?php 
	if(isset($_GET['appob'])){
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and state =  'DAAdmin' order by obdate desc";
		$result = $conn->query($sql);
		
			
	?>
	<form role = "form" action = "approval.php" style="margin-top: -20px;" method = "get">
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Disapproved Official Business Request </h2></td>
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
							echo '<p><font color = "red">Disapproved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "red">Disapproved by Accounting</p>';
						}else{
							echo '<p><font color = "red">Disapproved by Dep. Head</p></font>' . $row['dareason'];
						}
				echo '</td></tr>';
		}
		
	}echo '</tbody></table></form>';$conn->close();
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
		$sql = "SELECT * FROM undertime,login where login.account_id = $accid and undertime.account_id = $accid and state =  'DAAdmin'  ORDER BY datefile DESC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php" style="margin-top: -20px;" method = "get">
		<table id = "myTable" class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> Disapproved Undertime Request </h2></td>
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
						echo '<p><font color = "red">Disapproved by HR</p>';
					}else if($row['state'] == 'AACC'){
						echo '<p><font color = "red">Disapproved by Accounting</p>';
					}else{
						echo '<p><font color = "red">Disapproved by Dep. Head</p></font>' . $row['dareason'];
					}
						echo '</td></tr>';
					}
			
		}echo '</tbody></table></form>';
	}
	?>
	<?php 		
	if(isset($_GET['applea'])){
	include("conf.php");
	$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and state =  'DAAdmin' ORDER BY datefile DESC";
	$result = $conn->query($sql);
	
	?>	
	<form role = "form" action = "approval.php" style="margin-top: -20px;" method = "get">
			<table id = "myTable" class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 10 align = center  style="margin-top: -50px;"><h2> Disapproved Leave Request </h2></td>
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
							echo '<p><font color = "red">Disapproved by HR</p>';
						}else if($row['state'] == 'AACC'){
							echo '<p><font color = "red">Disapproved by Accounting</p>';
						}else{
							echo '<p><font color = "red">Disapproved by <br>Dep. Head</p></font>' . $row['dareason'];
						}
				echo '</td></tr>';
		}
		
	}echo '</tbody></table></form>';$conn->close();
	}?>
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