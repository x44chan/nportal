<?php session_start(); ?>
<?php  $title="Accounting Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
	include("conf.php");
?>
<?php	if($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	}	?>
<style type="text/css">
	#reports{
		font-size: 12px;
	}
	#reports label, #reports label{
		font-size: 13px;
	}
	<?php
		if(isset($_GET['print'])){
			echo 'body { visibility: hidden; }';
		}
	?>
	@media print {

		body * {
	    	visibility: hidden;
	    
	  	}
	  	<?php if(isset($_GET['print'])){ ?>
	  	#reportg #red {
	  		color: red !important;
	  	}
	  	#reportg #green{
	  		color: green !important;
	  	}
	  	#reportg h4{
	  		font-size: 20px !important;
	  	}
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#reportg, #reportg * {
	    	visibility: visible;
	 	}
		#reportg th{
	  		font-size: 16px !important;
	  		width: 0;
		} 
		#reportg td{
	  		font-size: 16px !important;
	  		bottom: 0px;
	  		padding: 1px;
	  		max-width: 210px;
		}
		#reportg p{
	  		font-size: 15px !important;
		}
		#totss{
			font-size: 17px !important;
		}
		#reportg {
	   		position: absolute;
	    	left: 0;
	    	top: 0;
	    	right: 0;
	  	}
	  	#backs{
	  		display: none;
	  	}
	  	p{
	  		font-size: 17px !important;
	  	}
	}
	<?php } ?>
	#myTablelea td, #myTablelea th{
		font-size: 13px;
	}

</style>
<script type="text/javascript" src="css/src/jquery.ptTimeSelect2.js"></script>
<link rel="stylesheet" type="text/css" href="css/src/jquery.ptTimeSelect2.css" />
<div align = "center">
	<?php if($_SESSION['level'] == 'ACC'){ ?>
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "?ac=penot">Home</a>
			<?php if($_SESSION['acc_id'] != '48'){ ?>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
			<?php
				include 'caloan/reqbut.php';
			?>
			<?php } ?>
			<div class="btn-group btn-group-lg">
		        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
		        	<ul class="dropdown-menu" role="menu">
		        		<li><a href = "acc-report.php">Cut Off Summary</a></li>
		            	<li><a href="hr-emprof.php">Employee Profile</a></li>
		            	<li><a href = "acc-report.php?sumar=leasum">Employee Leave Summary</a></li>
		            	<li><a href = "helper.php">Helper CA</a></li>
		            	<li><a data-toggle="modal" data-target="#newAcc">Add User</a></li>
		          	</ul>
		    </div>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?replenish">Petty Replenish Report</a></li>
				  <li class="divider"></li>
				  <li><a type = "button" href = "accounting-petty.php?pettydate"> Petty Date Summary </a></li>
				  <li><a type = "button" href = "accounting-petty.php?expenses"> Expenses </a></li>
				  <li><a type = "button" href = "accounting-petty.php?expsum"> BIR Expenses </a></li>
				   <li><a type = "button" href = "accounting.php?expn"> Sales Project Expenses </a></li>
				</ul>
			</div>
			<?php if($_SESSION['acc_id'] != '48'){ ?>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">My Request Status <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "req-all.php?appot">All Request</a></li>
				  <li><a href = "acc-req-app.php">My Approved Request</a></li>
				  <li><a href = "acc-req-dapp.php">My Disapproved Request</a></li>	
				</ul>
			</div>	
			<?php } ?>
			<a type = "button" class = "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class="btn-group btn-group" role="group">
			<a role = "button"class = "btn btn-success"  href = "?ac=penot"> Overtime Req. Status </a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penob"> Official B. Req. Status</a>			
			<a role = "button"class = "btn btn-success"  href = "?ac=penlea"> Leave Req. Status</a>		
			<a role = "button"class = "btn btn-success"  href = "?ac=penundr"> Undertime Req. Status</a>
			<?php if($_SESSION['acc_id'] != '48'){ ?>
			<a role = "button"class = "btn btn-success"  href = "?ac=penhol"> Holiday Req. Status</a>
			<a role = "button"class = "btn btn-success"  href = "?ac=penpty"> Petty Req. Status</a>
			<?php
				if($_SESSION['category'] == "Regular"){
					echo '
						<a role = "button"class = "btn btn-success"  href = "?ac=penca"> Cash Adv. Req. Status</a>
						';
				}
			?>	
			<?php } ?>
			<a role = "button"class = "btn btn-success"  href = "?ac=penloan"> Loan Req. Status</a>
		</div>
	</div>
	<?php } else { ?>
		<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a href = "admin.php"  type = "button"class = "btn btn-primary"  id = "showneedapproval">Home</a>	
			<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>			
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee List <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a href = "admin-emprof.php" type = "button">Employee Profile</a></li>
				  <li><a href = "admin-emprof.php?loan" type = "button">Employee Loan List</a></li>
				  <li><a href = "admin-emprof.php?sumar=leasum" type = "button">Employee Leave Summary</a></li>
				  <li><a href = "admin-emprof.php?leaverep" type = "button">Employee Leave Report</a></li>
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
				  <li><a type = "button" href = "admin-petty.php?expenses"> Expenses </a></li>
				  <li><a type = "button" href = "admin-petty.php?expn"> Sales Project Expenses </a></li>
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
			<a class="btn btn-danger"  href = "logout.php"  role="button">Logout</a>
		</div><br><br>
	</div>
	<?php } ?>
