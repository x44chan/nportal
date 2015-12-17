<?php session_start(); ?>
<?php  $title="Admin Page";
	include('header.php');	
	include 'conf.php';
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
    	$('#myTable').DataTable({"order": [ 1, "desc" ]});
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
			<a href = "?login_log" type = "button"class = "btn btn-primary">Login Log</a>				
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				 <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>
			<a type = "button"class = "btn btn-primary"  href = "tech-sched.php">Tech Schedule</a>
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
if(isset($_GET['login_log'])){
		include 'login_log.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_POST['submitrans'])){
		$petid = mysql_escape_string($_POST['petty_id']);
		$valcode = mysql_escape_string($_POST['valcode']);
		$refcode = mysql_escape_string($_POST['transctc']);
		$source = mysql_escape_string($_POST['source']);
		$xxsql = "SELECT * FROM `petty` where petty_id = '$petid' and rcve_code = '$valcode' and state = 'TransProcCode'";
		$xxresult = $conn->query($xxsql);		
		if($xxresult->num_rows <= 0){
			$_SESSION['transct'] = $refcode;	
			echo '<script type="text/javascript">alert("Wrong code");window.location.replace("?transrelease=1&petty_id='.$petid.'"); </script>';
					
		}else{
			$sql = "UPDATE `petty` set state = 'AAPettyRep',transfer_id = '$refcode',source = '$source' where petty_id = '$petid' and state = 'TransProcCode'";
			if($conn->query($sql) == TRUE){
				echo '<script type="text/javascript">alert("Successful");window.location.replace("admin.php"); </script>';	
			}
		}
	}
	if(isset($_GET['transrelease'])){
		echo '<form action = "" method = "post">';
		echo '<table align = "center" class = "table table-hover table-bordered" style = "width: 65%;">';
		echo '<thead><th colspan = 2><h2>Petty Transfer</h2></th></thead>';
		include("conf.php");
		$pettyid = $_GET['petty_id'];
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid' and state = 'TransProcCode'";
		$result = $conn->query($sql);
		$xrefcode = "";
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				if(isset($_SESSION['transct'])){
					$xrefcode = $_SESSION['transct'];
				}
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("M j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Reason: </td><td style = "width: 50%;">' . $row['petreason'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">Transfer</td></tr>';
				echo '<tr><td style = "width: 30%;">Employee Code: <font color = "red">*</font></td><td style = "width: 50%;"><input required type = "text" class = "form-control" name = "valcode" placeholder = "Enter code"/></td></tr>';
				echo '<tr><td style = "width: 30%;">Source of Fund <font color = "red">*</font></td><td><select required name = "source" class = "form-control"><option value = "">-------</option><option value = "Eliseo">Eliseo</option><option value = "Sharon">Sharon</option></select></td></tr>';
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'"/></td></tr>';
				echo '<tr><td>Reference #: <font color = "red">*</font></td><td><input value = "'.$xrefcode.'" placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transctc"/></tr></td>'; 
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitrans">Submit</button><br><br><a href = "admin.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

			}
			
		}
		echo "</table></form></div><div style = 'display: none;'>";
	}else{
		unset($_SESSION['transct']);
	}
	

	if(isset($_GET['liqdate']) && $_GET['liqdate'] == ""){
		include 'conf.php';
		$sql = "SELECT * FROM `petty` where (source = 'Eliseo' or source = 'Sharon')";
		$result = $conn->query($sql);
			echo '<div id = "report"><div align = "center"><i><h3>Liquidate List</h3></i></div>';
			//echo '<div id = "backs" style = "margin-bottom: 50px;"><a class = "btn btn-primary pull-right" href = "acc-printallchange.php"/>Print All To Return Changes</a></div>';
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
					$tots = '<td>₱ ' . number_format($data2['totalliq'],2) . '</td>';
    				$a = str_replace(',', '', $row['amount']);
					$change =  $a - $data2['totalliq'];
					$change = number_format($change,2);
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
				}elseif($row['source'] == ""){
					echo '<td><b>Pending for Approval</td>';
				}elseif($data['liqstate'] == ""){
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
				echo '<td>₱ '. number_format($data['liqamount'],2).'</td>';
				echo '<td>' . $rcpt . '</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				//echo '<td>'. $data['liqcode'].'</td>';
				echo '</tr>';	
				$totalliq += $data['liqamount'];
			}
			if($data['accval'] == null){
				$excess = '<a href = "petty-exec.php?excesscode='.$_GET['liqdate'].'&acc='.$_GET['acc'].'" class = "btn btn-success">Receive Change</a>';
			}else{
				$excess = $data['admincode'];
			}
			if($data['accval'] == 'AdminRcv'){
				$rcv = 'Pending Accounting Validation';
			}elseif($data['accval'] == null){
				$rcv = "";
			}else{
				$rcv = '<font color = "green">Completed</font>';
			}
			$a = str_replace(',', '', $amount['amount']);
			$change = ($a - $totalliq);
			if($change == 0){
				$rcv = " - ";
				$excess = " - ";
			}
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: <br><br>Code: <br><br>Status: </b></td><td>₱ '.number_format($totalliq,2).'<br><br>₱ '. number_format($change,2) .'<br><br>'.$excess.'<br><br><b>'.$rcv.'</b></td><td></td><td></td></tr>';
			echo '</tbody></table></div>';
			echo '<div align = "center"><a href = "admin-petty.php?liqdate" class = "btn btn-danger">Back</a>';
		}else{
			echo '<script type="text/javascript">window.location.replace("?liqdate"); </script>';
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
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and (state = 'UAPetty' or state = 'TransProcCode')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php 
					if($row['state'] == 'UAPetty'){
						echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';
					}elseif($row['state'] == 'TransProcCode'){
						echo '<a class = "btn btn-success" style = "width: 100px" href = "?transrelease=1&petty_id='.$row['petty_id'].'">Release</a> ';
					}
					?></td>
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
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
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
              			<option value = "Check" '.$check.'>Check</option>';				
				echo '</select></td></tr>';	
				echo '<tr><td style = "width: 30%;">Source of Fund <font color = "red">*</font></td><td><select required name = "source" class = "form-control"><option value = "">-------</option><option value = "Eliseo">Eliseo</option><option value = "Sharon">Sharon</option><option value = "Accounting">Accounting</option></select></td></tr>';
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'"/></td></tr>';
				echo '<tr><td>Reference #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; 
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "admin.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

			}
		}
		echo "</table></form>";
}
	if(isset($_GET['report']) && $_GET['report'] == '1'){
		if(isset($_SESSION['dates'])){
			$date1 = $_SESSION['dates'];
			$date2 = $_SESSION['dates0'];
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));			
		}else{
			$date1 = date("Y-m-01");
			$date2 = date("Y-m-t");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}
		if(isset($_POST['repfilter'])){
			$_SESSION['dates'] = $_POST['repfr'];
			$_SESSION['dates0'] = $_POST['repto'];
			echo '<script type = "text/javascript">window.location.replace("admin-petty.php?report=1&'.$_POST['reptype'].'&'.$_POST['status'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['dates']);
			unset($_SESSION['dates0']);
			echo '<script type = "text/javascript">window.location.replace("admin-petty.php?report=1");</script>';
		}
