<?php session_start(); ?>
<?php  $title="Admin Page";
	include('header.php');	
	include('conf.php');
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
<style type="text/css">
	#tohide{
		display: none;
	}
</style>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTable').DataTable({
		    "iDisplayLength": 50 ,
		    "order": [[ 6, "desc" ]],  		   	 
		});
		
		$('input[name = "transct"]').hide();
		$('select[name = "source"]').change(function() {
		    var selected = $(this).val();			
			if(selected == 'Accounting' || ($('select[name = "appart"]').val() == 'Cash' || $('select[name = "appart"]').val() == 'Auto Debit')){
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
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a href = "admin.php"  type = "button"class = "btn btn-primary"  id = "showneedapproval">Home</a>	
			<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>			
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee List <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "admin-emprof.php" type = "button">Employee Profile</a></li>
				  <li><a href = "admin-emprof.php?loan" type = "button">Employee Loan List</a></li>
				  <li><a href = "admin-emprof.php?sumar=leasum" type = "button">Employee Leave Summary</a></li>
				  <li><a href = "admin-emprof.php?leaverep" type = "button">Employee Leave Report</a></li>
				</ul>
			</div>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				  <li class="divider"></li>
				  <li><a type = "button" href = "admin-petty.php?pettydate"> Petty Date Summary </a></li>
				  <li><a type = "button" href = "admin-petty.php?expenses"> Expenses </a></li>
				  <li><a type = "button" href = "admin-petty.php?expn"> Sales Project Expenses </a></li>
				</ul>
			</div>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">H.R. / Tech Modules <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "?login_log" type = "button">Login Log</a></li>
				  <li><a type = "button" href = "tech-sched.php">Tech Schedule</a></li>
				  <li><a type = "button" href = "hr-timecheck.php">H.R Time Checking</a></li>
				</ul>
			</div>
			<a type = "button"class = "btn btn-primary"  href = "admin-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button"class = "btn btn-primary" href = "admin-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a class="btn btn-danger"  href = "logout.php"  role="button">Logout</a>
		</div><br><br>
	</div>
</div>
<?php
	include('conf.php');
	if(isset($_GET['dofficialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['dofficialbusiness_id']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$query1 = "SELECT * FROM `officialbusiness`,`login` where officialbusiness.account_id = login.account_id and officialbusiness_id = '$id'";
		$data1 = $conn->query($query1)->fetch_assoc();
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapprove Official Business </h3></th>
						</tr>
					</thead>
					<tr>
						<td> Name: </td>
						<td>' . $data1['fname'] . ' ' . $data1['lname'] . '</td>
					</tr>
					<tr>
						<td> Date of Request: </td>
						<td>' . date("M j, Y", strtotime($data1['obdate'])) . '</td>
					</tr>
					<tr>
						<td> Schedule: </td>
						<td>' . $data1['officialworksched'] . '</td>
					</tr>
					<tr>
						<td> Reason: </td>
						<td>' . $data1['obreason'] . '</td>
					</tr>
					<tr>
						<td align = "right"><label for = "dareason">Input Disapproval reason</label></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penob" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "officialbusiness_id" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
					</tr>
				</table>
			</form>';	
			echo '<div style = "display: none;">';	
	}
?>


<?php
	include('conf.php');
	if(isset($_GET['dundertime'])){
		$id = mysqli_real_escape_string($conn, $_GET['dundertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$query1 = "SELECT * FROM `undertime`,`login` where undertime.account_id = login.account_id and undertime_id = '$id'";
		$data1 = $conn->query($query1)->fetch_assoc();
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
						<tr>
							<td> Name: </td>
							<td>' . $data1['fname'] . ' ' . $data1['lname'] . '</td>
						</tr>
						<tr>
							<td> Date of Request: </td>
							<td>' . date("M j, Y", strtotime($data1['dateofundrtime'])) . '</td>
						</tr>
						<tr>
							<td> Time: </td>
							<td>' . $data1['undertimefr'] . ' to ' . $data1['undertimeto'] . '</td>
						</tr>
						<tr>
							<td> Reason: </td>
							<td>' . $data1['reason'] . '</td>
						</tr>
					<tr>
						<td align = "right"><label for = "dareason">Input Disapproval reason</label></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penundr" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "undertime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
					</tr>
				</table>
			</form>';	echo '<div style = "display: none;">';	
}
?>


<?php
	include('conf.php');
	if(isset($_GET['dleave'])){
		$id = mysqli_real_escape_string($conn, $_GET['dleave']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$query1 = "SELECT * FROM `nleave`,`login` where nleave.account_id = login.account_id and leave_id = '$id'";
		$data1 = $conn->query($query1)->fetch_assoc();
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td> Name: </td>
						<td>' . $data1['fname'] . ' ' . $data1['lname'] . '</td>
					</tr>
					<tr>
						<td> Date of Request: </td>
						<td>' . date("M j, Y", strtotime($data1['dateofleavfr'])) . ' to ' . date('M j, Y', strtotime($data1['dateofleavfr'])) . '</td>
					</tr>
					<tr>
						<td> Type: </td>
						<td>' . $data1['typeoflea'] . '</td>
					</tr>
					<tr>
						<td> Reason: </td>
						<td>' . $data1['reason'] . '</td>
					</tr>
					<tr>
						<td align = "right"><label for = "dareason">Input Disapproval reason</label></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penlea" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "leave" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
					</tr>
				</table>
			</form>';echo '<div style = "display: none;">';				
	}

		if(isset($_GET['dovertime'])){	
			$id = mysqli_real_escape_string($conn, $_GET['dovertime']);
			$state = mysqli_real_escape_string($conn, $_GET['approve']);
			$query1 = "SELECT * FROM `overtime`,`login` where overtime.account_id = login.account_id and overtime_id = '$id'";
			$data1 = $conn->query($query1)->fetch_assoc();
			echo '<form action = "approval.php" method = "get" class = "form-group">
					<table class = "table table-hover" align = "center">
						<thead>
							<tr>
								<th colspan  = 3><h3> Disapproval Reason </h3></th>
							</tr>
						</thead>
						<tr>
							<td> Name: </td>
							<td>' . $data1['fname'] . ' ' . $data1['lname'] . '</td>
						</tr>
						<tr>
							<td> Date of Request: </td>
							<td>' . date("M j, Y", strtotime($data1['dateofot'])) . '</td>
						</tr>
						<tr>
							<td> Time: </td>
							<td>' . $data1['startofot'] . ' to ' . $data1['endofot'] . '</td>
						</tr>
						<tr>
							<td> Reason: </td>
							<td>' . $data1['reason'] . '</td>
						</tr>
						<tr>
							<td align = "right"><label for = "dareason">Input Disapproval reason</label></td>
							<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
						</tr>
						<tr>
							<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penot" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a></td>
						</tr>
						<tr>
							<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
							<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						</tr>
					</table>
				</form>';echo '<div style = "display: none;">';	
			}
			if(isset($_GET['dhol'])){	
			$id = mysqli_real_escape_string($conn, $_GET['dhol']);
			$state = mysqli_real_escape_string($conn, $_GET['approve']);
			$query1 = "SELECT * FROM `holidayre`,`login` where holidayre.account_id = login.account_id and holidayre_id = '$id'";
			$data1 = $conn->query($query1)->fetch_assoc();
			echo '<form action = "approval.php" method = "get" class = "form-group">
					<table class = "table table-hover" align = "center">
						<thead>
							<tr>
								<th colspan  = 3><h3> Disapproval Reason </h3></th>
							</tr>
						</thead>
						<tr>
							<td> Name: </td>
							<td>' . $data1['fname'] . ' ' . $data1['lname'] . '</td>
						</tr>
						<tr>
							<td> Date of Request: </td>
							<td>' . date("M j, Y", strtotime($data1['holiday'])) . '</td>
						</tr>
						<tr>
							<td> Reason: </td>
							<td>' . $data1['reason'] . '</td>
						</tr>
						<tr>
							<td align = "right"><label for = "dareason">Input Disapproval reason</label></td>
							<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
						</tr>
						<tr>
							<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penot" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a></td>
						</tr>
						<tr>
							<td><input type = "hidden" name = "hol" value = "'.$id.'"/></td>
							<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						</tr>
					</table>
				</form>';echo '<div style = "display: none;">';	
			}
?>
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
	if(isset($_POST['submitrans'])){
		$petid = mysql_escape_string($_POST['petty_id']);
		$valcode = mysql_escape_string($_POST['valcode']);
		$refcode = mysql_escape_string($_POST['transctc']);
		$source = mysql_escape_string($_POST['source']);
		$releasedate = date("Y-m-d");
		$xxsql = "SELECT * FROM `petty` where petty_id = '$petid' and rcve_code = '$valcode' and state = 'TransProcCode'";
		$xxresult = $conn->query($xxsql);		
		if($xxresult->num_rows <= 0){
			$_SESSION['transct'] = $refcode;	
			echo '<script type="text/javascript">alert("Wrong code");window.location.replace("?transrelease=1&petty_id='.$petid.'"); </script>';
					
		}else{
			$sql = "UPDATE `petty` set state = 'AAPettyRep',transfer_id = '$refcode',source = '$source',releasedate = '$releasedate' where petty_id = '$petid' and state = 'TransProcCode'";
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
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'"/></td></tr>';
				echo '<tr><td>Reference #: <font color = "red">*</font></td><td><input value = "'.$xrefcode.'" placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transctc"/></tr></td>'; 
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitrans">Submit</button><br><br><a href = "admin.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';

			}
			
		}
		echo "</table></form></div><div style = 'display: none;'>";
	}else{
		unset($_SESSION['transct']);
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
				$cash = ""; $check = ""; $autodeb = ""; $trans = "";
				if($row['particular'] == "Cash"){
					$cash = ' selected ';
				}elseif($row['particular'] == "Check"){
					$check = " selected ";
				}elseif($row['particular'] == 'Auto Debit'){
					$autodeb = " selected ";
				}else{
					$trans = " selected ";
				}
					echo '<option value = "">----------</option>
              			<option value = "Cash" '.$cash.'>Cash</option>
              			<option value = "Check" '.$check.'>Check</option>
              			<option value = "Auto Debit" '.$autodeb.'>Auto Debit</option>';
				echo '</select></td></tr>';	
				echo '<tr><td style = "width: 30%;">Source of Fund <font color = "red">*</font></td><td><select required name = "source" class = "form-control"><option value = "">-------</option><option value = "Eliseo">Eliseo</option><option value = "Sharon">Sharon</option><option value = "Accounting">Accounting</option></select></td></tr>';
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;"><input pattern = "[.0-9,]*" class = "form-control" type = "text" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'"/></td></tr>';
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
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
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
				echo '	<tr>
							<td style = "width: 30%;"><b>Source: </td>
							<td style = "width: 50%;">
								<select class = "form-control" name = "loan_source" required>
									<option value = ""> - - - - - - - - </option>
									<option value = "Sharon"> Sharon </option>
									<option value = "Eliseo"> Eliseo </option>
									<option value = "Petty Change"> Petty Change </option>
								</select>	
							</td>
						</tr>';			
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
					<th width = "12%" style="text-align: left !important;"><i>Date File</i></th>					
					<th width = "16%" ><i>Name of Employee</i></th>
					<th width = "16%" ><i>Type</i></th>
					<th width = "23%" ><i>Reason</i></th>
					<th width = "16%" ><i>Checked By.</i></th>
					<th width = "18%" ><i>Action</i></th>
					<th id = "tohide"> to hide </th>
				</tr>
			</thead>
			<tbody id="people">
			<?php
	include("conf.php");
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and (state = 'UAPetty' or state = 'TransProcCode')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$proj = "SELECT * FROM `project` where name = '$row[project]'";
			$resproj = $conn->query($proj)->fetch_assoc();
			$pettype = '<br>'.$row['projtype'].': <font color = "green">'.$row['project'].'</font>';
			if(isset($resproj['loc']) && $resproj['loc'] != ""){
				$pettype = '<br>Loc: <font color = "green">'. $resproj['loc']. '</font>'.$pettype;
			}
			if($row['project'] == null){
				$pettype = '<br><font color = "green">' . $row['projtype'] . '</font>';
			}
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><b>Petty: <i><font color = "green"><?php echo $row['particular'];?></font><br><b>Amount: <font color = "green"><i>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></font><?php echo $pettype;?></i></td>
				<td><?php echo $row['petreason'];?></td>
				<td><?php if($row['acctrans'] != null){ echo '<i><b>ACC: ' .date("M j, Y g:i A", strtotime($row['acctrans'])) . '</b></i>'; } else { echo ' - '; } ?> </td>
				<td><?php 
					if($row['state'] == 'UAPetty'){
						echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';
					}elseif($row['state'] == 'TransProcCode'){
						echo '<a class = "btn btn-success" style = "width: 100px" href = "?transrelease=1&petty_id='.$row['petty_id'].'">Release</a> ';
					}					
					?></td>
				<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['date']));?></td>
				</tr>

	<?php
		}
	
	}
	$sql = "SELECT * from `login` where empcatergory is null and active = 1 and level != 'Admin' and uname != 'accounting' and uname != 'hradmin' and uname != 'mitchortiz' order by lname ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><b>Categorization</b></td>			
				<td><?php if($row['fname'] != ""){ echo $row['fname']. ' '.$row['lname']; } else{ echo 'Pending for Update Profile'; }?></td>
				<td> - </td>
				<td> - </td>
				<td> <b> Pending for H.R Categorization </b> </td>
				<td> - </td>
				<td id="tohide"></td>
				</tr>

	<?php
		}
	
	}	
	$sql = "SELECT * from `login` where active = 1 and level != 'Admin' and uname != 'accounting' and uname != 'hradmin' and uname != 'mitchortiz'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
		if($row['empcatergory'] == 'Probationary'){
        	$edate = $row['probidate'];
        	$tonull = $row['probidate'];
        }elseif($row['empcatergory'] == 'Contractual'){
        	$edate = $row['contractdate'];
        	$tonull = $row['contractdate'];
        }else{
        	$edate = "";
        	$tonull = 'asd';
        }
		if($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+5 months", strtotime($edate)))){
            $flagcolor = " style = 'color: red; font-weight: bold;' ";
        }elseif($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+4 months", strtotime($edate))) ){
        	$flagcolor = " style = 'color: green; font-weight: bold;'";
        }else{
        	continue;
        }  
	?>
				<tr <?php echo $flagcolor; ?>>
				<td><b>Categorization</b></td>			
				<td><?php if($row['fname'] != ""){ echo $row['fname']. ' '.$row['lname']; } else{ echo 'Pending for Update Profile'; }?></td>
				<td> - </td>
				<td> <?php echo '<b>'. $row['empcatergory'] . '<br>' . date("M j, Y",strtotime($edate)); ?> </td>
				<td>  Pending for H.R Categorization </b> </td>
				<td> - </td>
				<td id="tohide"></td>
				</tr>

	<?php
		}
	
	}

	$sql = "SELECT *,DATE(DATE_ADD(enddate, INTERVAL 1 DAY)) as enddatex from `loan_cutoff`,`login` where login.account_id = loan_cutoff.account_id and state = 'CutOffPaid' and CURDATE() BETWEEN cutoffdate and DATE(DATE_ADD(enddate, INTERVAL 1 DAY)) and full is null and active = 1";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y",strtotime($row['enddatex']));?></td>			
				<td><?php if($row['fname'] != ""){ echo $row['fname']. ' '.$row['lname']; } else{ echo 'Pending for Update Profile'; }?></td>
				<td> <b><font color = "green">Loan</font><br><i>Amount: ₱ <?php $row['cutamount'] = str_replace(',', '', $row['cutamount']); echo number_format($row['cutamount'],2); ?> </td>
				<td> - </td>
				<td> <b><?php if(date("Y-m-d") >= $row['enddatex']){ echo '<font color = "green"> Deducted </font>'; }else { ?><font color = "red"> Pending </font></b> <?php } ?></td>
				<td> - </td>
				<td id = "tohide"><?php //echo date("Y/m/d", strtotime($row['enddatex']));?></td>
				</tr>

	<?php
		}
	
	}	
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and (petty.state != 'DAPetty' and petty.state != 'CPetty')";
	$result = $conn->query($sql);
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

			if($data['liqstate'] == 'CompleteLiqdate' || $data['liqstate'] == 'EmpVal'){
				continue;
			}
			$date1 = date("Y-m-d");
			if($row['appdate'] != '0000-00-00 00:00:00' && ($row['state'] != 'UAPetty' || $row['state'] != 'CPetty' || $row['state'] != 'DAPetty')){
				$date2 = date("Y-m-d", strtotime("+6 days", strtotime($row['appdate'])));
			}else{
				$date2 = date("Y-m-d", strtotime("+6 days", strtotime($row['date'])));
			}
			if($date1 >= $date2){
				$red = ' style = "color: red;" ';
			}else{
				continue;
			}

	?>
				<tr <?php echo $red; ?>>
				<td><?php echo date("M j, Y",strtotime($row['date']));?></td>			
				<td><?php if($row['fname'] != ""){ echo $row['fname']. ' '.$row['lname']; } else{ echo 'Pending for Update Profile'; }?></td>
				<td> <b><font color = "green">Petty Cash</font><br><i>Amount: ₱ <?php echo $row['amount']; ?> </td>
				<td> - </td>
				<td> <b><?php if($data['liqstate'] == ""){ echo ' Pending Liquidation ';} elseif($data['liqstate'] == 'LIQDATE') { echo ' Pending for Completion ';}?> </b> </td>
				<td> - </td>
				<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['date']));?></td>
				</tr>

	<?php
		}
	
	}
	$sql = "SELECT * from `login` where islock = '2'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td> - </td>			
				<td><?php if($row['fname'] != ""){ echo $row['fname']. ' '.$row['lname']; } else{ echo 'Pending for Update Profile'; }?></td>
				<td> <b> Request to unlock Employee Profile </td>
				<td> - </td>
				<td> <b> H.R. </b> </td>
				<td> 
					<?php
						echo '<a class = "btn btn-primary" href = "cancel-req.php?unlock&app='.$row['account_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" href = "cancel-req.php?unlock&dapp='.$row['account_id'].'"">Disapprove</a>';
					?>
				</td>
				<td id = "tohide"></td>
				</tr>

	<?php
		}
	
	}	
	$sql = "SELECT * from `cashadv`,`login` where login.account_id = cashadv.account_id and (state = 'UACA' or state = 'ARcvCash')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
					<td><?php echo date("M j, Y", strtotime($row['cadate']));?></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b>Cash Advance<br><b>Amount: <i><font color = "green">₱ <?php echo number_format($row['caamount']);?></font></td>
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
					<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['cadate']));?></td>
				</tr>
	<?php
		}
	}

	$sql = "SELECT * from `loan`,`login` where login.account_id = loan.account_id and (state = 'ARcvCashCode')";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			if($row['penalty'] == 1){
				$row['penalty'] = '<b><font color = "red"> Penalty Loan </font></b>';
			}elseif($row['penalty'] == 2){
				$row['penalty'] = '<b><font color = "green"> Personal Loan </font></b>';
			}else{
				$row['penalty'] = '<b> Salary Loan </b>';
			}
			$sq = "SELECT * FROM login where account_id = '$row[acctid]'";
			$tas = $conn->query($sq)->fetch_assoc();
	?>
				<tr>
					<td><?php echo date("M j, Y", strtotime($row['loandate']));?></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b><?php echo $row['penalty'];?><br><b>Amount: <i><font color = "green">₱ <?php echo number_format($row['loanamount']);?></td>
					<td><?php echo $row['loanreason'];?></td>
					<td><b>Accounting <?php echo '<br>Date: <i><font color = "green">' .date("M j, Y h:i A", strtotime($row['dateacc']));?></font></i><br>Req. Amount: <i><font color="green">₱ <?php echo number_format($row['oldamnt']);?></font> </td>
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
					<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['loandate']));?></td>
				</tr>
	<?php
		}
	}
	$sql = "SELECT * from `nleave_bal`,`login` where nleave_bal.account_id = login.account_id and state ='UA'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
					<td><?php echo date("M j, Y h:i A", strtotime($row['datefile']));?></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b>Leave Balance<br>Vacation Leave: <font color = "green"> <?php echo $row['vleave'];?></font><br>Sick Leave: <font color = "green"> <?php echo $row['sleave'];?><br></font><b>For: <font color = "green"><?php echo date("M", strtotime($row['startdate']));?> - <?php echo date("M Y", strtotime($row['enddate']));?></font></td>
					<td> - </td>
					<td><b> HR Department </b></td>
					<td> <?php 
							echo '<a class = "btn btn-primary" href = "oleave-exec.php?adleave=a&leavebal_id='.$row['leavebal_id'].'">Approve</a> ';
							echo '<a class = "btn btn-primary" href = "oleave-exec.php?adleave=d&leavebal_id='.$row['leavebal_id'].'"">Disapprove</a>';
						?>
					</td>
					<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['datefile']));?></td>
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
			if($row['vacleave'] != '0' && date("Y-m-d") < "2015-12-29"){
				$tag2 = 'Vacation Leave<br><br>' . $tag2;
				$tag = 'Vacation Leave: ' . $row['vacleave'] . '<br> <font color = "red">Used V.Leave: ' . $row['usedvl'] .'</font><br>'. $tag;
			}
			if($row['sickleave'] != '0' && date("Y-m-d") < "2015-12-29"){
				$tag2 = 'Sick Leave<br><br>' . $tag2;
				$tag = 'Sick Leave: ' . $row['sickleave'] . '<br> <font color = "red">Used S.Leave: ' . $row['usedsl'] .'</font><br>'. $tag;
			}
			if($row['empcatergory'] == 'Regular'){
				$datecat = '<br>Date: ' . date("M j, Y", strtotime($row['regdate']));
				if($row['regdate'] == null){
					$datecat = "";
				}
			}elseif($row['empcatergory'] == 'Probationary'){
				$datecat = '<br>Date: ' . date("M j, Y", strtotime($row['probidate']));
			}elseif($row['empcatergory'] == 'Contractual'){
				$datecat = '<br>Date: ' . date("M j, Y", strtotime($row['contractdate']));
			}

			if($row['empcatergory'] == 'Contractual' && $row['contractdate'] < date("2000-12-31")){
				$datecat = "";
			}
			
	?>
				<tr>
					<td><b>Categorization</b></td>			
					<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
					<td><b><?php echo $tag2;?></td>
					<td><b><i><font color = "red"><?php echo '</font><font color = "green">'. $tag . $row['empcatergory'] . '</font>' . $datecat . '<br>Payment: <font color = "green">' . $row['payment'] . '</font>';?></b></td>
					<td><b> HR Department </b></td>
					<td>
						<?php 
							if($row['hrchange'] != '0'){
								echo '<a class = "btn btn-primary" href = "newuser-exec.php?promotion=a&account_id='.$row['account_id'].'">Approve</a> ';
								echo '<a class = "btn btn-primary" href = "newuser-exec.php?promotion=d&account_id='.$row['account_id'].'"">Disapprove</a>';
							}
						?>
					</td>
					<td id="tohide"></td>
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
			if($row['projtype'] == 'P.M.' || $row['projtype'] == 'Internet'){
				$pettype = '<br>'.$row['projtype'].': <font color = "green">'.$row['project'].'</font>';
			}else{
				$xx = "SELECT * FROM project where name = '$row[project]'";
				$xxx = $conn->query($xx)->fetch_assoc();
				$pettype = '<br>Loc: <font color ="green">' . $xxx['loc'] .'</font><br>Proj: <font color = "green">' . $row['project'].'</font>';
			}
			if($row['project'] == ""){
				$pettype = '<br><font color = "green">'.$row['project'].'</font>';
			}

	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><b>Petty: <font color = "green"><?php echo $row['particular'];?></font><br><b>Amount: <i><font color = "green">₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></font><?php echo $pettype;?></i></td>
				<td><?php echo $row['petreason'];?></td>
				<td> - </td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
				</td>
				<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['date']));?></td>
				</tr>
	<?php
		}	
	}
	$sql = "SELECT * FROM holidayre,login where login.account_id = holidayre.account_id and state = 1 order by state ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			echo '<tr>';
			echo '<td>' . date("M j, Y h:i A", strtotime($row['datefile'])) . '</td>';
			echo '<td>'.$row['fname']. ' '.$row['lname'].'</td>';
			echo '<td><i><b>'.$row['type'].'<br> Date: <font color = "green">'.date("M j, Y",strtotime($row['holiday'])).'</font></b></td>';
			echo '<td>'.$row['reason'].'</td>';
			echo '<td style = "text-align: left;"><b>HR: '.date("M j, Y h:i A",strtotime($row['datehr'])).'</td>';
			echo '<td>
					<a href = "approval.php?approve=A'.$_SESSION['level'].'&hol='.$row['holidayre_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
					<a href = "?approve=DA'.$_SESSION['level'].'&dhol='.$row['holidayre_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
				</td>';
			echo '<td style = "display: none;">'.date("Y/m/d",strtotime($row['datefile'])) .'</td>';
			echo '</tr>';
		}
	}

