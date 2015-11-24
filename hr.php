<?php session_start(); ?>
<?php  $title="H.R. Page";
	include('header.php');	
	include('conf.php');
	date_default_timezone_set('Asia/Manila');
	$accid = $_SESSION['acc_id'];
?>
<?php if($_SESSION['level'] != 'HR'){
	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
	
	<?php
	}
	if(isset($_SESSION['err']) && $_SESSION['err'] == 'ex'){
	echo "<script type=\"text/javascript\">
			$(document).ready(function(){	      
				$('#newAcc').modal({
				backdrop: 'static',
				keyboard: false
				});
			});
			</script>";
	}
	$accid = $_SESSION['acc_id'];
	if($_SESSION['level'] == 'HR'){
		$state = 'AHR';
	}
?>
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "hr.php?ac=penot">Home</a>
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
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="hr-emprof.php" id = "newovertime">Employee Profile</a></li>
				  <li><a type="button" data-toggle="modal" data-target="#newAcc">Add User</a></li>
				</ul>
			</div>			
			<a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda">My Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda">My Dispproved Request</a>
			<a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
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
		include("conf.php");
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

		echo '<div align = "center" style = "margin-top: 30px;"><h2>No Record Found</h2</div>';
	}$conn->close();
}
?> 	
<?php
	if(isset($_GET['acc']) && isset($_GET['update']) && $_GET['acc'] == 'penot'){
		$oid = mysql_escape_string($_GET['o']);
		$_SESSION['otid'] = $oid;
		$_SESSION['acc'] = $_GET['acc'];

		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and overtime_id = '$oid' and state = '$state'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center" action = "update-exec.php" method = "post">
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
				<tr>
					<td>OT Break: </td>
					<td>
						<select class = "form-control" name = "otbreak">
							<option value ="">--------</option>
							<option <?php if($row['otbreak'] == '-30 Minutes'){ echo 'selected '; } ?> value = "30 Mins">30 Mins</option> 
							<option <?php if($row['otbreak'] == '-1 Hour'){ echo 'selected '; } ?>value = "1 Hour">1 Hour</option>
						</select>
					</td>					
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
						<label for = "fr">From:</label><input onkeydown="return false;"name = "upoffr" value = "<?php echo $ex1;?>"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;"name = "upoffto"value = "<?php echo $ex2;?>"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  />
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
						$("#submits").click(function(){						
							if($("#obtimein").val() == "" && $("#obtimeout").val() == "" ){
								$("#warning").show();
								return false;
							}else{
								$("#warning").hide();
							}
						});
					});
				</script>
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
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and undertime_id = '$oid' and state = '$state'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 70%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 2 align = center>
						<h2> Undertime Request </h2>
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
		$sql = "SELECT * FROM nleave,login where nleave.account_id = $accid and login.account_id = $accid and leave_id = '$oid' and state = '$state'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 60%;"align = "center">';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td colspan = 3 align = center>
						<h2> Update Leave Request </h2>
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
		$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and officialbusiness_id = '$oid' and state = '$state'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<div ><form role = "form"  align = "center"action = "update-exec.php" method = "post">
			<table class = "table table-hover" style = "width: 50%;" align = "center">';
			while($row = $result->fetch_assoc()){
			?>
			<tr>
					<td colspan = 2 align = center>
						<h2> Official Business Update </h2>
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
						<input  class = "form-control" value = "<?php echo $row['obtimein'];?>" name = "obtimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td>Time Out: </td>
					<td><input  class = "form-control" value = "<?php echo $row['obtimeout'];?>" name = "obtimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
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
						<label for = "to">To:</label><input value = "<?php echo $ex1;?>" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  name = "obofficialworkschedto"/>
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
						<input type = "submit" name = "upobsubmit" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-primary"/>					
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
		if($date17 > 16){
			$forque = 16;
			$endque = 31;
		}else{
			$forque = 1;
			$endque = 16;
		}
		if(date("d") <= 2){
			$date17 = 16;
			$forque = 16;
			$endque = 32;
			$dated = date("m", strtotime("previous month"));
		}
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and state like 'UA' and DAY(datefile) >= $forque and DAY(datefile) <= $endque and MONTH(datefile) = $dated and YEAR(datefile) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
			
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
			$error = 0;
			if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("F j, Y", strtotime($originalDate));

				$datetoday = date("Y-m-d");
				if($datetoday >= $row['2daysred'] ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}

				if($row['otbreak'] != null){
					$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
				}else{
					$otbreak = "";
				}
				echo 
					'	<td width = 180>'.$newDate.'</td>
						<td>'.date("F j, Y", strtotime($row["dateofot"])).'</td>
						<td>'.$row["nameofemp"].'</td>
						<td width = 250 height = 70>'.$row["reason"]. '</td>
						<td>'.$row["startofot"] . ' - ' . $row['endofot']. $otbreak .'</td>
						<td>'.$row["officialworksched"].'</td>';
				if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
					echo '<td width = "250">
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button"><span class="glyphicon glyphicon-check"></span> Ok</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&upovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-edit"></span> Edit</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" style = "margin-top: 2px; role="button"><span class="glyphicon glyphicon-remove-sign"></span> Disapprove</a>
						</td>
					</tr>';
				}
			}
		}else{
			$error += 1;
		}

		$sql = "SELECT * FROM overtime,login where overtime.account_id = $accid and login.account_id = $accid and DAY(dateofot) >= $forque and DAY(dateofot) <= $endque and MONTH(dateofot) = $dated and YEAR(dateofot) = $datey ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$datetoday = date("Y-m-d");
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$newDate2 = date("M j, Y", strtotime($row['dateofot']));
				if($datetoday >= $row['2daysred'] && $row['state'] == 'UA'){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				if($row['oldot'] != null && $row['state'] == 'AHR'){
					$oldot = '</b><br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
					$hrot = '<b>App. OT: <i><font color = "green">';
					$hrclose = "</font></i>";
				}else if($row['oldot'] != null && $row['state'] == 'AAdmin'){
					$oldot = '<br><b>Based On: <i><font color = "green">'.$row['dareason'].'</font></b></i><br><b>Filed OT: <i><font color = "red">'. $row['oldot'] . '</font></i>';
					$hrot = '<b>App. OT: <i><font color = "green">';//( '.$row['approvedothrs'] . ' ) ';
					$hrclose = "</font></i>";
				}else{
					$oldot = "";
					$hrot = '';
					$hrclose ='';
				}
				if($row['otbreak'] != null){
					$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
				}else{
					$otbreak = "";
				}
				echo 
					'
						<td>'.$newDate .'</td>	
						<td>'.$newDate2 . '</td>					
						<td>'.$row["nameofemp"].'</td>	
						<td width = 300 height = 70>'.$row["reason"].'</td>					
						<td style = "text-align:left;">'. $hrot . $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . ' </b>'.$oldot. $otbreak.'</td>						
						<td>'.$row["officialworksched"].'</td>				
						<td><b>';
							if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['overtime_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}
						echo '<td></tr>';
			}
		}else{
			$error += 1;
		}
		if($error > 1){
			echo '<tr><td colspan = 7><h2>No record found</h2></td></tr>';
		}
		echo '</tbody></table></form>';
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
		$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and state like 'UA' and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php"    method = "get">
			<table width = "100%"class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 10 align = center><h2> Pending Leave Request </h2></td>
					</tr>
					<tr>
						<th width = "160">Date File</th>
						<th width = "170">Name of Employee</th>
						<th width = "170">Date Hired</th>
						<th>Department</th>
						<th>Position</th>
						<th width = "200">Date of Leave</th>
						<th width = "120"># of Day/s</th>
						<th width = "170">Type of Leave</th>
						<th width = "180">Reason</th>
						<th width = "240">Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		$error = 0;
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("F j, Y", strtotime($originalDate));

				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				echo 
					'<td>'.$newDate.'</td>
					 <td>'.$row["nameofemployee"].'</td>
					 <td>'.date("F d, Y", strtotime($row["datehired"])).'</td>
					 <td >'.$row["deprt"].'</td>
					 <td>'.$row['posttile'].'</td>					
					 <td> Fr: '.date("F d, Y", strtotime($row["dateofleavfr"])) .'<br>To: '.date("F d, Y", strtotime($row["dateofleavto"])).'</td>
					 <td>'.$row["numdays"].'</td>					
					 <td >'.$row["typeoflea"]. ' : ' . $row['othersl']. '</td>	
					 <td >'.$row["reason"].'</td>';
					 if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				echo'	
					 <td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Checked</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dleave='.$row['leave_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button">Disapprove</a>
					</td>
					</tr>';
			}
		}
	}else{
		$error += 1;
	}
		$sql = "SELECT * FROM nleave,login where login.account_id = $accid and nleave.account_id = $accid and YEAR(dateofleavfr) = $datey ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$newDate2 = date("M j, Y", strtotime($row["edatehired"]));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}		
				echo 
					'<td>'.$newDate.'</td>
					<td>'.$row["nameofemployee"].'</td>
					<td>'.$newDate2.'</td>
					<td >'.$row["deprt"].'</td>
					<td>'.$row['posttile'].'</td>					
					<td width = "300">Fr: '.date("M j, Y", strtotime($row["dateofleavfr"])) .' <br>To: '.date("M j, Y", strtotime($row["dateofleavto"])).'</td>
					<td>'.$row["numdays"].'</td>					
					<td >'.$row["typeoflea"]. ' ' . $row['othersl']. '</td>	
					<td >'.$row["reason"].'</td>	
					<td width = "200"><b>';
							if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['leave_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}
						echo '<td></tr>';
		}	
	}else{
		$error += 1;
	}
	if($error > 1){
		echo '<tr><td colspan = 7><h2>No record found</h2></td></tr>';
	}
