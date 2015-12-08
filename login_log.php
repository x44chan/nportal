<style type="text/css">
	#tbloginLog_length{
		display: none;
	}
</style>
<?php
	$date = date("M j, Y");
	include 'conf.php';
	$loginlog = "SELECT * FROM `login`,`login_log` where login.account_id = login_log.account_id and (level = 'TECH' or level = 'HR') and datetime like '%$date%' order by login_log_id desc";
	$result = $conn->query($loginlog);
?>
<div class="container">
	<i> <h4> HR and Tech. Supervisor Login Log for <?php echo $date; ?></h4> </i>
	<table class="table" id = "tbloginLog">
		<thead>
			<th>Name</th>
			<th>Login Log</th>
			<th>Log Type</th>
		</thead>
		<tbody>
<?php
		if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			if($row['logintype'] == 'in'){
				$row['datetime'] = $row['datetime'];
			}else{
				$row['datetime'] = $row['datetime'];
			}
?>
			<tr>
				<td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
				<td><?php echo $row['datetime'];?></td>
				<td><b><i><?php if($row['logintype'] == 'in'){echo "<font color = 'green'>Login</font>";} else { echo '<font color = "red">Logout</font>'; }?></td>
			</tr>
<?php
		}
	}
?>	
		</tbody>
	</table>
</div>