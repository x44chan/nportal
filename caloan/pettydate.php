<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 30,
        	"order": [[ 0, "desc" ]]

    	});
	});
</script>
<?php
	if(isset($_SESSION['dates'])){
		$date1 = $_SESSION['dates'];
		$date2 = $_SESSION['dates0'];
		$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));			
	}else{
		$date1 = date("Y-m-01");
		$date2 = date("Y-m-t");
		$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
	}
	if(isset($_POST['repfilter'])){
		$_SESSION['dates'] = mysql_escape_string($_POST['repfr']);
		$_SESSION['dates0'] = mysql_escape_string($_POST['repto']);
		echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?pettydate");</script>';
	}
	if(isset($_POST['represet'])){
		unset($_SESSION['dates']);
		unset($_SESSION['dates0']);
		echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?pettydate");</script>';
	}

?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i> Petty Date Filtering </i></u></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2 col-xs-offset-3" align="center" style="margin-top: -20px;">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['dates'])){ echo 'value = "'. $_SESSION['dates'] . '" '; }else{ echo ' value = "' .date("Y-m-01") . '" '; } ?> />
			</div>
			<div class="col-xs-2" align="center" style="margin-top: -20px;">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['dates0'])){ echo 'value = "'. $_SESSION['dates0'] . '" '; }else{ echo ' value = "' .date("Y-m-t") . '" '; } ?> />
			</div>
			<div class="col-xs-3" style="margin-top: -20px;">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="submit" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="margin-top: -20px;">
				<hr>
			</div>
		</div>
	</div>
</form>
<?php
	include 'conf.php';
	$sql = "SELECT * FROM `petty`,`login` where petty.account_id = login.account_id and date between '$date1' and '$date2' ORDER BY petty.date DESC";
	$result = $conn->query($sql);
?>
	<h2 align="center" style="margin-top: -20px;"> Petty Request Date Summary </h2>
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
			$sql23 = "SELECT * FROM petty_liqdate where petty_id = '$row[petty_id]'";
			$data3 = $conn->query($sql23)->fetch_assoc();
			if($data3['valdate'] == ""){
				$data3['valdate'] = ' - ';
			}else{
				$data3['valdate'] = date('M j, Y', strtotime($data3['valdate']));
			}
			if(!isset($data3['completedate'])){
				$data3['completedate'] = ' - ';
			}else{
				$data3['completedate'] = date('M j, Y', strtotime($data3['completedate']));
			}
			if(!isset($data3['liqdate'])){
				$data3['liqdate'] = ' - ';
			}else{
				$data3['liqdate'] = date('M j, Y', strtotime($data3['liqdate']));
			}
			echo '<tr>';
			echo	'<td>' . $row['petty_id'] . '</td>';
			echo	'<td>' . $data['fname'] . ' ' . $data['lname'] . '</td>';
			echo	'<td>' . date('M j, Y', strtotime($row['date'])) . '</td>';			
			echo	'<td>' . $data3['liqdate'] . '</td>';
			echo	'<td>' . $data3['completedate'] . '</td>';
			echo	'<td>' . $data3['valdate'] . '</td>';
			echo '</tr>';
		}
	}
?>
		</tbody>
	</table>