?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Petty Report Filtering </i></u></h4>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-3" align="center">
				<label>Select Source</label>
				<select class="form-control input-sm" name ="reptype">
					<option <?php if(isset($_GET['all'])){ echo ' selected '; } ?> value="all">All</option>				
					<option <?php if(isset($_GET['Eliseo'])){ echo ' selected '; } ?> value="Eliseo">Eliseo</option>					
					<option <?php if(isset($_GET['Sharon'])){ echo ' selected '; } ?> value="Sharon">Sharon</option>
					<option <?php if(isset($_GET['Accounting'])){ echo ' selected '; } ?> value="Accounting">Accounting</option>
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label> Status </label>
				<select class="form-control input-sm" name = "status">
					<option <?php if(isset($_GET['sall'])){ echo ' selected '; } ?> value = "sall"> All </option>
					<option <?php if(isset($_GET['scompleted'])){ echo ' selected '; } ?> value = "scompleted"> Completed </option>
					<option <?php if(isset($_GET['sliqui'])){ echo ' selected '; } ?> value = "sliqui"> Pending Emp. Code </option>
					<option <?php if(isset($_GET['spendingliq'])){ echo ' selected '; } ?> value="spendingliq"> Pending Liquidation </option>
					<option <?php if(isset($_GET['spendingcomp'])){ echo ' selected '; } ?> value="spendingcomp"> Pending Completion </option>
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date'] . '" '; }else{ echo ' value = "' .date("Y-m-01") . '" '; } ?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date0'] . '" '; }else{ echo ' value = "' .date("Y-m-t") . '" '; } ?> />
			</div>
			<div class="col-xs-3">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="submit" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="container-fluid" style="margin-top: -10px;">
	<div class="row">
		<div class="col-xs-12">
			<hr>
		</div>
	</div>
</div>
<?php
	
?>
<div id = "report">
	<div class="row" >
		<div class="col-xs-12" align="center" <?php if(!isset($_GET['print'])){ echo 'style="margin-top: -40px;"'; } ?>>
			<i><h3>Petty Report</h3></i>
			<b><i>
				<?php echo date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2)); ?>
				<?php if(isset($_GET['Eliseo'])){ echo '<br> Source: '; echo ' Eliseo ';}elseif(isset($_GET['Sharon'])){ echo '<br> Source: '; echo ' Sharon '; }elseif(isset($_GET['Accounting'])){ echo '<br> Source: '; echo ' Accounting '; } ?>
			</i></b>
		</div>
		<div class="col-xs-12" align="right" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>>
			<i><b>Total Amount: <span class = "badge" id = "total" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>></span><br>
			Total Used Petty: <span class = "badge" id = "used" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>></span></b></i>
		</div>
	</div>
