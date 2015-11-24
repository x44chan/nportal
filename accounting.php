<?php session_start(); ?>
<?php  $title="Accounting Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
	include("conf.php");
?>
<?php	if($_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	}	?>
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "?ac=penot">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
				  <li><a href="#" id = "newundertime">Undertime Request Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
				</ul>
			</div>
			<a type = "button" class = "btn btn-primary" href = "acc-report.php" id = "showapproveda">Cutoff Summary</a>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>
			<a type = "button" class = "btn btn-primary" href = "acc-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "acc-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a type = "button" class = "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class="btn-group btn-group" role="group">
			<a style = "width: 250px;" role = "button"class = "btn btn-success"  href = "?ac=penot"> Overtime Request Status </a>
			<a style = "width: 250px;" role = "button"class = "btn btn-success" href = "?ac=penob"> Official Business Request Status</a>			
			<a style = "width: 250px;" role = "button"class = "btn btn-success"  href = "?ac=penlea"> Leave Request Status</a>		
			<a style = "width: 250px;" role = "button"class = "btn btn-success"  href = "?ac=penundr"> Undertime Request Status</a>
			<a style = "width: 250px;" role = "button"class = "btn btn-success"  href = "?ac=penpty"> Petty Request Status</a>	
		</div>
	</div>
</div>
<div id = "needaproval" style = "margin-top: -30px;">	
<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penpty'){
		
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid order by state ASC, source asc";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 8 align = center><h2> Pending Petty Request </h2></td>
					</tr>
					<tr>
						<th>Petty #</th>
						<th>Date File</th>
						<th>Name</th>
						<th>Particular</th>
						<th>Source</th>
						<th>Transfer Code</th>
						<th>Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['date']);
				$newDate = date("F j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				echo 
					'<tr>
						<td>'.$row['petty_id'].'</td>
						<td>'.$newDate.'</td>
						<td>'.$row['fname'] . ' '. $row['lname'].'</td>
						<td>'.$row['particular'].'</td>
						<td>'.$row['source'].'</td>
						<td>'.$row['transfer_id'].'</td>
						<td>&#8369; '.$row['amount'].'</td>
						<td>';
							if($row['state'] == "UAPetty"){
								echo '<b>Pending to Admin';
							}elseif($row['state'] == 'AAAPettyReceive'){
								echo '<a href = "petty-exec.php?o='.$row['petty_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Petty</a>';
							}elseif($row['state'] == 'DAPetty'){
								echo 'Disapproved request';
							}elseif($row['state'] == 'AAPettyReceived'){
								echo '<font color = "green"><b>Received ';
							}elseif($row['state'] == 'AAPetty'){
								echo '<font color = "green"><b>Pending to Accounting</font>';
							}elseif($row['state'] == 'AAPettyRep'){
								echo '<font color = "green"><b>Received ';
								if($row['source'] == 'Accounting'){echo ' from Accounting</font>';}
								if($row['source'] == 'Eli/Sha'){echo ' from Sir Eli/Maam Sha</font>';}
							}
				echo '</td></tr>';

		}
		echo '</tbody></table></form>';
	}else{
		echo '<div align = "center" style = "margin-top: 15px;"><h2> No Record </h2></div>';
	}$conn->close();
}
?> 
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penot'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];
		if(strtolower($_SESSION['post']) == 'service technician'){
			$state = 'UATech';
		}else{
			$state = 'UA';
		}
		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and overtime_id = '$oid' and state = '$state'";
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
				<td><input value = "<?php echo $row['dateofot'];?>" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "updateofot"/></td></tr>				
				<tr>
					<td>Reason (Work to be done): </td>
					<td><textarea required name = "reason"class = "form-control"><?php echo $row['reason'];?></textarea></td>	
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
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr>
					<td colspan = 2 >
						<label for="restday" style="font-size: 15px; width: 500px; margin-left: -200px;"><input type="checkbox" <?php if($row['officialworksched'] == "Restday"){ echo "checked";}?> value = "restday" name="uprestday" id="restday"/> Rest Day</label>
					</td>
				</tr>	
				<tr id = "rday" class = "form-inline" <?php if($row['officialworksched'] == "Restday"){ echo "style = 'display: none;'";}?>>
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input onkeydown="return false;"name = "upoffr" value = "<?php echo $ex1;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;"name = "upoffto"value = "<?php echo $ex2;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  />
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
		if(strtolower($_SESSION['post']) == 'service technician'){
			$state = 'UATech';
		}else{
			$state = 'UA';
		}
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and undertime_id = '$oid' and state = '$state'";
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
							<label for = "numhrs">Num. of Hrs/Mins </label><input required placeholder = "_hrs : __mins" id = "numhrs" class = "form-control" style = "width: 200px" name = "unumofhrs"/>
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
					<td>Reason: </td>
					<td><textarea required name = "unreason"class = "form-control"><?php echo $row['reason'];?></textarea></td>
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
		$sql = "SELECT * FROM nleave,login where nleave.account_id = $accid and login.account_id = $accid and leave_id = '$oid' and state = '$state'";
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

				<tr>
					<td>Reason: </td>
					<td><textarea class = "form-control" name = "leareason"required><?php echo $row['reason'];?></textarea></td>
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
		if(strtolower($_SESSION['post']) == 'service technician'){
			$state = 'UATech';
		}else{
			$state = 'UA';
		}
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and officialbusiness_id = '$oid' and state = '$state'";
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
				<tr>
					<td>Time In: </td>
					<td>
						<input class = "form-control" value = "<?php echo $row['obtimein'];?>" name = "obtimein" id = "obtimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>Time Out: </td>
					<td><input class = "form-control" value = "<?php echo $row['obtimeout'];?>" name = "obtimeout" id = "obtimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>				
				<?php 
					$count = strlen($row['officialworksched']);
					if($count < 8){
						$ex1 = "";
						$ex2 = "";
					}else{
						$explode = explode(" - ", $row['officialworksched']);
						$ex1 = $explode[0];
						$ex2 = $explode[1];
					}					
				?>
				<tr class = "form-inline">
					<td>Official Work Sched: </td>
					<td>
						<label for = "fr">From:</label><input value = "<?php echo $ex1;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  name = "obofficialworkschedfr"/>
						<label for = "to">To:</label><input value = "<?php echo $ex2;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  name = "obofficialworkschedto"/>
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
						$('#obtimein').click(function() {
							$("#warning").hide();
						});
						$("#submituped").click(function(){						
							if($("#obtimein").val() == "" && $("#obtimeout").val() == "" ){
								$("#warning").show();
								return false;
							}else{
								$("#warning").hide();
							}
						});
					});
				</script>
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
	<?php 
		
		if(isset($_GET['ac']) && $_GET['ac'] == 'penot'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 >= 17){
			$forque = date('Y-m-16');
			$endque = date('Y-m-31');
		}else{
			$forque = date('Y-m-01');
			$endque = date('Y-m-15');
		}
		if(date("d") < 2){
			$forque = date('Y-m-16', strtotime("previous month"));
			$endque = date('Y-m-d');
		}
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = '$accid' and overtime.account_id = '$accid' and datefile BETWEEN '$forque' and '$endque' ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> Pending Overtime Request </h2></td>
				</tr>
				<tr >
					<th>Date File</th>
					<th>Date of Overtime</th>
					<th>Name of Employee</th>
					<th>Reason</th>
					<th>From - To (Overtime)</th>
					<th>Offical Work Schedule</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['datefile']);
			$newDate = date("F j, Y", strtotime($originalDate));
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['2daysred'] && $row['state'] == 'UA'){
				echo '<tr style = "color: red">';
			}else{
				echo '<tr>';
			}
			echo '
				<td width = 180>'.$newDate.'</td>
				<td>'.date("F j, Y", strtotime($row["dateofot"])).'</td>
				<td>'.$row["nameofemp"].'</td>
				<td width = 250 height = 70>'.$row["reason"].'</td>
				<td>'.$row["startofot"] . ' - ' . $row['endofot'].'</td>
				<td>'.$row["officialworksched"].'</td>';
				echo '<td><b>';
					if($row['state'] == 'UA' && strtolower($row['position']) != 'service technician'){
						echo 'Pending to HR<br>';
						echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
					}else if($row['state'] == 'UA' && strtolower($row['position']) == 'service technician'){
						echo 'Pending to HR<br>';
					}else if($row['state'] == 'UATech' && strtolower($row['position']) == 'service technician'){
						echo 'Pending to Tech Supervisor<br>';
						echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
					}else if($row['state'] == 'AHR'){
						echo '<p><font color = "green">Approved by HR</font></p> ';
					}else if($row['state'] == 'AAdmin'){
						echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
					}else if($row['state'] == 'DAHR'){
						echo '<p><font color = "red">Dispproved by HR</font></p> '.$row['dareason'];
					}else if($row['state'] == 'DAAdmin'){
						echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
					}else if($row['state'] == 'DATECH'){
					echo '<p><font color = "red">Disapproved by Technician Supervisor</font></p>'.$row['dareason'];
				}
				echo '</b></td></tr>';
		}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Overtime Request </h2>';
		}$conn->close();
	}
