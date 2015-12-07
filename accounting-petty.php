<?php session_start(); ?>
<?php  $title="Petty Report";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	} ?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTable').DataTable();
    	$('#myTableliq').DataTable({
    		"paging":   false,
        	"order": [[ 6, "asc" ],[ 1, "desc" ],[ 5, "desc" ]]

    	} );
    $('select[name="source"]').change(function() {
	    var selected = $(this).val();
		
		if(selected == 'All'){
			$('#othersl').attr('disabled',false);
			$("#othersl").attr('required',true);
			$('#othersl').attr("placeholder", "Enter Type of Leave");
		}else{
			$('#othersl').val("");
			$('#othersl').attr('disabled',true);
			$('#othersl').attr("placeholder", " ");
			$("#othersl").attr('required',false);
		}
	});
});
</script>
<style type="text/css">
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
			<a  type = "button"class = "btn btn-primary"  href = "accounting.php?ac=penot">Home</a>
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
			<a type = "button" class = "btn btn-primary" href = "acc-report.php" id = "showapproveda">Cutoff Summary</a>							
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>	
			<a type = "button" class = "btn btn-primary" href = "acc-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "acc-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a type = "button" class = "btn btn-danger" href = "logout.php"  role="button">Logout</a>
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
					$tots = '<td>' . $data2['totalliq'] . '</td>';
    				$a = str_replace(',', '', $row['amount']);
					$change =  $a - $data2['totalliq'];
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
				echo '<td>P ' . $row['amount'] . '</td>';
				echo $tots;
				echo '<td>' .  $change . '</td>';
				if($data['liqstate'] == 'CompleteLiqdate'){
					echo '<td id = "backs" ><a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<td id = "backs" ><a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
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
				echo '<td>₱ '. number_format($data['liqamount']).'</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				echo '<td>'. $data['liqcode'].'</td>';
				echo '</tr>';	
				$totalliq += $data['liqamount'];
			}
			$a = str_replace(',', '', $amount['amount']);
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: </b></td><td>₱ '.number_format($totalliq).'<br><br>₱ '.number_format($a - $totalliq).'</td><td></td><td></td></tr>';
			echo '</tbody></table>';
			if(!isset($_GET['complete'])){
				echo '<div align="center"><a class = "btn btn-danger" href = "?liqdate">Back</a>';
			}else{
				echo '<div align="center"><a href="javascript:window.open(\'\',\'_parent\',\'\');window.close();" class = "btn btn-danger">Back</a>';
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
				<td><?php echo date("M j, y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
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
				<td><?php echo date("M j, y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
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
		$pettyid = $_GET['petty_id'];
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">' . $row['particular'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="'; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'"/></td></tr>';
				if($row['particular'] == "Check"){ echo '<tr><td>Check #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				echo '<input name = "appart" value = "' . $row['particular'] . '" type="hidden"/>';
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "accounting-petty.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';
			}
		}
		echo "</table></form>";
	}
	if(isset($_GET['report']) && $_GET['report'] == '1'){
		echo '<div id = "report"><h2 align = "center">Petty Report</h2>';
		echo '<div class = "pull-right" style = "margin-bottom: 10px;"><label>Select Source</label>';
		echo '<select name = "source" class = "form-control">';
			echo '<option value = "All"> All </option>';
			echo '<option value = "Eliseo"> Eliseo </option>';
			echo '<option value = "Sharon"> Sharon </option>';
			echo '<option value = "Accounting"> Accounting </option>';
		echo '</select></div><br>';
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
		echo '<div align = "center"><br><button id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" onclick = "window.print();"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</button><a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';
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
		$petid = $_GET['petty_id'];
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
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq']).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. ($a - $data2['totalliq']).'</td></tr>';
				echo '<tr><td><label>Liquidation:</label></td><td><a target = "_blank" href = "?liqdate='.$row['petty_id'].'&acc='.$row['account_id'].'&complete" class = "btn btn-primary">View Liquidate</a></td></tr>';
				
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
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "liqsubmit">Complete Liquidate</button> <a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}	
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['validate']) && $_GET['validate'] == 1){
		include("conf.php");
		$petid = $_GET['petty_id'];
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
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq']).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. number_format($a - $data2['totalliq']).'</td></tr>';
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