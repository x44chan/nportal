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
<style type="text/css">
	@media print {		
		body * {
	    	visibility: hidden;
	    
	  	}
	  	@page{
	  		margin-left: 5mm;
	  		margin-right: 5mm;
	  	}
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#report, #report * {
	    	visibility: visible;
	 	}
	 	#report h2{
	  		margin-bottom: 10px;
	  		margin-top: 10px;
	  		font-size: 20px;
	  		font-weight: bold;
	    }
	 	#report h4{
			font-size: 15px;
		}
		#report h3{
	  		margin-bottom: 10px;
		}
		#report th{
	  		font-size: 12px;
	  		width: 0;
		} 
		#report td{
	  		font-size: 11px;
	  		bottom: 0px;
	  		padding: 3px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 14px;
		}
		#report {
	   		position: absolute;
	    	left: 0;
	    	top: 0;
	    	right: 0;
	  	}
	  	#backs{
	  		display: none;
	  	}
	  		.dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate  {
		display: none; 
	}
	}

</style>
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
<div id = "needaproval">
<?php if(!isset($_GET['pettyac'])&& !isset($_GET['report'])){ ?>
<h2 align = "center"><i> Petty Dashboard </i></h2>
	<form role = "form">
		<table id="myTable" style = "width: 100%;"class = "table table-hover " align = "center">
			<thead>
				<tr>
					<th width="20%"><i>Date File</i></th>					
					<th width="20%"><i>Name of Employee</i></th>
					<th width="20%"><i>Type</i></th>
					<th width="20%"><i>Amount</i></th>
					<th width="20%"><i>Action</i></th>
				</tr>
			</thead>
			<tbody>
			
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
				<td><?php echo $row['particular'];?></td>
				<td>PHP: <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
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
				<td><?php echo $row['particular'];?></td>
				<td>PHP: <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td>
					<?php echo "<b>Received</b><br>";?>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "petty-exec.php?pettydone=a&petty_id='.$row['petty_id'].'">Done</a>';?>
				</td>
				</tr>
	<?php
		}
	
	}
}
	?>			
			</tbody>
		</table>
</form>
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
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "admin-petty.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

			}
		}
		echo "</table></form>";
}
if(isset($_GET['report']) && $_GET['report'] == '1'){
		echo '<div id = "report"><h2 align = "center">Petty Report</h2>';
		echo '<table id = "myTable" align = "center" class = "table table-hover" style="font-size: 14px;">';
		echo '<thead>
				<tr>
					<th>Petty#</th>
					<th>Date</th>
					<th>Name</th>
					<th>Particular</th>
					<th>Source</th>
					<th>Transfer Code</th>
					<th>Amount</th>
				</tr>
			  </thead>
			  <tbody>';
		include("conf.php");
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AApettyRep'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['date'])). '</td>';
				echo '<td>' . $row['fname'] . ' '. $row['lname'] . '</td>';				
				echo '<td>' . $row['particular'] . '</td>';
				echo '<td>' . $row['source'] . '</td>';
				echo '<td>';
				if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} 
				echo '</td>';
				echo '<td>PHP: ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); } ;
				echo '</td>';
				echo '</tr>';
			}
		}
		echo "</tbody></table></div>";
		echo '<div align = "center"><br><button id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" onclick = "window.print();"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</button><a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';
}
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
<?php include('footer.php');?>