?>	
		<?php
			include('conf.php');		
						
			$date17 = date("d");
			$dated = date("m");
			$datey = date("Y");
			$forque = date('Y-m-01 00:00:00', strtotime("previous month"));
			$endque = date('Y-m-d 23:59:59');	
			
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and (state = 'AHR' or state like 'UA%') and datefile BETWEEN '$forque' and '$endque' ORDER BY datefile ASC";
				
			}else{
				$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and (state = 'AHR' or state = 'UAAdmin' or state = 'UALate')  and datefile BETWEEN '$forque' and '$endque' ORDER BY datefile ASC";	
				
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
					$newDate = date("M j, Y h:i A", strtotime($originalDate));					
					$explo = (explode(":",$row['approvedothrs']));
					if($explo[1] > 0){
						$explo[1] = '.5';
					}else{
						$explo[1] = '.0';
					}	
					if($row['state'] == 'UALate'){
						$late = '<font color = "red"><i>Late Filed</i></font><br>';
					}else{
						$late = "";
					}
					$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
					if($row['otlate'] != null){
						$otlate =  '<br><br><b><font color = "red"><i>Approved Late Filing by the Dep. Head</i></font></b>';
					}else{
						$otlate = "";
					}
					if($row['projtype'] != ""){
						$project = '<b><br>'.$row['projtype'] . ': <font color = "green">' . $row['project'] . '</font>';
					}else{
						$project = "";
					}
					if($row['project'] == ""){
						$project = '<b><br><font color = "green">' . $row['projtype'] . '</font>';
					}
					if($row['correction'] == 1){
						$correctionx = '<b> ( Employee Error ) </b><br>';
					}else{
						$correctionx = "";
					}
					echo '<td style="align: left !important;">'.$newDate.'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>'.$late.'Overtime<br>Date: <i><font color = "green">'. date("M j, Y", strtotime($row['dateofot'])). '</font></i><br>O.T. : <i><font color = "green">'.$row['startofot'] . ' - ' . $row['endofot'].'</font><br>Sched: <font color = "green">'.$row['officialworksched'] .'</font>'.$otlate . $project.'</td>';
					echo '<td>'.nl2br($data1['reason']).'</td>';	
						if($row['datehr'] == ""){
							$datehr = '<b><i>Waiting for approval</i></b>';
							if(isset($_GET['bypass'])){
								$datehr = '<b><i> Bypass </i></b>';
							}
							if($row['state'] == 'UALate'){
								$datehr = '<b><font color = "red"><i>Late Filed O.T. Request <br>Waiting for Approval</i></font></b>';
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
								$otbreak = '<br><b><i></font>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
							}else{
								$otbreak = "";
							}
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						if($row['dateacc'] != "" && strtolower($row['position']) == 'service technician'){
							$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
						}elseif($row['dateacc'] == "" && strtolower($row['position']) == 'service technician'){
							$datetech = "";
						}

						if(strtolower($row['position']) <> 'service technician'){
							$datetech = "";
						}
						if($row['state'] == 'UAAdmin'){
							$datehr = "<b> Waiting for Approval</b>";
						}
						echo '<td style = "text-align:left;">'.$correctionx.'<b>HR: '.$datehr. $datetech .'</b><br>'.$row['csrnum'] . $hrot .  $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . $otbreak . ' </b>'.$oldot.'</td>';
					}	
					if($row['state'] == 'UALate'){
						if(strtolower($row['position']) == 'service technician'){
							$post = '&post=1';
						}else{
							$post = "";
						}
						$ualate = '&late' . $post;
					}else{
						$ualate = "";
					}
					if($row['level'] == 'HR'){
						$hrlevel = "&level=hr";
					}else{
						$hrlevel = "";
					}
					echo '<td>
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id']. $otbypass . $ualate . $hrlevel .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dovertime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td><td id = "tohide">'.date("Y/m/d", strtotime($row['datefile'])).'</td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and (state = 'AHR' or state like 'UA%') and datefile BETWEEN '$forque' and '$endque' ORDER BY datefile ASC";
			}else{
				$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and (state = 'AHR' or state = 'UAAdmin' or state = 'UALate') and datefile BETWEEN '$forque' and '$endque' ORDER BY datefile ASC";		
			}
			$result = $conn->query($sql);
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
					$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();	
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					if($row['state'] == 'UALate'){
						$late = '<b><i><font color = "red"> Late Filed </font></b><br>';
					}else{
						$late = "";
					}
					echo '<td style="align: left !important;">'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>'.$late.'Undertime<br>Date: <i><font color = "green">'. date("M j, Y", strtotime($row['dateofundrtime'])). '</font><br>Time: <font color = "green">'.$row['undertimefr'] . ' - ' . $row['undertimeto'] .'</td>';
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
						if($row['state'] == 'UALate'){
							$datehr = '<b><i><font color = "red"> Late Filed Undertime Request<br> Waiting for Approval</font></b>';
						}else{
							$datehr = "<b><i>Waiting for Approval";
						}
						echo '<td><b>'.$datehr. '</td>';
					}else{
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));

						echo '<td><b>HR: '.$datehr. '</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id']. $otbypass .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dundertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td><td id = "tohide">'.date("Y/m/d", strtotime($row['datefile'])).'/td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and (state = 'AHR' or state like 'UA%') and obdate BETWEEN '$forque' and '$endque' ORDER BY obdate ASC";
			}else{
				$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and (state = 'AHR' or state = 'UAAdmin' or state = 'UALate') or state and obdate BETWEEN '$forque' and '$endque' ORDER BY obdate ASC";		
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['obdate']);
					$newDate = date("M j, Y h:i A", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					if($datetoday >= $row['twodaysred'] ){
						echo '<tr style = "color: red">';
					}else{
						echo '<tr>';
					}
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("M j, Y h:i A", strtotime($row['dateacc']));
					if($row['oblate'] != ""){
						$late = '<b><i><font color = "red"> Late Filed </font></b><br>';
					}else{
						$late = "";
					}
					if($row['state'] == 'AHR'){
						$ss = "";
					}else{
						$ss = "&ua";
					}
					echo '<td style="align: left !important;">'.$newDate .'</td>';;
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td><b>'.$late.'Official Business<br>Date: <font color = "green">'. date("M j, Y", strtotime($row['obdatereq'])). '</font><br>Sched: <font color = "green">'.$row['officialworksched'].'</font><br> In-Out: <font color = "green">'. $row['obtimein'] . ' - ' . $row['obtimeout'] . '</td>';
					echo '<td>'.$row['obreason'].'</td>';

					if($row['dateacc'] != "" && strtolower($row['position']) == 'service technician'){
						$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
					}elseif($row['dateacc'] == "" && strtolower($row['position']) == 'service technician'){
						$datetech = "";
					}

					if(strtolower($row['position']) <> 'service technician'){
						$datetech = "";
					}
					if($row['state'] == 'UALate'){
						$late = "&late";
					}else{
						$late = "";
					}
					if($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						if(isset($_GET['bypass'])){
							$datehr = '<b><i> Bypass </i></b>';
						}
						if($row['state'] == 'UALate'){
							$datehr = '<b><i><font color = "red"> Late Filed O.B. Request<br> Waiting for Approval</font></b>';
						}else{
							$datehr = "<b><i>Waiting for Approval";
						}
						echo '<td><b>'.$datehr. '</td>';
					}else{
						if($row['dateacc'] == 1){
							$chk = 'ACC';
						}else{
							$chk = 'HR';
						}
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						echo '<td><b>'.$chk.': '.$datehr. '</td>';
					}
					echo '<td width = "200">
							<a href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id']. $otbypass . $late . $ss .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
						</td><td id = "tohide">'.date("Y/m/d", strtotime($row['datefile'])) .'</td></tr>';
				}
			}
			if(isset($_GET['bypass'])){
				$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and (state = 'AHR' or state like 'UA%' or state = 'ReqCLeaHR') and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
			}else{
				$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and (state = 'AHR' or state = 'ReqCLeaHR' or state = 'UAAdmin') and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";	
			}
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['datefile']);
					$newDate = date("M j, Y h:i A", strtotime($originalDate));
					$datetoday = date("Y-m-d");
					$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
					$dateacc = date("M j, Y h:i A", strtotime($row['dateacc']));
					if($row['lealte'] > 0){
						$lealate = '<br><font color = "red"> <b> Late Filed </b></font><br>';
					}else{
						$lealate = "";
					}
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
					if($row['state'] == 'ReqCLeaHR'){
						$cancel = '<font color = "red"> Cancelation of Leave </font><p style = "text-decoration: line-through;">';
					}else{
						$cancel = "";
					}
					$query1 = "SELECT * FROM `nleave` where leave_id = '$row[leave_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();	
					echo '<td style="align: left !important;">'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';	
					echo '<td><b>'.$cancel.$row['typeoflea']. '</b><br>' .$othersl. '<b><i style = "color: green;"> '.$ftowork. ' </i>Fr: <font color = "green">'.date("M j, Y", strtotime($row['dateofleavfr'])).'</font><br>To: <font color = "green">'.date("M j, Y", strtotime($row['dateofleavto'])).'</font><br>Num days: <i><font color = "green">' .$row['numdays'].'</font></i><b></td>';
					echo '<td>'.$data1['reason'].'</td>';
					if($row['dateacc'] != ""){
						$datetech =  '<br>TECH: ' .date("M j, Y h:i A", strtotime($row['dateacc']));
					}else{
						$datetech = "";
					}
					if($row['leapay'] == 'wthoutpay'){
						$row['leapay'] = 'Payment: <font color = "red">w/o Pay';
					}else{
						$row['leapay'] = 'Payment: <font color = "green">w/ Pay';
					}
					if($row['state'] == 'ReqCLeaHR'){
						echo '<td> - </td>';
					}elseif($row['datehr'] == ""){
						$datehr = 'HR REQUEST';
						if(isset($_GET['bypass'])){
								$datehr = '<b><i> Bypass </i></b>';
							}
						if($row['state'] == 'UAAdmin'){
							$datehr = "Waiting for approval. <br><i> Scheduled Vacation Leave";
						}
						echo '<td><b>'.$lealate.$datehr. $datetech .'</td>';
					}else{						
						$datehr = date("M j, Y h:i A", strtotime($row['datehr']));
						echo '<td><b>HR: '.$datehr. $datetech . '<br>' . $row['leapay'] .'</td>';
					}
					if($row['state'] != 'ReqCLeaHR'){
						if($row['state'] == 'UAAdmin'){
							$sched = "&sched";
						}else{
							$sched = "";
						}
						echo '<td width = "200">
								<a href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id']. $otbypass . $sched .'"';?><?php echo'" class="btn btn-primary" role="button">Approve</a>
								<a href = "?approve=DA'.$_SESSION['level'].'&dleave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-primary" role="button">Disapprove</a>
							</td>';
					}else{
						echo '<td width = "200">
								<a href = "cancel-req.php?adlea=' . $row['leave_id'] . '" class = "btn btn-danger"> Approve </a>								
							</td>';
					}
					?>
					<td id = "tohide"><?php echo date("Y/m/d", strtotime($row['datefile']));?></td></tr>
					<?php
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
	if($_SESSION['pass'] == 'defaultpass'){
		include('up-pass.php');
	}
	?>
<?php include('footer.php');?>