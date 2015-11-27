<?php
	include 'conf.php';
	$sql = "SELECT * FROM cashadv,login where login.account_id = $accid and cashadv.account_id = $accid order by cadate desc";
	$result = $conn->query($sql);
?>
	<div align="center" style="margin-top: 40px;"><h3><i><u>Cash Advance Status</u></i></h3></div>
	<table class="table">
		<thead>
			<th width="20%">Date File</th>
			<th width="20%">Amount</th>
			<th width="40%">Reason</th>
			<th width="20%">State</th>
		</thead>
		<tbody>
<?php
	if($result->num_rows > 0){
		while ($row = $result->fetch_assoc()) {
			echo '<tr><td>' . date("M j, Y", strtotime($row['cadate'])) . '</td>';
			echo '<td>₱ ' . number_format($row['caamount']) . '</td>';
			echo '<td>' . $row['careason'] . '</td>';
			echo '<td><b>';
				if($row['state'] == 'UACA'){
					echo 'Pending to Admin';
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