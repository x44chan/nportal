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
    	$('#myTable').DataTable({
		    "iDisplayLength": 50 ,
		    "order": [[ 0, "desc" ]]  	
		});
		
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
	if(isset($_GET['cashac']) && $_GET['cashac'] == 'a'){
		include 'caloan/cashac-admin.php';
	}
	if(isset($_GET['login_log'])){
		include 'login_log.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['loanac']) && $_GET['loanac'] == 'a'){
		include 'caloan/loanac-admin.php';
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
				if($row['transfer_id'] != null){echo '<tr><td><b>Reference #: </td><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "codesub">Release Petty</button> <a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['loanrelease']) && $_GET['loanrelease'] == 1){
		include("conf.php");
		$petid = $_GET['petty_id'];
		$sql = "SELECT * from `loan`,`login` where login.account_id = loan.account_id and loan_id = '$petid' and state = 'ARcvCashCode'";
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
				echo '<tr><td><label>Loan ID #</label></td><td>' . $row['loan_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['loandate'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['loanamount'])){ echo $row['amount']; }else{ echo number_format($row['loanamount']); } ;
				echo '</td></tr>';
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['loan_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "codelon">Release Petty</button> <a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['cashacre']) && $_GET['cashacre'] == 'a'){
		include("conf.php");
		$petid = $_GET['cashadv_id'];
		$sql = "SELECT * from `cashadv`,`login` where login.account_id = cashadv.account_id and cashadv_id = '$petid' and state = 'ARcvCash'";
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
				echo '<tr><td><label>Petty #</label></td><td>' . $row['cashadv_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['cadate'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				
								
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['caamount'])){ echo $row['caamount']; }else{ echo number_format($row['caamount']); } ;
				echo '</td></tr>';
				
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['cashadv_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "cashadvre">Release Cash Advance</button> <a id = "backs" class = "btn btn-danger" href = "admin-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php if(isset($_GET['pettyac']) || isset($_GET['release']) || isset($_GET['cashacre']) || isset($_GET['cashac']) || isset($_GET['loanac']) || isset($_GET['loanrelease'])){ echo '<div style = "display: none !important;">';} ?>
<div id = "needaproval" >
	
	<h2 align = "center"><i> Admin Dashboard <br><?php if(isset($_GET['bypass'])){ echo ' (System Bypass) ';}?></i></h2>
	<?php 
		if(!isset($_GET['bypass'])) {$otbypass = '';echo '<a href="?bypass" class="btn btn-primary pull-right" style="margin-right: 10px; margin-bottom: 10px;"><span class = "glyphicon glyphicon-lock"></span> Bypass Approval </a>';}else{$otbypass = '&bypass=1';echo '<a href="admin.php" class="btn btn-primary pull-right" style="margin-right: 10px; margin-bottom: 10px;"><span class = "glyphicon glyphicon-ban-circle"></span> Un-Bypass Approval </a>';}

	?>
	<form role = "form">
		<table id="myTable" style = "width: 100%;"class = "table table-hover " align = "center">
			<thead>
				<tr>
					<th width = "12%" ><i>Date File</i></th>					
					<th width = "16%" ><i>Name of Employee</i></th>
					<th width = "13%" ><i>Type</i></th>
					<th width = "23%" ><i>Reason</i></th>
					<th width = "19%" ><i>Checked By.</i></th>
					<th width = "18%" ><i>Action</i></th>
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
				<td><b>Petty: <i><font color = "green"><?php echo $row['particular'];?></font><br><b>Amount: <font color = "green"><i>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></font></i></td>
				<td><?php echo $row['petreason'];?></td>
				<td> - </td>
				<td><?php echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';?></td>
				</tr>

	<?php
		}
	
	}	$sql = "SELECT * from `cashadv`,`login` where login.account_id = cashadv.account_id and (state = 'UACA' or state = 'ARcvCash')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
					<td><?php echo date("M j, Y", strtotime($row['cadate']));?></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b>Cash Advance<br><b>Amount: <i><font color = "green">₱ <?php echo $row['caamount'];?></font></td>
					<td><?php echo $row['careason'];?></td>
					<td> - </td>
					<td>
						<?php 
							if($row['state'] == 'UACA'){
								echo '<a class = "btn btn-primary" href = "?cashac=a&cashadv_id='.$row['cashadv_id'].'">Approve</a> ';
								echo '<a class = "btn btn-primary" href = "loan-exec.php?cashadvact=d&cashadv_id='.$row['cashadv_id'].'"">Disapprove</a>';
							}elseif($row['state'] == 'ARcvCash'){
								echo '<a class = "btn btn-success" href = "?cashacre=a&cashadv_id='.$row['cashadv_id'].'">Release</a> ';
							}
						?>
					</td>
				</tr>
	<?php
		}
	}

	$sql = "SELECT * from `loan`,`login` where login.account_id = loan.account_id and (state = 'UALoan' or state = 'ARcvCashCode')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
					<td><?php echo date("M j, Y", strtotime($row['loandate']));?></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b>Loan<br><b>Amount: <i><font color = "green">₱ <?php echo $row['loanamount'];?></td>
					<td><?php echo $row['loanreason'];?></td>
					<td> - </td>
					<td>
						<?php 
							if($row['state'] == 'UALoan'){
								echo '<a class = "btn btn-primary" href = "?loanac=a&loan_id='.$row['loan_id'].'">Approve</a> ';
								echo '<a class = "btn btn-primary" href = "loan-exec.php?loadact=d&loan_id='.$row['loan_id'].'"">Disapprove</a>';
							}elseif($row['state'] == 'ARcvCashCode'){
								echo '<a class = "btn btn-success" style = "width: 100px" href = "?loanrelease=1&petty_id='.$row['loan_id'].'">Release</a>';
							}
						?>
					</td>
				</tr>
	<?php
		}
	}
	$sql = "SELECT * from `login` where hrchange != '0'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		
		while($row = $result->fetch_assoc()){
			$tag2 = "Category";
			$tag = "";
			if($row['probidate'] != null){
				$edate = date("M j, Y", strtotime($row['probidate']));
			}else{
				$edate = "";
			}
			if($row['vacleave'] != '0'){
				$tag2 = 'Vacation Leave<br><br>' . $tag2;
				$tag = 'Vacation Leave: ' . $row['vacleave'] . '<br> <font color = "red">Used V.Leave: ' . $row['usedvl'] .'</font><br>'. $tag;
			}
			if($row['sickleave'] != '0'){
				$tag2 = 'Sick Leave<br><br>' . $tag2;
				$tag = 'Sick Leave: ' . $row['sickleave'] . '<br> <font color = "red">Used S.Leave: ' . $row['usedsl'] .'</font><br>'. $tag;
			}
	?>
				<tr>
					<td><b>Categorization</b></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b><?php echo $tag2;?></td>
					<td><b><i><font color = "red"><?php echo '</font><font color = "green">'. $tag . $row['empcatergory'] . '</font>';?></b></td>
					<td><b> HR Department </b></td>
					<td>
						<?php 
							if($row['hrchange'] != '0'){
								echo '<a class = "btn btn-primary" href = "newuser-exec.php?promotion=a&account_id='.$row['account_id'].'">Approve</a> ';
								echo '<a class = "btn btn-primary" href = "newuser-exec.php?promotion=d&account_id='.$row['account_id'].'"">Disapprove</a>';
							}
						?>
					</td>
				</tr>
	<?php
		}
	}
	/*$sql = "SELECT *,loan_cutoff.state as loanstate from `loan_cutoff`,`login`,`loan` where loan.loan_id = loan_cutoff.loan_id and login.account_id = loan_cutoff.account_id and loan_cutoff.state = 'UALoanCut'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
					<td><?php echo date("M j, Y", strtotime($row['cutoffdate']));?><br><i><b>Start of Payment</b></i><br><?php echo date("M j, Y", strtotime($row['enddate']));?><br><i><b>End of Payment</b></i></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b>Loan Payment<br>₱ <?php echo number_format($row['cutamount']);?> / Cut-Off</td>
					<td><?php echo $row['loanreason'];?></td>
					<td> - </td>
					<td>
						<?php 
							if($row['loanstate'] == 'UALoanCut'){
								echo '<a class = "btn btn-primary" href = "loan-exec.php?loan_cutoff=a&loan_id='.$row['loan_id'].'&cutoff_id='.$row['cutoff_id'].'">Approve</a> ';
								echo '<a class = "btn btn-primary" href = "loan-exec.php?loan_cutoff=d&loan_id='.$row['loan_id'].'&cutoff_id='.$row['cutoff_id'].'"">Disapprove</a>';
							}
						?>
					</td>
				</tr>
	<?php
		}
	
	}*/
	//if(isset($_GET['bypass'])){
	//	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPettyReceived' and (source = 'Eli/Sha' or source = 'Accounting')";
	//}else{
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPettyReceived' and (source = 'Eliseo' or source = 'Sharon')";
	//}
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><b><?php echo $row['particular'];?><br><b>Amount: <i><font color = "green">₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); }?></font></i></td>
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
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and (state = 'AHR' or state like 'UA%') and DAY(dateofot) >= $forque and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey ORDER BY datefile ASC";
				
			}else{
				$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and state = 'AHR' and DAY(dateofot) >= $forque and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey ORDER BY datefile ASC";	
				
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				
				while($row = $result->fetch_assoc()){
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['2daysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					if($row['csrnum'] != ""){
						$row['csrnum'] = '<b>CSR Number: '.$row['csrnum'] .'</b><br>';
					}
					$originalDate = date($row['datefile']);
					$newDate = date("M j, Y", strtotime($originalDate));					
					$explo = (explode(":",$row['approvedothrs']));
					if($explo[1] > 0){
						$explo[1] = '.5';
					}else{
						$explo[1] = '.0';
					}	
					$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
					echo '<td>'.$newDate.'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>Overtime<br>Date: <i><font color = "green">'. date("M j, Y", strtotime($row['dateofot'])). '</font></i><br>O.T. : <i><font color = "green">'.$explo[0].$explo[1].'</font></td>';
					echo '<td>'.$data1['reason'].'</td>';	
						if($row['datehr'] == ""){
							$datehr = '<b><i>HR REQUEST</i></b>';
							if(isset($_GET['bypass'])){
								$datehr = '<b><i> Bypass </i></b>';
							}
							echo '<td > '.$datehr. '</td>';
						}else{
							if($row['oldot'] != null && $row['state'] == 'AHR'){
								$oldot = '</b><br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
								$hrot = '<b>App. OT: <i><font color = "green">';
								$hrclose = "</font></i>";
							}else{
								$oldot = "";
								$hrot = '<b>Filed OT: <font color = "green">';
								$hrclose ='</b>';
							}
							if($row['otbreak'] != null){
								$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
							}else{
								$otbreak = "";
							}
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						if($row['dateacc'] != ""){
							$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
						}else{
							$datetech = "";
						}
						echo '<td style = "text-align:left;"><b>HR: '.$datehr. $datetech .'</b><br>'.$row['csrnum']. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>';
					}	
					echo '<td >
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id']. $otbypass .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and (state = 'AHR' or state like 'UA%') and DAY(dateofundrtime) >= $forque and DAY(dateofundrtime) <= $endque and MONTH(dateofundrtime) = $dated and YEAR(dateofundrtime) = $datey ORDER BY datefile ASC";
			}else{
				$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and state = 'AHR' and DAY(dateofundrtime) >= $forque and DAY(dateofundrtime) <= $endque and MONTH(dateofundrtime) = $dated and YEAR(dateofundrtime) = $datey ORDER BY datefile ASC";		
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['datefile']);
					$newDate = date("M j, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();	
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("M j, Y h:i A", strtotime($row['dateacc']));
					echo '<td>'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>Undertime<br>Date: <i><font color = "green">'. date("M j, Y", strtotime($row['dateofundrtime'])). '</font></td>';
					echo '<td>'.$data1['reason'].'</td>';
					if($row['dateacc'] != ""){
						$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
					}else{
						$datetech = "";
					}
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						if(isset($_GET['bypass'])){
							$datehr = '<b><i> Bypass </i></b>';
						}
						echo '<td><b>'.$datehr. $datetech .'</td>';
					}else{
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						echo '<td><b>HR: '.$datehr. $datetech .'</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id']. $otbypass .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and (state = 'AHR' or state like 'UA%') and DAY(obdatereq) >= $forque and DAY(obdatereq) <= $endque and MONTH(obdatereq) = $dated and YEAR(obdatereq) = $datey ORDER BY obdate ASC";
			}else{
				$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'AHR' and DAY(obdatereq) >= $forque and DAY(obdatereq) <= $endque and MONTH(obdatereq) = $dated and YEAR(obdatereq) = $datey ORDER BY obdate ASC";		
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['obdate']);
					$newDate = date("M j, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("M j, Y h:i A", strtotime($row['dateacc']));
					echo '<td>'.$newDate .'</td>';;
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>Official Business<br>Date: <font color = "green">'. date("M j, Y", strtotime($row['obdate'])). '</font></td>';
					echo '<td>'.$row['obreason'].'</td>';

					if($row['dateacc'] != ""){
						$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
					}else{
						$datetech = "";
					}
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						if(isset($_GET['bypass'])){
							$datehr = '<b><i> Bypass </i></b>';
						}
						echo '<td><b>'.$datehr. $datetech .'</td>';
					}else{
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						echo '<td><b>HR: '.$datehr. $datetech .'</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id']. $otbypass .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and (state = 'AHR' or state like 'UA%') and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
			}else{
				$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and state = 'AHR' and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";	
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['datefile']);
					$newDate = date("M j, Y", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("M j, Y h:i A", strtotime($row['dateacc']));
	
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					if($row['typeoflea'] == "Sick Leave"){
						$ftowork = $row['ftowork'] . '<br>';
					}else{
						$ftowork = "";
					}
					if($row['othersl'] != null){
						$othersl = $row['othersl'] . '<br>';
					}else{
						$othersl = "";
					}
					$query1 = "SELECT * FROM `nleave` where leave_id = '$row[leave_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();	
					echo '<td>'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';	
					echo '<td><b>'.$row['typeoflea']. '</b><br>' .$othersl. '<b><i style = "color: green;"> '.$ftowork. ' </i>Fr: <font color = "green">'.date("M j, Y", strtotime($row['dateofleavfr'])).'</font><br>To: <font color = "green">'.date("M j, Y", strtotime($row['dateofleavto'])).'</font><br>Num days: <i><font color = "green">' .$row['numdays'].'</font></i><b></td>';
					echo '<td>'.$data1['reason'].'</td>';
					if($row['dateacc'] != ""){
						$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
					}else{
						$datetech = "";
					}
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						if(isset($_GET['bypass'])){
								$datehr = '<b><i> Bypass </i></b>';
							}
						echo '<td><b>'.$datehr. $datetech .'</td>';
					}else{
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						echo '<td><b>HR: '.$datehr. $datetech .'</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id']. $otbypass .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "approval.php?approve=DA'.$_SESSION['level'].'&leave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td></tr>';
				}
			}
		?>
		</tbody>
		</table>
	</form>
</div>
<?php if(isset($_GET['pettyac']) || isset($_GET['release']) || isset($_GET['cashacre']) || isset($_GET['loanrelease'])){ echo '</div>';} ?>
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
<?php 
	if($_SESSION['pass'] == 'defaultadmin'){
		include('up-pass.php');
	}
	?>
<?php include('footer.php');?>