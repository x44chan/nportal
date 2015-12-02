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
			$loan_id = $row['loan_id'];

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
			<div class="col-xs-3">
				<label>Approved Loan</label>
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row['appamount']); ?></p></i>
			</div>
		</div>
<?php
	$stmts3 = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and CURDATE() <= enddate";
	$result3 = $conn->query($stmts3);
	if($result3->num_rows > 0){
?>
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
<?php
		while ($row3 = $result3->fetch_assoc()) {
?>
		<div class="row">
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php echo date("M j, Y", strtotime($row3['cutoffdate'])); ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php echo $row3['duration']; ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;">₱ <?php echo number_format($row3['cutamount']); ?></p></i>
			</div>
			<div class="col-xs-3">
				<i><p style="margin-left: 10px;"><?php if($row3['state'] == 'DALoan'){echo '<b ><font color ="red">Disapproved </font></b>';}elseif($row3['state'] == 'UALoanCut'){echo '<b>Pending</b>';}else{ echo '<b><font color = "green">Approved</font></b>';}?></p></i>
			</div>
		</div>
<?php			

		}
	}
?>
<?php
	$stmts = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'CutOffPaid' and CURDATE() <= enddate";
	$data = $conn->query($stmts)->fetch_assoc();
	if($loan_id != $data['loan_id']){

?>
		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -20px;">Payment Details</h4></u></i>
				<hr>
			</div>
		</div>
		<div class="row">	
			<div class = "col-xs-2">
				<label>Duration</label>
				<select name = "duration" class="form-control" id = "duration" required>
					<option value = ""> ----------- </option>
					<option value = "1"> 1 Month </option>
					<option value = "2"> 2 Months </option>
					<option value = "3"> 3 Months </option>
					<option value = "Others"> Others </option>
				</select>
			</div>
			<div class="col-xs-2">
				<label>Others</label>
				<input type =  "text" maxlength="2" class="form-control" name = "others" disabled=""/>
			</div>
			<div class="col-xs-3">
				<label>Payment Start (Month)</label>
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
			<div class="col-xs-2">
				<label>Payment Start (Day)</label>
				<select class="form-control" name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="01">01</option>
					<option value="16">16</option>
				</select>
			</div>
			<div class="col-xs-3">
				<label>Payment Start (Year)</label>
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
				<?php if(isset($_GET['apploan'])){ echo '<a href = "?apploan" class = "btn btn-danger">Back</a>';}else{?>
				<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger">Back</a><?php }?>
			</div>
		</div>		
	</div>
	<input type = "hidden" name = "loanid" value = "<?php echo $row['loan_id'];?>"/>
	<input type = "hidden" name = "loanamnt" value = "<?php echo $row['loanamount'];?>"/>
	<input type = "hidden" name = "accid" value = "<?php echo $row['account_id'];?>"/>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#duration').change(function() {
	    var selected = $(this).val();
		
		if(selected == 'Others'){
			$('input[ name = "others" ]').attr('disabled',false);
			$('input[ name = "others" ]').attr("placeholder", "# of Months");
		}else{
			$('input[ name = "others" ]').attr('disabled',true);
			$('input[ name = "others" ]').attr("placeholder", "");
		}
	});
});
  /*$("button[ name = 'loancutoff'").click(function(){
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
});*/
</script>
<?php
			}else{
				$stmts2 = "SELECT * FROM `loan_cutoff` where loan_id = '$loan_id' and state = 'CutOffPaid' and CURDATE() <= enddate";
				$data2 = $conn->query($stmts2)->fetch_assoc();
?>

		<div class="row">
			<div class="col-xs-12">
				<i><u><h4 style="margin-left: -25px;">Loan Deduction</h4></u></i>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<label>Duration</label>
				<i>
				<?php 
					$len = substr($data2['duration'],0,2) * 2;
					$day = substr($data2['cutoffdate'], 8, 10);
					$cuts = 0;
					$fif = 0;
					for($i = 1; $i <= $len; $i++){
						$cuts = 15 * $i;
						$fif += 15;
						if($day == '16'){
							$day = '16';
							$end = 't';
						}else{
							$day = '1';
							$end = '15';
						}
						echo '<p style="margin-left: 10px;">'.date("M ".$day.", Y", strtotime('+'.$fif.' days', strtotime($data2['cutoffdate']))) .' - ' . date("M ".$end.", Y", strtotime('+'.$cuts.' days', strtotime($data2['cutoffdate']))).'</p>';
						if($day == '16'){
							$day = '1';
							$end = '15';
						}else{
							$day = '16';
							$end = 't';
						}
					} 

				?>
				</i>
			</div>
			<div class="col-xs-3">
				<label>Cutoff Amount</label>
				<i>
					<?php
						$len = substr($data2['duration'],0,2) * 2;
						for($i = 1; $i <= $len; $i++){
							echo '<p style="margin-left: 10px;">₱ '. number_format($data2['cutamount']).'</p>'; 
						}
						
					?>
				</i>
			</div>
			<div class="col-xs-3">
				<label>Loan Balance</label>
				<i>
					<?php
						$len = substr($data2['duration'],0,2) * 2;
						$day = substr($data2['cutoffdate'], 8, 10);
						$cuts = 0;
						$comp = $row['loanamount'];
						for($i = 1; $i <= $len; $i++){
							$cuts = 15 * $i;
							if($day == '16'){
								$day = '16';
								$end = 't';
							}else{
								$day = '01';
								$end = '15';
							}
							$date1  =	date("Y-m-".$day, strtotime("+15 days", strtotime($data2['cutoffdate'])));
							$date2	=	date("Y-m-".$end."", strtotime('+'.$cuts.' days', strtotime($data2['cutoffdate'])));
							if($date2 < date("Y-m-d")){
								$comp -= $data2['cutamount'];
								$prt = '₱ '. number_format($comp);
							}else{
								$prt = ' - ';
							}
							if($day == '16'){
								$day = '01';
								$end = '15';
							}else{
								$day = '16';
								$end = 't';
							}
						 
						
							
							echo '<p style="margin-left: 10px;">'.$prt.'</p>'; 
						}
						
					?>
				</i>
			</div>
			<div class="col-xs-3">
				<label>Status</label>
				<i>
				<?php 
					$len = substr($data2['duration'],0,2) * 2;
					$day = substr($data2['cutoffdate'], 8, 10);
					$cuts = 0;
					for($i = 1; $i <= $len; $i++){
						$cuts = 15 * $i;
						if($day == '16'){
							$day = '16';
							$end = 't';
						}else{
							$day = '01';
							$end = '15';
						}
						$date1  =	date("Y-m-".$day, strtotime("+15 days", strtotime($data2['cutoffdate'])));
						$date2	=	date("Y-m-".$end."", strtotime('+'.$cuts.' days', strtotime($data2['cutoffdate'])));
						if($date2 < date("Y-m-d")){
							echo '<p style = "margin-left: 10px;"><b><font color = "green"> Deducted </font></b></p>';
						}else{
							echo '<p style = "margin-left: 10px;"><b><font color = "red"> Pending </font></b></p>';
						}
						if($day == '16'){
							$day = '01';
							$end = '15';
						}else{
							$day = '16';
							$end = 't';
						}
					} 

				?>
				</i>
			</div>
			<div class="row">
				<div class="col-xs-12" align="center">
					<hr>
					<?php if(isset($_GET['apploan'])){ echo '<a href = "?apploan" class = "btn btn-danger">Back</a>';}else{?>
					<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger">Back</a><?php }?>
				</div>
			</div>
		</div>
<?php
			}
		}
	}else{
		echo '<script type="text/javascript">window.location.replace("?ac=penloan"); </script>';
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


?>