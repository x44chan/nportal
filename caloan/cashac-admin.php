<?php
		echo '<form action = "petty-exec.php" method = "post">';
		echo '<table align = "center" class = "table table-hover" style = "width: 65%; ">';
		echo '<thead><th colspan = 2><h2>Cash Advance</h2></th></thead>';
		include("conf.php");
		$pettyid = $_GET['cashadv_id'];
		$sql = "SELECT * from `cashadv`,`login` where login.account_id = cashadv.account_id and cashadv_id = '$pettyid' and state = 'UACA'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;"><b>Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['cadate'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Name: </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Reason: </td><td style = "width: 50%;">' . $row['careason'] .'</td></tr>';
				echo '<tr><td style = "width: 30%;"><b>Amount: </td><td style = "width: 50%;"><input class = "form-control" type = "text" value = "' . $row['caamount'] .'" name = "caamount" pattern = "[0-9]*"/></td></tr>';
				echo '<input type = "hidden" name = "cashadvid" value = '.$pettyid.'/>';
				echo '<input type = "hidden" name = "accid" value = '.$row['account_id'].'/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" name = "caapp">Approve Cash Advance</button> <a href = "admin.php" class = "btn btn-danger"> Back </a></td></tr>';
			}
			echo "</table></form>";
		}else{
			echo '<script type="text/javascript">window.location.replace("admin.php"); </script>';
		}
		

?>