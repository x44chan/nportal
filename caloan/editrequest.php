<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penot'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
				
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and overtime_id = '$oid' and (state = 'UAAdmin' or state = 'UALate')";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 50%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = "2" align = "center">
						<h2> Edit Overtime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date("F j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['nameofemp']?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $row['department'];?></td>
				</tr>
				<tr>
					<td>Date Of Overtime: </td>
					<td><input value = "<?php echo $row['dateofot'];?>" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "updateofot"/></td>
				</tr>	
				<tr>
					<td>CSR #: </td>
					<td><input class = "form-control" type = "text" value = "<?php echo $row['csrnum'];?>" placeholder = "Enter CSR Number" name = "csrnum"/></td>
				</tr>
				<tr>
					<td> Type: <font color = "red">*</font></td>
					<td>
						<select required class="form-control" name = "ottype">
		          			<option value=""> Select ( P.M / Internet / Project / Others)  </option>
		          			<option <?php if($row['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
		          			<option <?php if($row['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
		          			<option <?php if($row['projtype'] == 'Project'){ echo ' selected '; } ?> value="Project"> Project </option>
		          			<option <?php if($row['projtype'] == 'Others'){ echo ' selected '; } ?> value="Others"> Others </option>	
						</select>
					</td>
				</tr>
				<tr <?php if($row['projtype'] != 'Project'){ echo ' style = "display: none;" '; } ?> id = "otproject">
            		<td><label>Project <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otproject">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'P.M.'){ echo ' style = "display: none;" '; } ?> id = "otpm">
            		<td><label>P.M. <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otpm">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'Internet'){ echo ' style = "display: none;" '; } ?> id = "otinternet">
            		<td><label>Internet <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otinternet">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1'";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>			
				<?php
					$query1 = "SELECT * FROM `overtime` where overtime_id = '$row[overtime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
				?>
				<tr>
					<td>Reason (Work to be done): </td>
					<td><textarea required name = "reason"class = "form-control"><?php echo $data1['reason'];?></textarea></td>	
				</tr>
			<div class = "ui-widget-content" style = "border: none;">
				<tr>
					<td>Start of OT: </td>
					<td>
						<input id = "timein" onkeydown="return false;" value = "<?php echo $row['startofot'];?>" required class = "form-control" name = "uptimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>End of OT: </td>
					<td><input  value = "<?php echo $row['endofot'];?>" onkeydown="return false;"required class = "form-control" name = "uptimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>
				<tr>
					<td>OT Break ( if applicable ):  </td>
					<td>
						<select class = "form-control" name = "otbreak" id = "otbreak">
							<option value ="">--------</option>
							<option <?php if($row['otbreak'] == "-30 Minutes"){ echo ' selected ';}?> value = "30 Mins">30 Mins</option>
							<option <?php if($row['otbreak'] == "-1 Hour"){ echo ' selected ';}?> value = "1 Hour">1 Hour</option>
						</select>
					</td>					
				</tr>
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						if(stristr($row['officialworksched'], '<br>') !== FALSE){
							$explode1 = explode('<br>', $row['officialworksched']);
							$row['officialworksched'] = $explode1[1];
						}
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>					
					<td colspan="2">
						<label for="restday" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Restday'){ echo ' checked '; } ?> value = "restday" name="uprestday" id="restday"/> Rest Day </label>
						<label for="oncall" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Oncall'){ echo ' checked '; } ?> value = "oncall" name="uponcall" id = "oncall"/> Oncall </label>
						<label for="sw" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Special N-W Holliday'){ echo ' checked '; } ?> value = "sw" name="sw" id = "sw"/> Special N-W Holliday </label>
						<label for="lg" style="font-size: 15px;"><input type="checkbox" <?php if(isset($explode1[0]) && $explode1[0] == 'Legal Holliday'){ echo ' checked '; } ?> value = "sw" name="lg" id = "lg"/> Legal Holliday </label>
					</td>
				</tr>	
				<tr class = "form-inline">>
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input onkeydown="return false;" required name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;" required name = "upoffto"value = "<?php echo $ex2;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  />
					</td>					
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upotsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="uptimein"]').ptTimeSelect();
							$('input[name="uptimeout"]').ptTimeSelect();
							$('input[name="upoffr"]').ptTimeSelect();							
							$('input[name="upoffto"]').ptTimeSelect();
						});
					</script>
			</div>
	<?php
			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penundr'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and undertime_id = '$oid' and (state = 'UAAdmin' or state = 'UALate')";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 70%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 2 align = center>
						<h2> Edit Undertime Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date("F j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['name'];?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $_SESSION['post'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $_SESSION['dept'];?></td>
				</tr>
				<tr>
					<td>Date Of Undertime: </td>
					<td>
						<input required class = "form-control" type = "date" value = "<?php echo $row['dateofundrtime'];?>" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "click to set date"min = "<?php echo date('m/d/Y'); ?>" name = "undatereq"/>
					</td>						
				</tr>									
				<div class = "ui-widget-content" style = "border: none;">		
					<tr class = "form-inline">
						<td>Time of Undertime: </td>
						<td>
							<label for = "fr"> From: </label><input value = "<?php echo $row['undertimefr'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "to" class = "form-control"  name = "untimefr"/>
							<label for = "to"> To:  </label><input value = "<?php echo $row['undertimeto'];?>" placeholder = "Click to Set time" required style = "width: 150px;" autocomplete ="off" id = "fr" class = "form-control" name = "untimeto"/>
							<label for = "numhrs">Num. of Hrs/Mins </label><input required placeholder = "_hrs : __mins" value = "<?php echo $row['numofhrs'];?>" id = "numhrs" class = "form-control" style = "width: 200px" name = "unumofhrs"/>
						</td>	
					</tr>			
					<script type="text/javascript">
						$(document).ready(function(){
							$('input[name="untimeto"]').ptTimeSelect();
							$('input[name="untimefr"]').ptTimeSelect();
						});
					</script>
				</div>	
				<?php
					$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
				?>					
				<tr>
					<td>Reason: </td>
					<td><textarea required name = "unreason"class = "form-control"><?php echo $data1['reason'];?></textarea></td>
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upunsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
	<?php
			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penlea'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		if(strtolower($_SESSION['post']) == 'service technician'){
			$state = 'UATech';
		}else{
			$state = 'UA';
		}
		$sql = "SELECT * FROM nleave,login where nleave.account_id = $accid and login.account_id = $accid and leave_id = '$oid' and ((state = 'UA' and accadmin is null) or (state = 'UAAdmin' and accadmin is null))";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 60%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 3 align = center>
						<h2> Edit Leave Request </h2>
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
						<?php if($row['typeoflea'] == 'Others'){ echo $row['typeoflea'] . ': '.$row['othersl'];}else{echo $row['typeoflea'];}?>
					</td>
				</tr>	
				<div style = "display: none;">
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['nameofemployee'];?></td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date('F j, Y', strtotime($row['datefile']));?></td>
				</tr>				
				<tr>
					<td>Date Hired: </td>
					<td><?php echo date('F j, Y', strtotime($_SESSION['datehired'])); ?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $_SESSION['dept'];?></td>
				</tr>
				<tr>
					<td>Position Title: </td>
					<td><?php echo $_SESSION['post'];?></td>
				</tr>
				<tr>
					<td colspan = 3 align = "center">
						<h3>LEAVE DETAILS</h3>
				</tr>
				<tr class = "form-inline">
					<td>Inclusive Dates: </td>
					<td style="float:left;">
						From: <input required class = "form-control" type = "date" placeholder = "Click to set date" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['dateofleavfr']; ?>" name = "dateofleavfr"/>
						To: <input required class = "form-control" type = "date" placeholder = "Click to set date" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['dateofleavto']; ?>" n name = "dateofleavto"/>
						Number of Days: <input value = "<?php echo $row['numdays'];?>" maxlength = "3" style = "width: 90px;"type = "text" pattern = '[0-9.]+' required name = "numdays"class = "form-control"/>
					</td>
				</tr>					
				<?php
					$query1 = "SELECT * FROM `nleave` where leave_id = '$row[leave_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
				?>
				<tr>
					<td>Reason: </td>
					<td><textarea class = "form-control" name = "leareason"required><?php echo $data1['reason'];?></textarea></td>
				</tr>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "upleasubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
	<?php
			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form>';
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penob'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and officialbusiness_id = '$oid' and (state = 'UAAdmin' or state = 'UALate' or state = 'UA')";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<div ><form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 50%;" align = "center">';
			while($row = $result->fetch_assoc()){
			?>
			<tr>
					<td colspan = 2 align = center>
						<h2> Edit Official Business Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><?php echo date('F j, Y', strtotime($row['obdate']));?></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><?php echo $row['obename'];?></td>
				</tr>
				<tr>
					<td>ID No: </td>
					<td><?php echo $_SESSION['acc_id'];?></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><?php echo $_SESSION['post'];?></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><?php echo $_SESSION['dept'];?></td>
				</tr>
				<tr>
					<td>Date Of Official Business: </td>
					<td><input value = "<?php echo $row['obdatereq'];?>" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "updateofob"/></td>
				</tr>				
				<tr>
					<td>Description of Work Order: </td>
					<td><textarea required name = "obreason" class = "form-control col-sm-10"><?php echo $row['obreason'];?></textarea></td>
					
				</tr>
				<div class = "ui-widget-content" style = "border: none;">	
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						if(stristr($row['officialworksched'], '<br>') !== FALSE){
							$explode1 = explode('<br>', $row['officialworksched']);
							$row['officialworksched'] = $explode1[1];
						}
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>
					<td colspan = 2 style="float: center;">
						<label for="restday" style="font-size: 15px; width: 500px; margin-left: -200px;"><input type="checkbox" <?php if(isset($explode1[0])){ echo "checked";}?> value = "restday" name="uprestday" id="restday"/> Rest Day</label>
					</td>
				</tr>	
				<tr class = "form-inline">
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  />
						<label for = "to">To:</label><input name = "upoffto"value = "<?php echo $ex2;?>" placeholder = "Click to Set time"  style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  />
					</td>					
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
						$('input[name="upoffr"]').ptTimeSelect();
						$('input[name="upoffto"]').ptTimeSelect();							
					//	$('input[name="obtimeout"]').ptTimeSelect();
						$("#submituped").click(function(){
							if($("#toasd").val() == "" && $("#frasd").val() == ""){
								$("#warning").show();
								return false;							
							}
						});
					});
				</script>
				</div>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" id = "submituped"name = "upobsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
						<a href = "?ac=<?php echo $_GET['acc']?>" class = "btn btn-danger" value = "Cancel">Cancel</a>
					</td>
				</tr>
		<?php

			}
		}else{
			echo "<div align = 'center'><h2 >No record found.</h2>";
			echo '<a href = "?ac='. $_GET['acc'].'" class = "btn btn-danger" value = "Cancel">Back</a></div>';
		}
		echo '</table>
	</form></div>';
	}
	
?>	