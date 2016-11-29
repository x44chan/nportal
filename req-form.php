<script type="text/javascript">
$(document).ready(function(){
	// jQuery plugin to prevent double submission of forms
	jQuery.fn.preventDoubleSubmission = function() {
	  $(this).on('submit',function(e){
	    var $form = $(this);

	    if ($form.data('submitted') === true) {
	      // Previously submitted - don't submit again
	      e.preventDefault();
	    } else {
	      // Mark it so that the next submit can be ignored
	      $form.data('submitted', true);
	    }
	  });

	  // Keep chainability
	  return this;
	};
	$('form').preventDoubleSubmission();
});
</script>

<?php
	$_SESSION['exec'] = 0;
	if(isset($_GET['late_filing'])){
		echo '<div id = "latefiling">';
		include 'caloan/latefiling.php';
		echo '</div>';
	}
	if((stristr($_SESSION['post'], 'sales') !== false) || stristr($_SESSION['post'], 'marketing') !== false || $_SESSION['level'] == "ACC"){
		if(isset($_GET['expn'])){
			include 'caloan/expn.php';
			echo '</div>';
			if(isset($_GET['print'])){
				exit;
			}
		}
	}
?>

<div id = "offb" style = "margin-top: -30px; display: none; min-height: 600px; height: calc(100%);">
	<form role = "form"  align = "center"action = "ob-exec.php" method = "post">
		<div class = "form-group">
			<table width = "60%" align = "center" class="table-responsive">
				<tr>
					<td colspan = 3 align = center>
						<h2> Official Business Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text"  class = "form-control" readonly name = "obdate" value = "<?php echo date('F j, Y h:i A');?>"/></td>
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
					<td><input readonly value = "<?php echo $_SESSION['post'];?>"required class = "form-control" type = "text" name = "obpost"/></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input readonly value = "<?php echo $_SESSION['dept'];?>"required class = "form-control" type = "text" name = "obdept"/></td>
				</tr>
				<tr>
					<td>Date Of Official Business: </td>
					<td><input required class = "form-control" type = "date" placeholder = "Click to set date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "obdatereq"/></td>
				</tr>				
				<tr>
					<td>Description of Work Order: </td>
					<td><textarea required name = "obreason" placeholder = "Enter your work order" class = "form-control col-sm-10"></textarea></td>
					<td></td>
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
				<tr>					
					<td style="float: right;">
						<label for="restday2" style="font-size: 15px;"><input type="checkbox" value = "restday" name="restday" id="restday2"/> Rest Day</label>
					</td>
				</tr>	
				<tr class = "form-inline" >
					<td>Work Sched: <font color = "red">*</font></td>
					<td style="float:left;">
						<label for = "fr">From:</label><input placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" id = "reqto"class = "form-control"  name = "obofficialworkschedfr"/>
						<label for = "to">To:</label><input placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" class = "form-control" id = "reqfr"  name = "obofficialworkschedto"/>
					</td>					
				</tr>
				<tr>
					<td colspan="2"><label><input name = "nxtday" value = "nxtday" type = "checkbox"/> For next day out <br>( ex. Date of OB Nov 11, 16 (2pm- 12am) , 12am out is good as Nov 12, 16 (- 12 am) ) </td>
				</tr>	
				<tr id = "warning" style="display: none;">
					<td></td>
					<td>
						<div class="alert alert-danger fade in">
						  <strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b>
						</div>
					</td>
				</tr>
				<script type="text/javascript">
					$(document).ready(function(){
					//	$('input[name="obtimein"]').ptTimeSelect();
						$('input[name="obofficialworkschedto"]').ptTimeSelect();
						$('input[name="obofficialworkschedfr"]').ptTimeSelect();							
					//	$('input[name="obtimeout"]').ptTimeSelect();
						$("#submits").click(function(){
							if($("#reqto").val() == "" && $("#reqfr").val() == ""){
								$("#warning").show();
								return false;							
							}
						});
						$("#reqto").click(function(){
							$("#warning").hide();
						});
						$("#reqfr").click(function(){
							$("#warning").hide();
						});

					});
				</script>
				</div>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "submit" id = "submits" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-default"/>					
						<input type = "button" id = "hideob" name = "submit" class = "btn btn-default" value = "Cancel">
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>
<div id = "undertime"style = "margin-top: -30px; display: none; height: calc(100%);">
	<?php include('undertime.php'); ?>
