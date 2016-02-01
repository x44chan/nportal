<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penpty'){
		include("conf.php");
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 9 align = center><h2> Pending Petty Request </h2></td>
					</tr>
					<tr>
						<th>Petty #</th>
						<th>Date File</th>
						<th>Name</th>
						<th>Particular</th>
						<th>Source</th>
						<th>Reference #</th>
						<th>Amount</th>
						<th width="20%">Reason</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['date']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				$petid = $row['petty_id'];
				if($row['state'] == 'AAPettyRep'){
					$transcode = $row['transfer_id'];
				}else{
					$transcode = "";
				}
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid' and petty_liqdate.liqstate = 'CompleteLiqdate'";
				$data = $conn->query($sql)->fetch_assoc();	
				if(date("Y-m-d") > date("Y-m-d", strtotime("+12 days", strtotime($row['date']))) && $data['liqstate'] == 'CompleteLiqdate'){
					continue;
				}
				echo 
					'<tr>
						<td>'.$row['petty_id'].'</td>
						<td>'.$newDate.'</td>
						<td>'.$row['fname'] . ' '. $row['lname'].'</td>
						<td>'.$row['particular'].'</td>
						<td>'.$row['source'].'</td>
						<td>'.$transcode.'</td>
						<td>&#8369; ';if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo '</td>
						<td>'.$row['petreason'].'
						<td>';
							if($row['state'] == "CPetty"){
								echo '<b><font color = "red">Canceled Petty</font></b>';
							}elseif($row['state'] == "UAPetty"){
								echo '<b>Pending to Admin <br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'AAAPettyReceive'){
								echo '<a href = "petty-exec.php?o='.$row['petty_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Petty</a>';
							}elseif($row['state'] == 'DAPetty'){
								echo '<b><font color = "red">Disapproved request';
							}elseif($row['state'] == 'AAPettyReceived'){
								echo '<font color = "green"><b>Received ';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPetty'){
								echo '<font color = "green"><b>Pending to Accounting</font>';
							}elseif($row['state'] == 'UATransfer'){
								echo '<b> Pending for Processing</b><br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'TransProc'){
								echo '<b><font color = "green"> Proccessed by the Accounting </font></b><br>';
								echo '<a href = "?getcode='.$row['petty_id'].'" class = "btn btn-success">Get Code</a>';
							}elseif($row['state'] == 'TransProcCode'){
								echo '<font color = "green"><b>For Admin Verification & Releasing';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPettyRep'){
								$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
								$data = $conn->query($sql)->fetch_assoc();
								if($data['petty_id'] == null){
									echo '<a class = "btn btn-danger" href = "?lqdate=' . $row['petty_id'] . '"/> To Liquidate </a>';
								}elseif($data['liqstate'] == 'EmpVal'){
									echo '<font color = "green"><b>Liquidated</font><br>';
									echo '<a href = "?validate=' . $petid . '" class = "btn btn-success">Validate Code</a>';
								}elseif($data['liqstate'] == 'CompleteLiqdate'){
									echo '<font color = "green"><b>Completed</font>';
								}elseif($data['liqstate'] == 'LIQDATE'){
									echo '<b>Pending Completion</b><br>';
									echo '<a href = "?editliqdate='.$row['petty_id'].'" class = "btn btn-danger"> Edit Liquidation </a>';
								}elseif($data['liqstate'] == 'AdmnApp'){
									echo '<b>Pending Completion <br> App by Admin</b><br>';
									echo '<a href = "?editliqdate='.$row['petty_id'].'" class = "btn btn-danger"> Edit Liquidation </a>';
								}
							}
				echo '</td></tr>';

		}
		
	}echo '</tbody></table></form>';$conn->close();
}
if(isset($_GET['editpetty']) && $_GET['editpetty'] > 0){
	include("caloan/editpetty.php");
}
if(isset($_GET['editliqdate']) && $_GET['editliqdate'] > 0){
	include("caloan/editliqdate.php");
}
if(isset($_GET['getcode'])){
	$code = mysql_escape_string($_GET['getcode']);
	$sql = "UPDATE `petty` set state = 'TransProcCode' where petty_id = '$code' and state = 'TransProc'";
	if($conn->query($sql) == TRUE){
	 	if($_SESSION['level'] == 'EMP'){
    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'ACC') {
    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'TECH') {
    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'HR') {
    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
    	}
	}
}
?> 