echo '</tbody></table></form>';
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
		if(date("d") < 2){
			$date17 = 16;
			$forque = 16;
			$endque = 32;
			$dated = date("m", strtotime("previous month"));
		}
		include("conf.php");
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id and state like 'UA' and DAY(datefile) >= $forque and DAY(datefile) < $endque and MONTH(datefile) = $dated and YEAR(datefile) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		
	?>
	<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>				
					<tr>
						<td colspan = 7 align = center><h2> Pending Undertime Request </h2></td>
					</tr>
					<tr >
						<th>Date File</th>
						<th>Date of Undertime</th>
						<th>Name of Employee</th>
						<th>Reason</th>
						<th>Fr - To (Undertime)</th>
						<th>Number of Hrs/Minutes</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		$error = 0;
		if($result->num_rows > 0){
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
					<td>'. date("F j, Y", strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>
					<td width = 250 height = 70>'.$row["reason"].'</td>
					<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
					<td>'.$row["numofhrs"].'</td>	';
					 if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
				echo'				
					<td width = "200">
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Checked</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dundertime='.$row['undertime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button">Disapprove</a>
					</td>
				</tr>';
			}
		}
			
		}else{
			$error += 1;
		}
		$sql = "SELECT * FROM undertime,login where undertime.account_id = $accid and login.account_id = $accid and DAY(dateofundrtime) >= $forque and DAY(dateofundrtime) <= $endque and MONTH(dateofundrtime) = $dated and YEAR(dateofundrtime) = $datey ORDER BY state ASC,datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
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
					<td>'. date("F j, Y", strtotime($row["dateofundrtime"])).'</td>
					<td>'.$row["name"].'</td>
					<td width = 250 height = 70>'.$row["reason"].'</td>
					<td>'.$row["undertimefr"] . ' - ' . $row['undertimeto'].'</td>
					<td>'.$row["numofhrs"].'</td>
					<td><b>';
						if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['undertime_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}
					echo '<td></tr>';
			}
		}else{
			$error += 1;
		}

		if($error > 1){
			echo '<tr><td colspan = 7><h2>No record found</h2></td></tr>';
		}
	echo '</tbody></table></form>';
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
	if(date("d") < 2){
		$date17 = 16;
		$forque = 16;
		$endque = 32;
		$dated = date("m", strtotime("previous month"));
	}
	include("conf.php");
	$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state like 'UA' and DAY(obdatereq) >= $forque and DAY(obdatereq) < $endque and MONTH(obdatereq) = $dated and YEAR(obdatereq) = $datey ORDER BY obdate ASC";
	$result = $conn->query($sql);	
