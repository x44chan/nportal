<div class="container" style="margin-top: -30px;">
	<form action = "ot-exec.php" method="post" >
		<div class="row">
			<div class="col-xs-12"><u><i><h4>For Late Filing</h4></i><p style="font-size: 13px;"><i><font color = "red"> If your overtime is yesterday, apply it on the other form. </font></i></p></u><hr></div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<label>Name </label>
				<p style="margin-left: 10px"><i><?php echo $_SESSION['name'];?></i></p>
			</div>
			<div class="col-xs-3">
				<label>Date of Request<font color = "red"> * </font></label>
				<input class="form-control" required name = "dateofot" type="date" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD"/>
			</div>
			<div class="col-xs-2">
				<label>Start of OT <font color = "red"> * </font></label>
				<input class = "form-control" required name = "startofot" autocomplete ="off" placeholder = "Click to Set time"/>
			</div>
			<div class="col-xs-2">
				<label>End of OT <font color = "red"> * </font></label>
				<input class = "form-control" required name = "endofot" placeholder = "Click to Set time" autocomplete ="off" />
			</div>
			<div class="col-xs-2">
				<label>CSR #</label>
				<input class = "form-control" name = "csrnum" placeholder = "Enter CSR #" autocomplete ="off" />
			</div>
		</div>
		<hr/>
		<div class="row">
			<div id = "rday3">
				<div class="col-xs-2">
					<label>Official Sched. (Fr) <font color = "red"> * </font></label>
					<input placeholder = "Click to Set time" required autocomplete ="off" id = "reqto3"class = "form-control"  name = "officialworkschedfr"/>
				</div>
				<div class="col-xs-2">
					<label>Official Sched. (To) <font color = "red"> * </font></label>
					<input placeholder = "Click to Set time" required autocomplete ="off" class = "form-control" id = "reqfr3"  name = "officialworkschedto"/>					
				</div>
			</div>
			<div class="col-xs-3 pull-right">
				<label>OT Break (if applicable)<br> <i><font color = "red" style="font-size: 12px; margin-left: 15px;"> for less than 8 Hrs </font></i></label>
				<select class = "form-control" name = "otbreak">
					<option value ="">--------</option>
					<option value = "30 Mins">30 Mins</option>
					<option value = "1 Hour">1 Hour</option>
				</select>
			</div>
			<div class="col-xs-5 pull-right">
				<label>Description of Work Order <font color = "red"> * </font></label>
				<textarea required name = "reason" placeholder = "Enter your work order" class = "form-control col-sm-10"></textarea>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-4" style="margin-top: -20px;">
				<label for="restday3" style="font-size: 15px;"><input type="checkbox" value = "restday" name="restday" id="restday3"/> Rest Day</label>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-12" align="center">
				<u><i><p style="font-size: 13px;"><font color = "red"> If your overtime is yesterday, apply it on the other form. </font></p></i></u>
				<button class="btn btn-primary" name = "lateotsub" type="submit" onclick="return confirm('Are you sure?');"> Submit Request </button> <a href="index.php" class="btn btn-danger"> Back </a>
			</div>
		</div>
	</form>
</div>
