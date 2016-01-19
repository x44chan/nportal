<?php
	session_start();
	include('header.php');	
	include('conf.php');

	$accid = $_SESSION['acc_id'];

	if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'Admin'){
	}else{
		echo '<script type="text/javascript">	window.location.replace("index.php");</script>';
	}

?>

<script type="text/javascript" src="css/src/jquery.ptTimeSelect2.js"></script>
<link rel="stylesheet" type="text/css" href="css/src/jquery.ptTimeSelect2.css" />
<script type="text/javascript">
$(document).ready(function(){	
	$('input[name="schedtimein"]').click(function() {
		$("#warning").hide();
	});
	$("button[name='schedsub']").click(function(){						
		if($('input[name="schedtimein"]').val() == "" && $('input[name="schedtimeout"]').val() == "" ){
			$('input[name="schedtimein"]').attr("required", true);
			$('input[name="schedtimeout"]').attr("required", true);
		}else{
			$('input[name="schedtimein"]').attr("required", false);
			$('input[name="schedtimeout"]').attr("required", false);
		}
	});
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		//$('input[name="schedtimein"]').ptTimeSelect2();
		//$('input[name="schedtimeout"]').ptTimeSelect2();
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
				  <li><a data-toggle="modal" data-target="#newAcc">Add User</a></li>
				  <li><a href = "tech-sched.php">Tech Scheduling</a></li>
				  <li><a href = "hr-emprof.php">Employee Profile</a></li>
				  <li><a href = "hr-timecheck.php">In/Out Reference</a></li>
				  <li class="divider"></li>
				  <li><a href = "accounting-petty.php">Petty List</a></li>
				</ul>
			</div>
			<?php 
			} 
			if($_SESSION['level'] == "HR"){
				$hrf = "hr-req-app.php";
				$hrf2 = "hr-req-dapp.php";
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
					  <li class="divider"></li>
          			  <li><a type = "button" href = "admin-petty.php?pettydate"> Petty Date Summary </a></li>
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
?>
<div id = "dash">
<?php 
	if(!isset($_GET['view'])){	
		if(isset($_POST['datefr']) && isset($_POST['dateto'])){
			$strt = mysql_escape_string($_POST['datefr']);
			$end = mysql_escape_string($_POST['dateto']);
			$sql = "SELECT * FROM hrtime,login  where hrtime.account_id = login.account_id and tdate BETWEEN '$strt' and '$end' order by tdate desc";
		}else{
			$sql = "SELECT * FROM hrtime,login  where hrtime.account_id = login.account_id and CURDATE() >= tdate order by tdate desc";
		}
		$result = $conn->query($sql);	
		if(isset($strt) && isset($end)){
				$notifs = '<i><b>From: </b>'. date("M j, Y", strtotime($strt)) . '<b> To: </b>' . date("M j, Y", strtotime($end)) .'</i>';				
			}else{
				$notifs = "";
				$strt = date("Y-m-d", strtotime("-1 day"));
				$end = date("Y-m-d", strtotime("+5 day"));
			}
		echo '<div class = "container" style = "margin-top: -25px;">
				<div class = "row" style="margin-bottom: 10px;">
					<div class="col-xs-12">
				    	<div align = "left">
							<i><h4 style = "text-decoration: underline;"><span class = "icon-file-text2"></span> Time Checking </h4></i>
							'.$notifs.'
						</div>
				    </div>
				</div>
			</div>	
			<div class = "row" >
				<div class="col-xs-12" align = "center" style = "margin-top: -30px;">
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
						<a href = "hr-timecheck.php" class = "btn btn-danger  btn-sm" name = "daterange"><span class="icon-spinner11"></span> Clear</a>
					</div>
				</div>
			</form>
			<div class ="row">
				<div class = "col-xs-12">
					<hr>
				</div>
			</div>
			';

	if($_SESSION['level'] == 'HR'){
			echo '<div class = "container"><div class = "row">
					<div class = "col-xs-12" style = "margin-top: -30px;">
						<i><h4 style = "text-decoration: underline;"><span class = "icon-file-text2"></span> Add time </h4></i>
					</div>
				</div>
				<form action = "" method = "post">
					<div class = "row">
						<div class = "col-xs-3">
							<label>Select Employee</label>
							<select name = "tech" required class = "form-control input-sm" >
								<option value = ""> - - - - - - - </option>';
							$query = "SELECT * FROM login where level != 'Admin' and position != 'House Helper' and active != '0' order by lname";
							$result2 = $conn->query($query);	
							if($result2->num_rows > 0){
								while ($row2 = $result2->fetch_assoc()) {
									if(strtoupper($row2['lname']) == $row2['fname'] || strtoupper($row2['lname']) == $row2['lname']){
										$row2['fname'] = strtolower($row2['fname']);
										$row2['lname'] = strtolower($row2['lname']);
									}
									echo '<option style = "text-align: capitalize;" value = "' . $row2['account_id'] . '">' . ucfirst($row2['lname']) . ', ' . ucfirst($row2['fname']) . '</option>';
								}
							}
			echo '				</select>
						</div>
						<div class = "col-xs-2">
							<label>Date</label>
							<input required autocomplete = "off" type = "date" name = "scheddate" class = "form-control input-sm" value = "' . date("Y-m-d", strtotime("-1 day")) . '"/>
						</div>
						<div class = "col-xs-2">
							<label>Time In</label>
							<input type = "text" autocomplete = "off" name = "schedtimein" class = "form-control input-sm" placeholder = "8:00 AM"/>
						</div>
						<div class = "col-xs-2">
							<label>Time Out</label>
							<input type = "text" autocomplete = "off" name = "schedtimeout" class = "form-control input-sm" placeholder = "6:00 PM"/>
						</div>
						<div class = "col-xs-3">
							<label>Remarks</label>
							<textarea required autocomplete = "off" type = "text" name = "location" class = "form-control input-sm" placeholder = "Enter Remarks"></textarea>
						</div>
					</div>
					<div class = "row" id = "warning" style = "display: none;">
						<div class = "col-xs-12" align = "center">
							<div class="alert alert-danger fade in">
	 							<small><strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b></small>
	 						</div>
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
						<th>Employee</th>
						<th>Time In - Time Out</th>
						<th>Remarks</th>
					</thead>
					<tbody>';
		if($result->num_rows > 0){		
			while($row = $result->fetch_assoc()){
				if(strtoupper($row['lname']) == $row['fname'] || strtoupper($row['lname']) == $row['lname'] || strtoupper($row['mname']) == $row['mname']){
					$row['fname'] = strtolower($row['fname']);
					$row['lname'] = strtolower($row['lname']);
					$row['mname'] = strtolower($row['mname']);
				}
				echo '<tr>';
					echo '<td>' . date("M j, Y", strtotime($row['tdate'])) . '</td>';
					echo '<td>' . ucfirst($row['lname']) . ', ' . ucfirst($row['fname']) . ' ' . ucfirst($row['mname']) .'</td>';
					echo '<td>' . $row['tin'] . ' - ' . $row['tout'] . '</td>';
					echo '<td>' . $row['remarks'] . '</td>';
				echo '</tr>';
			}
		
		}
		echo '</tbody></table>';
	}
?>
<?php 
	if(isset($_POST['schedsub'])){
		if(date("Y-m-d", strtotime($_POST['scheddate'])) > date("Y-m-d")){
			echo '<script type="text/javascript"> alert("Wrong date."); window.location.replace("hr-timecheck.php");</script>';
		}else{
			$stmt = $conn->prepare("INSERT INTO `hrtime` (account_id, tin, tout, tdate, remarks, hrdate) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("isssss", $_POST['tech'], $_POST['schedtimein'], $_POST['schedtimeout'], $_POST['scheddate'], $_POST['location'], date("Y-m-d"));
			$stmt->execute();
			echo '<script type="text/javascript">	window.location.replace("hr-timecheck.php");</script>';
		}
		
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