<?php
		if(isset($_GET['Eliseo'])){
			$link = "&Eliseo";
		}elseif(isset($_GET['Sharon'])){
			$link = "&Sharon";
		}elseif(isset($_GET['Accounting'])){
			$link = "&Accounting";
		}elseif(isset($_GET['all'])){
			$link = "&all";
		}else{
			$link = "";
		}
		if(isset($_GET['sall'])){
			$link2 = "&sall";
		}elseif(isset($_GET['scompleted'])){
			$link2 = "&scompleted";
		}elseif(isset($_GET['spendingliq'])){
			$link2 = "&spendingliq";
		}elseif(isset($_GET['spendingcomp'])){
			$link2 = "&spendingcomp";
		}elseif(isset($_GET['sliqui'])){
			$link2 = "&sliqui";
		}else{
			$link2 = "";
		}
		if(isset($_GET['print'])){
			echo '<table align = "center" class = "table table-hover" style="font-size: 14px;">';
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?report=1'.$link.$link2.'";});</script>';
		}else{
			echo '<table id = "myTable" align = "center" class = "table table-hover" style="font-size: 14px;">';
		}
		
		echo '<thead>
				<tr>
					<th>Petty#</th>
					<th>Date</th>
					<th>Name</th>
					<th>Particular</th>
					<th>Source</th>
					<th>Reference #</th>
					<th>Amount</th>
					<th>Used Petty</th>
					<th>Liquidation Status</th>
				</tr>
			  </thead>
			  <tbody>';
		include("conf.php");
		if(isset($_GET['Sharon'])){
			$filt = "and source = 'Sharon' ";
		}elseif(isset($_GET['Eliseo'])){
			$filt = "and source = 'Eliseo' ";
		}elseif(isset($_GET['all'])){
			$filt = "";
		}elseif(isset($_GET['Accounting'])){
			$filt = "and source = 'Accounting'";
		}else{
			$filt = "";
		}

		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and (state = 'AApettyRep' or state = 'AAAPettyReceive' or state = 'AAPettyReceived' or state = 'AAPetty') $filt order by petty_id desc";
		$result = $conn->query($sql);
		$total = 0;
		$change = 0;
		$used = 0;
		if($result->num_rows > 0){
			
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
				$data = $conn->query($sql)->fetch_assoc();
				if(isset($_GET['scompleted'])){
					if($data['liqstate'] != 'CompleteLiqdate'){
						continue;
					}
				}
				if(isset($_GET['sliqui'])){
					if($row['state'] == 'AAAPettyReceive' || $row['state'] == 'AAPettyReceived'){
						
					}else{
						continue;
					}
				}
				if(isset($_GET['spendingliq'])){
					if($data['liqtype'] != ''){
						continue;
					}
					if($row['state'] == 'AAAPettyReceive'){
						continue;
					}
					if($row['state'] == 'AAPettyReceived'){
						continue;
					}
				}
				if(isset($_GET['spendingcomp']) && $data['liqstate'] != 'LIQDATE') {
					continue;
				}
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['date'])). '</td>';
				echo '<td>' . $row['fname'] . ' '. $row['lname'] . '</td>';				
				echo '<td>' . $row['particular'] . '</td>';
				echo '<td>' . $row['source'] . '</td>';
				echo '<td>';
				if($row['transfer_id'] == null){echo ' - ';}else{echo $row['transfer_id'];} 
				echo '</td>';
				echo '<td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
				echo '</td>';
				echo '<td>₱ ';
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo number_format($data2['totalliq'],2) . '</td>';
				$used += $data2['totalliq'];
				
				echo '<td><b>';
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
				$data = $conn->query($sql)->fetch_assoc();
				if($row['state'] == 'AAAPettyReceive'){
					echo 'Pending for Employee Code';
				}elseif($row['state'] == 'AAPettyReceived'){
					echo 'Pending for Employee Code';
				}elseif($row['state'] == 'AAPetty'){
					echo '<font color = "green"><b>Pending to Accounting</font>';
				}elseif($data['petty_id'] == null){
					echo '<b>Pending Liquidation</b>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<font color = "green"><b>Liquidated</font><br>';
				}elseif($data['liqstate'] == 'CompleteLiqdate'){
					echo '<font color = "green"><b>Completed</font>';
				}elseif($data['liqstate'] == 'LIQDATE'){
					echo '<b>Pending Completion</b><br>';
				}
				echo '</td>';
				echo '</tr>';
				$total += $a;
				$change += $a - $data2['totalliq'];
			}
		}
		echo '<script type = "text/javascript">$(document).ready(function(){ $("#total").text("₱ '.number_format($total,2).'");  $("#used").text("₱ '.number_format($used,2).'"); });</script>';
		if(isset($_GET['print'])){
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td><b> Total: </td><td>₱ '.number_format($total,2).'</td><td>₱ '.number_format($used,2).'</td><td></td></tr>';
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td></td><td><b>Change: </td><td>₱ '.number_format($change,2).'</td><td></td></tr>';
		}		
		echo "</tbody></table></div>";	
		echo '<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?report=1&print'.$link.$link2.'"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a><a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';
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
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
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
