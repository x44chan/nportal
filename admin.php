<?php session_start(); ?>
<?php  $title="Admin Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'Admin'){
	?>		
	<script type="text/javascript"> 
		window.location.replace("index.php");
		alert("Restricted");
	</script>	
	
	<?php
	}
?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTable').DataTable();
	});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a href = "admin.php"  type = "button"class = "btn btn-primary"  id = "showneedapproval">Home</a>	
			<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>			
     		<a href = "admin-emprof.php" type = "button"class = "btn btn-primary"  id = "newuserbtn">Employee Profile</a>	
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>
			<a type = "button"class = "btn btn-primary"  href = "admin-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button"class = "btn btn-primary" href = "admin-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a class="btn btn-danger"  href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		
	</div>
</div>
<?php	
	if(isset($_GET['suc'])){
		if($_GET['suc'] == 1){
			echo '<div id = "regerror" class="alert alert-success" align = "center"><strong>Success!</strong> New user added.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show();	$("#needaproval").hide(); });</script>';
		}else if($_GET['suc'] == 0){
			echo '<div id = "regerror" class="alert alert-warning" align = "center"><strong>Warning!</strong> Username already exists.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show();	$("#needaproval").hide(); });</script>';
		}
		else if($_GET['suc'] == 3){
			echo '<div id = "regerror" class="alert alert-warning" align = "center"><strong>Warning!</strong> Password does not match.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show(); $("#needaproval").hide(); });</script>';
		}
	}
?>
<?php
	if(isset($_GET['pettyac']) && $_GET['pettyac'] == 'a'){
		echo '<form action = "petty-exec.php" method = "post">';
		echo '<table align = "center" class = "table table-hover table-bordered" style = "width: 65%;">';
		echo '<thead><th colspan = 2><h2>Petty Voucher</h2></th></thead>';
		include("conf.php");
		$pettyid = $_GET['petty_id'];
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">' . $row['particular'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'"/></td></tr>';
				if($row['particular'] == "Transfer"){ echo '<tr><td>Transfer ID: </td><td><input placeholder = "Enter transaction code" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				if($row['particular'] == "Check"){ echo '<tr><td>Reference #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				echo '<tr><td style = "width: 30%;">Source of Fund <font color = "red">*</font></td><td><select required name = "source" class = "form-control"><option value = "">-------</option><option value = "Eli/Sha">Eli/Sha</option><option value = "Accounting">Accounting</option></select></td></tr>';
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "admin.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

			}
		}
		echo "</table></form>";
}
if(isset($_GET['liqdate']) && $_GET['liqdate'] != ""){
		include 'conf.php';
		$petyid = $_GET['liqdate'];
		$sql = "SELECT * FROM `petty_liqdate` where petty_id = '$petyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$query1 = "SELECT * FROM `login` where account_id = '$_GET[acc]'";
			$data1 = $conn->query($query1)->fetch_assoc();
			$query15 = "SELECT * FROM `petty` where petty_id = '$petyid'";
			$amount = $conn->query($query15)->fetch_assoc();
			$amounts = $amount['amount'];
			echo '<div class = "container-fluide" style = "padding: 5px 10px;"><div class = "row">
				<div class = "col-xs-4">
					<label>Name: </label>
					<p>'.$data1['fname'] . ' ' . $data1['lname'] . '</p>
				</div>
				<div class = "col-xs-4">
					<label>Amount: </label>
					<p>P '.$amount['amount'] . '</p>
				</div>
				</div>';
			echo '<table class = "table" id = "myTableliq">';
			echo '<thead>';
				echo '<tr>';
				echo '<th>Date</th>';
				echo '<th>Type</th>';
				echo '<th>Amount</th>';
				echo '<th>Info</th>';
				echo '<th>Code</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$totalliq = 0;
			while($row = $result->fetch_assoc()){
				$petid = $row['liqdate_id'];
				$accid = $row['account_id'];
				$query = "SELECT * FROM `petty_liqdate` where liqdate_id = '$petid'";
				$data = $conn->query($query)->fetch_assoc();
				$query1 = "SELECT * FROM `login` where account_id = '$accid'";
				$data1 = $conn->query($query1)->fetch_assoc();
				echo '<tr>';
				echo '<td>'. date("M j, Y", strtotime($data['liqdate'])).'</td>';
				echo '<td>'. $data['liqtype'].'</td>';
				echo '<td>'. $data['liqamount'].'</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				echo '<td>'. $data['liqcode'].'</td>';
				echo '</tr>';	
				$totalliq += $data['liqamount'];
			}
			$a = str_replace(',', '', $amount['amount']);
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: </b></td><td>'.$totalliq.'<br><br>'.($a - $totalliq).'</td><td></td><td></td></tr>';
			echo '</tbody></table>';
			echo '<div align="center"><a class = "btn btn-danger" href = "?liqdate">Back</a>';

		}
	}
