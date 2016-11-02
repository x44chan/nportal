<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penot'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
				
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and overtime_id = '$oid' and (state = 'UA')";
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
		          			<option value=""> Select ( P.M / Internet / Project / Luwas / Netlink)  </option>
		          			<option <?php if($row['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
		          			<option <?php if($row['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
		          			<option <?php if($row['projtype'] == 'Project'){ echo ' selected '; } ?> value="Project"> Project </option>
		          			<option <?php if($row['projtype'] == 'Support'){ echo ' selected '; } ?> value="Support"> Project Support </option>
		          			<option <?php if($row['projtype'] == 'Oncall'){ echo ' selected '; } ?> value="Oncall"> Oncall </option>
		          			<option <?php if($row['projtype'] == 'Luwas'){ echo ' selected '; } ?> value="Luwas"> Luwas </option>	
		          			<option <?php if($row['projtype'] == 'Netlink'){ echo ' selected '; } ?> value="Netlink"> Netlink </option>	
						</select>
					</td>
				</tr>
				<tr <?php if($row['projtype'] != 'Oncall'){ echo ' style = "display: none;" '; } ?> id = "otoncall">
            		<td><label>On Call <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "otoncall">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1'";
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
				<tr <?php if($row['projtype'] != 'Project'){ echo ' style = "display: none;" '; } ?> id = "otproject">
            		<td><label>Project <font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "loc" onchange="showUser(this.value,'proj','')">
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
		            </td>
		        </tr>
		        <tr <?php if($row['projtype'] != 'Support'){ echo ' style = "display: none;" '; } ?> id = "otsupport">
            		<td><label>Project Support<font color = "red">*</font></label></td>
            		<td>
            			<select class="form-control" name = "locx" onchange="showUser(this.value,'','supp')">
		            		<option value = ""> - - - - - </option>
		            		<?php
		            			$xsql = "SELECT * FROM `project` where type = 'Support' and state = '1' group by loc order by CHAR_LENGTH(loc)";
		            			$xresult = $conn->query($xsql);
		            			$loc = "";
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					$xsql2 = "SELECT loc FROM `project` where type = 'Support' and name = '$row[project]'";
		            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
		            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
		            						$selecteds = ' selected ';		            						
		            						$locx = $xresult2['loc'];
		            					}else{
		            						$selecteds = "";
		            					}

		            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
		            				}
		            			}
		            		?>
		            	</select>
		            </td>
		        </tr>		        
		        <tr id = "loc" >
		        	<?php if($row['projtype'] == 'Project' || $row['projtype'] == 'Support'){ ?>
		        	<td><b>PO <font color = "red"> * </font></b></td>
		        	<td>
		        		<select name = "otproject" class = "form-control">
		        			<?php
		        				$xtype = $row['projtype'];
		            			$xsql = "SELECT * FROM `project` where type = '$xtype' and state = '1' and (loc = '$loc' or loc = '$locx') order by CHAR_LENGTH(name)";
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
		        	<?php } ?>
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
					<td><textarea required name = "reason"class = "form-control"><?php if(stristr($data1['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Sick Leave)</i></b>", "", $data1['reason']); }elseif(stristr($data1['reason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Emergency Leave)</i></b>", "", $data1['reason']); }elseif(stristr($data1['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo str_replace("<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>", "", $data1['reason']); }else{ echo $data1['reason'];}?></textarea></td>	
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
				<tr>
					<td> For Late Filing</td>
					<td>
						<select name="onleave" class="form-control">
							<option value="">--------</option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo ' selected '; } ?> value="Sick Leave"> Sick Leave </option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo ' selected '; } ?> value="Emergency Leave"> Emergency Leave </option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo ' selected '; } ?> value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
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
				<tr class = "form-inline">
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
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penhol'){
		$o = mysqli_real_escape_string($conn, $_GET['o']);
		$sql = "SELECT * FROM holidayre where account_id = '$_SESSION[acc_id]' and holidayre_id = '$o' and state = 0";
		if($conn->query($sql)->num_rows <= 0){
			echo '<script type="text/javascript">alert("No record found");window.location.replace("?ac=penhol"); </script>';
		}
		$data = $conn->query($sql)->fetch_assoc();
