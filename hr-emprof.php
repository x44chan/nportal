<?php session_start(); include("savelogs.php");?>
<?php  $title="Employee Profile";
	include('header.php');	
  include 'conf.php';
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'HR' && $_SESSION['level'] != 'ACC'){
	?>		
	<script type="text/javascript"> 
		window.location.replace("index.php");
		alert("Restricted");
	</script>		
	<?php
	}
?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('[data-toggle="tooltip"]').tooltip();
      $('#myTable').DataTable({
        <?php if(!isset($_GET['active'])) { ?>
          "order": [[ 4, "desc" ]]
        <?php }else{ ?>
          "order": [[ 8, "desc" ]]
        <?php } ?>
    } );

	});
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
<div align = "center">
  <div class="alert alert-success"><br>
    Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
    <?php echo date('l jS \of F Y h:i A'); ?> <br><br>
    <div class="btn-group btn-group-lg">
      <a  type = "button"class = "btn btn-primary" href = "index.php">Home</a>
      <?php if($_SESSION['acc_id'] == '4'){ ?>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">Update Profile</button>
        <?php
          include 'caloan/reqbut.php';
        ?>
      <?php } ?>
      <?php if($_SESSION['level'] == 'HR') { ?>
      <div class="btn-group btn-group-lg">
        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a data-toggle="modal" data-target="#newAcc">Add User</a></li>
          <!--<li><a href = "tech-sched.php">Tech Scheduling</a></li>-->
          <li><a href = "hr-emprof.php">Employee Profile</a></li>
          <li><a href = "hr-emprof.php?correctionrep">Correction Reports</a></li>         
          <li><a href = "hr-emprof.php?export">O.T. & O.B Exporting</a></li>
          <!--<li><a href = "hr-timecheck.php">In/Out Reference</a></li>-->
        </ul>
      </div>
      <a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda">My Approved Request</a>
      <a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda">My Dispproved Request</a>
      <?php }else{ ?>
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
      <?php if($_SESSION['acc_id'] == '4'){ ?>
      <div class="btn-group btn-group-lg">
        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">My Request Status <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a href = "req-all.php?appot">All Request</a></li>
          <li><a href = "acc-req-app.php">My Approved Request</a></li>
          <li><a href = "acc-req-dapp.php">My Disapproved Request</a></li>  
        </ul>
      </div>
      <?php }} ?>
      <a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
    </div>
  </div>
</div>
<?php
  if(isset($_GET['correctionrep'])){
    include("caloan/correctionrep.php");
    echo '<div style = "display:none;">';
  }
  if(isset($_GET['export'])){
    include("caloan/newfilter.php");
    echo '<div style = "display:none;">';
  }
?>
<?php	
	if(isset($_GET['suc'])){
		if($_GET['suc'] == 1){
			echo '<div id = "regerror" class="alert alert-success" align = "center"><strong>Success!</strong> New user added.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show();	$("#needaproval").hide(); });</script>';
		}else if($_GET['suc'] == 0){
			echo '<div id = "regerror" class="alert alert-warning" align = "center"><strong>Warning!</strong> Username already exists.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show();	$("#needaproval").hide(); });</script>';
		}
		else if($_GET['suc'] == 3){
			echo '<div id = "regerror" class="alert alert-warning" align = "center"><strong>Warning!</strong> Password does not match.</div>';
			echo '<script type = "text/javascript">$(document).ready(function(){ $("#newuser").show(); $("#needaproval").hide(); });</script>';
		}
	}
?>
<div id = "needaproval" style="min-height: 300px; text-transform: capitalize;">
<?php
  if(isset($_POST['inactsub'])){
      $lastday = mysql_escape_string($_POST['lastday']);
      $ldreason = mysql_escape_string($_POST['ldreason']);
      $accid = mysql_escape_string($_POST['accid']);
      $update = "UPDATE login set last_day = '$lastday', ldreason = '$ldreason', active = '0' where account_id = '$accid'";
      if($conn->query($update) == TRUE){
       echo '<script type="text/javascript"> window.location.href = "hr-emprof.php?active=0"; </script>';
        $stmts2xx = "SELECT * FROM `login` where account_id = '$accid'";
        $dataxx = $conn->query($stmts2xx)->fetch_assoc();  
        savelogs("Account InActive", $dataxx['fname'] . ' ' . $dataxx['lname'] . " Reason: " . $ldreason . " - Last Day: " . $lastday);
      }
  }
  if(isset($_GET['inacres'])){
    $xinactive = "SELECT * FROM `login` where account_id = '$_GET[inacres]'";
    $datainactive = $conn->query($xinactive)->fetch_assoc();
?>  
<form action = "" method = "post">
  <div class="container">
    <div class="row">
      <div class="col-xs-12"><h4><u><i>Employee Details</i></u></h4></div>
    </div>
    <div class="row">
      <div class="col-xs-4">
        <label><i>Name</i></label>
        <p style="margin-left: 10px;"><i> <?php echo $datainactive['fname'] . ' ' . $datainactive['lname']; ?> </i></p>
      </div>
      <div class="col-xs-4">
        <label><i>Last Day</i></label>
        <input type = "date" class="form-control" name = "lastday" required>
      </div>
      <div class="col-xs-4">
        <label><i>Reason</i></label>
        <select class="form-control" name = "ldreason" required>
          <option value=""> -------- </option>
          <option value = "Resigned">Resigned</option>
          <option value="Terminated">Terminated</option>
          <option value="End of Contract">End of Contract</option>
          <option value="Awol">Awol</option>
        </select>
      </div>
    </div>
    <input type="hidden" value = "<?php echo $_GET['inacres'];?>" name = "accid">
    <div class="row">
      <div class="col-xs-12" align="center">
        <button class="btn btn-primary" name = "inactsub"> Update Account </button> <a href = "hr-emprof.php?active=0" class="btn btn-danger"> Back </a>
      </div>
    </div>
  </div>
</form>
<?php
    echo '</div>
    <div style = "display: none;>';

  }