?>

<?php 
		if(isset($_GET['ac']) && $_GET['ac'] == 'penlea'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 > 16){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 16;
		}
		include("conf.php");
		$sql = "SELECT * FROM nleave,login where login.account_id = '$accid' and nleave.account_id = '$accid' and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table width = "100%"class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 10 align = center><h2> Leave Application Status </h2></td>
				</tr>
				<tr>
					<th width = "150">Date File</th>
					<th width = "170">Name of Employee</th>
					<th width = "170">Date Hired</th>
					<th>Department</th>
					<th>Position</th>
					<th width = "210">Date of Leave</th>
					<th width = "100"># of Day/s</th>
					<th width = "170">Type of Leave</th>
					<th width = "180">Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['datefile']);
			$newDate = date("F j, Y", strtotime($originalDate));
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA'){
				echo '<tr style = "color: red">';
			}else{
				echo '<tr>';
			}
			echo '
					<td>'.$newDate.'</td>
					<td>'.$row["nameofemployee"].'</td>
					<td>'.date("F d, Y", strtotime($row["datehired"])).'</td>
					<td >'.$row["deprt"].'</td>
					<td>'.$row['posttile'].'</td>					
					<td> From: '.date("F d, Y", strtotime($row["dateofleavfr"])) .'<br>To: '.date("F d, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					<td >'.$row["reason"].'</td>';
					 if($row['state'] == 'UAACCAdmin' || $row['state'] == 'AACC'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				/*echo'
					<td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dleave='.$row['leave_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
					</td>
				</tr>';*/
			}
		}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Leave Request </h2>';
		}
	}
