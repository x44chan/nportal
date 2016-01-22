<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 30,
        	"order": [[ 5, "desc" ]]

    	});
	});
</script>
<?php
	include 'conf.php';
	$sql = "SELECT * FROM `petty` where petreason like '%CALACA BATANGAS BPLS%' or petreason like '%GMA OMNICON%' or petreason like '%GENERAL TRIAS CAVITE PO-PB-15078%' or petreason like '%MUNTINLUPA PO-018700/018697%' ORDER BY date DESC";
	$result = $conn->query($sql);
?>
	<h2 align="center" style="margin-top: -20px;"> Expenses </h2>
	<table class="table" id = "myTabledate">
		<thead>
			<th width="10%"> Petty # </th>
			<th width="25%"> Name </th>
			<th width="13%"> Amount </th>
			<th width="12%"> Total Used </th>
			<th width="50%"> Project/Reason </th>
		</thead>
		<tbody>
<?php
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$sql2 = "SELECT * FROM login where account_id = '$row[account_id]'";
			$data = $conn->query($sql2)->fetch_assoc();
			
			$sql23 = "SELECT sum(liqamount) as sumliq,petty_liqdate.* from petty_liqdate where petty_id = '$row[petty_id]'";
			$data23 = $conn->query($sql23)->fetch_assoc();
			
			if(!is_numeric($row['amount'])){
				$row['amount'] = str_replace(',', "", $row['amount']);
			}else{
				$row['amount'] = $row['amount'];
			}
			/*if(strpos($row['petreason'], 'MUNTINLUPA PO-018700/018697')){
				$row['petreason'] = 'MUNTINLUPA PO-018700/018697';
			}elseif(strpos($row['petreason'], 'CALACA BATANGAS BPLS')){
				$row['petreason'] = 'CALACA BATANGAS BPLS';
			}elseif(strpos($row['petreason'], 'GENERAL TRIAS CAVITE PO-PB-15078')){
				$row['petreason'] = 'GENERAL TRIAS CAVITE PO-PB-15078';
			}elseif(strpos($row['petreason'], 'GMA OMNICON')){
				$row['petreason'] = 'GMA OMNICON';
			}*/
			echo '<tr>';
			echo	'<td>' . $row['petty_id'] . '</td>';
			echo	'<td>' . $data['fname'] . ' ' . $data['lname'] . '</td>';
			echo	'<td>' . number_format($row['amount'], 2) . '</td>';		
			echo	'<td>' . number_format($data23['liqamount'], 2) . '</td>';
			echo	'<td>' . $row['petreason'] . '</td>';
			echo '</tr>';
		}
	}
?>
		</tbody>
	</table>