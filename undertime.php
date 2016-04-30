	<form role = "form"  align = "center"action = "oundrtime-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center"width = "60%">
				<tr>
					<td colspan = 3 align = center>
						<h2> Undertime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" class = "form-control" readonly name = "undate" value = "<?php echo date('F j, Y');?>"/></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "unename"/></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><input readonly value = "<?php echo $_SESSION['post'];?>" required class = "form-control" type = "text" name = "unpost"/></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input readonly value = "<?php echo $_SESSION['dept'];?>" required class = "form-control" type = "text" name = "undept"/></td>
				</tr>	
				<tr>
					<td>Reason: </td>
					<td><textarea required name = "unreason"class = "form-control" placeholder = "enter reason"></textarea></td>
					
				</tr>			
				<tr>
					<td>Date Of Undertime: </td>
					<td>
						<input required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "click to set date"	min = "<?php echo date('m/d/Y'); ?>" name = "undatereq"/>
					</td>						
				</tr>	
				<tr>
					<td> For Late Filing</td>
					<td>
						<select name="onleave" class="form-control">
							<option value="">--------</option>
							<option value="Sick Leave"> Sick Leave </option>
							<option value="Emergency Leave"> Emergency Leave </option>
							<option value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
						</select>
					</td>
				</tr>				
				<div class = "ui-widget-content" style = "border: none;">		
					<tr class = "form-inline">
						<td>Time of Undertime: </td>
						<td>
							<label for = "fr"> From: </label><input placeholder = "Click to Set time" required  autocomplete ="off" id = "to" class = "form-control"  name = "untimefr"/>
							<label for = "to"> To:  </label><input placeholder = "Click to Set time" required  autocomplete ="off" id = "fr" class = "form-control" name = "untimeto"/>
						</td>	
					</tr>			
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="untimeto"]').ptTimeSelect();
							$('input[name="untimefr"]').ptTimeSelect();
						});
					</script>
				</div>						
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" onclick = "return confirm('Are you sure? You can still review your application.');"  name = "unsubmit" class = "btn btn-default"/>
						<input type = "button" id = "hideout" name = "submit" class = "btn btn-default" value = "Cancel">
					</td>
				</tr>
			</table>
		</div>
	</form>
