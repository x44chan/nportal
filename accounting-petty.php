<?php session_start(); ?>
<?php  $title="Petty Report";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'HR' && $_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	} ?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTable').DataTable();
    	$('#myTableliq').DataTable({
    		"iDisplayLength": 50,
        	"order": [[ 1, "desc" ],[ 0, "desc" ]]

    	} );
    	$('#myTableliq').DataTable({
    		"iDisplayLength": 50,
        	"order": [[ 1, "desc" ]]

    	} );
    	 $('#myTablepet').DataTable( {
	        "order": [ 1, "desc" ],
	        "iDisplayLength": 12
	    });
	});
</script>
<style type="text/css">
	#bords tr, #bords td{border-top: 1px black solid !important;}
	<?php
		if(isset($_GET['print'])){
			echo 'body { visibility: hidden; }';
		}
	?>
	@media print {		
		body  {
	    	visibility: hidden;
	    
	  	}
	  	@page{
	  		margin-left: 2mm;
	  		margin-right: 2mm;
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
	  		font-size: 17px;
	  		font-weight: bold;
	    }
	 	#report h4{
			font-size: 13px;
		}
		#report h3{
	  		margin-bottom: 10px;
		}
		#report th{
	  		font-size: 10px;
	  		width: 0;
		} 
		#report td{
	  		font-size: 9px;
	  		bottom: 0px;
	  		padding: 3px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 12px;
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
	  	#show{
	  		display: table-cell !important;
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
      <a  type = "button"class = "btn btn-primary" href = "index.php">Home</a>
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
      <?php if($_SESSION['level'] == 'HR') { ?>
      <div class="btn-group btn-group-lg">
        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a data-toggle="modal" data-target="#newAcc">Add User</a></li>
          <li><a href = "tech-sched.php">Tech Scheduling</a></li>
          <li><a href = "hr-emprof.php">Employee Profile</a></li>
          <li><a href = "hr-timecheck.php">In/Out Reference</a></li>
          <li class="divider"></li>
          <li><a href = "accounting-petty.php">Petty List</a></li>
        </ul>
      </div>
      <a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda">My Approved Request</a>
      <a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda">My Dispproved Request</a>
      <?php }else{ ?>
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
      <?php } ?>
      <a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
    </div>
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
	if(isset($_GET['pettydate'])){
		include 'caloan/pettydate.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['transfer'])){
		include 'caloan/transferpty.php';
		echo '</div><div style = "display: none;">';
	}
	
	if(isset($_GET['replenish'])){
		include 'caloan/replenish.php';
		echo '</div><div style = "display: none;">';
	}
