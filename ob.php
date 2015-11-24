	<form role = "form"  align = "center"action = "ob-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center">
				<tr>
					<td colspan = 3 align = center>
						<h2> Official Business Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" style = "width: 350px;" class = "form-control" readonly name = "obdate" value = "<?php echo date('F j, Y - hA');?>"/></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "obename"/></td>
				</tr>
				<tr>
					<td>ID No: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['acc_id'];?>" readonly name = "idnum"/></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><input required class = "form-control" type = "text" name = "obpost"/></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input required class = "form-control" type = "text" name = "obdept"/></td>
				</tr>
				<tr>
					<td>Date Of Official Business: </td>
					<td><input required class = "form-control" type = "date" min = "<?php echo date('m/d/Y'); ?>" name = "obdatereq"/></td>
				</tr>				
				<tr>
					<td>Description of Work Order: </td>
					<td><textarea required name = "obreason" class = "form-control"></textarea></td>
					<td></td>
				</tr>
				<div class = "ui-widget-content"style = "border: none;" >
				<tr>
					<td>Time In: </td>
					<td>
						<input required class = "form-control" readonly name = "obtimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>Time Out: </td>
					<td><input required class = "form-control" readonly name = "obtimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>				
				<tr class = "form-inline">
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  name = "obofficialworkschedfr"/>
						<label for = "to">To:</label><input readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  name = "obofficialworkschedto"/>
					</td>
					
				</tr>
				<script type="text/javascript">
					$(document).ready(function(){
						$('input[name="obtimein"]').ptTimeSelect();
						$('input[name="obofficialworkschedto"]').ptTimeSelect();
						$('input[name="obofficialworkschedfr"]').ptTimeSelect();							
						$('input[name="obtimeout"]').ptTimeSelect();
					});
				</script>
				</div>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "submit" class = "btn btn-default"/>
					
						<input type = "button" id = "hideob" name = "submit" class = "btn btn-default" value = "Cancel">
					</td>
				</tr>
			</table>
		</div>
	</form>