?>
<?php 
	if(!isset($_GET['view']) && !isset($_GET['modify'])){?>
	<div id = "report"><h2 align = "center"><?php if(isset($_GET['active']) && $_GET['active'] == '0'){echo '<i>In-Active</>';}?> Employee List</h2>
    <?php 
    if(isset($_GET['active']) && $_GET['active'] == '0'){
      echo '<a href ="hr-emprof.php" class = "btn btn-success pull-right" style = "margin-bottom: 20px;  margin-right: 10px;"><span class="glyphicon glyphicon-user"></span>  View Active Employee </a>';
    }else{
      echo '<a href ="hr-emprof.php?active=0" class = "btn btn-danger pull-right" style = "margin-bottom: 20px; margin-right: 10px;"><span class="glyphicon glyphicon-eye-close"></span>  View In-Active Employee </a>';
    }
  ?>
		<table id = "myTable" align = "center" class = "table table-hover" style="font-size: 14px;">
		<thead>
				<tr>
					<th>Account ID</th>
					<th>Name</th>
					<th>Position</th>
					<th>Department</th>
          <?php if(isset($_GET['active']) && $_GET['active'] == 0){echo '<th> Last Day / Reason </th>'; }?>
          <th>Category</th>
          <th>Date Hired</th>
					<th>Action</th>
          <th style = "display: none;">hide</th>
				</tr>
			  </thead>
			  <tbody>
<?php 
		include("conf.php");
		if(isset($_GET['active']) && $_GET['active'] == '0'){
      $sql = "SELECT * from `login` where level != 'Admin' and active = '0' order by edatehired ASC";
    }else{
      $sql = "SELECT * from `login` where level != 'Admin' and (active = '1' or active IS NULL) and (account_id != 47 and account_id != 48) order by edatehired ASC";
    }
   
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
        if($row['empcatergory'] == 'Probationary'){
          $edate = $row['probidate'];
          $tonull = $row['probidate'];
        }elseif($row['empcatergory'] == 'Contractual'){
          $edate = $row['contractdate'];
          $tonull = $row['contractdate'];
        }else{
          $edate = "";
          $tonull = 'asd';
        }
        if(isset($_GET['active']) && $_GET['active'] == '0'){
          echo '<tr>';
        }else{
          if($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+5 months", strtotime($edate)))){
            echo '<tr style = "color: red; font-weight: bold;">';
          }elseif($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+4 months", strtotime($edate))) ){
           echo '<tr style = "color: green; font-weight: bold;">';
          }else{
            echo '<tr>';
          }
        }
        if($row['edatehired'] < date("2005-m-d")){
          $row['edatehired'] = "";
        }else{
          $row['edatehired'] = date("M j, Y", strtotime($row['edatehired']));
        }
				echo '<td>' . $row['account_id'] . '</td>';
				echo '<td>' . $row['fname'] . ' ' . $row['mname']. ' ' . $row['lname'] . '</td>';				
				echo '<td>' . $row['position'] . '</td>';
				echo '<td>' . $row['department'] . '</td>';
        if(isset($_GET['active']) && $_GET['active'] == 0){echo '<td> '.date("M j, Y", strtotime($row['last_day'])). ' / <font color = "red"><b>' . $row['ldreason'] .' </td>'; }
        echo '<td>' . $row['empcatergory'] . '</td>';
        echo '<td>' . $row['edatehired'] . '</td>';
        if(isset($_GET['active'])){
          //$btn = '<a onclick = "return confirm(\'Are you sure?.\');"  href = "?reactive=' . $row['account_id']. '" class = "btn btn-success"><span class="glyphicon glyphicon-check"></span> Re-Activate</a>';
          $edit = '<a href = "?active=0&inacres=' . $row['account_id']. '" class = "btn btn-warning" data-toggle="tooltip" title = "Modify Account"><span class="glyphicon glyphicon-edit"></span></a> ';
          $btn = "";
          $requnlock = "";
        }else{
          $edit = '<a href = "?modify=' . $row['account_id']. '" class = "btn btn-warning" data-toggle="tooltip" title = "Modify Account"><span class="glyphicon glyphicon-edit"></span></a> ';
          $btn = '<a href = "?inacres=' . $row['account_id']. '" class = "btn btn-danger" data-toggle="tooltip" title = "Mark as In-Active"><span class="glyphicon glyphicon-remove"></span></a>';
          if($row['islock'] == "0"){
              $requnlock = ' <a disabled class = "btn btn-success" data-toggle="tooltip" title = "Unlocked"><span class="glyphicon glyphicon-ok-circle"></span></a>';
          }else{
              $requnlock = ' <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?requnlock=' . $row['account_id']. '" class = "btn btn-success" data-toggle="tooltip" title = "Request to Unlock"><span class="glyphicon glyphicon-ban-circle"></span></a>';
          }
        }
        if($row['ldreason'] != "" && isset($_GET['active']) && $_GET['active'] == '0'){
          $edit = "";
        }
        
				echo '<td><a href = "?view=' . $row['account_id']. '" class = "btn btn-primary" target = "_blank" data-toggle="tooltip" title = "View Profile"><span class="glyphicon glyphicon-search"></span></a> '.$edit.$btn.$requnlock.' </td>' ; 
				echo '<td style = "display: none;">' . date("m/d/Y",strtotime($row['last_day'])) . '</td>';
        echo '</tr>';
			}
		}
	echo '</tbody></table></div>';
	}elseif(isset($_GET['modify'])){
	    include("conf.php");
	    $accid = mysql_escape_string($_GET['modify']);
	    $sql = "SELECT * from `login` where account_id = '$accid' and level != 'Admin'";
	    $result = $conn->query($sql);
	    if($result->num_rows > 0){
	      while($row = $result->fetch_assoc()){
?>

<div class="container">
  <form action = "" method="post">
    <div class="row">
      <div class="col-xs-12">
        <h4 style="font-size: 21px; margin-left: -20px;"><u><i>Employee Details</i></u></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-3">
        <label>Name</label>
        <p><i><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];?></i></p>
      </div>
      <div class="col-xs-3">
        <label>Date Hired</label>
        <p><i><?php echo date("M j, Y", strtotime($row['edatehired']));?></i></p>
      </div>
      <div class="col-xs-3">
        <label>Position</label>
        <p><i><?php echo $row['position'];?></i></p>
      </div>
      <div class="col-xs-3">
        <label>Department</label>
        <p><i><?php echo $row['department'];?></i></p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <hr>
        <h4 style="font-size: 21px; margin-left: -20px;"><u><i>Update Category</i></u></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-4">
        <label>Edit Category <font color = "red"> * </font></label>
        <select class="form-control" required name = "empcatergory">
          <option value="">----------------</option>
          <option <?php if($row['empcatergory'] == "Contractual"){ echo ' selected '; } ?> value="Contractual">Contractual</option>
          <option <?php if($row['empcatergory'] == "Probationary"){ echo ' selected '; } ?> value="Probationary">Probationary</option>
          <option <?php if($row['empcatergory'] == "Regular"){ echo ' selected '; } ?> value="Regular">Regular</option>
        </select>
      </div>
      <div class="col-xs-4">
        <label>Payment <font color = "red"> * </font></label>
        <select class="form-control" required name = "payment">
          <option value="">----------------</option>
          <option <?php if($row['payment'] == "Daily"){ echo ' selected '; } ?> value="Daily">Daily</option>
          <option <?php if($row['payment'] == "Monthly"){ echo ' selected '; } ?> value="Monthly">Monthly</option>
        </select>
      </div>
      <div class="col-xs-4">
        <label>Date</label>
        <input required type = "date" <?php if($row['empcatergory'] == "Regular"){ echo ' value = "' . $row['regdate'] . '"'; } elseif($row['empcatergory'] == "Probationary"){ echo ' value = "' . $row['probidate'] . '"';}else{ echo ' value = "' . $row['contractdate'] . '"';}?> data-date='{"startView": 2, "openOnMouseFocus": true}' required name = "catdate" class="form-control"/>
      </div>
    </div>
    <?php if(date("Y-m-d") < "2015-12-29"){ ?>
    <div class = "row">
      <div class="col-xs-3">
        <label>Sick Leave <font color = "red"> * <i>(0 if none)</i></font></label>
        <input <?php if($row['vacleave'] > 1){ echo ' value = "' . $row['sickleave'] . '" '; } else { echo 'value = "0"' ;} ?> type="text" value = "0" name = "sickleave" pattern = "[0-9]*" class="form-control" placeholder = "Enter Sick Leave #">
      </div>
      <div class="col-xs-2">
        <label>Used S.L. </label>
        <input type="text" <?php if($row['usedsl'] != null){ echo ' value = "' . $row['usedsl'] . '" '; } else { echo ' value = "0" ' ;} ?> name = "usedsl" pattern = "[0-9]*" class="form-control" placeholder = "Enter Sick Leave #">
      </div>
      <div class="col-xs-3">
        <label>Vacation Leave <font color = "red"> * <i>(Enter 0 if none)</i></font></label>
        <input type="text" <?php if($row['vacleave'] > 1){ echo ' value = "' . $row['vacleave'] . '" '; } else { echo ' value = "0" ' ;} ?> name = "vacleave" pattern = "[0-9]*" class="form-control" placeholder = "Enter Vacation Leave #">
      </div>
      <div class="col-xs-2">
        <label>Used V.L. </label>
        <input <?php if($row['usedvl'] != null){ echo ' value = "' . $row['usedvl'] . '" '; } else { echo ' value = "0" ' ;} ?> type="text" name = "usedvl" pattern = "[0-9]*" class="form-control" placeholder = "Enter Vacation Leave #">
      </div>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-xs-12" align="center">
        <button class="btn btn-primary" name = "upsub"> Update Account </button>
      </div>
    </div>
    <input type = "hidden" value = "<?php echo  $accid;?>" name = "accid"/>
  </form>
  <?php if($row['empcatergory'] == "Regular"){ ?>
  <?php 
      $id = mysql_escape_string($_GET['modify']);
      $sqlxx = "SELECT * FROM nleave_bal where account_id = '$id' and CURDATE() BETWEEN startdate and enddate and state = 'AAdmin'";
      $dataxx = $conn->query($sqlxx)->fetch_assoc();
      
  ?>
  <hr>
  <div class="row">
    <div class="col-xs-12">
      <h4 style="font-size: 21px; margin-left: -20px;"><u><i>Update Leave </i></u></h4>
    </div>
  </div>
  <form action = "" method="post">
    <div class = "row">
      <div class="col-xs-3">
        <label>Sick Leave <font color = "red"> * </font></label>
        <input required type="text" <?php if($dataxx['sleave'] > 0){ echo ' value = "' . $dataxx['sleave'] . '"'; } ?>name = "sickleave" pattern = "[0-9]*" class="form-control" placeholder = "Enter Sick Leave #">
      </div>
      <div class="col-xs-3">
        <label>Vacation Leave <font color = "red"> * </font></label>
        <input type="text" required <?php if($dataxx['sleave'] > 0){ echo ' value = "' . $dataxx['vleave'] . '" '; } ?> name = "vacleave" pattern = "[0-9]*" class="form-control" placeholder = "Enter Vacation Leave #">
      </div>
      <?php if($dataxx['sleave'] <= 0 && $dataxx['sleave'] <= 0) { ?>
      <div class="col-xs-3">
        <label>Balance For <font color = "red"> * </font></label>
        <input type = "date" class="form-control" name = "startdate" placeholder = "Enter Start Date">
      </div>
      <?php } ?>
    </div>
    
    <div class="row">
      <div class="col-xs-12" align="center">
      <?php if($dataxx['sleave'] <= 0 && $dataxx['sleave'] <= 0) { ?>
        <button class="btn btn-primary" name = "updateleave"> Update Leave </button>
      <?php } ?>
      </div>
    </div>
    
  </form>
    <?php } ?>
    <div align="center">
      <a href = "hr-emprof.php" class="btn btn-danger"> Back </a>
    </div>
</div>
  
<?php
if(isset($_POST['updateleave'])){
  $sleave = mysql_escape_string($_POST['sickleave']);
  $vleave = mysql_escape_string($_POST['vacleave']);
  $startdate = mysql_escape_string($_POST['startdate']);
  $enddate = date('Y-12-31', strtotime($_POST['startdate']));
  $accid = mysql_escape_string($_GET['modify']);
  $state = 'UA';
  $datefile = date("Y-m-d");
  $sql = $conn->prepare("INSERT INTO `nleave_bal` (account_id, sleave, vleave, startdate, enddate, state, datefile) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $sql->bind_param("iiissss", $accid, $sleave, $vleave, $startdate, $enddate, $state, $datefile);
  $select = "SELECT count(account_id) as penleave FROM nleave_bal where account_id = '$accid' and (state != 'DAAdmin' and state != 'AAdmin')";
  $datax = $conn->query($select)->fetch_assoc();
  if($datax['penleave'] == 0){  
    if($sql->execute()){
      echo '<script type = "text/javascript">alert("Successful"); window.location.replace("hr-emprof.php");</script>';
      $stmts2xx = "SELECT * FROM `login` where account_id = '$accid'";
      $dataxx = $conn->query($stmts2xx)->fetch_assoc();  
      savelogs("Update Leave Balance", $dataxx['fname'] . ' ' . $dataxx['lname'] . " Vacation Leave: " . $vleave . " Sick Leave: " . $sleave);
    }
  }else{
    echo '<script type = "text/javascript">alert("You still have pending changes."); window.location.replace("hr-emprof.php");</script>';
  }
}
if(isset($_POST['upsub'])){
  $empcatergory = mysql_escape_string($_POST['empcatergory']);  
  $catdate = mysql_escape_string($_POST['catdate']);  
  $modify = mysql_escape_string($_GET['modify']);
  $payment = mysqli_real_escape_string($conn, $_POST['payment']);
  if(date("Y-m-d") < "2015-12-29"){
    $sickleave = mysql_escape_string($_POST['sickleave']);
    $vacleave = mysql_escape_string($_POST['vacleave']);
    $usedvl = mysql_escape_string($_POST['usedvl']);
    $usedsl = mysql_escape_string($_POST['usedsl']);
  }  

  if($empcatergory  == 'Probationary'){
    $oldpost = 'Contractual';
    $hrchange = date("Y-m-d");
    $catdates = ", probidate = '$catdate'";
  }elseif($empcatergory  == 'Contractual'){
    $oldpost = "Contractual";
    $hrchange = date("Y-m-d");
    $catdates = ", contractdate = '$catdate'";
  }else{
    $oldpost  = "Probationary";
    $probidate = "";
    $hrchange = date("Y-m-d");
    $catdates = ", regdate = '$catdate'";
  }
  $stmts2 = "SELECT count(account_id) as count FROM `login` where account_id = '$modify' and hrchange != '0'";
  $data = $conn->query($stmts2)->fetch_assoc();  

  if(date("Y-m-d") < "2015-12-29"){
    $stmt = "UPDATE `login` 
        set empcatergory = '$empcatergory', sickleave = '$sickleave', vacleave = '$vacleave', hrchange = '$hrchange', oldpost = '$oldpost',
            usedvl = '$usedvl', usedsl = '$usedsl' $catdates
        where account_id = '$modify' and hrchange = 0";
  }else{
    $stmt = "UPDATE `login` 
        set empcatergory = '$empcatergory', hrchange = '$hrchange', oldpost = '$oldpost', payment = '$payment' $catdates
        where account_id = '$modify' and hrchange = 0";
  }
  if($data['count'] == 0){
    if($conn->query($stmt) == TRUE){
      echo '<script type = "text/javascript">alert("Successful"); window.location.replace("hr-emprof.php");</script>';
      $stmts2xx = "SELECT * FROM `login` where account_id = '$modify'";
      $dataxx = $conn->query($stmts2xx)->fetch_assoc();  
      savelogs("Update Category", $dataxx['fname'] . ' ' . $dataxx['lname'] . " Category: " . $empcatergory . " Old Position: " . $oldpost . " Payment: " . $payment . ' Date' . $catdate);
    }       
  }else{
     echo '<script type = "text/javascript">alert("You still have pending changes."); window.location.replace("hr-emprof.php");</script>';
  } 
}
?>
<?php
      }
    }
  }else{
		include("conf.php");
		$sql = "SELECT * from `login` where account_id = '$_GET[view]' and level != 'Admin'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
?>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog modal-lg">    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding:25px 50px; ">
        <a href="javascript:window.open('','_parent','');window.close();" type="button" data-toggle="tooltip" data-placement="bottom" title="Close" class="close" style="font-size: 35px;"><font color = "#CC0000">&times;</font></a>
       <h4><span class="glyphicon glyphicon-user"></span> Employee Profile</h4>        
      </div>
      <div class="modal-body" style="padding:20px 50px;">
        <form role="form" action = "" method = "post">
         <div class = "row">         
        </div>
        <h3>Personal Information</h3>
        <div style="border-bottom: 1px solid #eee;"></div>
        <div class = "row">
        </div>
        <div class="row">
        <div class = "col-lg-5 col-md-5 col-sm-5 col-xs-5" align="center">
            <img style = "margin: auto;"src="<?php if(file_exists('images/'.$_GET['view'] .'.jpg"')){ echo 'images/'.$_GET['view'] .'.jpg';} else { echo "images/default.jpg"; }?>" class="img-rounded" onerror="if (this.src != 'images/default.jpg') this.src = 'images/default.jpg';"alt="Cinque Terre" width="200" height="180"><br><br>
         </div>
          <div class="col-md-7">
            <label for="esname"> Surname <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"autofocus value = "<?php echo $row['lname']; ?>"name = "esname" id = "esname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter surname">
          </div>
          <div class="col-md-7">
            <label for="efname"> First Name <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"name = "efname" value = "<?php echo $row['fname']; ?>" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter first name">
          </div>
          <div class="col-md-7">
            <label for="emname"> Middle Name <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"name = "emname" value = "<?php echo $row['mname']; ?>" id = "emname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter middle name">
          </div>
        </div>
         <div class="row">
         <div class="col-md-4">
            <label for="usrname"> Employee ID <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['phoenix_empid']; ?>"name = "phoenix_empid" required style = "font-weight:normal;text-transform:capitalize;" class="form-control" placeholder="UISI - 001">
          </div>
          <div class="col-md-4">
            <label for="usrname"> Chrono # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['phoenix_chrono']; ?>"name = "phoenix_chrono" pattern = '[0-9-]+' required style = "font-weight:normal;text-transform:capitalize;" class="form-control" placeholder="141">
          </div>
        </div>
        <div class="row">
         <div class="col-md-8">
            <label for="usrname"> Home Address <font color = "red">*</font></label>
            <textarea type="textarea" name = "eaddress" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Enter complete address"><?php echo $row['eaddress']; ?></textarea>
          </div>
          <div class="col-md-4">
            <label for="usrname"> Contact # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['econt']; ?>"name = "econt" pattern = '[0-9-]+' required style = "font-weight:normal;text-transform:capitalize;" class="form-control" placeholder="091234567890">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="esname"> Position <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['position']; ?>"name = "epost"id = "esname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter position">
          </div>
          <div class="col-md-4">
            <label for="efname"> Category <font color = "red">*</font></label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empcatergory']?></p></i>
            <input type="hidden" value = "<?php echo $row['eduration']; ?>"name = "eduration" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter duration">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Tel. #</label>
            <input type="text" value = "<?php echo $row['etel']; ?>"name = "etel" pattern = '[0-9-]+' style = "font-weight:normal;text-transform:capitalize;"  class="form-control" placeholder="091234567890">
          </div>
        </div>
       <div class="row">
        <div class="col-md-4">
          <label for="esname"> Civil Status <font color = "red">*</font></label>
           <select class = "form-control" id = "cstatus" name  = "ecstatus" required>
            <option value = "">-------------</option>
            <option <?php if($row['ecstatus'] == 'Single'){echo ' selected="selected"';}?>value="Single">Single</option>
            <option <?php if($row['ecstatus'] == 'Married'){echo ' selected="selected"';}?>value="Married">Married</option>
          </select>
          </div>
        <div class="col-md-4">
          <label for="efname"> Date Hired <font color = "red">*</font></label>
          <input type="date" data-date='{"startView": 1, "openOnMouseFocus": true}'  value = "<?php echo $row['edatehired']; ?>" name = "edatehired" style = "font-weight:normal;" class="form-control" required autocomplete="off"placeholder="Enter middle name">
        </div>
        <div class="col-md-4">
          <label for="usrname"> Gender <font color = "red">*</font></label>
          <select class = "form-control" required name = "egender">
            <option value = "">-------------</option>
            <option <?php if($row['egender'] == 'Male'){echo ' selected="selected"';}?> value="Male">Male</option>
            <option <?php if($row['egender'] == 'Female'){echo ' selected="selected"';}?> value="Female">Female</option>
          </select>
        </div>
      </div>
      <div id = "marriedform" style="<?php if($row['ecstatus'] == 'Married'){ echo 'display: inline';} else{ echo 'display: none;';}?>">
          <div>
            <div style="border-bottom: 1px solid #eee;"></div>
            <h3>For Married</h3>          
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Spouse Name <font color = "red">*</font></label>
            </div> 
            <div class="col-md-6">
              <label for="efname"> Number of Children <font color = "red">*</font></label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text"value = "<?php echo $row['espouse']; ?>" id = "spousename" name = "espouse" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter Spouse Name">
            </div>
            <div class="col-md-6">            
              <input type="number" value = "<?php echo $row['enumchild']; ?>" id = "numofchildren" name = "enumchild" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter number of children">
            </div>
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Name of Children <font color = "red">*</font></label>
            </div> 
            <div class="col-md-6">
              <label for="esname"> Birthdate <font color = "red">*</font></label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname1']; ?>"id = "childname" name = "childname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3" >
              <input type="date"  data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo date('Y-m-d', strtotime($row['childbday1']));?>" id = "childbday" name = "childbday" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname2']; ?>"id = "childname" name = "childname2" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo date('Y-m-d', strtotime($row['childbday2']));?>" id = "childbday" name = "childbday2" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname3']; ?>"id = "childname" name = "childname3" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday3'];?>" id = "childbday" name = "childbday3" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname4']; ?>"id = "childname" name = "childname4" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday4'];?>" id = "childbday" name = "childbday4" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname5']; ?>"id = "childname" name = "childname5" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}' value="<?php echo $row['childbday5'];?>" id = "childbday" name = "childbday5" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
        </div>
      <div class="row">
          <div class="col-md-4">
            <label for="esname"> Blood Type <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['eblood']; ?>" id = "esname" name = "eblood" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter Blood Type">
          </div>
          <div class="col-md-4">
            <label for="efname"> Religion <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ereligion']; ?>" id = "efname" name = "ereligion" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter religion">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Dep./Sec. <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['department']; ?>" name = "edept" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Enter Dept./Sec.">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="esname"> Birth Date <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['ebday']; ?>" name = "ebday" id = "esname" style = "font-weight:normal;" class="form-control" required autocomplete="off"placeholder="Enter birthdate">
          </div>
          <div class="col-md-4">
            <label for="efname"> Birth Place <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ebirth']; ?>" name = "ebirth" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter birthplace">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Nationality <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['enationality']; ?>" name = "enationality" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Nationality">
          </div>
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Mother's Name <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['emothern']; ?>" name = "emothern" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Mother's Name">
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['emontherb']; ?>" name = "emontherb" style = "font-weight:normal;" class="form-control" required placeholder="Birthdate">
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Father's Name <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['efathern']; ?>" name = "efathern" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Father's Name">
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['efatherb']; ?>" name = "efatherb" style = "font-weight:normal;" class="form-control" required placeholder="Birthdate">
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-4">
            <label for="esname"> SSS # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['esss']; ?>" name = "esss" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="SSS">
          </div> 
          <div class="col-md-4">
            <label for="efname"> Philhealth # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ephilhealth']; ?>" name = "ephilhealth" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Philhealth">
          </div>
          <div class="col-md-4">
            <label for="efname"> T.I.N # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['etin']; ?>" name = "etin" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="T.I.N">
          </div>  
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="epagibig"> Pagibig # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['epagibig']; ?>" name = "epagibig" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Pagibig">
          </div>  
        </div>
        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Educational Background</h3>          
        </div>
         <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> Name of School <font color = "red">*</font></label>           
            <input type="text" value = "<?php echo $row['enameofschool']; ?>" name = "enameofschool" required id = "childname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of school">
         </div>
        </div>
        <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> School Address <font color = "red">*</font></label>           
            <textarea id = "childname" name = "eschooladd" required style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter school address"><?php echo $row['eschooladd']; ?></textarea>
         </div>
        </div>
        <div class="row">
          <div class="col-md-4"> 
            <label for="esname"> Highest Attainment <font color = "red">*</font></label>           
            <select class = "form-control" required name = "egrad">
              <option value = "">----------</option>
              <option <?php if($row['egrad'] == 'High School Graduate'){echo ' selected="selected"';}?>value = "High School Graduate">High School Graduate</option>
              <option <?php if($row['egrad'] == 'College Undergraduate'){echo ' selected="selected"';}?>value = "College Undergraduate">College Undergraduate</option>
              <option <?php if($row['egrad'] == 'Vocational Degree'){echo ' selected="selected"';}?>value = "Vocational Degree">Vocational Degree</option>
              <option <?php if($row['egrad'] == 'Bachelor\'s Degree'){echo ' selected="selected"';}?>value = "Bachelor's Degree">Bachelor's Degree</option>
              <option <?php if($row['egrad'] == 'Masteral/Doctoral Degree'){echo ' selected="selected"';}?>value = "Masteral/Doctoral Degree">Masteral/Doctoral Degree</option>
            </select>
          </div>
          <div class="col-md-5"> 
           <label for="esname"> Course <font color = "red">*</font></label>   
           <input type="text" value = "<?php echo $row['ecourse']; ?>"id = "childname" name = "ecourse" required style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Course">
          </div>
          <div class="col-xs-3"> 
           <label for="esname"> Year Graduated <font color = "red">*</font></label>   
           <input type="text" value = "<?php echo $row['eyrgrad']; ?>"id = "childname" name = "eyrgrad" required  style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Year Graduated">
          </div>
        </div>
        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Employment History</h3>          
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="esname"> Position <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Company Name <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Start <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> End <font color = "red">*</font></label>   
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost']; ?>"name = "empost" placeholder = "Position"/>
          </div>
          <div class="col-md-3">
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany" placeholder = "Company Name"><?php echo $row['emcompany']; ?></textarea>
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr']; ?>"type = "date" name = "empdatefr" placeholder = "Start"/> 
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdateto']; ?>"type = "date" name = "empdateto" placeholder = "End"/>             
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">            
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost2']; ?>"name = "empost2" placeholder = "Position"/>
          </div>
          <div class="col-md-3"> 
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany2" placeholder = "Company Name"><?php echo $row['emcompany2']; ?></textarea>
          </div>
          <div class="col-md-3"> 
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr2']; ?>"type = "date" name = "empdatefr2" placeholder = "Start"/>
          </div>
          <div class="col-md-3"> 
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdateto2']; ?>"type = "date" name = "empdateto2" placeholder = "End"/>           
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost3']; ?>"name = "empost3" placeholder = "Position"/> 
          </div>
          <div class="col-md-3">
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany3" placeholder = "Company Name"><?php echo $row['emcompany3']; ?></textarea>
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 1, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr3']; ?>"type = "date" name = "empdatefr3" placeholder = "Start"/>  
          </div>
          <div class="col-md-3">
            <input  class = "form-control"data-date='{"startView": 1, "openOnMouseFocus": true}'  value = "<?php echo $row['empdateto3']; ?>"type = "date" name = "empdateto3" placeholder = "End"/>
          </div>
        </div> 
        <div style="margin-top: 15px;">
          <?php if($row['islock'] == 0){ ?>
          <button type="submit" style = "width: 50%; margin: auto;"name = "submitprof" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Update</button>
          <?php } ?>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<style>
.row {
  margin-bottom: 10px;
}
.modal-header h4, .modal-header, .close {
  background-color: #5cb85c;
  color:white !important;
  text-align: center;
  font-size: 30px;
}
.modal-footer {
  background-color: #f9f9f9;
}
</style>
<script type="text/javascript">
  $('#textareaaa').keyup(validateTextarea);

function validateTextarea() {
        var errorMsg = "Please match the format requested.";
        var textarea = this;
        var pattern = new RegExp('^' + $(textarea).attr('pattern') + '$');
        // check each line of text
        $.each($(this).val().split("\n"), function () {
            // check if the line matches the pattern
            var hasError = !this.match(pattern);
            if (typeof textarea.setCustomValidity === 'function') {
                textarea.setCustomValidity(hasError ? errorMsg : '');
            } else {
                // Not supported by the browser, fallback to manual error display...
                $(textarea).toggleClass('error', !!hasError);
                $(textarea).toggleClass('ok', !hasError);
                if (hasError) {
                    $(textarea).attr('title', errorMsg);
                } else {
                    $(textarea).removeAttr('title');
                }
            }
            return !hasError;
        });
    }
</script>
<script type="text/javascript">
  $('#cstatus').change(function() {
    var selected = $(this).val();  
    if(selected == 'Married'){
        $('#marriedform').show();
        $("#spousename").attr('required',true);
        $("#numofchildren").attr('required',true);
      }else{
        $('#marriedform').hide();
        $("#spousename").attr('required',false);
        $("#numofchildren").attr('required',false);
      }
  });

</script>

<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
  keyboard: false,
  backdrop: 'static'
});
  $(document).on('hide.bs.modal','#myModal2', function () {
    window.location.href = 'hr-emprof.php';
 //Do stuff here
});
});
</script>
<?php
	}
}
}
?>


