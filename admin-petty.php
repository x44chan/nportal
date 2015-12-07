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
    	$('#myTableliq').DataTable({
    		"paging":   false,
        	"order": [[ 6, "asc" ],[ 1, "desc" ],[ 5, "desc" ]]

    	} );
    	$('input[name = "transct"]').hide();
		$('select[name = "source"]').change(function() {
		    var selected = $(this).val();			
			if(selected == 'Accounting' || $('select[name = "appart"]').val() == 'Cash'){
				$('input[name = "transct"]').attr('required',false);
				$('input[name = "transct"]').hide();
			}else{
				$('input[name = "transct"]').attr('required',true);
				$('input[name = "transct"]').show();
			}
		});
		$('select[name = "appart"]').change(function() {
		    var selected = $(this).val();

			$('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
			if(selected != 'Cash'){
				$('input[name = "transct"]').attr('required',true);
				$('input[name = "transct"]').show();
				$('select[name = "source"]').val("");			
			}else{
				$('input[name = "transct"]').attr('required',false);
				$('input[name = "transct"]').hide();

			}
		});
	});
</script>
<style type="text/css">
	.tbl {border-bottom:0px !important;}
	.tbl th, .tbl td {border: 0px !important;}
	#bords tr, #bords td{border-top: 1px black solid !important;}
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
<div id = "needaproval">
<?php
	if(isset($_GET['liqdate']) && $_GET['liqdate'] == ""){
		include 'conf.php';
		$sql = "SELECT * FROM `petty` where (source = 'Eliseo' or source = 'Sharon')";
		$result = $conn->query($sql);
			echo '<div id = "report"><div align = "center"><i><h3>Liquidate List</h3></i></div>';
			echo '<div id = "backs" style = "margin-bottom: 50px;"><a class = "btn btn-primary pull-right" href = "acc-printallchange.php"/>Print All To Return Changes</a></div>';
			echo '<table class = "table" id = "myTableliq">';
			echo '<thead>';
				echo '<tr>';
				echo '<th>Petty ID</th>';
				echo '<th>Date</th>';
				echo '<th>Name</th>';
				echo '<th>Petty Amount</th>';
				echo '<th>Total Used Petty</th>';
				echo '<th>Change</th>';
				echo '<th id = "backs" >Status</th>';
				echo '<th id = "show" style = "display: none;">Code</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data = $conn->query($query)->fetch_assoc();
				$query1 = "SELECT * FROM `login` where account_id = '$accid'";
				$data1 = $conn->query($query1)->fetch_assoc();				
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				
				if($data2['totalliq'] != ""){
					$tots = '<td>₱ ' . number_format($data2['totalliq']) . '</td>';
    				$a = str_replace(',', '', $row['amount']);
					$change =  $a - $data2['totalliq'];
					$change = number_format($change);
					if($change == 0){
						$change =  " - ";
					}
				}else{
					$tots = '<td> - </td>'; 
					$change =  " - ";
				}
				$date1 = date("Y-m-d");
				$date2 = date("Y-m-d", strtotime("+3 days", strtotime($data['liqdate'])));
				if($date1 >= $date2){
					$red = '<tr style = "color: red;">';
				}else{
					$red = '<tr>';
				}
				
				if($data['liqdate'] == ""){
					echo '<tr style = "display: none;">';
				}elseif($data['liqstate'] != 'CompleteLiqdate'){
					echo $red;
				}elseif($change == " - "){
					echo '<tr id = "backs">';
				}else{
					echo '<tr>';
				}				
				echo '<td>'.$row['petty_id'].'</td>';
				echo '<td>'.date("M j, Y", strtotime($data['liqdate']));
				echo '<td>'.$data1['fname'] . ' ' . $data1['lname'].'</td>';
				echo '<td>₱ ' . $row['amount'] . '</td>';
				echo $tots;
				echo '<td>₱ ' .  $change . '</td>';
				if($data['liqstate'] == 'CompleteLiqdate'){
					echo '<td id = "backs" ><b><font color = "green">Completed</font></b><br>';
					echo '<a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<td id = "backs" ><b><font color = "red">For Employee Validation</font></b><br>';
					echo '<a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'LIQDATE'){
					echo '<td><b> Pending Completion</b><br><a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}else{
					echo '<td><b> Pending Liquidate</td>';
				}
				echo '<td id = "show" style = "display: none;"></td>';
				echo '</tr>';	
			}	
			echo '</tbody></table></div>';
		}
	}elseif(isset($_GET['liqdate']) && $_GET['liqdate'] != ""){
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
				echo '<th width="12%">Date</th>';
				echo '<th width="12%">Type</th>';
				echo '<th width="12%">Amount</th>';
				echo '<th width="12%">Receipt</th>';
				echo '<th width="40%">Info</th>';
				echo '<th width="12%">Code</th>';
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
				if($data['rcpt'] != null){
					$rcpt = "<b><font color = 'green'>w/ </font></b> Receipt";
				}else{
					$rcpt = "<b><font color = 'red'>w/o</font></b> Receipt";
				}
				echo '<tr>';
				echo '<td>'. date("M j, Y", strtotime($data['liqdate'])).'</td>';
				echo '<td>'. $data['liqtype'].'</td>';
				echo '<td>₱ '. number_format($data['liqamount']).'</td>';
				echo '<td>' . $rcpt . '</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				echo '<td>'. $data['liqcode'].'</td>';
				echo '</tr>';	
				$totalliq += $data['liqamount'];
			}
			$a = str_replace(',', '', $amount['amount']);
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: </b></td><td>₱ '.number_format($totalliq).'<br><br>₱ '.number_format($a - $totalliq).'</td><td></td><td></td><td></td></tr>';
			echo '</tbody></table>';
			echo '<hr>';
			if(!isset($_GET['complete'])){
				echo '<div align="center"><a class = "btn btn-danger" href = "?liqdate">Back</a>';
			}else{
				echo '<div align="center"><a href = "?complete=1&petty_id='.$_GET['liqdate'].'" class = "btn btn-danger">Back</a>';
			}
		}
	}
