<?php
		echo '<form action = "loan-exec.php" method = "post">';
		echo '<table align = "center" class = "table table-hover" style = "width: 65%; ">';
		echo '<thead><th colspan = 2><h2>Loan</h2></th></thead>';
		include("conf.php");
		$pettyid = $_GET['loan_id'];
		$sql = "SELECT * from `loan`,`login` where login.account_id = loan.account_id and loan_id = '$pettyid' and state = 'UALoan'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;"><b>Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['loandate'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Name: </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Reason: </td><td style = "width: 50%;">' . $row['loanreason'] .'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" value = "' . $row['loanamount'] .'" name = "loanamount"/></td></tr>';
				echo '<input type = "hidden" name = "loanid" value = '.$pettyid.'/>';
				echo '<input type = "hidden" name = "accid" value = '.$row['account_id'].'/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" name = "caapp">Approve Loan</button> <a href = "admin.php" class = "btn btn-danger"> Back </a></td></tr>';
			}
			echo "</table></form>";
		}else{
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		}
		

?>