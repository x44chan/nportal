<?php
	include 'conf.php';
	$sql = "SELECT * FROM cashadv,login where login.account_id = $accid and cashadv.account_id = $accid order by cadate desc";
	$result = $conn->query($sql);
?>
	<table class="table">
		<thead>
			<tr>
				<td colspan = 8 align = center><h2> Cash Advance Request Status </h2></td>
			</tr>
			<tr>
				<th width="15%">Date File</th>
				<th width="10%">Source</th>
				<th width="15%">Amount</th>
				<th width="40%">Reason</th>
				<th width="20%">State</th>
			</tr>
		</thead>
		<tbody>
<?php
	if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			echo '<tr><td>' . date("M j, Y", strtotime($row['cadate'])) . '</td>';
			echo '<td><b>Admin/Dep. Head</td>';
			echo '<td>₱ ' . number_format($row['caamount']) . '</td>';
			echo '<td>' . $row['careason'] . '</td>';
			echo '<td><b>';
				if($row['state'] == 'UACA'){
					echo 'Pending to Admin';
				}elseif($row['state'] == 'DACA'){
					echo '<font color = "red">Disapproved by the Admin</font>';
				}elseif($row['state'] == 'ACash'){
					echo '<a href = "petty-exec.php?cashadv='.$row['cashadv_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Cash Advance</a>';
				}elseif($row['state'] == 'ARcvCash'){
					echo '<font color = "green">Received</font><br>Code: '.$row['rcve_code'];
				}elseif($row['state'] == 'ACashReleased'){
					echo '<font color = "green">Completed</font>';
				}
			echo '</b></td>';
			echo '</tr>';
		}
	}
?>
		</tbody>
	</table>