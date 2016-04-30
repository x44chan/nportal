<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penhol'){
		include("conf.php");
		$sql = "SELECT * FROM holidayre,login where login.account_id = $accid and holidayre.account_id = $accid order by state ASC";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 9 align = center><h2> Holiday Request Status </h2></td>
					</tr>
					<tr>
						<th width="10%">Date File</th>
						<th width="10%">Name</th>
						<th width="10%">Date of Request</th>
						<th width="17%">Type</th>
						<th width="20%">Reason</th>
						<th width="15%">Status</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
?>
			<tr>
				<td><?php echo date("M j, Y", strtotime($row['datefile'])); ?></td>
				<td><?php echo $row['fname'] . ' '. $row['lname']; ?></td>
				<td><?php echo date("M j, Y", strtotime($row['holiday'])); ?></td>
				<td><?php echo $row['type']; ?></td>
				<td><?php echo $row['reason']; ?></td>
				<td><b>
				<?php
					if($row['state'] == 0){
						echo 'Pending to HR';
						echo '<br><a href = "?acc=penhol&update=1&o=' . $row['holidayre_id'] . '" class = "btn btn-danger"> Edit Application </a>';
					}elseif($row['state'] == 1){
						echo 'Pending to Admin<br>';
						echo '<font color = "green"> Approved  by HR </font>';
					}elseif($row['state'] == 2){
						echo '<font color = "green"> Approved  by Dep. Head </font>';
					}elseif($row['state'] == -1){
						echo '<font color = "red"> Disapproved by HR </font>';
						echo '<br>' . $row['dareason'];
						echo '<br>Date: <i>' . $row['datehr'];
					}elseif($row['state'] == -2){
						echo '<font color = "red"> Disapproved by Dep. Head </font>';
						echo '<br>' . $row['dareason'];
					}
				?>
				</b></td>
<?php
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