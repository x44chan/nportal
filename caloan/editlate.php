<?php
	$oid = mysql_escape_string($_GET['edit_late']);
	$latefiling = "SELECT * FROM `overtime` where account_id = '$accid' and overtime_id = '$oid' and state = 'UALate'";
	$filing = $conn->query($latefiling)->fetch_assoc();
	$_SESSION['otid'] = $filing['overtime_id'];
	if($filing['overtime_id'] == null){
		echo '<script type = "text/javascript"> window.location.href = "employee.php?ac=penot" </script>';
	}

?>
<div class="container" style="margin-top: -30px;">
	<form action = "update-exec.php" method="post" >
		<div class="row">
			<div class="col-xs-12"><u><i><h4>For Late Filing</h4></i></u></div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<label>Name </label>
				<p style="margin-left: 10px"><i><?php echo $_SESSION['name'];?></i></p>
			</div>
			<div class="col-xs-3">
				<label>Date of Request<font color = "red"> * </font></label>
				<input class="form-control" required value = "<?php echo $filing['dateofot'];?>" name = "updateofot" type="date" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD"/>
			</div>
			<div class="col-xs-2">
				<label>Start of OT <font color = "red"> * </font></label>
				<input class = "form-control" required value = "<?php echo $filing['startofot'];?>" name = "uptimein" autocomplete ="off" placeholder = "Click to Set time"/>
			</div>
			<div class="col-xs-2">
				<label>End of OT <font color = "red"> * </font></label>
				<input class = "form-control" required value = "<?php echo $filing['endofot'];?>" name = "uptimeout" placeholder = "Click to Set time" autocomplete ="off" />
			</div>
			<div class="col-xs-2">
				<label>CSR #</label>
				<input class = "form-control" name = "csrnum" value = "<?php echo $filing['csrnum'];?>" placeholder = "Enter CSR #" autocomplete ="off" />
			</div>
		</div>
		<hr/>
		<?php 
			$count = strlen($filing['officialworksched']);
			if($count < 8){
				$ex1 = "";
				$ex2 = "";
			}else{
				$explode = explode(" - ", $filing['officialworksched']);
				$ex1 = $explode[0];
				$ex2 = $explode[1];
			}					
		?>
		<div class="row">
			<div id = "rday3" <?php if($filing['officialworksched'] == 'Restday'){ echo ' style = "display: none;" '; }?>>
				<div class="col-xs-2">
					<label>Official Sched. (Fr) <font color = "red"> * </font></label>
					<input placeholder = "Click to Set time"  <?php if($filing['officialworksched'] != 'Restday'){ echo ' required '; }?> autocomplete ="off" value = "<?php echo $ex1;?>"id = "reqto3"class = "form-control"  name = "upoffr"/>
				</div>
				<div class="col-xs-2">
					<label>Official Sched. (To) <font color = "red"> * </font></label>
					<input placeholder = "Click to Set time"  <?php if($filing['officialworksched'] != 'Restday'){ echo ' required '; }?>autocomplete ="off" value = "<?php echo $ex2;?>"class = "form-control" id = "reqfr3"  name = "upoffto"/>					
				</div>
			</div>
			<div class="col-xs-3 pull-right">
				<label>OT Break (if applicable)<br> <i><font color = "red" style="font-size: 12px; margin-left: 15px;"> for less than 8 Hrs </font></i></label>
				<select class = "form-control" name = "otbreak">
					<option value ="">--------</option>
					<option <?php if($filing['otbreak'] == "-30 Minutes"){ echo ' selected ';}?> value = "30 Mins">30 Mins</option>
					<option <?php if($filing['otbreak'] == "-30 Minutes"){ echo ' selected ';}?>value = "1 Hour">1 Hour</option>
				</select>
			</div>
			<div class="col-xs-5 pull-right">
				<label>Description of Work Order <font color = "red"> * </font></label>
				<textarea required name = "reason" placeholder = "Enter your work order" class = "form-control col-sm-10"><?php echo $filing['reason'];?></textarea>
			</div>
		</div>

		<div class="row" >
			<div class="col-xs-4" style="margin-top: -20px;">
				<label for="restday3" style="font-size: 15px;"><input type="checkbox" <?php if($filing['officialworksched'] == 'Restday'){ echo ' checked '; }?> value = "restday" name="uprestday" id="restday3"/> Rest Day</label>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-12" align="center">
				<button class="btn btn-primary" name = "lateotupsub" type="submit"> Submit Request </button> <a href="index.php" class="btn btn-danger"> Back </a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="uptimein"]').ptTimeSelect();
		$('input[name="uptimeout"]').ptTimeSelect();
		$('input[name="upoffr"]').ptTimeSelect();							
		$('input[name="upoffto"]').ptTimeSelect();
	});
</script>