</div>
<?php
	if(isset($_GET['datefr']) && isset($_GET['dateto'])){
		$datefr = mysqli_real_escape_string($conn, $_GET['datefr']);
		$dateto = mysqli_real_escape_string($conn, $_GET['dateto']);
	}else{
		$datefr = date("Y-m-d");	
		$dateto = date("Y-m-d");
	}
?>
<div id = "needaproval">
	<div class="row" >
		<form action="" method="get">
			<div class="col-xs-3"></div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name = "datefr" type = "date" <?php echo ' value = "' . $datefr . '"'; ?> name = "datefr"/>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "dateto" type = "date" <?php echo ' value = "' . $dateto . '"';  ?> name = "dateto"/>
			</div>
			<div class="col-xs-5">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<a type="submit" class="btn btn-danger btn-sm" name ="represet" href = "?"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
				</div>
			</div>
		</form>
	</div>
	<div id = "reportg">
	<h4 style="margin-left: 20px;" style="visibility: hidden !important;" id = "xxhead"><i><u> Helper Cash Advance </u></i></h4>
	<h4 align="center"><i><u> <?php if(!isset($_GET['id'])){ echo date("M j, Y", strtotime($datefr)) . ' - ' . date("M j, Y", strtotime($dateto)); }?> </u></i></h4>
	<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<th width="25%">Date File</th>
					<th width="25%">Helper Name</th>				
					<th width="25%">Amount</th>
					<th width="25%">Reason</th>
				</tr>
			</thead>
			<tbody>
	<?php		
		if(isset($_GET['id']) && $_GET['id'] != ""){
			$xxid = " helperca_id = '" . mysqli_real_escape_string($conn, $_GET['id']) . "' and ";
		}else{
			$xxid = "";
		}
		$sql = "SELECT * FROM helperca where  $xxid datefile BETWEEN '$datefr' and '$dateto'";
		$result = $conn->query($sql);
		$total = 0;
		while($row = $result->fetch_assoc()){	
			$total += $row['amount'];
			echo 
				'<tr>
					<td>'. date("M j, Y", strtotime($row['datefile'])) . '</td>
					<td>'. $row['hname'] .'</td>						
					<td>₱ '. number_format($row["amount"],2) .'</td>
					<td>'. $row["reason"] .'</td>';
				if(!isset($_GET['print'])){
					echo '<td><a href = "?datefr='. $datefr . '&dateto='. $dateto.'&hname='. $row['hname'] . '&id='.$row['helperca_id'].'&print" class = "btn btn-primary"> Print </a></td>';
				}
			echo '</tr>';
		}
			if(!isset($_GET['id'])){
				echo '<tr>
					<td></td><td style = "text-align: right;"><b>Total:</td>
					<td>₱ ' . number_format($total,2) . '</td>
					<td></td><td></td>
					</tr>';
			}

		?>
		</tbody>
		<?php if(isset($_GET['hname']) && $_GET['hname'] != ""){ ?>
			<tr align="center" style="border-top: none !important;">
				<td><?php echo '<br><br><br>___________________________<br>'.$_GET['hname']; ?></td>
			</tr>
		<?php }

			if(isset($_GET['print'])){
				echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?datefr='. $datefr . '&dateto='. $dateto.'";});</script>';
			}
		?>
	</table>
	<?php if(!isset($_GET['print'])){ echo '<div align = "center"><a href = "?datefr='. $datefr . '&dateto='. $dateto.'&print" class = "btn btn-primary"> Print All </a></div>'; } ?>
		
	</div>
</div>
<?php if(!isset($_GET['print'])){ include('emp-prof.php') ?>
<?php 
	if($_SESSION['pass'] == 'default'){
		include('up-pass.php');
	}else if(($_SESSION['201date'] == null || $_SESSION['201date'] == '0000-00-00') && ($_SESSION['level'] != 'Admin')){
	?>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
    backdrop: 'static',
    keyboard: false
  });
});
</script>
<?php }include('req-form.php');?>
<?php include("footer.php"); }?>