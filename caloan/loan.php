<?php
	include 'conf.php';
	$loan = mysql_escape_string($_GET['loan']);
	if($_SESSION['level'] != 'Admin'){
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid and loan_id = '$loan' order by loandate desc";
	}else{
		$accid = mysqli_real_escape_string($conn, $_GET['accid']);
		$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid and loan_id = '$loan' order by loandate desc";
	}
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
<style type="text/css">
	#loanss label{
		font-size: 13pt;
	}
	#loanss{
		font-size: 11pt;
	}
</style>
<form accept="" method="post"> 
	<div class="container" id = "loanss">
		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -20px;">Loan Details</h4></u></i>
				<hr>
			</div>
		</div>
<?php
		while ($row = $result->fetch_assoc()) {
			$loanamount = $row['loanamount'];
			$loan_id = $row['loan_id'];
?>
		<div class="row">
			<div class="col-xs-3">
				<label>Name</label>
				<i><p style="margin-left: 10px;"><?php echo $row['fname'] . ' ' . $row['lname']; ?></p></i>
			</div>
			<div class="col-xs-3">
				<label>Type</label>
				<i><p style="margin-left: 10px;">
					<?php 
						if($row['penalty'] == 1){
							echo ' Penalty Loan ';
						}else{
							echo ' Salary Loan';
						}
						echo '</h2></th></thead>';
					?>
			</div>
			<div class="col-xs-3">
				<label>Loan Amount</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['loanamount']); ?></p></i>
			</div>
			<div class="col-xs-3">
				<label>Approved Loan</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['appamount']); ?></p></i>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -20px;">Requested Cut-Offs Details</h4></u></i>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<label>Requested Start Date</label>
			</div>
			<div class="col-xs-3">
				<label>Duration</label>
			</div>
			<div class="col-xs-3">
				<label>Deduction per Cut-Off</label>
			</div>
			<div class="col-xs-3">
				<label>State</label>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php echo date("M j, Y", strtotime($row['startdate'])); ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php echo $row['duration']; ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['appamount']/($row['duration'] * 2), 2); ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php if($row['state'] == 'DALoan'){echo '<b ><font color ="red">Disapproved </font></b>';}elseif($row['state'] == 'UALoanCut'){echo '<b>Pending</b>';}else{ echo '<b><font color = "green">Approved</font></b>';}?></p></i>
			</div>
		</div>
