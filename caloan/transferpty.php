<?php
		echo '<form action = "" method = "post">';
		echo '<table align = "center" class = "table table-hover" style = "width: 65%;">';
		echo '<thead>
				<th colspan = 2>
					<h2>Petty Transfer</h2><br>
					<font color = "red"><h5><i>Make sure that you have already emailed the processed file before you click the submit button.</i></h5>
				</th>
			  </thead>';
		include("conf.php");
		$pettyid = $_GET['petty_id'];
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid' and state = 'UATransfer'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">' . $row['particular'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;">₱ '; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'</td></tr>';
				echo '<tr><td style = "width: 30%;">Reason: </td><td style = "width: 50%;">'.$row['petreason'].'</td></tr>';
				if($row['particular'] == "Check"){ echo '<tr><td>Check #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				echo '<input class = "form-control" type = "hidden" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); };echo'"/></td></tr>';
		
				echo '<input name = "appart" value = "' . $row['particular'] . '" type="hidden"/>';
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitrans">Submit</button><br><br><a href = "accounting-petty.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';
			}
		}else{
			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		}
		echo "</table></form>";
if(isset($_POST['submitrans'])){
	$acctrans = date("Y-m-d g:i A");
	function random_string($length) {
	    $key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));

	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }

	    return $key;
	}
	$code = random_string(4);
	$petid = mysql_escape_string($_POST['petty_id']);
	$sql = "UPDATE `petty` set acctrans = '$acctrans', state = 'TransProc', rcve_code = '$code' where petty_id = '$petid' and state = 'UATransfer'";
	if($conn->query($sql) == TRUE){
		savelogs("Approve Petty Transfer", "Petty #: " . $petid);
		echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';	
	}
}