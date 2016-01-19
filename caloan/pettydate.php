<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 30,
        	"order": [[ 0, "desc" ]]

    	});
	});
</script>
<?php
	include 'conf.php';
	$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id GROUP BY petty_liqdate.petty_id ORDER BY petty.date DESC";
	$result = $conn->query($sql);
?>
	<h2 align="center"> Petty Request Date Summary </h2>
	<table class="table" id = "myTabledate">
		<thead>
			<th> Petty # </th>
			<th> Name </th>
			<th> Petty Date </th>
			<th> Liquidation Date </th>
			<th> Completion Date </th>
			<th> Employee Validation Date </th>
		</thead>
		<tbody>
<?php
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$sql2 = "SELECT * FROM login where account_id = '$row[account_id]'";
			$data = $conn->query($sql2)->fetch_assoc();
			if($row['valdate'] == ""){
				$row['valdate'] = ' - ';
			}else{
				$row['valdate'] = date('M j, Y', strtotime($row['valdate']));
			}
			if($row['completedate'] == ""){
				$row['completedate'] = ' - ';
			}else{
				$row['completedate'] = date('M j, Y', strtotime($row['completedate']));
			}
			echo '<tr>';
			echo	'<td>' . $row['petty_id'] . '</td>';
			echo	'<td>' . $data['fname'] . ' ' . $data['lname'] . '</td>';
			echo	'<td>' . date('M j, Y', strtotime($row['date'])) . '</td>';			
			echo	'<td>' . date('M j, Y', strtotime($row['liqdate'])) . '</td>';
			echo	'<td>' . $row['completedate'] . '</td>';
			echo	'<td>' . $row['valdate'] . '</td>';
			echo '</tr>';
		}
	}
?>
		</tbody>
	</table>