<?php
		}
	}
	$stmtss = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' ORDER BY cutoff_id desc limit 1";
	$datas = $conn->query($stmtss)->fetch_assoc();
	if($_SESSION['level'] == 'Admin' && $datas['state'] != 'Full'){
?>
	<div class="row">
		<div class="col-xs-12">
			<i><u><h4 style="margin-left: -25px;">Advance Payment</h4></u></i>
			<hr>
		</div>
	</div>
<form action="" method="post">
	<div class="row">
		<div class="col-xs-6 col-xs-offset-3">
			<label>Number of Cut-Off</label>
			<input type = "number" required name = "numcutoff" class="form-control input-sm" placeholder = "Enter number of cut-off"/>			
		</div>
		<div class="col-xs-12" align="center">
			<br>
			<button class="btn btn-primary btn-sm" required onclick="return confirm('Are you sure?');" name = "cutoffsub"> Submit </button>
			<input type = "text" style="display: none;" value = "<?php echo $_GET['loan'];?>" name = "loan">
		</div>
	</div><br>
</form>
<?php
}
	if(isset($_POST['cutoffsub']) && $_SESSION['level'] == 'Admin'){
		for($i = 1; $i <= $_POST['numcutoff']; $i++){
			$full = date("Y-m-d");
			$loanid = $_POST['loan'];
			$stmt = "UPDATE `loan_cutoff` set 
				 state = 'Advance', full = '$full'
			where account_id = '$accid' and loan_id = '$loanid' and state = 'CutOffPaid' and CURDATE() <= enddate ORDER BY cutoff_id limit 1";
			if ($conn->query($stmt) === TRUE) {
				echo '<script type="text/javascript">window.location.replace("admin-emprof.php?loan='.$loanid.'&accid='.$accid.'"); </script>';
			}
		}
	}
		$stmts2 = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' order by cutoffdate asc";
		$result = $conn->query($stmts2);
		if($result->num_rows > 0){
?>

		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -25px;">Loan Deduction</h4></u></i>
				<hr>
			</div>
		</div>
		<div class="row" align="center">
			<div class="col-xs-3">
				<label>Duration</label>				
			</div>
			<div class="col-xs-3">
				<label>Cutoff Amount</label>				
			</div>
			<div class="col-xs-3">
				<label>Loan Balance</label>				
			</div>
			<div class="col-xs-3">
				<label>Status</label>				
			</div>
		</div>
<?php
	$count = 0; 
	while ($row = $result->fetch_assoc()) {
		$stmtss = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' ORDER BY cutoff_id desc limit 1";
		$datas = $conn->query($stmtss)->fetch_assoc();
		if($_SESSION['level'] == 'Admin' && $count < 1 && $row['state'] != 'Cancel' && $datas['state'] != 'Full'){
			$link = '
				<br><a onclick = "return confirm(\'Are you sure?\');" class = "btn btn-success btn-sm" href = "cancel-req.php?loanid='.$row['loan_id'].'&fullpay=' . $row['cutoff_id'] . '&accid='.$row['account_id'].'"> Full Payment </a>
				 <a onclick = "return confirm(\'Are you sure?\');" class = "btn btn-danger btn-sm" href = "cancel-req.php?loanid='.$row['loan_id'].'&canloan=' . $row['cutoff_id'] . '&accid='.$row['account_id'].'"> Move Loan </a>';
			$count = 1;
		}else{
			$link = "";
		}

?>
		<div class="row" align="center">
			<div class="col-xs-3" <?php if($row['state'] == 'Cancel'){ echo "style = 'text-decoration: line-through;'";} ?>>
				<p><i><?php echo date("M j, Y", strtotime($row['cutoffdate'])) . ' - ' . date("M j, Y", strtotime($row['enddate'])); ?></i></p>
			</div>
			<div class="col-xs-3" <?php if($row['state'] == 'Cancel'){ echo "style = 'text-decoration: line-through;'";} ?>>
				<p><i>₱ <?php echo number_format($row['cutamount'],2); ?></i></p>
			</div>
			<div class="col-xs-3" <?php if($row['state'] == 'Cancel'){ echo "style = 'text-decoration: line-through;'";} ?>>
				<p><i><?php if((date("Y-m-d") > $row['enddate'] && $row['state'] != 'Cancel') || ($row['state'] == 'Advance')){ $loanamount -= $row['cutamount'];echo '₱ '.number_format($loanamount,2); }elseif($row['state'] == 'Full'){ echo '₱ 0';} else { echo ' - '; } ?></i></p>
			</div>
			<div class="col-xs-3" <?php if($row['state'] == 'Cancel'){ echo "style = 'text-decoration: line-through;'";} ?>>
				<p><i><?php if($row['state'] == 'Advance'){ $count = 0; echo '<b><font color = "green"> Advanced Payment: ' .date("M j, Y", strtotime($row['full'])).' </font></b>'; }elseif($row['state'] == 'Full'){ echo '<b><font color = "green"> Fully Paid as of ' .date("M j, Y", strtotime($row['full'])).' </font></b>'; }elseif($row['state'] == 'Cancel'){ echo '<b><font color = "red"> Moved </font></b>';}elseif(date("Y-m-d") > $row['enddate']){ $count = 0; echo '<b><font color = "green">Deducted</font></b>'; }else{ echo '<b><font color = "red"> Pending </font></b>'.$link;} ?></i></p>
			</div>
		</div>
<?php 
	}
?>
			<div class="row">
				<div class="col-xs-12" align="center">
					<hr>
					<?php 
						if($_SESSION['level'] == 'Admin'){ 
							echo '<a href = "?loan" class = "btn btn-danger">Back</a>';
						}else{ 
							echo '<a href = "?ac='.$_GET['acc'].'" class = "btn btn-danger">Back</a>';
						}
					?>
				</div>
			</div>
		
<?php
			
	}else{
		if($_SESSION['level'] == 'Admin'){
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		}else{
			echo '<script type="text/javascript">window.location.replace("?ac=penloan"); </script>';
		}
		
	}

?>
<?php
	
	if(isset($_POST['loancutoff'])){
		if(isset($_POST['others'])){
			$duration = $_POST['others'] . ' Months';
			$dur = $_POST['others'];
		}else{
			$duration = $_POST['duration'] .' Months';
			$dur = $_POST['duration'];
		}
		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
		$enddate = date("Y-m-d", strtotime($duration, strtotime($date)));
		$cutamount = $_POST['loanamnt'] / ($dur * 2);
		$state = 'UALoanCut';
		$stmt = $conn->prepare("INSERT INTO `loan_cutoff` (loan_id, account_id, cutamount, cutoffdate, state, duration, enddate) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("iisssss", $_POST['loanid'], $_POST['accid'], $cutamount, $date, $state, $duration, $enddate);
		if($stmt->execute()){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?loan='.$_GET['loan'].'&acc='.$_GET['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?loan='.$_GET['loan'].'&acc='.$_GET['acc'].'"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?loan='.$_GET['loan'].'&acc='.$_GET['acc'].'");"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?loan='.$_GET['loan'].'&acc='.$_GET['acc'].'");"); </script>';
	    	}
		}
		

	}
if($_SESSION['level'] == 'Admin'){
	echo '</div>';
}

?>
