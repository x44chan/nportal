<?php session_start(); ?>
<?php  $title="H.R. Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'HR'){
	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
	
	<?php
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
			<a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class = "btn-group btn-group-justified" style = "width: 80%">
			<a  type = "button"class = "btn btn-success" id = "forpndot" href = "?ac=penot"> Pending Overtime Request </a>
			<a  type = "button"class = "btn btn-success" id = "forpndob" href = "?ac=penob"> Pending Official Business Request </a>			
			<a  type = "button"class = "btn btn-success" id = "forpnlea" href = "?ac=penlea"> Pending Leave Request </a>		
			<a  type = "button"class = "btn btn-success" id = "fordpndun" href = "?ac=penundr"> Pending Undertime Request </a>	
		</div> 
	</div>
</div>
<div id = "needaproval" style = "margin-top: -30px;">		
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
		include("conf.php");
		$sql = "SELECT * FROM overtime,login where login.account_id = overtime.account_id and state like 'UA' and DAY(datefile) >= $forque and DAY(datefile) < $endque and MONTH(datefile) = $dated and YEAR(datefile) = $datey ORDER BY datefile ASC";
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
			while($row = $result->fetch_assoc()){				
				$originalDate = date($row['datefile']);
				$newDate = date("F j, Y", strtotime($originalDate));

				$datetoday = date("Y-m-d");
				if($datetoday >= $row['2daysred'] ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				echo 
					'	<td width = 180>'.$newDate.'</td>
						<td>'.date("F j, Y", strtotime($row["dateofot"])).'</td>
						<td>'.$row["nameofemp"].'</td>
						<td width = 250 height = 70>'.$row["reason"].'</td>
						<td>'.$row["startofot"] . ' - ' . $row['endofot'].'</td>
						<td>'.$row["officialworksched"].'</td>';
				if($row['state'] == 'UAACCAdmin'){
						echo '<td><strong>Pending to Admin<strong></td>';
				}else{
					echo '<td width = "200">
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&overtime='.$row['overtime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dovertime='.$row['overtime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td>
					</tr>';
				}
			}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Pending Overtime Request </h2>';
		}
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
		$sql = "SELECT * FROM nleave,login where login.account_id = nleave.account_id and state like 'UA' and MONTH(dateofleavfr) = $dated and YEAR(dateofleavfr) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
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
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&leave='.$row['leave_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dleave='.$row['leave_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
					</td>
					</tr>';
			}
		}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Pending Leave Request </h2>';
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
		$sql = "SELECT * FROM undertime,login where login.account_id = undertime.account_id and state like 'UA' and DAY(datefile) >= $forque and DAY(datefile) < $endque and MONTH(datefile) = $dated and YEAR(datefile) = $datey ORDER BY datefile ASC";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
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
						<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&undertime='.$row['undertime_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
						<a href = "?approve=DA'.$_SESSION['level'].'&dundertime='.$row['undertime_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
					</td>
				</tr>';
			}}
			echo '</tbody></table></form>';
		}else{
			echo '<h2 align = "center" style = "margin-top: 30px;"> No Pending Undertime Request </h2>';
		}
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
	$sql = "SELECT * FROM officialbusiness,login where login.account_id = officialbusiness.account_id and state like 'UA' and DAY(obdate) >= $forque and DAY(obdate) < $endque and MONTH(obdate) = $dated and YEAR(obdate) = $datey ORDER BY obdate ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
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
							<a onclick = "return confirm(\'Are you sure?\');" href = "approval.php?approve=A'.$_SESSION['level'].'&officialbusiness_id='.$row['officialbusiness_id'].'&ac='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "?approve=DA'.$_SESSION['level'].'&dofficialbusiness_id='.$row['officialbusiness_id'].'&acc='.$_GET['ac'].'"';?><?php echo'" class="btn btn-info" role="button" id = "DAHR">Disapprove</a>
						</td>
					</tr>';
					}
		}
		echo '</tbody></table></form>';
	}else{
		echo '<div id = "dash"><h2 align = "center" style = "margin-top: 30px;"> No Pending Official Request </h2></div>';
	}$conn->close();
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