</div>
<div id = "formhidden"style = "margin-top: -30px;display: none; min-height: 700px; height: calc(100%);" >
	<form role = "form"  align = "center"action = "ot-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center" width="60%" class="table-responsive">
				<tr>
					<td colspan = 2 align = center>
						<h2> Overtime Request </h2>
					</td>
				</tr>
				<tr style = "display: none;">
					<td colspan = 2 align = center>
						<h5><p style = "font-style: italic; color: red;">No over 30 Minutes or less than 30 Minutes. (Counted Overtime is 30 Minutes or Hour/s Only)<br>6:00 PM - 8.50 PM (Counted Overtime: 2 Hours and 30 Minutes)	</h5>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" class = "form-control" readonly name = "datefile" value = "<?php echo date('F j, Y h:i A');?>"/></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "nameofemployee"/></td>
				</tr>
				<tr>
					<td>Date Of Overtime : <font color = "red">*</font></td>
					<td><input style="width: 100%;" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "dateofot"/></td>
				</tr>	
				<tr>
					<td>CSR #: </td>
					<td><input class = "form-control" type = "text" autocomplete = "off" placeholder = "Enter CSR Number" name = "csrnum"/></td>
				</tr>			
				<tr>
					<td>Reason (Work to be done): <font color = "red">*</font></td>
					<td><textarea required placeholder = "Enter your work to be done" name = "reason"class = "form-control"></textarea></td>
				</tr>
				<tr>
					<td> Type: <font color = "red">*</font></td>
					<td>
						<select required class="form-control" name = "ottype">
		          			<option value=""> Select ( P.M / Internet / Project / Luwas / Netlink)  </option>
		          			<option value="P.M."> P.M. </option>
		          			<option value="Internet"> Internet </option>
		          			<option value="Project"> Project </option>
		          			<option value="Support"> Project Support </option>
		          			<option value="Oncall"> Oncall </option>
		          			<option value="Corporate"> Corporate </option>
		          			<option value="Luwas"> Luwas </option>
		          			<option value="Netlink"> Netlink </option>	
						</select>
					</td>
				</tr>
				<tr style = "display: none;" id = "otoncall">
            		<td><label>Internet <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otoncall">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr style = "display: none;" id = "otcorpo">
            		<td><label>Corporate <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otcorpo">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Corporate' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
            	<tr style = "display: none;" id = "otproject">
            		<td><label>Location <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "loc" onchange="showUser(this.value,'proj','')">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT loc FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					echo '<option value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr style = "display: none;" id = "otsupport">
            		<td><label>Location <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "locx" onchange="showUser(this.value,'','sup')">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsqlx = "SELECT loc FROM `project` where type = 'Support' and state = '1' group by loc order by CHAR_LENGTH(name)";
		            			$xresultx = $conn->query($xsqlx);
		            			if($xresultx->num_rows > 0){
		            				while($xrowx = $xresultx->fetch_assoc()){
		            					echo '<option value = "' . $xrowx['loc'] . '"> ' . $xrowx['loc'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr id = "loc">
		        	
		        </tr>
		        <tr style = "display: none;" id = "otpm">
            		<td><label>P.M. <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otpm">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr style = "display: none;" id = "otinternet">
            		<td><label>Internet <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otinternet">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1' order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
				<tr><div class = "ui-widget-content" style = "border: none;" >
					<td>Start (Time of OT): <font color = "red">*</font></td>
					<td>
						<input required onkeydown="return false;" class = "form-control"  name = "startofot" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>					
				</tr>				
				<tr>
					<td>End (Time of OT): <font color = "red">*</font></td>
					<td><input required onkeydown="return false;" class = "form-control" name = "endofot" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>	
				<tr>
					<td>OT Break ( if applicable <font color = "red" style="font-size: 12px;"><i>*for less than 8 hrs *</i></font>):<br> <font color = "red" style="font-size: 12px;"><i>Automatic <b>1 Hr</b> Deduction for more than 8 OT Hrs.</i></font>  </td>
					<td>
						<select class = "form-control" name = "otbreak" id = "otbreak">
							<option value ="">--------</option>
							<option value = "30 Mins">30 Mins</option>
							<option value = "1 Hour">1 Hour</option>
						</select>
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
				<tr>					
					<td colspan="2">
						<label for="restday" style="font-size: 15px; margin-left: 20px;"><input type="checkbox" value = "restday" name="restday" id="restday"/> Rest Day </label>
						<label for="oncall" style="font-size: 15px; margin-left: 20px;"><input type="checkbox" value = "oncall" name="oncall" id = "oncall"/> Oncall </label>
						<label for="sw" style="font-size: 15px; margin-left: 20px;"><input type="checkbox" value = "sw" name="sw" id = "sw"/> Special N-W Holliday </label>
						<label for="lg" style="font-size: 15px; margin-left: 20px;"><input type="checkbox" value = "lg" name="lg" id = "lg"/> Legal Holliday </label>
					</td>
				</tr>	
				<tr class = "form-inline" >
					<td>Official Work Sched: <font color = "red">*</font></td>
					<td style="float:left;">
						<label for = "fr">From:</label><input onkeydown="return false;" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  name = "officialworkschedfr"/>
						<label for = "to" style="margin-left: 5px;">To:</label><input onkeydown="return false;" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  name = "officialworkschedto"/>
					</td>					
				</tr>	
										
				<script type="text/javascript">
					$(document).ready(function(){
						$('input[name="startofot"]').ptTimeSelect();
						$('input[name="officialworkschedto"]').ptTimeSelect();
						$('input[name="officialworkschedfr"]').ptTimeSelect();							
						$('input[name="endofot"]').ptTimeSelect();
					});
				</script>
				</div>
				<tr>
					<td colspan = 2 align = center><input type = "submit" name = "otsubmit" class = "btn btn-default"/><input type = "button" id = "hideot" name = "submit" class = "btn btn-default" value = "Cancel"></td>
				</tr>
			</table>
		</div>
	</form>
</div>

<div id = "leave" style = "margin-top: -30px; display: none;">
<?php
	include('conf.php');
	$accid = $_SESSION['acc_id'];
	$sql = "SELECT * from `login` where account_id = '$accid' and empcatergory = 'Regular'";
	$result = $conn->query($sql);
	$datey = date("Y");
	$availsick = 0;
	$totavailvac = 0;
	if($result->num_rows > 0){		
		while($row = $result->fetch_assoc()){
			$cstatus = $row['ecstatus'];
			$accidd = $row['account_id'];
			$egender = $row['egender'];
			if(date("Y") == 2015){	
				$sl = $row['sickleave'] - $row['usedsl'];
				$vl = $row['vacleave'] - $row['usedvl'];
				$usedsl = $row['usedsl'];
				$usedvl = $row['usedvl'];
			}else{				
				$leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and CURDATE() BETWEEN startdate and enddate and state = 'AAdmin'";
				$datalea = $conn->query($leaveexec)->fetch_assoc();
				$sl = $datalea['sleave'];
				$vl = $datalea['vleave'];
				$usedsl = 0;
				$usedvl = 0;
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Sick Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$availsick = $sl - $row1['count'];
					$scount = $row1['count'];						
					}
			}		
			if($scount == null){
				$scount = " - ";
			}			
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Vacation Leave'  and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$availvac = $vl - $row1['count'];
					$count = $row1['count'];
					}
			}		
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Other%' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$totavailvac = $availvac - $row1['count'];
					$count = $row1['count'];
					}
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Paternity Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$patternity = 7 - $row1['count'];
					$count = $row1['count'];
					}
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Wedding Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$wedding = 7 -  $row1['count'];
					$count = $row1['count'];
					}
			}				
			
		}
	}
	if($_SESSION['category'] == 'Regular'){
			$quarterdate = array();
			$date1=date_create($datalea['startdate']);
			$date2=date_create($datalea['enddate']);
			$diff=date_diff($date1,$date2);
			$months = $diff->format("%m");
			if($months > 9 && $months <= 12){
				$months = ceil($vl / 4);
				$quarter = 4;
			}elseif($months > 6 && $months <= 9){
				$months = ceil($vl / 3);
				$quarter = 3;
			}elseif($months > 3 && $months <= 6) {
				$months = ceil($vl / 2);
				$quarter = 2;
			}elseif($months > 0 && $months <= 3){
				$months = $vl;
				$quarter = 1;
			}
			$plus = 0;
			for($i = 1; $i <= $quarter; $i++){
				if($i > 1){
					$plus += 3;
				}else{
					$plus = 0;
				}
				$quarterdate[] = date("Y-m-d",strtotime('+'.$plus.' month', strtotime($datalea['startdate'])));
			}
			$xcount = array();
			$chan = array();
			$quar = array();
			for($i = 0; $i < $quarter; $i++){
				if($i == ($quarter - 1)){
					$two = date("Y-12-31");
				}else{
					$plus1 = $i+1;
					$two = date("Y-m-t",strtotime("-1 month",strtotime($quarterdate[$plus1])));
				}
				$one = $quarterdate[$i];
				if(date("Y-m-d") > $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accid' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					if($counter['count'] == ""){
						$months += ($months-1);
					}elseif($counter['count'] < $months){
						$months += ($months-$counter['count']);
					}
				}
				if(date("Y-m-d") >= $one && date("Y-m-d") <= $two){
					$sql = "SELECT sum(numdays) as count from nleave where account_id = '$accid' and (typeoflea = 'Vacation Leave' or typeoflea = 'Others') and state = 'AAdmin' and dateofleavfr BETWEEN '$one' and '$two' and leapay = 'wthpay'";
					$counter = $conn->query($sql)->fetch_assoc();
					$xcount[] = $counter['count'];
				}else{
					continue;
				}
			}
			for($i = 0; $i < $quarter; $i++){
				if(!isset($xcount[$i])){
					continue;
				}				
				if($xcount[$i] > $months) {
					$wthpay = 'withoutpay';
				}elseif(($months - $xcount[$i]) <= 0){
					$wthpay = 'withoutpay';
				}else{
					$wthpay = null;
				}
				if(stristr($sql, '2016-12-31') == true){
					$wthpay = null;
				}

			}
		}

