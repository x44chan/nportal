<?php
	session_start();
	include('header.php');	
	include('conf.php');

	$accid = $_SESSION['acc_id'];

	if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'TECH' || $_SESSION['level'] == 'Admin'){
	}else{
		echo '<script type="text/javascript">	window.location.replace("index.php");</script>';
	}

?>

<script type="text/javascript" src="css/src/jquery.ptTimeSelect2.js"></script>
<link rel="stylesheet" type="text/css" href="css/src/jquery.ptTimeSelect2.css" />
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="schedtimein"]').ptTimeSelect2();
		$('input[name="schedtimeout"]').ptTimeSelect2();
		$('#schedulingTble').DataTable({
		    "iDisplayLength": 25,
		    "order": [[ 0, "desc" ]]  	
		});
	});
</script>
<style type="text/css">
	label{
		font-size: 14px;
	}
	tr th{
		font-size: 14px;
	}
	tr td{
		font-size: 13px;
	}
</style>
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); if($_SESSION['level'] != 'Admin'){?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "index.php">Home</a>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href="#" id = "newovertime">Overtime Request</a></li>
				  <li><a href="#" id = "newoffb">Official Business Request</a></li>
				  <li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
				  <li><a href="#" id = "newundertime">Undertime Request Form</a></li>
				  <li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
				  <?php
				  	if($_SESSION['category'] == "Regular"){
				  ?>
				  	<li class="divider"></li>
				  	<li><a href="#"  data-toggle="modal" data-target="#cashadv">Cash Advance Form</a></li>
				  	<li><a href="#"  data-toggle="modal" data-target="#loan">Loan Form</a></li>
				  <?php
				  	}
				  ?>
				</ul>
			</div>
			<?php if($_SESSION['level'] == "HR") {?>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type="button" data-toggle="modal" data-target="#newAcc">Add User</a></li>
				  <li><a  type = "button" href = "tech-sched.php">Tech Scheduling</a></li>
				  <li><a href="hr-emprof.php" id = "newovertime">Employee Profile</a></li>
				  <li><a  type = "button" href = "hr-timecheck.php">In/Out Reference</a></li>
				</ul>
			</div>	
			<?php 
			} 
			if($_SESSION['level'] == "HR"){
				$hrf = "hr-req-app.php";
				$hrf2 = "hr-req-dapp.php";
			}else{
				$hrf = "techsupervisor-app.php";
				$hr2 = "techsupervisor-dapp.php";
				echo '<a  type = "button"class = "btn btn-primary active"  href = "tech-sched.php" >Tech Scheduling</a>';
			}
			?>
			<a type = "button" class = "btn btn-primary"  href = "<?php echo $hrf;?>" id = "showapproveda"> Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "<?php echo $hrf2;?>"  id = "showdispproveda"> Dispproved Request</a>
			<?php }else{ ?><br><br>
			<div class="btn-group btn-group-lg">
				<a href = "admin.php"  type = "button"class = "btn btn-primary"  id = "showneedapproval">Home</a>	
				<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>	     			
				<div class="btn-group btn-group-lg">
					<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee List <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
					  <li><a href = "admin-emprof.php" type = "button">Employee Profile</a></li>
					  <li><a href = "admin-emprof.php?loan" type = "button">Employee Loan List</a></li>
					  <li><a href = "admin-emprof.php?sumar=leasum" type = "button">Employee Leave Summary</a></li>
					</ul>
				</div>
				<div class="btn-group btn-group-lg">
					<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
					  <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
					  <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
					  <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
					</ul>
				</div>
				<div class="btn-group btn-group-lg">
					<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">H.R. / Tech Modules <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
					  <li><a href = "?login_log" type = "button">Login Log</a></li>
					  <li><a type = "button" href = "tech-sched.php">Tech Schedule</a></li>
					  <li><a type = "button" href = "hr-timecheck.php">H.R Time Checking</a></li>
					</ul>
				</div>
				<a type = "button"class = "btn btn-primary"  href = "admin-req-app.php" id = "showapproveda">Approved Request</a>
				<a type = "button"class = "btn btn-primary" href = "admin-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<?php } ?>
			<a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
	</div>
</div>
<?php
	if($_SESSION['level'] == 'Admin'){
		if(isset($_GET['login_log'])){
			include 'login_log.php';
			echo '</div><div style = "display: none;">';
		}
	}

?>
<?php
	if(isset($_GET['modify']) && $_SESSION['level'] == 'TECH'){
		$tid = mysql_escape_string($_GET['modify']);
		$query = "SELECT * FROM `tech_sched`,`login` where tech_sched.account_id = login.account_id and techsched_id = '$tid' and CURDATE() < scheddate";
		$data = $conn->query($query)->fetch_assoc();
		$accid = $data['account_id'];
		if($accid == null){
			echo '<script type="text/javascript">window.location.replace("tech-sched.php"); </script>';
		}
?>
<div class = "container">
	<div class="row">
		<div class="col-xs-12">
			<u><i><h4>Edit Schedule</h4></i></u>
			<hr>
		</div>
	</div>
	<form action = "" method="post">
		<div class="row">
			<div class="col-xs-3">
				<label>Name</label>
				<p style="margin-left: 10px"><i><?php echo $data['fname'] . ' ' . $data['lname'];?></i></p>
			</div>
			<div class="col-xs-2">
				<label>Date</label>
				<input type = "date" value = "<?php echo $data['scheddate'];?>" name = "scheddate" class = "form-control"/>
			</div>
			<div class="col-xs-2">
				<label>Time In</label>
				<input type = "text" value = "<?php echo $data['schedtimein'];?>" name = "schedtimein" class = "form-control"/>
			</div>
			<div class="col-xs-2">
				<label>Time Out</label>
				<input type = "text" value = "<?php echo $data['schedtimeout'];?>" name = "schedtimeout" class = "form-control"/>
			</div>
			<div class="col-xs-3">
				<label>Location</label>
				<textarea class="form-control"><?php echo $data['location'];?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" align="center">
				<button class="btn btn-primary" name = "updatesched" type = "submit"> Edit Schedule </button> <a href = "tech-sched.php" class="btn btn-danger"> Back </a>
			</div>
		</div>
	</form>
</div>
<?php
	}
	if(isset($_POST['updatesched'])){
		$scheddate = mysql_escape_string($_POST['scheddate']);
		$schedtimein = mysql_escape_string($_POST['schedtimein']);
		$schedtimeout = mysql_escape_string($_POST['schedtimeout']);
		$modify = mysql_escape_string($_GET['modify']);
		$stmt = "UPDATE `tech_sched` set 
			scheddate = '$scheddate', schedtimein = '$schedtimein', schedtimeout = '$schedtimeout'
		where account_id = '$accid' and techsched_id = '$modify' and CURDATE() < scheddate";
	
		if ($conn->query($stmt) === TRUE) {
		
	    	echo '<script type="text/javascript">window.location.replace("tech-sched.php"); </script>';
	    	
	  	}
	}
	if(isset($_GET['del'])){
		$del = mysql_escape_string($_GET['del']);
		$stmt = "DELETE FROM `tech_sched` where techsched_id = '$del'";
	
		if ($conn->query($stmt) === TRUE) {
			echo '<script type="text/javascript">alert("Deleted");window.location.replace("tech-sched.php"); </script>';
		}
	}

?>
<div id = "dash" <?php if(isset($_GET['late_filing']) || isset($_GET['modify'])){ echo ' style = "display: none;" ' ; } ?> >
<?php 
	if(!isset($_GET['view'])){	
		if(isset($_POST['datefr']) && isset($_POST['dateto'])){
			echo '<script type="text/javascript">	window.reload();</script>';
			$strt = mysql_escape_string($_POST['datefr']);
			$end = mysql_escape_string($_POST['dateto']);
			$sql = "SELECT * FROM tech_sched,login  where tech_sched.account_id = login.account_id and scheddate BETWEEN '$strt' and '$end' order by scheddate desc";
		}else{
			$sql = "SELECT * FROM tech_sched,login  where tech_sched.account_id = login.account_id and CURDATE() <= scheddate order by scheddate desc";
		}
		$result = $conn->query($sql);	
		if(isset($strt) && isset($end)){
				$notifs = '<i><b>From: </b>'. date("M j, Y", strtotime($strt)) . '<b> To: </b>' . date("M j, Y", strtotime($end)) .'</i>';				
			}else{
				$notifs = "";
				$strt = date("Y-m-d");
				$end = date("Y-m-d", strtotime("+5 day"));
			}
		echo '<div class = "container" style = "margin-top: -25px;">
				<div class = "row" style="margin-bottom: 10px;">
					<div class="col-xs-12">
				    	<div align = "left">
							<i><h4 style = "text-decoration: underline;"><span class = "icon-file-text2"></span> Technician Schedule </h4></i>
							'.$notifs.'
						</div>
				    </div>
				</div>
			</div>	
			<div class = "row" style = "margin-top: -30px;">
				<div class="col-xs-12" align = "center">
			    	<div>
						<i><h4>Search by Date Range</h4></i>
					</div>
			    </div>
			</div>
			<form class="form-inline" action = "" role = "form" method = "post">
				<div class = "row">
					<div class="col-xs-12"align = "center">
						<label for = "datestrt">Date From:</label>
						<input required type = "date" class = "form-control input-sm" name = "datefr" value = "'.$strt.'"/>					
						<label style = "margin-left: 10px;"for = "datestrt">Date To:</label>
						<input required type = "date" class = "form-control input-sm" name = "dateto" value = "'.$end.'"/>					
						<button style = "margin-left: 10px;"class = "btn btn-primary btn-sm" name = "daterange"><span class="icon-search"></span> Search</button>
						<a href = "tech-sched.php" class = "btn btn-danger  btn-sm" name = "daterange"><span class="icon-spinner11"></span> Clear</a>
					</div>
				</div>
			</form>
			<div class ="row">
				<div class = "col-xs-12">
					<hr>
				</div>
			</div>
			';

	if($_SESSION['level'] == 'TECH'){
			echo '<div class = "container"><div class = "row">
					<div class = "col-xs-12" style = "margin-top: -30px;">
						<i><h4 style = "text-decoration: underline;"><span class = "icon-file-text2"></span> Scheduling </h4></i>
					</div>
				</div>
				<form action = "" method = "post">
					<div class = "row">
						<div class = "col-xs-3">
							<label>Select Technician</label>
							<select name = "tech" required class = "form-control input-sm" >
								<option value = ""> - - - - - - - </option>';
							$query = "SELECT * FROM login where position like '%ervice%' and active != '0'";
							$result2 = $conn->query($query);	
							if($result2->num_rows > 0){
								while ($row2 = $result2->fetch_assoc()) {
									echo '<option style = "text-align: capitalize;" value = "' . $row2['account_id'] . '">' . $row2['fname'] . ' ' . $row2['lname'] . '</option>';
								}
							}
		echo '				</select>
						</div>
						<div class = "col-xs-2">
							<label>Date</label>
							<input required type = "date" name = "scheddate" class = "form-control input-sm" value = "' . date("Y-m-d", strtotime("+1 day")) . '"/>
						</div>
						<div class = "col-xs-2">
							<label>Time In</label>
							<input required type = "text" name = "schedtimein" class = "form-control input-sm" placeholder = "Click to set Tiime"/>
						</div>
						<div class = "col-xs-2">
							<label>Time Out</label>
							<input required type = "text" name = "schedtimeout" class = "form-control input-sm" placeholder = "Click to set Tiime"/>
						</div>
						<div class = "col-xs-3">
							<label>Location</label>
							<input required type = "text" name = "location" class = "form-control input-sm" placeholder = "Enter Location"/>
						</div>
					</div>
					<div class = "row">
						<div class = "col-xs-12" align ="center">
							<button class = "btn btn-primary btn-sm" name = "schedsub">Submit</button>
						</div>
					</div>
				</form>
			</div>
			<div class ="row">
				<div class = "col-xs-12">
					<hr>
				</div>
			</div>';
		}
		echo'
			
			<div class="table-responsive" id = "csr123" style = "margin: 0px 10px 0px 10px;">
				<table class = "table table-hover" id = "schedulingTble">
					<thead>
						<th>Date</th>
						<th>Technician</th>
						<th>Time In - Time Out</th>
						<th>Location</th>
						';
					if($_SESSION['level'] == 'TECH'){
						echo '<th>Action</th>';
					}else{
						echo '<th>Status</th>';
					}

			echo '
					</thead>
					<tbody>';
		if($result->num_rows > 0){		
			while($row = $result->fetch_assoc()){
				echo '<tr>';
					echo '<td>' . date("M j, Y", strtotime($row['scheddate'])) . '</td>';
					echo '<td>' . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'].'</td>';
					echo '<td>' . $row['schedtimein'] . ' - ' . $row['schedtimeout'] . '</td>';
					echo '<td>' . $row['location'] . '</td>';
					if($_SESSION['level'] == 'TECH' && date("Y-m-d") < date("Y-m-d", strtotime($row['scheddate']))){
						echo '<td><a href = "?modify=' . $row['techsched_id'] .'" class = "btn btn-warning"><span class="glyphicon glyphicon-edit"></span></a> <a onclick = "return confirm(\'Are you sure?\');"href = "?del=' . $row['techsched_id'] .'" class = "btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>';
					}elseif(date("Y-m-d") == date("Y-m-d", strtotime($row['scheddate']))){
						echo '<td><b>Ongoing</b></td>';
					}elseif(date("Y-m-d") < date("Y-m-d", strtotime($row['scheddate']))){
						echo '<td><b>Pending</b></td>';
					}else{
						echo '<td><b>Completed</b></td>';
					}
					//echo '<td><a style = "width: 100px;" target = "_blank" href = "?view='.$row['techsched_id'] .'" class = "btn btn-primary"><span class = "icon-eye"></span> View</a></td>';
				echo '</tr>';
			}
		
		}
		echo '</tbody></table>';
	}
?>
<?php 
	if(isset($_POST['schedsub'])){
		//if(date("Y-m-d", strtotime($_POST['scheddate'])) <= date("Y-m-d")){
		//	echo '<script type="text/javascript"> alert("Wrong date."); window.location.replace("tech-sched.php");</script>';
		//}else{
			$stmt = $conn->prepare("INSERT INTO `tech_sched` (account_id, schedtimein, schedtimeout, scheddate, location) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("issss", $_POST['tech'], $_POST['schedtimein'], $_POST['schedtimeout'], $_POST['scheddate'], $_POST['location']);
			$stmt->execute();
			echo '<script type="text/javascript">	window.location.replace("tech-sched.php");</script>';
		//}
		
	}

?>

</div>
<?php if($_SESSION['level'] != 'Admin'){ include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'defaultpass'){
		include('up-pass.php');
	}else if($_SESSION['201date'] == null && $_SESSION['level'] != 'Admin'){
	?>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
    backdrop: 'static',
    keyboard: false
  });
});
</script>
<?php }?>
<?php include("footer.php");?>