?>
<?php if(!isset($_GET['pettyac']) && !isset($_GET['report']) && !isset($_GET['release']) && !isset($_GET['liqdate'])){ ?>
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
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td><?php echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';?></td>
				</tr>

	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPettyReceived' and (source = 'Eliseo' or source = 'Sharon')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
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
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("M j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Reason: </td><td style = "width: 50%;">' . $row['petreason'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">
					<select name = "appart" class = "form-control">';
				if($row['particular'] == "Cash"){
					$cash = ' selected ';
					$check = "";
					$trans = "";
				}elseif($row['particular'] == "Check"){
					$check = " selected ";
					$trans = "";
					$cash = "";
				}else{
					$trans = " selected ";
					$cash = "";
					$check = "";
				}
					echo '<option value = "">----------</option>
              			<option value = "Cash" '.$cash.'>Cash</option>
              			<option value = "Check" '.$check.'>Check</option>
              			<option value = "Transfer" '.$trans.'>Transfer</option>';				
				echo '</select></td></tr>';	
				echo '<tr><td style = "width: 30%;">Source of Fund <font color = "red">*</font></td><td><select required name = "source" class = "form-control"><option value = "">-------</option><option value = "Eliseo">Eliseo</option><option value = "Sharon">Sharon</option><option value = "Accounting">Accounting</option></select></td></tr>';
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'"/></td></tr>';
				echo '<tr><td>Reference #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; 
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "admin.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

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
				echo '<td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); } ;
				echo '</td>';
				echo '</tr>';
			}
		}
		echo "</tbody></table></div>";
		echo '<div align = "center"><br><button id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" onclick = "window.print();"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</button><a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';
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
</div>
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
				<td><input placeholder = "Enter Username" pattern=".{4,}" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input placeholder = "Enter Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regpword"/></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input placeholder = "Enter Confirm Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regcppword"/></td>
			</tr>
			<tr>
				<td>Account Level:</td>
				<td>
					<select name = "level" class ="form-control">
						<option value = "">------------
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
<?php include('footer.php'); ?>