?>
	<form role = "form"  align = "center"action = "oleave-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center" width = "60%" class="table-responsive">
				<tr>
					<td colspan = 3 align = center>
						<h2> Leave Request </h2>
					</td>
				</tr>
				<tr>
					<td colspan = 3 align = center>
						<h5><p style = "font-style: italic; color: red;">For scheduled leave, submit Leave request to Human Resources Department seven(7) days prior to leave date. </h5>
					</td>
				</tr>		
				<tr class = "form-inline" >
					<td>Type of Leave</td>
					<td align = "left">
						<select style = "width: 60%; float: left;" required class="form-control" id = "typeoflea" name="typeoflea">
							<option value = ""> ---- </option>							
							<option value = "Sick Leave">Sick Leave</option>
							<option value = "Vacation Leave">Vacation Leave</option>
							<?php //if($patternity > 0 && $egender == "Male"){ echo '<option value = "Paternity Leave">Paternity Leave </option>'; }?>
							<?php //if($wedding  > 0 && $cstatus != 'Married' && $egender == "Female"){ echo '<option value = "Wedding Leave">Wedding Leave </option>'; echo $cstatus;}?>
							<option value = "Others">Others(Pls. Specify)</option>
						</select>						
						<input disabled type = "text" name = "othersl" class = "form-control" id = "othersl" style = "width: 40%;"/>
					</td>
				</tr>	
				<div style = "display: none;">
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "nameofemployee"/></td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" class = "form-control" readonly name = "datefile" required value = "<?php echo date('F j, Y h:i A');?>"/></td>
				</tr>				
				<tr>
					<td>Date Hired: </td>
					<td><input readonly value = "<?php echo date('F j, Y', strtotime($_SESSION['datehired'])); ?>"type = "text" class = "form-control"  name = "datehired" required /></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input readonly value = "<?php echo $_SESSION['dept'];?>" type = "text" class = "form-control"  name = "deprt" required/></td>
				</tr>
				<tr>
					<td>Position Title: </td>
					<td><input readonly value = "<?php echo $_SESSION['post'];?>" type = "text" class = "form-control"  name = "posttile" required/></td>
				</tr>
				<tr>
					<td colspan = 3 align = "center">
					<h3>LEAVE DETAILS</h3>
				</tr>
				<tr>
					<td align = center>	Sick Leave Balance: </td>
					<td><input readonly="" id = "sickleave" value = "<?php echo $availsick;?>" class = "form-control"/></td>
				</tr>	
				<tr>
					<td align = center>	Vacation Leave Balance: </td>
					<td><input readonly="" id = "vacleave" value = "<?php echo $totavailvac;?>" class = "form-control"/></td>
				</tr>
				<tr>
					<td abbr="center">V.L. Balance for this Quarter</td>
					<td><input readonly="" id = "vacleave" value = "<?php if($totavailvac >= $months){ echo $months-$xcount[0]; }elseif(isset($xcount[0]) && $months-$xcount[0] <= 0){echo  $months-$xcount[0];}else{ echo $totavailvac;}?>"class = "form-control"/></td>
				</tr>
				<tr class = "form-inline">
					<td>Inclusive Dates: </td>
					<td style="float:left;">
						From: <input required class = "form-control" type = "date" placeholder = "Click to set date"required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "dateofleavfr"/>
						To: <input required class = "form-control" type = "date" placeholder = "Click to set date"required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "dateofleavto"/>
						Number of Days: <input maxlength = "3" style = "width: 90px;"type = "text" pattern = '[0-9.]+' required name = "numdays"class = "form-control"/>
					</td>
				</tr>					

				<tr>
					<td>Reason: </td>
					<td><textarea placeholder = "Enter leave reason" class = "form-control" name = "leareason"required></textarea></td>
				</tr>
				<tr>
					<td colspan = 4 align = center><input type = "submit" name = "leasubmit" class = "btn btn-default"/><input type = "button" onclick = "$('#leave').hide(); $('#dash').show(); location.reload();" id = "hideot" name = "submit" class = "btn btn-default" value = "Cancel"></td>					
				</tr>
			</table>
		</div>
	</form>