?>

	<form role = "form" action = "approval.php"    method = "get">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 9 align = center><h2> Pending Official Business Request </h2></td>
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
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php
		$error = 0;
		if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			
			$originalDate = date($row['obdate']);
			$newDate = date("F j, Y", strtotime($originalDate));
			$datetoday = date("Y-m-d");
			if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
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
					<td >'.$row["obreason"].'</td>	';
					if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
					}else{
					echo'
						<td width = "200">
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Checked</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-danger" role="button" id = "DAHR">Disapprove</a>
						</td>
					</tr>';
					}
		}
	}else{
		$error += 1;
	}
	$sql = "SELECT * FROM officialbusiness,login where login.account_id = $accid and officialbusiness.account_id = $accid and DAY(obdatereq) >= $forque and DAY(obdatereq) <= $endque and MONTH(obdatereq) = $dated and YEAR(obdatereq) = $datey ORDER BY state ASC,obdate ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$originalDate = date($row['obdate']);
				$newDate = date("F j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] && $row['state'] == 'UA' ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}		
				if($row["obtimein"] != null && $row['obtimeout'] != null){
					$split = " - ";
				}else{
					$split = "";
				}
				echo 
					'	<td>'.$newDate.'</td>
						<td>'.$row["obename"].'</td>
						<td>'.$row["obpost"].'</td>
						<td >'.$row["obdept"].'</td>
						<td>'.date("F j, Y", strtotime($row['obdatereq'])).'</td>					
						<td>'.$row["obtimein"] . $split . $row['obtimeout'].'</td>
						<td>'.$row["officialworksched"].'</td>				
						<td >'.$row["obreason"].'</td>	
					<td><b>';
							if($row['state'] == 'AHR'){
								echo 'Pending to Admin<br>';
								echo '<a class = "btn btn-danger"href = "?acc='.$_GET['ac'].'&update=1&o='.$row['officialbusiness_id'].'">Edit Application</a>';
							}else if($row['state'] == 'AAdmin'){
								echo '<p><font color = "green">Approved by Dep. Head</font></p> ';
							}else if($row['state'] == 'DAAdmin'){
								echo '<p><font color = "red">Dispproved by Dep. Head</font></p> '.$row['dareason'];
							}
						echo '<td></tr>';
		}
	}else{
		$error += 1;
	}
	if($error > 1){
		echo '<tr><td colspan = 9><h2>No record found</h2></td></tr>';
	}
	$conn->close();
	echo '</tbody></table></form>';
}
?>


