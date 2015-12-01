<?php
	session_start();
	include('header.php');	
	include('conf.php');

	$accid = $_SESSION['acc_id'];

	if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'TECH'){
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
				  <li><a href="hr-emprof.php" id = "newovertime">Employee Profile</a></li>
				  <li><a type="button" data-toggle="modal" data-target="#newAcc">Add User</a></li>
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
			}
			?>	
			<a type = "button" class = "btn btn-primary"  href = "tech-sched.php" id = "showapproveda"> Tech Schedule</a>	
			<a type = "button" class = "btn btn-primary"  href = "<?php echo $hrf;?>" id = "showapproveda"> Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "<?php echo $hrf2;?>"  id = "showdispproveda"> Dispproved Request</a>
			<a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
	</div>
</div>
<div id = "dash">
<?php 
	if(!isset($_GET['view'])){	
		if(isset($_POST['datefr']) && isset($_POST['dateto'])){
			$strt = mysql_escape_string($_POST['datefr']);
			$end = mysql_escape_string($_POST['dateto']);
			$sql = "SELECT * FROM tech_sched,login  where tech_sched.account_id = login.account_id and scheddate BETWEEN '$strt' and '$end' order by scheddate desc";
		}else{
			$sql = "SELECT * FROM tech_sched,login  where tech_sched.account_id = login.account_id and CURDATE() = scheddate order by scheddate desc";
		}
		$result = $conn->query($sql);	
		if(isset($strt) && isset($end)){
				$notifs = '<i><b>From: </b>'. date("M j, Y", strtotime($strt)) . '<b> To: </b>' . date("M j, Y", strtotime($end)) .'</i>';				
			}else{
				$notifs = "";
				$strt = date("Y-m-d");
				$end = date("Y-m-d");
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
						<input required type = "date" class = "form-control" name = "datefr" value = "'.$strt.'"/>					
						<label style = "margin-left: 10px;"for = "datestrt">Date To:</label>
						<input required type = "date" class = "form-control" name = "dateto" value = "'.$end.'"/>					
						<button style = "margin-left: 10px;"class = "btn btn-primary" name = "daterange"><span class="icon-search"></span> Search</button>
						<a href = "?module=daterange" class = "btn btn-danger" name = "daterange"><span class="icon-spinner11"></span> Clear</a>
					</div>
				</div>
			</form>
			<div class = "container">
				<div class = "row">
					<div class = "col-xs-12">
						<i><h4 style = "text-decoration: underline;"><span class = "icon-file-text2"></span> Scheduling </h4></i>
					</div>
				</div>
				<form action = "" method = "post">
					<div class = "row">
						<div class = "col-xs-3">
							<label>Select Technician</label>
							<select name = "tech" required class = "form-control input-sm" >
								<option value = ""> - - - - - - - </option>';
							$query = "SELECT * FROM login where position like '%ervice%'";
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
							<input required type = "date" name = "scheddate" class = "form-control input-sm" value = "' . date("Y-m-d") . '"/>
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
							<input required type = "text" name = "location" class = "form-control input-sm" placeholder = "Click to set Tiime"/>
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
			</div>
			<div class="table-responsive" id = "csr123">
				<table class = "table table-hover" id = "myTable2">
					<thead>
						<th>Date</th>
						<th>Technician</th>
						<th>Time In - Time Out</th>
						<th>Location</th>
					</thead>
					<tbody>';
		if($result->num_rows > 0){		
			while($row = $result->fetch_assoc()){
				echo '<tr>';
					echo '<td>' . date("M j, Y", strtotime($row['scheddate'])) . '</td>';
					echo '<td>' . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'].'</td>';
					echo '<td>' . $row['schedtimein'] . ' - ' . $row['schedtimeout'] . '</td>';
					echo '<td>' . $row['location'] . '</td>';
					//echo '<td><a style = "width: 100px;" target = "_blank" href = "?view='.$row['techsched_id'] .'" class = "btn btn-primary"><span class = "icon-eye"></span> View</a></td>';
				echo '</tr>';
			}
		
		}
		echo '</tbody></table>';
	}
?>
<?php 
	if(isset($_POST['schedsub'])){
		$stmt = $conn->prepare("INSERT INTO `tech_sched` (account_id, schedtimein, schedtimeout, scheddate, location) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("issss", $_POST['tech'], $_POST['schedtimein'], $_POST['schedtimeout'], $_POST['scheddate'], $_POST['location']);
		$stmt->execute();
		echo '<script type="text/javascript">	window.location.replace("tech-sched.php");</script>';
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
<?php }?>
<?php include("footer.php");?>

<?php include("req-form.php"); ?>