</div>
<?php if(!isset($_GET['view'])){ include('emp-prof.php'); } ?>
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

<?php
  if(isset($_GET['inactive'])){
    $active = mysql_escape_string($_GET['inactive']);
    $sql = "UPDATE `login` set active = '0' where account_id = '$active'";
    if ($conn->query($sql) === TRUE) {
      echo '<script type="text/javascript">window.location.replace("hr-emprof.php"); </script>';
    }
  }
?>
<?php
  if(isset($_GET['reactive'])){
    $active = mysql_escape_string($_GET['reactive']);
    $sql = "UPDATE `login` set active = '1' where account_id = '$active'";
    if ($conn->query($sql) === TRUE) {
      echo '<script type="text/javascript">window.location.replace("hr-emprof.php"); </script>';
    }
  }
?>
<?php
if(isset($_POST['submitprof'])){
  include('conf.php');
  //emp info
  $esname = ucwords(mysql_escape_string($_POST['esname']));
  $efname = ucwords(mysql_escape_string($_POST['efname']));
  $emname = ucwords(mysql_escape_string($_POST['emname']));
  $epost = ucwords(mysql_escape_string($_POST['epost']));
  $edept = ucwords(mysql_escape_string($_POST['edept']));

  $eaddress = ucwords(mysql_escape_string($_POST['eaddress']));
  $econt = ucwords(mysql_escape_string($_POST['econt']));
  $eduration = ucwords(mysql_escape_string($_POST['eduration']));
  $etel = ucwords(mysql_escape_string($_POST['etel']));
  $ecstatus = ucwords(mysql_escape_string($_POST['ecstatus']));
  $edatehired = ucwords(mysql_escape_string($_POST['edatehired']));
  $egender = ucwords(mysql_escape_string($_POST['egender']));
  $eblood = ucwords(mysql_escape_string($_POST['eblood']));
  $ereligion = ucwords(mysql_escape_string($_POST['ereligion']));
  $ebday = ucwords(mysql_escape_string($_POST['ebday']));
  $ebirth = ucwords(mysql_escape_string($_POST['ebirth']));
  $enationality = ucwords(mysql_escape_string($_POST['enationality']));
  $emothern = ucwords(mysql_escape_string($_POST['emothern']));
  $emontherb = ucwords(mysql_escape_string($_POST['emontherb']));
  $efathern = ucwords(mysql_escape_string($_POST['efathern']));
  $efatherb = ucwords(mysql_escape_string($_POST['efatherb']));
  $esss = ucwords(mysql_escape_string($_POST['esss']));
  $ephilhealth = ucwords(mysql_escape_string($_POST['ephilhealth']));
  $etin = ucwords(mysql_escape_string($_POST['etin']));
  $epagibig = ucwords(mysql_escape_string($_POST['epagibig']));
  $acc_id = mysqli_real_escape_string($conn, $_GET['view']);
  //education prof
  $enameofschool = ucwords(mysql_escape_string($_POST['enameofschool']));
  $eschooladd = ucwords(mysql_escape_string($_POST['eschooladd']));
  $egrad = ucwords(mysql_escape_string($_POST['egrad']));
  $ecourse = ucwords(mysql_escape_string($_POST['ecourse']));
  $eyrgrad = ucwords(mysql_escape_string($_POST['eyrgrad']));
  $dates = date("Y-m-d");
  if($ecstatus == 'Married'){ 
    $espouse = mysql_escape_string($_POST['espouse']);
    $enumchild = mysql_escape_string($_POST['enumchild']);
    $childname = mysql_escape_string($_POST['childname']);
    $childname2 = mysql_escape_string($_POST['childname2']);
    $childname3 = mysql_escape_string($_POST['childname3']);
    $childname4 = mysql_escape_string($_POST['childname4']);
    $childname5 = mysql_escape_string($_POST['childname5']);
    $childbday = mysql_escape_string($_POST['childbday']);
    $childbday2 = mysql_escape_string($_POST['childbday2']);
    $childbday3 = mysql_escape_string($_POST['childbday3']);
    $childbday4 = mysql_escape_string($_POST['childbday4']);
    $childbday5 = mysql_escape_string($_POST['childbday5']);
    $sql2 ="UPDATE login set 
      espouse = '$espouse', enumchild = '$enumchild', childname1 = '$childname', childname2 = '$childname2', childname3 = '$childname3',
      childname4 = '$childname4', childname5 = '$childname5', childbday1 = '$childbday', childbday2 = '$childbday2', childbday3 = '$childbday3',
      childbday4 = '$childbday4', childbday5 = '$childbday5', islock = '1'
    where account_id = '$acc_id'";
    if ($conn->query($sql2) === TRUE) {

    }else{
      echo "Error updating record: " . $conn->error;
    }
  }

  $empost = mysql_escape_string($_POST['empost']);
  $empost2 = mysql_escape_string($_POST['empost2']);
  $empost3 = mysql_escape_string($_POST['empost3']);
  $emcompany = mysql_escape_string($_POST['emcompany']);
  $emcompany2 = mysql_escape_string($_POST['emcompany2']);
  $emcompany3 = mysql_escape_string($_POST['emcompany3']);
  $empdatefr = mysql_escape_string($_POST['empdatefr']);
  $empdatefr2 = mysql_escape_string($_POST['empdatefr2']);
  $empdatefr3 = mysql_escape_string($_POST['empdatefr3']);
  $empdateto = mysql_escape_string($_POST['empdateto']);
  $empdateto2 = mysql_escape_string($_POST['empdateto2']);
  $empdateto3 = mysql_escape_string($_POST['empdateto3']);
  $chrono = mysqli_real_escape_string($conn, $_POST['phoenix_chrono']);
  $empid = mysqli_real_escape_string($conn, $_POST['phoenix_empid']);

  $sql ="UPDATE login set 
    fname = '$efname', lname = '$esname', mname = '$emname', position = '$epost', department = '$edept',
    eaddress = '$eaddress', econt = '$econt', eduration = '$eduration', edatehired = '$edatehired', edatehired = '$edatehired',
    edatehired = '$edatehired', egender = '$egender', eblood = '$eblood', ereligion = '$ereligion', ebday = '$ebday', ebirth = '$ebirth',
    enationality = '$enationality', emothern = '$emothern', emontherb = '$emontherb', efathern = '$efathern', efatherb = '$efatherb',
    esss = '$esss', ephilhealth = '$ephilhealth', etin = '$etin', epagibig = '$epagibig', enameofschool = '$enameofschool', eschooladd = '$eschooladd', egrad = '$egrad',
    ecourse = '$ecourse', eyrgrad = '$eyrgrad',  etel = '$etel', ecstatus = '$ecstatus', 201date = '$dates', empost = '$empost', empost2 = '$empost2',
    empost3 = '$empost3', emcompany = '$emcompany', emcompany2 = '$emcompany2', emcompany3 = '$emcompany3', empdatefr = '$empdatefr', empdatefr2 = '$empdatefr2', empdatefr3 = '$empdatefr3',
    empdateto = '$empdateto', empdateto2 = '$empdateto2', empdateto3 = '$empdateto3', islock = '1', phoenix_chrono = '$chrono', phoenix_empid = '$empid'
    where account_id = '$acc_id'"; 
  if ($conn->query($sql) === TRUE) {
   echo '<script type = "text/javascript">alert("update successful"); window.location.replace("hr-emprof.php");</script>';
  }else {
    echo "Error updating record: " . $conn->error;
  }  
}
?>