<?php include("req-form.php"); }else{?>
<div id = "newuser" class = "form-group" style = "display: none;">
	<form role = "form" action = "newuser-exec.php" method = "post">
		<table id = "myTable" align = "center" width = "450">
			<tr>
				<td colspan = 5 align = "center"><h2>New Account</h2></td>
			</tr>
			<tr>
				<td colspan = 5><h3><font color = "red">Do not use your personal password</font></h3></td>
			</tr>
			<tr>
				<td>Username: </td>
				<td><input placeholder = "Enter Username" pattern=".{4,}" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input placeholder = "Enter Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regpword"/></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input placeholder = "Enter Confirm Password" required pattern=".{6,}" title="Six or more characters" class ="form-control"type = "password" name = "regcppword"/></td>
			</tr>
			<tr>
				<td>Account Level:</td>
				<td>
					<select name = "level" class ="form-control">
						<option value = "">------------
						<option value = "HR">HR
						<option value = "ACC">Accounting
						<option value = "TECH">Technician Supervisor
						<option value = "Admin">Admin
					</select>
				</td>
			</tr>			
			<tr>
				<td colspan = 2 align = center><input class = "btn btn-default" type = "submit" name = "regsubmit" value = "Submit"/></td>
			</tr>
		</table>
	</form>
</div>
<?php } ?>