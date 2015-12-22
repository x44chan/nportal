<?php session_start(); ?>
<?php  $title="Employee Profile";
	include('header.php');	
  include 'conf.php';
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'HR'){
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
        "aaSorting": []
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
      <div class="btn-group btn-group-lg">
        <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Employee Management <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a type="button" data-toggle="modal" data-target="#newAcc">Add User</a></li>
          <li><a  type = "button" href = "tech-sched.php">Tech Scheduling</a></li>
          <li><a href="hr-emprof.php" id = "newovertime">Employee Profile</a></li>
          <li><a  type = "button" href = "hr-timecheck.php">In/Out Reference</a></li>
        </ul>
      </div>
      <a type = "button" class = "btn btn-primary"  href = "hr-req-app.php" id = "showapproveda">My Approved Request</a>
      <a type = "button" class = "btn btn-primary" href = "hr-req-dapp.php"  id = "showdispproveda">My Dispproved Request</a>
      <a type = "button" class= "btn btn-danger" href = "logout.php"  role="button">Logout</a>
    </div>
  </div>
</div>
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
				</tr>
			  </thead>
			  <tbody>
<?php 
		include("conf.php");
		if(isset($_GET['active']) && $_GET['active'] == '0'){
      $sql = "SELECT * from `login` where level != 'Admin' and active = '0' order by edatehired ASC";
    }else{
      $sql = "SELECT * from `login` where level != 'Admin' and (active = '1' or active IS NULL)  and edatehired is not null order by edatehired ASC";
    }
   
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
        if($row['empcatergory'] == 'Probationary'){
          $edate = $row['probidate'];
          $tonull = $row['probidate'];
        }elseif($row['empcatergory'] == 'Contractual'){
          $edate = $row['edatehired'];
          $tonull = $row['edatehired'];
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
        }else{
          $edit = '<a href = "?modify=' . $row['account_id']. '" class = "btn btn-warning" data-toggle="tooltip" title = "Modify Account"><span class="glyphicon glyphicon-edit"></span></a> ';
          $btn = '<a href = "?inacres=' . $row['account_id']. '" class = "btn btn-danger" data-toggle="tooltip" title = "Mark as In-Active"><span class="glyphicon glyphicon-remove"></span></a>';
        }
				echo '<td><a href = "?view=' . $row['account_id']. '" class = "btn btn-primary" target = "_blank" data-toggle="tooltip" title = "View Profile"><span class="glyphicon glyphicon-search"></span></a> '.$edit.$btn.' </td>' ; 
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
      <div class="col-xs-5">
        <label>Edit Category <font color = "red"> * </font></label>
        <select class="form-control" required name = "empcatergory">
          <option value="">----------------</option>
          <option <?php if($row['empcatergory'] == "Contractual"){ echo ' selected '; } ?> value="Contractual">Contractual</option>
          <option <?php if($row['empcatergory'] == "Probationary"){ echo ' selected '; } ?> value="Probationary">Probationary</option>
          <option <?php if($row['empcatergory'] == "Regular"){ echo ' selected '; } ?> value="Regular">Regular</option>
        </select>
      </div>
      <div class="col-xs-5">
        <label>Date</label>
        <input required type = "date" <?php if($row['empcatergory'] == "Regular"){ echo ' value = "' . $row['regdate'] . '"'; } elseif($row['empcatergory'] == "Probationary"){ echo ' value = "' . $row['probidate'] . '"';}else{ echo ' value = "' . $row['contractdate'] . '"';}?> data-date='{"startView": 2, "openOnMouseFocus": true}' required name = "catdate" class="form-control"/>
      </div>
    </div>
    <?php if(date("Y") == "2015"){ ?>
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
        <a href = "hr-emprof.php" class="btn btn-danger"> Back </a>
      </div>
    </div>
    <input type = "hidden" value = "<?php echo  $accid;?>" name = "accid"/>
  </form>
  <?php if($row['empcatergory'] == "Regular"){ ?>
  <?php 
      $id = mysql_escape_string($_GET['modify']);
      $sqlxx = "SELECT * FROM nleave_bal where account_id = '$id' and state = 'AAdmin'";
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
      <div class="col-xs-3">
        <label>Balance For <font color = "red"> * </font></label>
        <select class="form-control" name = "startdate" required>
          <option value=""> - - - - - - - </option>
          <option value="<?php echo date('Y-m-01', strtotime('January Y'));?>"><?php echo date('F', strtotime('January'));?> - <?php echo date('F Y', strtotime('December Y'));?></option>
          <option value="<?php echo date('Y-m-01', strtotime('next year', strtotime('January Y')));?>"><?php echo date('F', strtotime('January'));?> - <?php echo date('F Y', strtotime('next year', strtotime('December Y')));?></option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12" align="center">
        <button class="btn btn-primary" name = "updateleave"> Update Leave </button>
        <a href = "hr-emprof.php" class="btn btn-danger"> Back </a>
      </div>
    </div>
   
  </form>
    <?php } ?>
</div>
  
<?php
if(isset($_POST['updateleave'])){
  $sleave = mysql_escape_string($_POST['sickleave']);
  $vleave = mysql_escape_string($_POST['vacleave']);
  $startdate = mysql_escape_string($_POST['startdate']);
  $enddate = date('Y-12-t', strtotime($_POST['startdate']));
  $accid = mysql_escape_string($_GET['modify']);
  $state = 'UA';
  $datefile = date("Y-m-d");
  $sql = $conn->prepare("INSERT INTO `nleave_bal` (account_id, sleave, vleave, startdate, enddate, state, datefile) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $sql->bind_param("iiissss", $accid, $sleave, $vleave, $startdate, $enddate, $state, $datefile);
  $select = "SELECT count(account_id) as penleave FROM nleave_bal where account_id = '$accid' and state != 'DALeave'";
  $datax = $conn->query($select)->fetch_assoc();
  if($datax['penleave'] == 0){  
    if($sql->execute()){
      echo '<script type = "text/javascript">alert("Successful"); window.location.replace("hr-emprof.php");</script>';
    }
  }else{
    echo '<script type = "text/javascript">alert("You still have pending changes."); window.location.replace("hr-emprof.php");</script>';
  }
}
if(isset($_POST['upsub'])){
  $empcatergory = mysql_escape_string($_POST['empcatergory']);  
  $catdate = mysql_escape_string($_POST['catdate']);  
  $modify = mysql_escape_string($_GET['modify']);
  if(date("Y") == 2015){
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
  

  if(date("Y") == 2015){
    $stmt = "UPDATE `login` 
        set empcatergory = '$empcatergory', sickleave = '$sickleave', vacleave = '$vacleave', hrchange = '$hrchange', oldpost = '$oldpost',
            usedvl = '$usedvl', usedsl = '$usedsl' $catdates
        where account_id = '$modify' and hrchange = 0";
  }else{
    $stmt = "UPDATE `login` 
        set empcatergory = '$empcatergory', hrchange = '$hrchange', oldpost = '$oldpost' $catdates
        where account_id = '$modify' and hrchange = 0";
  }
  if($data['count'] == 0){
    if($conn->query($stmt) == TRUE){
      echo '<script type = "text/javascript">alert("Successful"); window.location.replace("hr-emprof.php");</script>';
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
<style type="text/css">
	#needaproval p{
		text-decoration: underline;
	}

</style>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog modal-lg" >    
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header" style="padding:25px 50px; font-size: 20px; text-align: left;">
       <a href="javascript:window.open('','_parent','');window.close();" type="button" data-toggle="tooltip" data-placement="bottom" title="Close" class="close" style="font-size: 35px;"><font color = "#CC0000">&times;</font></a>
        	<div class="row" style="margin-left: 30px;">           
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label for="esname"> Name: </label>
            		<i><p style = "margin-left: 10px;" id = "esname"><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                   	<label for="usrname"> Contact # </label>
           			<i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['econt']?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                   <label for="emname"> Position </label>
                   <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['position']?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
               		<label for="usrname"> Dep./Sec. </label>
           			<i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['department']?></p></i>
           		</div>
            </div>
    		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                	<img style = "margin: auto;"src="images/<?php echo $_GET['view'];?>.jpg" onerror="if (this.src != 'images/default.jpg') this.src = 'images/default.jpg';" class="img-rounded" alt="Cinque Terre" width="250" height="200"><br><br>
            	</div>
            </div>
    </div>
      </div>
      <div class="modal-body" style="padding:20px 50px; font-size: 17px; overflow-y: auto;">
      <?php 
        if($row['usedsl'] > 0){
          $usedsl = '<div class="col-xs-3">
                    <label>Used Sick Leave</label>
                    <i><p style = "margin-left: 10px;" id = "usrname">'. $row['usedsl'].'</p></i>
                  </div>';
        }else{
          $usedsl = "";
        }
        if($row['usedvl'] > 0){
          $usedvl = '<div class="col-xs-3">
                    <label>Used Vacation Leave</label>
                    <i><p style = "margin-left: 10px;" id = "usrname">'. $row['usedvl'].'</p></i>
                  </div>';
        }else{
          $usedvl = "";
        }
        if($row['sickleave'] > 0 && $row['vacleave'] > 0){
          echo '<div class="row">
                  <div class="col-xs-3">
                    <label>Given Sick Leave</label>
                    <i><p style = "margin-left: 10px;" id = "usrname">'. $row['sickleave'].'</p></i>
                  </div>
                  '.$usedsl.'
                  <div class="col-xs-3">
                    <label>Given Vacation Leave</label>
                    <i><p style = "margin-left: 10px;" id = "usrname">'. $row['vacleave'].'</p></i>
                  </div>
                  '.$usedvl.'
                </div>';
        } 
      ?>
  		<div class="row">
         <div class="col-md-8">
            <label for="usrname"> Home Address </label>
   			<i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eaddress']?></p></i>  
          </div>
          <div class="col-md-4">
            <label for="usrname"> Tel. #</label>
            	<i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['etel']?></p></i>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
          		<label for="efname"> Date Hired </label>
         		<i><p style = "margin-left: 10px;" id = "usrname"><?php echo date('F j, Y', strtotime($row['edatehired']));?></p></i>
        	</div>
          <div class="col-md-4">
            <label for="efname"> Category </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empcatergory']?></p></i>
          </div>
          <div class="col-md-4">
            <label for="efname"> Expiry </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php $expiry = date('F j, Y', strtotime($row['eduration'], strtotime($row['edatehired']))); if($expiry != 'January 1, 1970'){echo $expiry; }else{echo "";}?></p></i>
          </div>
        </div>
       <div class="row">
        <div class="col-md-4">
          <label for="esname"> Civil Status </label>
           <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ecstatus']?></p></i>
          </div>

        <div class="col-md-4">
          <label for="usrname"> Gender </label>
          <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['egender']?></p></i>
        </div>
        <div class="col-md-4">
            <label for="esname"> Blood Type </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eblood']?></p></i>
        </div>
      </div>
              <div class="row">
          <div class="col-md-4">
            <label for="esname"> Birth Date </label>
           	<i><p style = "margin-left: 10px;" id = "usrname"><?php echo date('F j, Y', strtotime($row['ebday']));?></p></i>
           </div>
          <div class="col-md-4">
            <label for="efname"> Birth Place </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ebirth']?></p></i>
           </div>
         <div class="col-md-4">
            <label for="usrname"> Nationality </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enationality']?></p></i>
          </div>
        </div>
      <div id = "marriedform" style="<?php if($row['ecstatus'] == 'Married'){ echo 'display: inline';} else{ echo 'display: none;"';}?>">
          <div>
            <div style="border-bottom: 1px solid #eee;"></div>
            <h3>For Married</h3>          
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Spouse Name </label>
              <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['espouse']?></p></i>
            </div> 
            <div class="col-md-6">
              <label for="efname"> Number of Children </label>
              <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enumchild']?></p></i>
            </div>  
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Name of Children </label>
            </div> 
            <div class="col-md-6">
              <label for="esname"> Birthdate </label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
            	<?php if($row['childname1'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname1'] .'</p></i>';}?>
            	<?php if($row['childname2'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname2'] .'</p></i>';}?>
            	<?php if($row['childname3'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname3'] .'</p></i>';}?>
            	<?php if($row['childname4'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname4'] .'</p></i>';}?>
            	<?php if($row['childname5'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname5'] .'</p></i>';}?>
            </div>
            <div class="col-md-4">           
            	<?php if($row['childbday1'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday1'])) .'</p></i>';}?>
        		  <?php if($row['childbday2'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday2'])) .'</p></i>';}?>
        		  <?php if($row['childbday3'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday3'])) .'</p></i>';}?>
        		  <?php if($row['childbday4'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday4'])) .'</p></i>';}?>
        		  <?php if($row['childbday5'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday5'])) .'</p></i>';}?>
            </div>
            </div>
            <div style="border-bottom: 1px solid #eee;"></div>
        </div>
      <div class="row">
          <div class="col-md-4">
            <label for="efname"> Religion </label>
           	<i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ereligion']?></p></i> 
           </div>
         <div class="col-md-4">
            
          </div>
        </div>

        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Mother's Name </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emothern'];?></p></i> 
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date("F j, Y", strtotime($row['emontherb']));?></p></i> 
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Father's Name </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['efathern']?></p></i> 
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate </label>
           <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date("F j, Y", strtotime($row['efatherb']));?></p></i> 
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-4">
            <label for="esname"> SSS # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['esss']?></p></i> 
          </div> 
          <div class="col-md-4">
            <label for="efname"> Philhealth # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ephilhealth']?></p></i> 
          </div>
          <div class="col-md-4">
            <label for="efname"> T.I.N # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['etin']?></p></i> 
          </div>  
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="efname"> Pagibig # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['epagibig']?></p></i> 
          </div>  
        </div>
        <div>
          <div style="border-bottom: 2px solid #eee;"></div>
          <h3>Educational Background</h3>          
        </div>
         <div class="row">

          <div class="col-md-12"> 
            <label for="esname"> Name of School </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enameofschool']?></p></i>
         </div>
        </div>
                <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> School Address </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eschooladd']?></p></i>
         </div>
        </div>
        <div class="row">
        <div class="col-md-3"> 
            <label for="esname"> Highest Attainment </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['egrad']?></p></i>
          </div>
          <div class="col-md-6"> 
           <label for="esname"> Course </label>   
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ecourse']?></p></i>
          </div>
          <div class="col-xs-3"> 
           <label for="esname"> Year Graduated </label>   
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eyrgrad']?></p></i>
          </div>
        </div>

        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Employment History</h3>          
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="esname"> Position </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Company Name </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Start </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> End </label>   
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr']!= null){echo date("F j, Y", strtotime($row['empdatefr']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto']!= null){echo date("F j, Y", strtotime($row['empdateto']));}?></p></i>            
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost2']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany2']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr2']!= null){echo date("F j, Y", strtotime($row['empdatefr2']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto2']!= null){echo date("F j, Y", strtotime($row['empdateto2']));}?></p></i>            
          </div>
        </div>
                <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost3']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany3']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdatefr3']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto3']!= null){echo date("F j, Y", strtotime($row['empdateto3']));}?></p></i>            
          </div>
        </div>

       </div>
      </div>
    </div>
  </div>
</div>
<style>
.row {
  margin-bottom: 10px;
}
.modal-header, h4, .close {
  background-color: #5cb85c;
  color:#E6E6E6 !important;
  font-size: 18px;
}
.modal-footer {
  background-color: #f9f9f9;
}
</style>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#myModal2').modal({
  keyboard: false,
  backdrop: 'static'
});
  $(document).on('hide.bs.modal','#myModal2', function () {
                window.location.href = 'admin-emprof.php';
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