</div>
  <!-- Modal -->
  <div class="modal fade" id="petty" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Petty Cash Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
              <label for="usrname"> Particular <font color = "red">*</font></label>
              <select name = "particularpet" required class = "form-control">
             	<option value = "">----------</option>
              	<option value = "Cash">Cash</option>
              	<option value = "Check">Check</option>
              	<option value = "Transfer">Transfer</option>
              	<option value = "Auto Debit">Auto Debit</option>
              </select>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[.0-9,]*" id = "petamount" required name = "amountpet" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
          		<label>Type <font color = "red">*</font></label>
          		<select class="form-control" name = "pettype" required>
          			<option value=""> Select ( P.M / Internet / Project / Others)  </option>
          			<option value="P.M."> P.M. </option>
          			<option value="Internet"> Internet </option>
          			<option value="Project"> Project </option>
          			<option value="Support"> Project Support </option>
          			<option value="Oncall"> On-Call </option>
          			<option value="Combined"> P.M. & Internet </option>
          			<option value="Corporate"> Corporate </option>
          			<option value="Luwas"> Luwas </option>
          			<option value="Supplier"> Supplier </option>
          			<option value="Netlink"> Netlink </option>
          			<?php if($_SESSION['acc_id'] == '37') {  ?>
	      			<option value="House"> House </option>
	      			<?php } ?>
          		</select>
          	</div>
          	<div style = "display: none;" class="form-group" id = "project">
            	<label>Project <font color = "red">*</font></label>
            	<select class="form-control" name = "loc" onchange="showUserx(this.value,'proj','')">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			$loc = "";
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					$xsql2 = "SELECT loc FROM `project` where type = 'Project' and name = '$row[project]'";
            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
            						$selecteds = ' selected ';		            						
            						$loc = $xresult2['loc'];
            					}else{
            						$selecteds = "";
            					}

            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "support">
            	<label>Project Support <font color = "red">*</font></label>
            	<select class="form-control" name = "locx" onchange="showUserx(this.value,'','sup')">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Support' and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			$loc = "";
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					$xsql2 = "SELECT loc FROM `project` where type = 'Project' and name = '$row[project]'";
            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
            						$selecteds = ' selected ';		            						
            						$loc = $xresult2['loc'];
            					}else{
            						$selecteds = "";
            					}

            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div class="form-group" id = "locx">
	        </div>
            <div style = "display: none;" class="form-group" id = "pm">
            	<label>P.M. <font color = "red">*</font></label>
            	<select class="form-control" name = "pm">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "corpo">
            	<label>Corporate <font color = "red">*</font></label>
            	<select class="form-control" name = "corpo">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Corporate' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "supp">
            	<label>Supplier <font color = "red">*</font></label>
            	<select class="form-control" name = "supp">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Supplier' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "internet">
            	<label>Internet <font color = "red">*</font></label>
            	<select class="form-control" name = "internet">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "oncallxx">
            	<label>On Call <font color = "red">*</font></label>
            	<select class="form-control" name = "xoncall">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <div style = "display: none;" class="form-group" id = "combined">
            	<label>P.M. & Internet <font color = "red">*</font></label>
            	<select class="form-control" name = "combined">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Combined' and state = '1' order by CHAR_LENGTH(name)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					echo '<option value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
            <?php if($_SESSION['acc_id'] == '37') {  ?>
            <div style = "display: none;" class="form-group" id = "house">
            	<label>House <font color = "red">*</font></label>
            	<select class="form-control" name = "house">
            		<option value = ""> - - - - - </option>
            		<option value = "GROCERIES"> GROCERIES </option>
            		<option value = "FOODS"> FOODS </option>
            		<option value = "REPRESENTATION"> REPRESENTATION </option>
            		<option value = "MEDICINES"> MEDICINES </option>
            		<option value = "ANIMALS"> ANIMALS </option>
            		<option value = "HARDWARE"> HARDWARE </option>
            		<option value = "DEPARTMENT STORE"> DEPARTMENT STORE </option>
            		<option value = "PARKING"> PARKING </option>
            	</select>
            </div>
            <?php } ?>
          	<div class="form-group">
            	<label for="usrname"> Reason <font color = "red">*</font></label>
            	<textarea id = "petamount" required name = "petreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason"></textarea>
          	</div>
              <button type="submit" name = "submitpet" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
