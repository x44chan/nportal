<?php session_start(); ?>
<?php  $title="Admin Page";
	include('header.php');	
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'Admin'){
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
			<a href = "admin.php"  type = "button"class = "btn btn-primary"  id = "showneedapproval">Home</a>	
			<button  type = "button"class = "btn btn-primary"  id = "newuserbtn">New User</button>			
      <a href = "admin-emprof.php" type = "button"class = "btn btn-primary"  id = "newuserbtn">Employee Profile</a>	
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				 <li><a type = "button"  href = "admin-petty.php">Petty List</a></li>
          <li><a type = "button"  href = "admin-petty.php?liqdate">Petty Liquidate</a></li>
          <li><a type = "button"  href = "admin-petty.php?report=1">Petty Report</a></li>
				</ul>
			</div>
			<a type = "button"class = "btn btn-primary"  href = "admin-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button"class = "btn btn-primary" href = "admin-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a class="btn btn-danger"  href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		
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
	if(!isset($_GET['view'])){?>
	<div id = "report"><h2 align = "center"><?php if(isset($_GET['active']) && $_GET['active'] == '0'){echo '<i>In-Active</>';}?> Employee List </h2>
  <?php 
    if(isset($_GET['active']) && $_GET['active'] == '0'){
      echo '<a href ="admin-emprof.php" class = "btn btn-success pull-right" style = "margin-bottom: 20px;  margin-right: 10px;"><span class="glyphicon glyphicon-user"></span>  View Active Employee </a>';
    }else{
      echo '<a href ="admin-emprof.php?active=0" class = "btn btn-danger pull-right" style = "margin-bottom: 20px; margin-right: 10px;"><span class="glyphicon glyphicon-eye-close"></span>  View In-Active Employee </a>';
    }
  ?>
		<table id = "myTable" align = "center" class = "table table-hover" style="font-size: 14px;">
		<thead>
				<tr>
					<th>Account ID</th>
          <th>Name</th>
          <th>Position</th>
          <th>Department</th>
          <th>Category</th>
          <th>Date Hired</th>
          <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
<?php 
		include("conf.php");
    if(isset($_GET['active']) && $_GET['active'] == '0'){
      $sql = "SELECT * from `login` where level != 'Admin' and fname is not null and active = '0' order by lname ASC";
    }else{
      $sql = "SELECT * from `login` where level != 'Admin' and fname is not null and (active = '1' or active IS NULL) order by lname ASC";
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
        if($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+5 months", strtotime($edate)))){
          echo '<tr style = "color: red; font-weight: bold;">';
        }elseif($row['empcatergory'] != 'Regular' && $row['empcatergory'] != null && $tonull != null && date("Y-m-d") >= date("Y-m-d", strtotime("+4 months", strtotime($edate))) ){
         echo '<tr style = "color: green; font-weight: bold;">';
        }else{
          echo '<tr>';
        }   
        echo '<td>' . $row['account_id'] . '</td>';
        echo '<td>' . $row['fname'] . ' ' . $row['mname']. ' ' . $row['lname'] . '</td>';       
        echo '<td>' . $row['position'] . '</td>';
        echo '<td>' . $row['department'] . '</td>';
        echo '<td>' . $row['empcatergory'] . '</td>';
        echo '<td>' . date("M j, Y", strtotime($row['edatehired'])) . '</td>';
				echo '<td><a href = "?view=' . $row['account_id']. '" class = "btn btn-primary" target = "_blank"><span class="glyphicon glyphicon-search"></span> View Profile</a></td>'; 
				echo '</tr>';
			}
		}
	echo '</tbody></table></div>';
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
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog modal-lg" >    
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header" style="padding:25px 50px;">
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
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdatefr']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdateto']));}?></p></i>            
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
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdatefr2']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdateto2']));}?></p></i>            
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
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdateto3']));}?></p></i>            
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
<div id = "newuser" class = "form-group" style = "display: none;">
  <form role = "form" action = "newuser-exec.php" method = "post">
    <table align = "center" width = "450">
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
<?php include('footer.php');?>
