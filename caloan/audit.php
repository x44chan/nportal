<div class="container-fluid" style="font-size: 13.5px;" align="center">
	<div class="row">
		<div class="col-xs-12" align="center">
			<h4> Aduit Trail </h4>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-1">
			<label><i><u> Username </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Name </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Transaction </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Date of Transaction </u></i></label>
		</div>
		<div class="col-xs-4">
			<label><i><u> Details </u></i></label>
		</div>
		<div class="col-xs-1">
			<label><i><u> Unit Name </u></i></label>
		</div>
	</div>
	<?php
		include 'conf.php';
		$stmtx = "SELECT * FROM audit_trail";
		$resultx = $conn->query($stmtx);		
		if($resultx->num_rows > 0){
			while ($row = $resultx->fetch_assoc()) {
	?>
			<div class="row" style="font-size: 12.5px;">
				<div class="col-xs-1">
					<?php echo $row['username']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo $row['realname']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo $row['transaction']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo date("M j, Y h:i:s", strtotime($row['datetrans'])); ?>
				</div>
				<div class="col-xs-4">
					<?php echo $row['transdetail']; ?>
				</div>
				<div class="col-xs-1">
					<?php echo $row['pcname']; ?>
				</div>
			</div>
	<?php
			}
		}

	?>
</div>