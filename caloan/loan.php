<?php
	include 'conf.php';
	$loan = mysql_escape_string($_GET['loan']);
	$sql = "SELECT * FROM loan,login where login.account_id = $accid and loan.account_id = $accid and loan_id = '$loan' order by loandate desc";
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

?>


		<div class="row">
			<div class="col-xs-3">
				<label>Name</label>
				<i><p style="margin-left: 10px;"><?php echo $row['fname'] . ' ' . $row['lname']; ?></p></i>
			</div>
			<div class="col-xs-3">
				<label>Loan Amount</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['loanamount']); ?></p></i>
			</div>
			<?php
				$loan_id = $row['loan_id'];
				$stmts5 = "SELECT sum(cutamount) as cutamount FROM `loan_cutoff` where loan_id = '$loan_id' and state != 'DALoan'";
				$datas5 = $conn->query($stmts5)->fetch_assoc();
				if($datas5['cutamount'] > 0){
			?>
			<div class="col-xs-3">
				<label>Requested Payment</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($datas5['cutamount']); ?></p></i>
			</div>
			<?php
				}
			?>
			<?php
				$loan_id = $row['loan_id'];
				$stmts = "SELECT sum(cutamount) as cutamount FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'CutOffPaid' and CURDATE() >= cutoffdate";
				$data = $conn->query($stmts)->fetch_assoc();
				if($data['cutamount'] > 0){
			?>
			<div class="col-xs-3">
				<label>Total Paid Amount</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($data['cutamount']); ?></p></i>
			</div>
			<?php
				}
			?>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -20px;">Payment Details</h4></u></i>
				<hr>
			</div>
		</div>
		<div class="row">		
			<div class="col-xs-3">
				<label>Amount</label>
				<input type = "text" class="form-control" name = "cutoffamount" required placeholder = "Enter Amount of Request"/>
			</div>
			<div class="col-xs-3">
				<label>Cut-Off Month</label>
				<select class="form-control" name = "cutoffmonth" required >
					<option value="">-----------</option>
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
			</div>
			<div class="col-xs-3">
				<label>Cut-Off Day</label>
				<select class="form-control" name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="01">01</option>
					<option value="16">16</option>
				</select>
			</div>
			<div class="col-xs-3">
				<label>Year</label>
				<select class="form-control" required name = "cutofyr">
					<option value=""> - - - - - - - </option>
					<option value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
					<option value="<?php echo date("Y", strtotime("+1 year"));?>"><?php echo date("Y", strtotime("+1 year"));?></option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" align="center">
				<button class="btn btn-primary" name = "loancutoff" id = "submitss">Submit Request</button>
				<a href = "employee.php?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger">Back</a>
			</div>
		</div>
		<?php
			$stmts = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state != 'DALoan'";
			$results = $conn->query($stmts);
			if($results->num_rows > 0){
	?>
		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -20px;">Loan Payment Details</h4></u></i>
				<hr>
			</div>
		</div>
	<?php
				while ($rows = $results->fetch_assoc()) {
	?>

		<div class="row">
			<div class="col-xs-3">
				<label>Request Date</label>
				<i><p style="margin-left: 10px;"><?php echo date("M j, Y", strtotime("+1 day", strtotime($rows['cutoffdate']))); ?></p></i>
			</div>
			<div class="col-xs-3">
				<label>Request Amount</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($rows['cutamount']); ?></p></i>
			</div>
			<div class="col-xs-3">
				<label>Status</label>

				<i><p style="margin-left: 10px;"><?php if($rows['cutoffdate'] <= date("Y-m-d") && $rows['state'] == 'CutOffPaid'){echo '<font color = "green"><b> Deducted </b></font></p></i>';}else{echo '<font color = "red"><b> Pending for Next Cutoff </b></font></p></i>';}?>
			</div>
		</div>
	<?php
				}
			}

		?>
		
		
	</div>
	<input type = "hidden" name = "loanid" value = "<?php echo $row['loan_id'];?>"/>
	<input type = "hidden" name = "accid" value = "<?php echo $row['account_id'];?>"/>
</form>
<script type="text/javascript">
  $("button[ name = 'loancutoff'").click(function(){
     var requested = <?php if(!isset($datas5['cutamount'])){ $datas5['cutamount'] = 0; } echo $datas5['cutamount'];?>;
     var loan = <?php echo $loanamount;?>;
     var inputamnt = $("input[ name = 'cutoffamount']").val();
     var sub = loan - requested;

     if(inputamnt > sub){
     	alert("	Input correct amount. ");
     	return false;
     }else{
     	confirm("Are you sure?");
     }
});
</script>
<?php
		}
	}else{
		echo '<script type="text/javascript">window.location.replace("employee.php"); </script>';
	}
?>
<?php
	
	if(isset($_POST['loancutoff'])){
		if($_POST['cutoffday'] == '01'){
			$_POST['cutoffmonth'] -= 1;
			$date1 = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'];
			$date = date("Y-m-t", strtotime($date1));
		}else{
			$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-15';
		}
		$state = 'UALoanCut';
		$stmt = $conn->prepare("INSERT INTO `loan_cutoff` (loan_id, account_id, cutamount, cutoffdate, state) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("iisss", $_POST['loanid'], $_POST['accid'], $_POST['cutoffamount'], $date, $state);
		if($stmt->execute()){
			echo '<script type="text/javascript">window.location.replace("employee.php?loan='.$_GET['loan'].'&acc='.$_GET['acc'].'"); </script>';
		}


	}


?>