?>
<?php
	if(isset($_GET['release']) && $_GET['release'] == 1){
		include("conf.php");
		$petid = $_GET['petty_id'];
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyReceived'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				unset($_SESSION['err']);
			}else{
				$err = "";
			}
			echo '<div id = "report" align = "center"><h2 align = "center">Validate Code</h2>'.$err.'<hr>';
			echo '<form action = "petty-exec.php" method = "post"><table id = "myTable" align = "center" class = "table table-hover tbl" style="font-size: 14px; border: 0 !important; width: 50%;">
				  <tbody>';
			while($row = $result->fetch_assoc()){
				echo '<tr><td><label>Petty #</label></td><td>' . $row['petty_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['date'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				echo '<tr><td><label>Particular</label></td><td>' . $row['particular'] . '</td></tr>';
				echo '<tr><td><label>Source</label></td><td>' . $row['source'] . '</td></tr>';				
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); } ;
				echo '</td></tr>';
				if($row['transfer_id'] != null){echo '<tr><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "codesub">Release Petty</button> <a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php if(isset($_GET['pettyac']) || isset($_GET['release'])){ echo '<div style = "display: none !important;">';} ?>
<div id = "needaproval" >
	
	<h2 align = "center"><i> Admin Dashboard </i></h2>
	<form role = "form">
		<table id="myTable" style = "width: 100%;"class = "table table-hover " align = "center">
			<thead>
				<tr>
					<th width = "12%" ><i>Date File</i></th>					
					<th width = "15%" ><i>Name of Employee</i></th>
					<th width = "10%" ><i>Type</i></th>
					<th width = "23%" ><i>Reason</i></th>
					<th width = "20%" ><i>Checked By.</i></th>
					<th width = "20%" ><i>Action</i></th>
				</tr>
			</thead>
			<tbody id="people">
			<?php
	include("conf.php");
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'UAPetty'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><b><?php echo $row['particular'];?><br>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td><?php echo $row['petreason'];?></td>
				<td></td>
				<td><?php echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';?></td>
				</tr>

	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPettyReceived' and source = 'Eli/Sha'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><b><?php echo $row['particular'];?><br>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td><?php echo $row['petreason'];?></td>
				<td></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
				</td>
				</tr>
	<?php
		}
	
	
}
	?>	
		<?php
			include('conf.php');		
						
			$date17 = date("d");
			$dated = date("m");
			$datey = date("Y");
			if($date17 > 16){
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
			$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and state = 'AHR' and DAY(dateofot) >= $forque and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				
				while($row = $result->fetch_assoc()){
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['2daysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}

					$originalDate = date($row['datefile']);
					$newDate = date("F d, Y", strtotime($originalDate));					
						
					echo '<td>'.$newDate.'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>OT</td>';
					echo '<td>'.$row['reason'].'</td>';	
						if($row['datehr'] == ""){
							$datehr = '<b><i>HR REQUEST</i></b>';
							echo '<td > '.$datehr. '</td>';
						}else{
							if($row['oldot'] != null && $row['state'] == 'AHR'){
								$oldot = '</b><br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
								$hrot = '<b>App. OT: <i><font color = "green">';
								$hrclose = "</font></i>";
							}else{
								$oldot = "";
								$hrot = '<b>Filed OT: ';
								$hrclose ='</b>';
							}
							if($row['otbreak'] != null){
								$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
							}else{
								$otbreak = "";
							}
						$datehr = date("M d, Y h:i A", strtotime($row['datehr']));
						echo '<td style = "text-align:left;"><b>HR: '.$datehr. '</b><br>'. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>';
					}	
					echo '<td >
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and state = 'AHR' and DAY(dateofundrtime) >= $forque and DAY(dateofundrtime) <= $endque and MONTH(dateofundrtime) = $dated and YEAR(dateofundrtime) = $datey ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['datefile']);
					$newDate = date("F d, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					$datehr = date("F d, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("F d, Y h:i A", strtotime($row['dateacc']));
					echo '<td>'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>Undertime</td>';
					echo '<td>'.$row['reason'].'</td>';
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						echo '<td>HR: '.$datehr. '</td>';
					}else{
						$datehr = date("M d, Y h:i A", strtotime($row['datehr']));
						echo '<td>HR: '.$datehr. '</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'AHR' and DAY(obdatereq) >= $forque and DAY(obdatereq) <= $endque and MONTH(obdatereq) = $dated and YEAR(obdatereq) = $datey ORDER BY obdate ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['obdate']);
					$newDate = date("F d, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					$datehr = date("F d, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("F d, Y h:i A", strtotime($row['dateacc']));
					echo '<td>'.$newDate .'</td>';;
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>Official Business</td>';
					echo '<td>'.$row['obreason'].'</td>';
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						echo '<td>HR: '.$datehr. '</td>';
					}else{
						$datehr = date("M d, Y h:i A", strtotime($row['datehr']));
						echo '<td>HR: '.$datehr. '</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and state = 'AHR' and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['datefile']);
					$newDate = date("F d, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					$datehr = date("F d, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("F d, Y h:i A", strtotime($row['dateacc']));
	
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					echo '<td>'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';	
					echo '<td>'.$row['typeoflea']. ' ' .$row['othersl']. '</td>';
					echo '<td>'.$row['reason'].'</td>';
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						echo '<td>HR: '.$datehr. '</td>';
					}else{
						$datehr = date("M d, Y h:i A", strtotime($row['datehr']));
						echo '<td>HR: '.$datehr. '</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&leave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
		?>
		</tbody>
		</table>
	</form>
</div>
<?php if(isset($_GET['pettyac']) || isset($_GET['release'])){ echo '</div>">';} ?>
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
				<td><input required pattern="[a-zA-ZñÑ\s]+"class ="form-control"type = "text" name = "regfname"/></td>
			</tr>
			<tr>
				<td>Last Name:</td>
				<td> <input required pattern="[a-zA-ZñÑ\s]+" class ="form-control"type = "text" name = "reglname"/></td>
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
<?php 
	if($_SESSION['pass'] == 'defaultadmin'){
		include('up-pass.php');
	}
	?>
<?php include('footer.php');?>