<?php
	if(isset($_POST['submitpet']) && isset($_SESSION['acc_id'])){
		$acc_id = mysql_escape_string($_SESSION['acc_id']);
		$particularpet = mysql_escape_string($_POST['particularpet']);
		$amountpet = mysql_escape_string($_POST['amountpet']);
		if($particularpet == 'Transfer'){
			$state = 'UATransfer';	
		}else{
			$state = 'UAPetty';
		}
		$datefile = date("Y-m-d");
		$sql = "SELECT * FROM petty,login where login.account_id = '$acc_id' and login.position != 'House Helper' and petty.account_id = '$acc_id' and (petty.state != 'DAPetty' and petty.state != 'CPetty') order by state ASC, source asc";
		$result = $conn->query($sql);
		$count = 0;
		$day5 = 0;
		$projectcount = 0;
		if($result->num_rows > 0){	
			while($row = $result->fetch_assoc()){
			$petid = $row['petty_id'];
			$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
			$data = $conn->query($sql)->fetch_assoc();
				if($data['petty_id'] == null){
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink'  || $row['projtype'] == 'Auto Debit' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier'){
						$projectcount += 1;
					}
					
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
				}
				if($data['liqstate'] == 'LIQDATE'){
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink' || $row['projtype'] == 'Auto Debit' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier'){
						$projectcount += 1;
					}
				}
				if($data['liqstate'] == 'EmpVal'){
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink' || $row['projtype'] == 'Auto Debit' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier'){
						$projectcount += 1;
					}
				}
		   }
		}
		
		if(isset($_POST['pettype'])){
			if($_POST['pettype'] == 'Project' || $_POST['pettype'] == 'Support'){
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['otproject'];
			}elseif($_POST['pettype'] == 'Corporate'){
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['corpo'];
			}elseif($_POST['pettype'] == 'Supplier'){
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['supp'];
			}elseif($_POST['pettype'] == 'P.M.'){
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['pm'];
			}elseif($_POST['pettype'] == 'Internet'){
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['internet'];
			}elseif($_POST['pettype'] == 'Uplink'){
				$_POST['project'] = null;
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'House'){
				$_POST['project'] = $_POST['house'];
			}elseif($_POST['pettype'] == 'Combined'){
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['combined'];
			}elseif($_POST['pettype'] == 'Oncall'){
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$_POST['project'] = $_POST['xoncall'];
			}else{
				$_POST['project'] = null;
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}	
		}
		if($_SESSION['level'] == 'ACC' && $particularpet == 'Auto Debit'){
			$count = 0;
		}
		if($acc_id == '23'){
			$count = 0;
		}
		if($count > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
		}else{
			if($_POST['pettype'] == "" || ( ($_POST['pettype'] == 'Project' || $_POST['pettype'] == 'Support') && empty($_POST['project']))){
				if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">alert("Empty");window.location.replace("employee.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">alert("Empty");window.location.replace("accounting.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">alert("Empty");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">alert("Empty");window.location.replace("hr.php?ac=penpty"); </script>';
		    	}
				break;
			}
			if($_POST['pettype'] == 'Others'){
				$_POST['project'] = null;
			}
			$stmt = $conn->prepare("INSERT INTO petty (`account_id`,`date`, `particular`, `amount`, `state`, `petreason`, `project`, `projtype`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("isssssss", $acc_id, $datefile, $particularpet, $amountpet, $state, $_POST['petreason'], $_POST['project'], $_POST['pettype']);
			$stmt->execute();		
			if($_SESSION['level'] == 'EMP'){
				//include 'savelogs.php';  
				//$_POST['pettype'] .= ' ' . $_POST['project'];
				//savelogs("Request Petty", "Particular: " . $particularpet . ', Amount: ' . $amountpet . ', Petty Type: ' . $_POST['pettype']);
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
			$conn->close();
		}

	}