?>
<?php
	if(isset($_GET['liqdate']) && $_GET['liqdate'] == ""){
		include 'conf.php';
		$sql = "SELECT * FROM `petty`";
		$result = $conn->query($sql);
			echo '<div id = "report"><div align = "center"><i><h3>Liquidate List</h3></i></div>';
			echo '<div id = "backs" style = "margin-bottom: 50px;"><a class = "btn btn-primary pull-right" href = "acc-printallchange.php"/>Print All To Return Changes</a></div>';
			echo '<table class = "table" id = "myTableliq">';
			echo '<thead>';
				echo '<tr>';
				echo '<th>Petty ID</th>';
				echo '<th>Liquidation Date</th>';
				echo '<th>Name</th>';				
				echo '<th>Source</th>';
				echo '<th>Amount</th>';
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
					$change = '₱ ' . number_format($change);
					
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
				$liqdatess = date("M j, Y", strtotime($data['liqdate']));
				if($row['state'] == 'UAPetty'){
					continue;
				}elseif($data['liqdate'] == ""){
					$liqdatess = ' Pending ';
					$data['liqstate'] = "";
					echo '<tr>';
				}elseif($data['liqstate'] != 'CompleteLiqdate'){
					echo $red;
				}elseif($change == " - "){
					echo '<tr id = "backs">';
				}else{
					echo '<tr>';
				}		
				if($change == '₱ 0'){
					$change = ' - ';
				}		
				echo '<td>'.$row['petty_id'].'</td>';
				echo '<td>'. $liqdatess .'</td>';
				echo '<td>'.$data1['fname'] . ' ' . $data1['lname'].'</td>';
				echo '<td>' . $row['source'] . '</td>';
				echo '<td>₱ ' . $row['amount'] . '</td>';
				echo $tots;
				echo '<td>' .  $change . '</td>';
				if($data['liqstate'] == 'CompleteLiqdate'){
					echo '<td id = "backs" ><b><font color = "green">Completed</font></b><br>';
					echo '<a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<td id = "backs" ><b><font color = "red">Pending for Completion</font></b><br>';
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
		$petyid = mysql_escape_string($_GET['liqdate']);
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
			echo '<table class = "table">';
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
				if($data['liqtype'] == 'Others'){
					$data['liqothers'] = ' : ' . $data['liqothers'];
				}
				echo '<tr>';
				echo '<td>'. date("M j, Y", strtotime($data['liqdate'])).'</td>';
				echo '<td>'. $data['liqtype']. $data['liqothers'] .'</td>';
				echo '<td>₱ '. number_format($data['liqamount'],2).'</td>';
				echo '<td>' . $rcpt . '</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				echo '<td>'. $data['liqcode'].'</td>';
				echo '</tr>';	
				$totalliq += $data['liqamount'];
			}
			$a = str_replace(',', '', $amount['amount']);
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: </b></td><td>₱ '.number_format($totalliq, 2).'<br><br>₱ '.number_format($a - $totalliq, 2).'</td><td></td><td></td><td></td></tr>';
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
<?php if(!isset($_GET['pettyac']) && !isset($_GET['report']) && !isset($_GET['release']) && !isset($_GET['liqdate']) && !isset($_GET['complete']) && !isset($_GET['validate'])){ ?>
<h2 align = "center"><i> Pending Petty Request </i></h2>
	<form role = "form">
		<table id="myTable" style = "width: 100%;"class = "table table-hover " align = "center">
			<thead>
				<tr>
					<th ><i>Date File</i></th>					
					<th ><i>Name of Employee</i></th>
					<th ><i>Type</i></th>
					<th ><i>Amount</i></th>
					<th ><i>Transfer ID</i></th>
					<th ><i>Action</i></th>
				</tr>
			</thead>
			<tbody>
			
<?php
	include("conf.php");
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPetty' and source = 'Accounting'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td><?php echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" onclick = "return confirm(\'Are you sure?\');" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';?></td>
				</tr>
	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state like 'AAPettyReceived' and source = 'Accounting'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
				</td>
				</tr>
	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'UATransfer'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?transfer=1&petty_id='.$row['petty_id'].'"> Process </a>';?>
				</td>
				</tr>
	<?php
		}
	
	}
		$sql = "SELECT * from `petty_liqdate` where petty_liqdate.liqstate = 'LIQDATE' group by petty_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query2 = "SELECT * FROM `login` where account_id = '$accid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$query3 = "SELECT * FROM `petty` where petty_id = '$petid'";
				$data3 = $conn->query($query3)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . date("M j, Y", strtotime($data3['date'])). '</td>';
				echo '<td>' . $data2['fname'] . ' '. $data2['lname'] . '</td>';				
				echo '<td>' . $data3['particular'] . '</td>';
				echo '<td>₱ ' . $data3['amount'] . '</td>';
				echo '<td>';
				if($data3['transfer_id'] == null){echo 'N/A';}else{echo $data3['transfer_id'];} 
				echo '</td>';
				$query2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				echo '<td>';
				echo '<a class = "btn btn-success" href = "?complete=1&petty_id='.$row['petty_id'].'">Complete Petty</a>';
				echo '</td>';
				echo '</tr>';
			}
		}

	$sql = "SELECT * from `petty_liqdate` where petty_liqdate.accval = 'AdminRcv' group by petty_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query2 = "SELECT * FROM `login` where account_id = '$accid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$query3 = "SELECT * FROM `petty` where petty_id = '$petid'";
				$data3 = $conn->query($query3)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . date("M j, Y", strtotime($data3['date'])). '</td>';
				echo '<td>' . $data2['fname'] . ' '. $data2['lname'] . '</td>';				
				echo '<td>' . $data3['particular'] . '</td>';
				echo '<td>₱ ' . $data3['amount'] . '</td>';
				echo '<td>';
				if($data3['transfer_id'] == null){echo 'N/A';}else{echo $data3['transfer_id'];} 
				echo '</td>';
				$query2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				echo '<td>';
				echo '<a class = "btn btn-success" href = "?validate=1&petty_id='.$row['petty_id'].'">Validate Admin Code</a>';
				echo '</td>';
				echo '</tr>';
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
		$pettyid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">' . $row['particular'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;">₱ '; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'</td></tr>';
				if($row['particular'] == "Check"){ echo '<tr><td>Check #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				echo '<input class = "form-control" type = "hidden" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'"/></td></tr>';
		
				echo '<input name = "appart" value = "' . $row['particular'] . '" type="hidden"/>';
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "accounting-petty.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';
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
			$_SESSION['dates'] = mysql_escape_string($_POST['repfr']);
			$_SESSION['dates0'] = mysql_escape_string($_POST['repto']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?report=1&'.$_POST['reptype'].'&'.$_POST['status'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['dates']);
			unset($_SESSION['dates0']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?report=1");</script>';
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
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['dates'])){ echo 'value = "'. $_SESSION['dates'] . '" '; }else{ echo ' value = "' .date("Y-m-01") . '" '; } ?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['dates0'])){ echo 'value = "'. $_SESSION['dates0'] . '" '; }else{ echo ' value = "' .date("Y-m-t") . '" '; } ?> />
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
			echo '<table id = "myTablepet" align = "center" class = "table table-hover" style="font-size: 14px;">';
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
		if('2015-12-22' > $date2){
			$between = "petty_liqdate.completedate between '$date1' and '$date2'";
		}else{
			$between = "petty.date between '$date1' and '$date2'";
		}
		$sql = "SELECT * from `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id and petty_liqdate.account_id = petty.account_id and $between and (petty.state = 'AApettyRep' or petty.state = 'AAAPettyReceive' or petty.state = 'AAPettyReceived' or petty.state = 'AAPetty') $filt GROUP BY petty_liqdate.petty_id ORDER BY petty_liqdate.completedate asc ";
		$result = $conn->query($sql);
		$total = 0;
		$change = 0;
		$used = 0;
		if($result->num_rows > 0){
			
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
				$data = $conn->query($sql)->fetch_assoc();
				if($row['completedate'] == null || '2015-12-22' <= $date2){
					$row['completedate'] = $data['date'];
				}else{
					$row['completedate'] = $row['completedate'];
				}
				if(isset($_GET['scompleted'])){
					if($data['liqstate'] != 'CompleteLiqdate'){
						continue;
					}
				}
				elseif(isset($_GET['sliqui'])){
					if($row['state'] == 'AAAPettyReceive' || $row['state'] == 'AAPettyReceived'){
						
					}else{
						continue;
					}
				}
				elseif(isset($_GET['spendingliq'])){
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
				elseif(isset($_GET['spendingcomp'])){
					if($data['liqstate'] == 'LIQDATE' || $data['liqstate'] == 'EmpVal'){
						
					}else{
						continue;
					}
				}
				
				$sql33 = "SELECT * FROM `login` where account_id = '$row[account_id]'";
				$data33 = $conn->query($sql33)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['completedate'])). '</td>';
				echo '<td>' . $data33['fname'] . ' '. $data33['lname'] . '</td>';				
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
					echo '<b>Pending Completion</b><br>';
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
		$petid = mysql_escape_string($_GET['petty_id']);
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
				if($row['transfer_id'] != null){echo '<tr><td><label>Check #</td><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "codesub">Release Petty</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['complete']) && $_GET['complete'] == 1){
		include("conf.php");
		$petid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyRep'";
		$result = $conn->query($sql);
		$sql2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid' and liqstate != 'LIQDATE'";
		$result2 = $conn->query($sql2);
		if($result2->num_rows > 0){
			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		}
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
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. number_format($a - $data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Liquidation:</label></td><td><a href = "?liqdate='.$row['petty_id'].'&acc='.$row['account_id'].'&complete" class = "btn btn-primary">View Liquidate</a></td></tr>';
				
				if($row['transfer_id'] != null){echo '<tr><td><label>Transfer Code</td><td>';echo $row['transfer_id'];echo '</td></tr>';}
				function random_string($length) {
				    $key = '';
				    $keys = array_merge(range(0, 9), range('a', 'z'));

				    for ($i = 0; $i < $length; $i++) {
				        $key .= $keys[array_rand($keys)];
				    }

				    return $key;
				}
				$str = random_string(4);
				$str2 = $str;
				echo '<tr><td><label>Code</td><td><b><i>' . $str . '</td></tr>';
				echo '<input type = "hidden" value = "' . $str2 . '" name = "liqcode"/>'; 
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "liqsubmit">Complete Liquidate</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}	
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['validate']) && $_GET['validate'] == 1){
		include("conf.php");
		$petid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyRep'";
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
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. number_format($a - $data2['totalliq'], 2).'</td></tr>';
				if($row['transfer_id'] != null){echo '<tr><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Admin Status</label></td><td><i><u><b>Change Received by Admin</td></tr>';
				echo '<tr><td><label>Validate Code</label></td><td><input type = "text" name = "avalcode" class = "form-control" placeholder = "Enter Validation Code"/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "excesssubmit">Complete Transaction</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}	
			echo "</tbody></table></form></div>";
			
		}
	}
?>
</div>
<?php if(!isset($_GET['print'])){ include('emp-prof.php') ?>
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
<?php include("footer.php"); }?>