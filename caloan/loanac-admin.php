<?php
		echo '<form action = "loan-exec.php" method = "post">';
		echo '<table align = "center" class = "table table-hover" style = "width: 65%; ">';
		
		include("conf.php");
		$pettyid = $_GET['loan_id'];
		$sql = "SELECT * from `loan`,`login` where login.account_id = loan.account_id and loan_id = '$pettyid' and state = 'UALoan'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<thead><th colspan = 2><h2>';
					if($row['penalty'] == 1){
						echo ' Penalty Loan ';
					}else{
						echo ' Salary Loan';
					}
				echo '</h2></th></thead>';
				echo '<tr><td style = "width: 30%;"><b>Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['loandate'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Name: </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Reason: </td><td style = "width: 50%;">' . $row['loanreason'] .'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Loan Amount: </td><td style = "width: 50%;"><input autocomplete = "off" class = "form-control" pattern = "[0-9]*" type = "text" value = "' . $row['loanamount'] .'" name = "loanamount"/></td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Start Date: </b></td><td style = "width: 50%;">';
				echo '<label class = "pull-left">Cut Off Month</label><select name = "cutoffmonth" class = "form-control" required>';
					$datsss2 = substr($row['startdate'],5,2);
				?>	<option value=""> - - - - - - - </option>
					<option <?php if($datsss2 == '01'){echo ' selected ';}?> value="01">Jan</option>
					<option <?php if($datsss2 == '02'){echo ' selected ';}?> value="02">Feb</option>
					<option <?php if($datsss2 == '03'){echo ' selected ';}?> value="03">Mar</option>
					<option <?php if($datsss2 == '04'){echo ' selected ';}?> value="04">Apr</option>
					<option <?php if($datsss2 == '05'){echo ' selected ';}?> value="05">May</option>
					<option <?php if($datsss2 == '06'){echo ' selected ';}?> value="06">Jun</option>
					<option <?php if($datsss2 == '07'){echo ' selected ';}?> value="07">Jul</option>
					<option <?php if($datsss2 == '08'){echo ' selected ';}?> value="08">Aug</option>
					<option <?php if($datsss2 == '09'){echo ' selected ';}?> value="09">Sep</option>
					<option <?php if($datsss2 == '10'){echo ' selected ';}?> value="10">Oct</option>
					<option <?php if($datsss2 == '11'){echo ' selected ';}?> value="11">Nov</option>
					<option <?php if($datsss2 == '12'){echo ' selected ';}?> value="12">Dec</option>
				</select>
			<?php
				echo '<label class = "pull-left">Cut Off Day</label>';
				echo '<select name = "cutoffday" class = "form-control" required>';
				$datsss3 = substr($row['startdate'],8);
				$date = date("Y-m-d");
			?>
				<option value=""> - - - - - - - </option>
				<option value="01" <?php if($datsss3 == '01'){ echo ' selected '; }?>> 01 </option>
				<option value="16" <?php if($datsss3 == '16'){ echo ' selected '; } ?>> 16 </option>

			<?php
				echo '</select>';
				echo '<label class = "pull-left">Cut Off Year</label><select name = "cutoffyear" class = "form-control" required>';
				$datsss3 = substr($row['startdate'],0,4);
			?>
				<option value=""> - - - - - - </option>
				<option value="<?php echo date("Y"); ?>" <?php if($datsss3 == date("Y")){echo ' selected ';} ?>><?php echo date("Y"); ?></option>
				<option value='<?php echo date("Y", strtotime("next year")); ?>' <?php if($datsss3 == date("Y", strtotime("next year"))){echo ' selected ';} ?>><?php echo date("Y", strtotime("next year")); ?></option>
			<?php
				echo '</select>';
				echo '</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Duration (Months): </b></td><td style = "width: 50%;"><input maxlength = "2" autocomplete = "off" name = "upduration" type = "text" value = "' . substr($row['duration'], 0,2) . '" class = "form-control"/>';
				echo '<input type = "hidden" name = "loanid" value = '.$pettyid.'/>';
				echo '<input type = "hidden" name = "accid" value = '.$row['account_id'].'/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" name = "caapp">Approve Loan</button> <a href = "admin.php" class = "btn btn-danger"> Back </a></td></tr>';
			}
			echo "</table></form>";
		}else{
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		}
		

?>