?>

  <!-- caModal -->
  <div class="modal fade" id="cashadv" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Cash Advance Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[0-9]*" required name = "amountca" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Reason <font color = "red">*</font></label>
            	<textarea required name = "careason" class ="form-control" autocomplete = "off" placeholder = "Enter reason"></textarea>
          	</div>
              <button type="submit" name = "submitca" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
  <!-- caModal -->
  <div class="modal fade" id="holiday" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Holiday Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Description of Work Order (optional)</label>
            	<textarea class="form-control" name = "reason" placeholder = "Enter reason"></textarea>
          	</div>
          	<div class="form-group">
          		<label>Date <font color = "red">*</font></label>
          		<input required class = "form-control" type = "date" placeholder = "Click to set date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "holiday"/>
          	</div>
          	<div class="form-group">
          		<label>Type <font color = "red">*</font></label>
          		<select class="form-control" required name = "type">
          			<option value="">----------</option>
          			<option value="Legal Holiday"> Legal Holiday </option>
          			<option value="Special N-W Holiday"> Special N-W Holiday </option>
          		</select>
          	</div>
          	<div class="form-group">
          		<label>Late Filing</label>
          		<select name="onleave" class="form-control">
					<option value="">--------</option>
					<option value="Sick Leave"> Sick Leave </option>
					<option value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
				</select>
          	</div>
            <button type="submit" name = "submithol" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
  <?php
  	if(isset($_POST['submithol']) && $_POST['holiday'] != "" && $_POST['type']){
  		if(date("D") == 'Mon'){
			$minus = '+3 days';
		}else{
			$minus = '+1 days';
		}
  		if(date("Y-m-d") == date("Y-m-d",strtotime("-1 day",strtotime($_POST['holiday']))) || date("Y-m-d") == $_POST['holiday'] || date("Y-m-d") <= date("Y-m-d",strtotime($minus,strtotime($_POST['holiday'])))){
  			$restrict = 0;
  		}else{
  			$restrict = 1;
  		}
  		if($_POST['onleave'] != ""){
  			$restrict = 0;
  			$_POST['reason'] .= '<br><b><i>('. $_POST['onleave'] . ')</i></b>';
  		}
  		$stmt = $conn->prepare("INSERT INTO holidayre (account_id, datefile, holiday, type, reason) VALUES (?, now(), ?, ?, ?)");
  		$stmt->bind_param("isss", $_SESSION['acc_id'], $_POST['holiday'], $_POST['type'], $_POST['reason']);
	  	if($restrict == 0 && isset($_SESSION['acc_id'])){
	  		if($stmt->execute()){
	  			if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penhol"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penhol"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penhol"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penhol"); </script>';
		    	}
	  		}
	  	}else{
	  		if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("Wrong date");window.location.replace("employee.php?ac=penhol"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("Wrong date");window.location.replace("accounting.php?ac=penhol"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("Wrong date");window.location.replace("techsupervisor.php?ac=penhol"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("Wrong date");window.location.replace("hr.php?ac=penhol"); </script>';
	    	}
	  	}
  	}
  ?>
 <script type="text/javascript">
  $("button[name='submitca']").click(function(){
     if($("input[name='amountca']").val() > <?php if($_SESSION['category'] == 'Regular'){ echo ' 3000 '; }else{ echo ' 1500 '; } ?> ){
               alert("You can't request more than ₱ <?php if($_SESSION['category'] == 'Regular'){ echo '3,000'; }else{ echo '1,500'; } ?>.");
               return false;
     }
});
 </script>
<?php
	if(isset($_POST['submitca']) && isset($_SESSION['acc_id']) && !empty($_POST['amountca'])){
		$date = date("Y-m-d");
		if($date > date('Y-m-16')){
			$date = date("Y-m-01", strtotime("next month"));
			$date2 = date("Y-m-16");
		}else{
			$date = date("Y-m-16");
			$date2 = date("Y-m-01");
		}
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and ( ( loan.state != 'DALoan' ) and (CURDATE() <= enddate and loan_cutoff.state != 'Full' and loan_cutoff.state != 'Cancel' and loan_cutoff.state != 'Advance') )";
		$resquery = $conn->query($query);
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate <= '$date' and cadate >= '$date2'";
		$resquery2 = $conn->query($query2);
		if($resquery2->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("employee.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("hr.php?ac=penca"); </script>';
	    	}
		}elseif($resquery->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("employee.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
		}else{
			$datefile = date("Y-m-d");
			$state = 'UACA';
			$stmt = $conn->prepare("INSERT INTO cashadv (`account_id`,`cadate`, `caamount`, `careason`, state) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("issss", $_SESSION['acc_id'], $datefile, $_POST['amountca'], $_POST['careason'], $state);
			$stmt->execute();		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penca"); </script>';
	    	}
			$conn->close();
		}
	}

?>
<?php 
	if($_SESSION['category'] == "Regular"){ ?>
<?php
	if(isset($_POST['loanpet']) && isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		$state = "UALoan";
		$loandate = date("Y-m-d"); 
		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and ( ( loan.state != 'DALoan' ) and (CURDATE() <= enddate and loan_cutoff.state != 'Full' and loan_cutoff.state != 'Cancel' and loan_cutoff.state != 'Advance') ) and (penalty = '1' or penalty is null or penalty = '2')";
		$resquery = $conn->query($query);
		$sqlxx = "SELECT * FROM loan where account_id = '$accid' and state = 'UALoan' and (penalty = '1' or penalty is null)";
		$resqueryxx = $conn->query($sqlxx);
		$date = date("Y-m-d");
		if($date <= date('Y-m-08', strtotime('-1 month'))){
			$date = date("Y-m-23");
			$date2 = date("Y-m-07");
		}else{
			$date = date("Y-m-08");
			$date2 = date("Y-m-22");
		}
		$xsql = "SELECT * FROM login where account_id = '$_SESSION[acc_id]'";
		$limita = $conn->query($xsql)->fetch_assoc();
		if(date("Y-m-d") <= date("Y-m-d",strtotime('+1 years', strtotime($limita['regdate'])))){
			$limit = ($_SESSION['salary'] * .4);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+1 years', strtotime($limita['regdate']))) && date("Y-m-d") <= date("Y-m-d",strtotime('+2 years', strtotime($limita['regdate'])))){
			$limit = ($_SESSION['salary'] * .6);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+2 years', strtotime($limita['regdate']))) && date("Y-m-d") <= date("Y-m-d",strtotime('+4 years', strtotime($limita['regdate'])))){
			$limit = ($_SESSION['salary'] * .7);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+4 years', strtotime($limita['regdate'])))){
			$limit = ($_SESSION['salary'] * .9);
		}
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate < '$date' and cadate > '$date2'";
		$resquery2 = $conn->query($query2);
		if($limit < $_POST['loanamount']){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You can\'t request more than ₱ ' . number_format($limit). '");window.location.replace("employee.php?ac=penloan"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You can\'t request more than ₱ ' . number_format($limit). '");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You can\'t request more than ₱ ' . number_format($limit). '");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You can\'t request more than ₱ ' . number_format($limit). '");window.location.replace("hr.php?ac=penloan"); </script>';
	    	} 
		}elseif($_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'] < date("Y-m-d", strtotime('-1 month'))){
			echo '<script type="text/javascript">alert("Wrong date.");window.location.replace("employee.php?ac=penloan"); </script>';
		}elseif(($resquery->num_rows > 0) || ($resqueryxx->num_rows > 0)){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("employee.php?ac=penloan"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
		}elseif($resquery2->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("employee.php?ac=penca"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("hr.php?ac=penca`"); </script>';
	    	}
		}else{
			if($_POST['loanduration'] == 'Others'){
				$duration = $_POST['loanothers'] . ' Months';
			}else{
				$duration = $_POST['loanduration'] . ' Months';
			}	   		
	   		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
			$sql = $conn->prepare("INSERT INTO `loan` (account_id, loanamount, loanreason, state, loandate, duration, startdate) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$sql->bind_param("issssss", $accid, $_POST['loanamount'], $_POST['loanreason'], $state, $loandate, $duration, $date);
			if($sql->execute()){
				if($_SESSION['level'] == 'EMP'){
	    			echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
		    	}
			}
		}

	}

?>
	<?php
		$xsqlx = "SELECT * FROM login where account_id = '$_SESSION[acc_id]'";
		$limitax = $conn->query($xsqlx)->fetch_assoc();
		if(date("Y-m-d") <= date("Y-m-d",strtotime('+1 years', strtotime($limitax['regdate'])))){
			$limitx = ($_SESSION['salary'] * .4);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+1 years', strtotime($limitax['regdate']))) && date("Y-m-d") <= date("Y-m-d",strtotime('+2 years', strtotime($limitax['regdate'])))){
			$limitx = ($_SESSION['salary'] * .6);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+2 years', strtotime($limitax['regdate']))) && date("Y-m-d") <= date("Y-m-d",strtotime('+4 years', strtotime($limitax['regdate'])))){
			$limitx = ($_SESSION['salary'] * .7);
		}elseif(date("Y-m-d") > date("Y-m-d",strtotime('+4 years', strtotime($limitax['regdate'])))){
			$limitx = ($_SESSION['salary'] * .9);
		}
	?>
   <!-- loanModal -->
  <div class="modal fade" id="loan" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Loan Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font> (Max Allowed Loan: <?php echo number_format($limitx);?>)</label>
            	<input type = "text" pattern = "[0-9]*" required name = "loanamount" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Reason <font color = "red">*</font></label>
            	<textarea id = "petamount" required name = "loanreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason"></textarea>
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Duration <font color = "red">*</font></label>
            	<select name = "loanduration" class="form-control" id = "loanduration" required>
					<option value = ""> ----------- </option>
					<option value = "1"> 1 Month </option>
					<option value = "2"> 2 Months </option>
					<option value = "3"> 3 Months </option>
					<option value = "Others"> Others </option>
				</select>
            </div>
            <div class="form-group">
            	<label>Others</label>
				<input type =  "text" maxlength="2" class="form-control" name = "loanothers" disabled=""/>
            </div>
            <div class="form-group">
				<label>Payment Start (Month)</label>
				<select class="form-control" name = "cutoffmonth" required >
					<option value="">-----------</option>
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Day)</label>
				<select class="form-control" name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="23">23-07 (next month)</option>
					<option value="08">08-22</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Year)</label>
				<select class="form-control" required name = "cutofyr">
					<option value=""> - - - - - - - </option>
					<option value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
					<option value="<?php echo date("Y", strtotime("+1 year"));?>"><?php echo date("Y", strtotime("+1 year"));?></option>
				</select>
			</div>
			<div class="form-group">
				<p style="color: red; font-size: 12px;">
					<b>Example: <br>
						Your Payment Start is Feb 23 - 07(next month) <br>
						Deduction Date Feb 13, 2016</b>
				</p>
			</div>
              <button type="submit" name = "loanpet" id = "loanpet" onclick="return confirm('Are you sure?');" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
  
  <script type="text/javascript">
	$(document).ready(function(){
		$('#loanduration').change(function() {
		    var selected = $(this).val();			
			if(selected == 'Others'){
				$('input[ name = "loanothers" ]').attr('disabled',false);
				$('input[ name = "loanothers" ]').attr("placeholder", "# of Months");
			}else{
				$('input[ name = "loanothers" ]').attr('disabled',true);
				$('input[ name = "loanothers" ]').attr("placeholder", "");
			}
		});
		$('#loanpet').click(function(){
			if($("input[name='loanamount']").val() > <?php echo str_replace(",", "", number_format($limitx)); ?> ){
               alert("You can't request more than ₱ <?php echo number_format($limitx);?>.");
               return false;
    		}
		});
	});
</script>
 <?php } ?>
<script type="text/javascript">
$(document).ready(function(){
	    $('#restday2').change(function(){
	    if($('#restday2').is(":checked")){ 	        
	    	$("#rday2").hide();
	    	$("#reqto").attr('required',false);
	    	$("#reqfr").attr('required',false);
	    }else{
	    	$("#to").attr('required',true);
	    	$("#fr").attr('required',true);
	        $("#rday2").show();
	    }
	});
	$('#restday3').change(function(){
	    if($('#restday3').is(":checked")){ 	        
	    	$("#rday3").hide();
	    	$("#reqto3").attr('required',false);
	    	$("#reqfr3").attr('required',false);
	    	$("#reqfr3").val("");
	    	$("#reqto3").val("");

	    }else{
	    	$("#reqto3").attr('required',true);
	    	$("#reqfr3").attr('required',true);
	        $("#rday3").show();
	    }
	});
});
</script>
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshims.setOptions('forms-ext', {types: 'date'});
  webshims.polyfill('forms forms-ext');
</script>
<?php if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'ACC'){
	?>
  <!-- Modal -->
  <div class="modal fade" id = "newAcc" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>New Account</h4>

        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "newuser-exec.php" method = "post">
          	<div class="form-group">
	           <?php
	          	if(isset($_SESSION['err']) && $_SESSION['err'] == 'ex'){
	          		echo '<div class="alert alert-danger" align="center"><strong>Warning!</strong> Username already exist.</div>';
	          		$_SESSION['err'] = "";
	          	}
	          ?>
	         </div>
            <div class="form-group">
              <label for="usrname"> Username <font color = "red">*</font></label>
              <input pattern=".{4,}" placeholder = "Enter you desired username" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Password <font color = "red">*</font></label><br>
            	<input required pattern=".{6,}" placeholder = "Enter your desired password" title="Six or more characters" class ="form-control"type = "password" id = "psw" name = "regpword"/>
            </div>
             <div class="form-group">
            	<label for="usrname"> Confirm Password <font color = "red">*</font></label><br>
            	<input required pattern=".{6,}" placeholder = "Enter again your password" title="Six or more characters" class ="form-control"type = "password" id = "psw1" name = "regcppword"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Account Level <font color = "red">*</font></label><br>
            	<select name = "level" class ="form-control">
						<option value = "EMP">Employee
						<?php if($_SESSION['level'] == 'Admin'){ ?>
							<option value = "HR">HR
							<option value = "ACC">Accounting						
							<option value = "Admin">Admin
						<?php } ?>
				</select>
            </div>
              <button type="submit" id = "submita" name = "hreg" class="btn btn-success btn-block" onclick = "">Submit</button>
          </form>
        </div>
        <div class="modal-footer">          
        </div>
      </div>      
    </div>
  </div> 
  <script type="text/javascript">
$("#submita").click(function(){
    if($("#psw").val() != $("#psw1").val()){
        alert("Password does not match");
	    return false;
        //more processing here
    }
});
</script>
 <?php } ?>
 <script type="text/javascript">
	$(document).ready(function(){
		$('#loanduration2').change(function() {
		    var selected = $(this).val();			
			if(selected == 'Others'){
				$('input[ name = "loanothers" ]').attr('disabled',false);
				$('input[ name = "loanothers" ]').attr("placeholder", "# of Months");
			}else{
				$('input[ name = "loanothers" ]').attr('disabled',true);
				$('input[ name = "loanothers" ]').attr("placeholder", "");
			}
		});
	});