<?php
	
	include('conf.php');
	if(isset($_GET['dovertime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['dovertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penot" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';
			
	}

	if(isset($_GET['upovertime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['upovertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and overtime_id = '$id' and state = 'UA'";
		$result = $conn->query($sql);		
		if($result->num_rows > 0){
			echo '<form action = "update-exec.php" method = "post" class = "form-group">
					<table class = "table table-hover" style = "width: 720px; border: none;" align = "center">
						<thead>
							<tr>
								<th colspan  = 3><h3> Update Time  </h3></th>
							</tr>
						</thead>';
			while($row = $result->fetch_assoc()){
				?>	
				<tr>
					<td><b>Date File: </b></td>
					<td><?php echo date("F j, Y", strtotime($row['datefile']));?></td>
				</tr>
				<tr>
					<td><b>Name of Employee: </b></td>
					<td><?php echo $row['nameofemp']?></td>
				</tr>
				<tr>
					<td><b>Position: </b></td>
					<td><?php echo $row['position'];?></td>
				</tr>
				<tr>
					<td><b>Department: </b></td>
					<td><?php echo $row['department'];?></td>
				</tr>
				<tr>
					<td><b>Date Of Overtime: </b></td>
					<td><?php echo date("F j, Y", strtotime($row['dateofot']));?></td>
				</tr>				
				<tr>
					<td><b>Reason (Work to be done): </b></td>
					<td><?php echo $row['reason'];?></td>	
				</tr>
			<div class = "ui-widget-content" style = "border: none;">
				<tr>
					<td><b>Start of OT: </b></td>
					<td><?php echo $row['startofot'];?></td>
				</tr>				
				<tr>
					<td><b>End of OT: </b></td>
					<td><?php echo $row['endofot'];?></td>
				</tr>
				<?php
					if($row['otbreak'] != null){
				?>
				<tr>
					<td><b>OT Break: </b></td>
					<td><input type = "text" class="form-control" readonly name = "otbreak" value = "<?php echo substr($row['otbreak'], 1);?>"/></td>					
				</tr>
				<?php } ?>
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
				<tr id = "rday" class = "form-inline" >
					<td><b>Official Work Sched: </b></td>
					<td>
					<?php if($row['officialworksched'] != "Restday"){ echo'
					
						<label for = "fr">From:</label><input onkeydown="return false;"name = "upoffr" value = "'.$ex1.'"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "to"class = "form-control"  />
						<label for = "to">To:</label><input onkeydown="return false;"name = "upoffto"value = "'. $ex2.'"readonly placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "fr"  />
					';
					}else{
						echo 'RESTDAY';
					}
					?>	
					</td>			
				</tr>
				<tr>
					<td><b>New Start of OT: </b></td>
					<td>
						<input id = "timein" onkeydown="return false;" value = "<?php echo $row['startofot'];?>" required class = "form-control" name = "hruptimein" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>
				</tr>				
				<tr>
					<td><b>New End of OT: </b></td>
					<td><input  value = "<?php echo $row['endofot'];?>" onkeydown="return false;"required class = "form-control" name = "hruptimeout" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>
				<tr id = "warning" style="display: none;">
					<td></td>
					<td>
						<div class="alert alert-danger fade in">
						  <strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b>
						</div>
					</td>
				</tr>
				<tr>
					<td align = "right"><label for = "dareason"> Based On </label></td>
					<td>
						<select class = "form-control" required name = "dareason" id = "dareason">
							<option value = "">-------</option>
							<option value = "Biometrics">Biometrics</option>
							<option value="C.S.R">C.S.R.</option>	
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td>
						<input value = "<?php echo $row['startofot'];?>" type = "hidden" name = "oldotstrt"/>
						<input value = "<?php echo $row['endofot'];?>" type = "hidden" name = "oldotend"/>
						<input value = "<?php echo $row['account_id'];?>" type = "hidden" name = "accid"/>
					</td>
				</tr>		
				<script type="text/javascript">
					$(document).ready(function(){	
						$('#obtimein').click(function() {
							$("#warning").hide();
						});
						$("#submits").click(function(){						
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
						$('input[name="hruptimein"]').ptTimeSelect();
						$('input[name="hruptimeout"]').ptTimeSelect();
						
					});
				</script>
		</div>
	<?php
			}
			echo '<tr>
					<td colspan = 2>
						<button onclick = "return confirm(\'Are you sure? (Edit Time)\');" type = "submit" class = "btn btn-primary" name = "hrupdatetime" value = "Submit Edit"><span class="glyphicon glyphicon-ok-sign"></span> Submit</button>
						<a href = "?ac=penot" class = "btn btn-danger"><span class="glyphicon glyphicon-menu-left"></span> Back</a>
					</td>
				</tr>
			  	</tr>
			  	<tr>
					<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
					<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
					<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
			  	</tr>
			</table>
		</form>';
		}
		
			
	}
?>

<?php
	include('conf.php');
	if(isset($_GET['dofficialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['dofficialbusiness_id']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penob" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "officialbusiness_id" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';		
	}
?>


<?php
	include('conf.php');
	if(isset($_GET['dundertime'])){
		$id = mysqli_real_escape_string($conn, $_GET['dundertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penundr" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "undertime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';	
}
?>


<?php
	include('conf.php');
	if(isset($_GET['dleave'])){
		$id = mysqli_real_escape_string($conn, $_GET['dleave']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 2><input type = "submit" class = "btn btn-primary" name = "subda"/>   <a href = "?ac=penlea" class = "btn btn-danger">Back</a></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "leave" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['acc'].'"/></td>
					</tr>
				</table>
			</form>';			
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