?>
	<div class="modal fade" id="upholiday" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <a type="button" class="close" href = "?ac=penhol">&times;</a>
          <h4>Update Holiday</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Description of Work Order <font color = "red">*</font></label>
            	<textarea class="form-control"  name = "reason" placeholder = "Enter reason" required><?php if(stristr($data['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Sick Leave)</i></b>", "", $data['reason']); }elseif(stristr($data['reason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Emergency Leave)</i></b>", "", $data['reason']); }elseif(stristr($data['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo str_replace("<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>", "", $data['reason']); }else{ echo $data['reason'];}?></textarea>
          	</div>
          	<div class="form-group">
          		<label>Date</label>
          		<input required class = "form-control" <?php if(isset($data['holiday'])){ echo ' value = "' . $data['holiday'] . '" '; } ?>  type = "date" placeholder = "Click to set date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "holiday"/>
          	</div>
          	<div class="form-group">
          		<label>Type <font color = "red">*</font></label>
          		<select class="form-control" required name = "type">
          			<option value="">----------</option>
          			<option value="Legal Holiday" <?php if(isset($data['type']) && $data['type'] == 'Legal Holiday'){ echo ' selected'; } ?>> Legal Holiday </option>
          			<option value="Special N-W Holiday" <?php if(isset($data['type']) && $data['type'] == 'Special N-W Holiday'){ echo ' selected'; } ?>> Special N-W Holiday </option>
          		</select>
          	</div>
          	<div class="form-group">
          		<label>Late Filing</label>
          		<select name="onleave" class="form-control">
					<option value="">--------</option>
					<option <?php if(stristr($data['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo ' selected '; } ?> value="Sick Leave"> Sick Leave </option>
					<option <?php if(stristr($data['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo ' selected '; } ?> value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
				</select>
          	</div>
            <button type="submit" name = "updatehol" class="btn btn-success btn-block">Update</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div>
<script type="text/javascript">
	$(document).ready(function(){	      
	  $('#upholiday').modal({
	    backdrop: 'static',
	    keyboard: false
	  });
	});
</script>
<?php
	if(isset($_POST['updatehol']) && $_POST['reason'] != "" && $_POST['holiday'] != "" && $_POST['type']){
		$id = mysqli_real_escape_string($conn,$_GET['o']);
		$select = "SELECT * FROM holidayre where holidayre_id = '$id'";
		$data = $conn->query($select)->fetch_assoc();
		if(date('Y-m-d',strtotime($data['datefile'])) == date("Y-m-d",strtotime("-1 day",strtotime($_POST['holiday']))) || date('Y-m-d',strtotime($data['datefile'])) == $_POST['holiday'] || date('Y-m-d',strtotime($data['datefile'])) == date("Y-m-d",strtotime("+1 day",strtotime($_POST['holiday'])))){
  			$restrict = 0;
  		}else{
  			$restrict = 1;
  		}
  		if($_POST['onleave'] != ""){
  			$restrict = 0;
  			$_POST['reason'] .= '<br><b><i>('. $_POST['onleave'] . ')</i></b>';
  		}
		$stmt = $conn->prepare("UPDATE holidayre set holiday = ?, type = ?, reason = ? where holidayre_id = ? and account_id = ? and state = 0");
		$stmt->bind_param("sssii", $_POST['holiday'], $_POST['type'], $_POST['reason'], $_GET['o'], $_SESSION['acc_id']);
		if($restrict == 0){
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
<?php
	}
?>
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penundr'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and undertime_id = '$oid' and state = 'UA'";
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
				<?php
					$query1 = "SELECT * FROM `undertime` where undertime_id = '$row[undertime_id]'";
					$data1 = $conn->query($query1)->fetch_assoc();
				?>					
				<tr>
					<td>Reason: </td>
					<td><textarea required name = "unreason"class = "form-control"><?php if(stristr($data1['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Sick Leave)</i></b>", "", $data1['reason']); }elseif(stristr($data1['reason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Emergency Leave)</i></b>", "", $data1['reason']); }elseif(stristr($data1['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo str_replace("<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>", "", $data1['reason']); }else{ echo $data1['reason'];}?></textarea></td>
				</tr>
				<tr>
					<td>Date Of Undertime: </td>
					<td>
						<input required class = "form-control" type = "date" value = "<?php echo $row['dateofundrtime'];?>" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "click to set date"min = "<?php echo date('m/d/Y'); ?>" name = "undatereq"/>
					</td>						
				</tr>	
				<tr>
					<td> For Late Filing</td>
					<td>
						<select name="onleave" class="form-control">
							<option value="">--------</option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo ' selected '; } ?> value="Sick Leave"> Sick Leave </option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo ' selected '; } ?> value="Emergency Leave"> Emergency Leave </option>
							<option <?php if(stristr($row['reason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo ' selected '; } ?> value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
						</select>
					</td>
				</tr>								
				<div class = "ui-widget-content" style = "border: none;">		
					<tr class = "form-inline">
						<td>Time of Undertime: </td>
						<td>
							<label for = "fr"> From: </label><input value = "<?php echo $row['undertimefr'];?>" placeholder = "Click to Set time" required  autocomplete ="off" id = "to" class = "form-control"  name = "untimefr"/>
							<label for = "to"> To:  </label><input value = "<?php echo $row['undertimeto'];?>" placeholder = "Click to Set time" required  autocomplete ="off" id = "fr" class = "form-control" name = "untimeto"/>
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
					<td width="30%">Date File: </td>
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
					<td><textarea required name = "obreason" class = "form-control col-sm-10"><?php if(stristr($row['obreason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Sick Leave)</i></b>", "", $row['obreason']); }elseif(stristr($row['obreason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo str_replace("<br><b><i>(Emergency Leave)</i></b>", "", $row['obreason']); }elseif(stristr($row['obreason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo str_replace("<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>", "", $row['obreason']); }else{ echo $row['obreason'];}?></textarea></td>
				</tr>
				<tr>
					<td> For Late Filing</td>
					<td>
						<select name="onleave" class="form-control">
							<option value="">--------</option>
							<option <?php if(stristr($row['obreason'], '<br><b><i>(Sick Leave)</i></b>') == true) { echo ' selected '; } ?> value="Sick Leave"> Sick Leave </option>
							<option <?php if(stristr($row['obreason'], '<br><b><i>(Emergency Leave)</i></b>') == true) { echo ' selected '; } ?> value="Emergency Leave"> Emergency Leave </option>
							<option <?php if(stristr($row['obreason'], '<br><b><i>(On Service/Project Stay-in with no Internet Access)</i></b>') == true) { echo ' selected '; } ?> value="On Service/Project Stay-in with no Internet Access"> On Service/Project Stay-in with no Internet Access </option>
						</select>
					</td>
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