</script>
 <!-- loanModal -->
  <div class="modal fade" id="penalty" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Penalty Loan Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[0-9]*" required name = "loanamount" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Reason <font color = "red">*</font></label>
            	<textarea id = "petamount" required name = "loanreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason"></textarea>
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Duration <font color = "red">*</font></label>
            	<select name = "loanduration" class="form-control" id = "loanduration2" required>
					<option value = ""> ----------- </option>
					<option value = "1"> 1 Month </option>
					<option value = "2"> 2 Months </option>
					<option value = "3"> 3 Months </option>
					<option value = "Others"> Others </option>
				</select>
            </div>
            <div class="form-group">
            	<label>Others</label>
				<input type =  "text" maxlength="2" class="form-control" name = "loanothers" disabled=""/>
            </div>
            <div class="form-group">
				<label>Payment Start (Month)</label>
				<select class="form-control" name = "cutoffmonth" required >
					<option value="">-----------</option>
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Day)</label>
				<select class="form-control" required name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="23">23 - 07</option>
					<option value="08">08 - 22</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Year)</label>
				<select class="form-control" required name = "cutofyr">
					<option value=""> - - - - - - - </option>
					<option value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
					<option value="<?php echo date("Y", strtotime("+1 year"));?>"><?php echo date("Y", strtotime("+1 year"));?></option>
				</select>
			</div>
			<div class="form-group">
				<p style="color: red; font-size: 12px;">
					<b>Example: <br>
						Your Payment Start is Feb 23 - 07(next month) <br>
						Deduction Date Feb 13, 2016</b>
				</p>
			</div>
              <button type="submit" name = "penaltysub" onclick="return confirm('Are you sure?');" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
      </div>      
    </div>
  </div> 
  <?php
	if(isset($_POST['penaltysub']) && isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		$state = "UALoan";
		$loandate = date("Y-m-d"); 
		$type = '1';
		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and ( ( loan.state != 'DALoan' ) and (CURDATE() <= enddate and loan_cutoff.state != 'Full' and loan_cutoff.state != 'Cancel' and loan_cutoff.state != 'Advance') ) and loan.penalty = '$type'";
		$resquery = $conn->query($query);
		$sqlxx = "SELECT * FROM loan where account_id = '$accid' and state = 'UALoan' and penalty = '1'";
		$resqueryxx = $conn->query($sqlxx);
		$date = date("Y-m-d");
		if($date <= date('Y-m-08')){
			$date = date("Y-m-23");
			$date2 = date("Y-m-07");
		}else{
			$date = date("Y-m-08");
			$date2 = date("Y-m-22");
		}
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate < '$date' and cadate > '$date2'";
		$resquery2 = $conn->query($query2);
		if($_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'] < date("Y-m-d")){
			echo '<script type="text/javascript">alert("Wrong date.");window.location.replace("employee.php?ac=penloan"); </script>';
		}else{
			if($_POST['loanduration'] == 'Others'){
				$duration = $_POST['loanothers'] . ' Months';
			}else{
				$duration = $_POST['loanduration'] . ' Months';
			}	
	   		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
			$sql = $conn->prepare("INSERT INTO `loan` (account_id, loanamount, loanreason, state, loandate, duration, startdate, penalty) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$sql->bind_param("issssssi", $accid, $_POST['loanamount'], $_POST['loanreason'], $state, $loandate, $duration, $date, $type);
			if($sql->execute()){
				if($_SESSION['level'] == 'EMP'){
	    			echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
		    	}
			}
		}

	}
	if($_SESSION['level'] == 'ACC' && !isset($_GET['expenses'])){
		$_SESSION['searchbox'] = "";
	}
?>