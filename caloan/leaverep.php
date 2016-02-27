<script type="text/javascript">		
    $(document).ready( function () {
    <?php if(!isset($_GET['print'])){ ?>
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 30,
    		"bSort": false
    	});
    <?php } ?>
	});
</script>
<?php
	include 'conf.php';
	if($_SESSION['level'] == 'Admin'){
		$link = 'admin-emprof.php?leaverep';
	}elseif($_SESSION['level'] == 'ACC'){
		$link = 'accounting-petty.php?leaverep';
	}
	if(isset($_SESSION['dates'])){
		$date1 = $_SESSION['dates'];
		$date2 = $_SESSION['dates0'];	
	}else{
		$date1 = date("Y-m-01");
		$date2 = date("Y-m-t");
	}
	if(isset($_POST['repfilter'])){
		$_SESSION['dates'] = mysql_escape_string($_POST['repfr']);
		$_SESSION['dates0'] = mysql_escape_string($_POST['repto']);
		echo '<script type = "text/javascript">window.location.replace("'.$link.'");</script>';
	}
	if(isset($_POST['represet'])){
		unset($_SESSION['dates']);
		unset($_SESSION['dates0']);
		echo '<script type = "text/javascript">window.location.replace("'.$link.'");</script>';
	}
	if(isset($_GET['print'])) { 
		echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "'.$link.'";});</script>';
	}
	if(date("Y", strtotime($date1)) != date("Y", strtotime($date2))){
		$cutoffdate11 = date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
	}else{
		$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
	}
?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i> Date Filtering </i></u></h4>
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
			<div class="col-xs-12" style="margin-top: -20px; margin-bottom: -20px;">
				<hr>
			</div>
		</div>
	</div>
</form>
<?php
	$sql = "SELECT * FROM nleave,login where nleave.account_id = login.account_id and (state = 'AAdmin' or state = 'CheckedHR') and dateofleavfr BETWEEN '$date1' and '$date2' ORDER BY dateofleavfr DESC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
<br>
<div id = "reportg">
	<h2 align="center"> Leave Report <br></h2>
	<i><h4 align="center"> <?php if(isset($cutoffdate11)){ echo $cutoffdate11; }?> </h4></i>
	<table class="table" id = "myTabledate">
		<thead>
			<th width="15%"> Name </th>
			<th width="15%"> Type </th>
			<th width="15%"> Used Leave </th>
			<th width="20%"> Leave Date (From - To) </th>
			<th width="30%"> Leave Detais </th>
		</thead>
		<tbody>
<?php
		while ($row = $result->fetch_assoc()) {
			if($row['typeoflea'] == 'Others'){
				$row['typeoflea'] = $row['typeoflea'] . ': ' . $row['othersl'];
			}
			if(date("Y", strtotime($row['dateofleavfr'])) != date("Y", strtotime($row['dateofleavto']))){
				$leavedate = date("M j, Y", strtotime($row['dateofleavfr'])) . ' - ' . date("M j, Y", strtotime($row['dateofleavto']));
			}else{
				$leavedate = date("M j", strtotime($row['dateofleavfr'])) . ' - ' . date("M j, Y", strtotime($row['dateofleavto']));
			}
			echo	'<tr>';
				echo	'<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
				echo	'<td>' . $row['typeoflea'] . '</td>';
				echo	'<td>' . $row['numdays'] . '</td>';
				echo	'<td>' . $leavedate . '</td>';
				echo	'<td>' . $row['reason'] . '</td>';
			echo	'</tr>';
		}
	}
?>
		</tbody>
	</table>
</div>
<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?leaverep&print"><span id = "backs"class="glyphicon glyphicon-print"></span> Print/Save Report</a></div>