?>
<?php 
		if(isset($_GET['ac']) && $_GET['ac'] == 'penundr'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 > 16){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 16;
		}
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = '$accid' and undertime.account_id = '$accid'   and DAY(dateofundrtime) >= $forque and DAY(dateofundrtime) <= $endque and MONTH(dateofundrtime) = $dated and YEAR(datefile) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
	?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>				
				<tr>
					<td colspan = 7 align = center><h2> Undertime Application Status </h2></td>
				</tr>
				<tr >
					<th>Date File</th>
					<th>Date of Overtime</th>
					<th>Name of Employee</th>
					<th>Reason</th>
					<th>From - To (Overtime)</th>
					<th>Number of Hrs/Minutes</th>
				</tr>
			</thead>
			<tbody>
	<?php
		while($row = $result->fetch_assoc()){				
			$originalDate = date($row['datefile']);
			$newDate = date("F j, Y", strtotime($originalDate));
			
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
				echo '<tr style = "color: red">';
			}else{
				echo '<tr>';
			}		
			echo 
					'<td width = 180>'.$newDate.'</td>
					<td>'.date('F j, Y', strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>
					<td width = 250 height = 70>'.$row["reason"].'</td>
					<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
					<td>'.$row["numofhrs"].'</td>';
					 if($row['state'] == 'UAACCAdmin' || $row['state'] == 'AACC'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				/*echo'				
					<td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dundertime='.$row['undertime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
					</td>
				</tr>';*/
			}
		}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Undertime Request </h2>';
		}$conn->close();
	}
?>
<?php
	if(isset($_GET['ac']) && $_GET['ac'] == 'penob'){
		$date17 = date("d");
		$dated = date("m");
		$datey = date("Y");
		if($date17 > 16){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 16;
		}
		include("conf.php");
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = '$accid' and officialbusiness.account_id = '$accid'  and DAY(obdate) >= $forque and DAY(obdate) <= $endque and MONTH(obdate) = $dated and YEAR(obdate) = $datey ORDER BY obdate ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
?>
	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Official Business Application Status </h2></td>
				</tr>
				<tr>
					<th>Date File</th>
					<th>Name of Employee</th>
					<th>Position</th>
					<th>Department</th>
					<th>Date of Request</th>
					<th>Time In - Time Out</th>
					<th>Offical Work Schedule</th>
					<th>Reason</th>
				</tr>
			</thead>
			<tbody>
<?php
		while($row = $result->fetch_assoc()){			
			$originalDate = date($row['obdate']);
			$newDate = date("F j, Y", strtotime($originalDate));
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA'){
				echo '<tr style = "color: red">';
			}else{
				echo '<tr>';
			}		
			echo 
					'<td>'.$newDate.'</td>
					<td>'.$row["obename"].'</td>
					<td>'.$row["obpost"].'</td>
					<td >'.$row["obdept"].'</td>
					<td>'.date("F d, Y", strtotime($row['obdatereq'])).'</td>					
					<td>'.$row["obtimein"] . ' - ' . $row['obtimeout'].'</td>
					<td>'.$row["officialworksched"].'</td>				
					<td >'.$row["obreason"].'</td>';
					 if($row['state'] == 'UAACCAdmin' || $row['state'] == 'AACC'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				/*echo'
					<td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button" id = "DAHR">Disapprove</a>
					</td>
				</tr>';*/
		}
	}
		echo '</tbody></table></form>';
	}else{
		echo '<h2 align = "center" style = "margin-top: 30px;"> No Official Request </h2>';
	}$conn->close();
}
?>
</div>
<?php include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'defaultpass'){
		include('up-pass.php');
	}else if($_SESSION['201date'] == null){
	?>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
    backdrop: 'static',
    keyboard: false
  });
});
</script>
<?php } include("req-form.php");?>
<?php include("footer.php");?>
