<?php 
	session_start();
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		if($_SESSION['level'] != 'ACC'){
			header("location: index.php");
		}
	}else{
		header("location: index.php");
	}
	date_default_timezone_set('Asia/Manila');
	if(!isset($_GET['rep'])){
		$_GET['rep'] = 'all';
		$title = 'Over All Report';
	}
	if($_GET['rep'] == 'ot'){
		$title = 'Overtime Report';
	}else if($_GET['rep'] == 'ob'){
		$title = 'Official Business Report';
	}else if($_GET['rep'] == 'lea'){
		$title = 'Leave Report';
	}else if($_GET['rep'] == 'undr'){
		$title = 'Undertime Report';
	}
	include("header.php");	
?>

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
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#reportg, #reportg * {
	    	visibility: visible;
	 	}
		#reportg th{
	  		font-size: 11px;
	  		width: 0;
		} 
		#reportg td{
	  		font-size: 10px;
	  		bottom: 0px;
	  		padding: 1px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 13px;
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
	  		font-size: 12px;
	  	}
	}

	#myTablelea td, #myTablelea th{
		font-size: 14px;
	}

</style>
<script type="text/javascript">		
$(document).ready( function () {
   	$('#myTablelea').DataTable( {
        "aaSorting": [],
        "iDisplayLength": 12
    });	
   	
    
    $('#myTable').DataTable( {
    	"iDisplayLength": 12
    });
});
</script>
<div align = "center" style = "margin-bottom: 30px;">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong><br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br>	<br>	
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary" href = "accounting.php?ac=penot">Home</a>		
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
			<a type = "button" class = "btn btn-primary  active" href = "acc-report.php" id = "showapproveda">Cutoff Summary</a>
			<div class="btn-group btn-group-lg">
				<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">Petty Voucher <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				  <li><a type = "button"  href = "accounting-petty.php">Petty List</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?liqdate">Petty Liquidate</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?report=1">Petty Report</a></li>
				  <li><a type = "button"  href = "accounting-petty.php?replenish">Petty Replenish Report</a></li>
				</ul>
			</div>				<a  type = "button"class = "btn btn-primary"  href = "acc-req-app.php"> Approved Request</a>		
			<a type = "button"class = "btn btn-primary"  href = "acc-req-dapp.php">Dispproved Request</a>		
			<a href = "logout.php" class="btn btn-danger" onclick="return confirm('Do you really want to log out?');"  role="button">Logout</a>
		</div>
	</div>
</div>
<?php 
	if(isset($_GET['sumar']) && $_GET['sumar'] == 'leasum'){
		$title = "Employee Leave Summary";
?>
	<div id = "report">
		<i><h2 align = "center">Employee Leave Summary</h2>
		<h4 align = "center">January <?php echo date("Y");?> - December <?php echo date("Y");?></h4></i>
		<table id = "myTablelea" align = "center" class = "table table-hover" style="font-size: 16px;">
		<thead>
				<tr>
					<th>Account ID</th>
					<th>Name</th>
					<th>Category</th>
					<th>Given S.L.</th>
					<th>Total Used S.L.</th>
					<th>Avail S.L.</th>
					<th>Given V.L.</th>
					<th>Total Used V.L.</th>
					<th>Avail V.L.</th>
					<!--<th>Paternity/Wedding Leave</th>-->
				</tr>
			  </thead>
			  <tbody>
<?php 
		include("conf.php");
		$sql = "SELECT * from `login` where level != 'Admin' and active != 0 order by edatehired";
		$result = $conn->query($sql);
		$datey = date("Y");
		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$availsick = 0;
		        $totavailvac = 0;
		        $vlcount = 0;
		        $scount = 0;
		        $accidd = $row['account_id'];
		        if(date("Y") == 2015){  
		          $sl = $row['sickleave'];
		          $vl = $row['vacleave'];
		          $usedsl = $row['usedsl'];
		          $usedvl = $row['usedvl'];
		        }else{        
		          $leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and state = 'AAdmin'";
		          $datalea = $conn->query($leaveexec)->fetch_assoc();
		          $sl = $datalea['sleave'];
		          $vl = $datalea['vleave'];
		          $usedsl = 0;
		          $usedvl = 0;
		        }
		        if($row['sickleave'] < 1){
		          $row['sickleave'] = ' - ';
		        }
		        if($row['vacleave'] < 1){
		          $row['vacleave'] = ' - ';
		        }

		        $sql1 = "SELECT SUM(numdays) as scount  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Sick Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
		        $result1 = $conn->query($sql1);
		        if($result1->num_rows > 0){
		          while($row1 = $result1->fetch_assoc()){
		            $availsick = $sl - $row1['scount'] - $usedsl;
		            $scount += $row1['scount'];           
		            }
		        }
		        
		        $sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Vacation Leave'  and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
		        $result1 = $conn->query($sql1);
		        if($result1->num_rows > 0){
		          while($row1 = $result1->fetch_assoc()){
		            $availvac = $vl - $row1['count'];
		            $vlcount += $row1['count'];
		            }
		        }   
		        $sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Other%' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
		        $result1 = $conn->query($sql1);
		        if($result1->num_rows > 0){
		          while($row1 = $result1->fetch_assoc()){
		            $totavailvac = $availvac - $row1['count'] - $usedvl;
		            $vlcount += $row1['count'];
		            }
		        }
		        if($totavailvac == 0){
		          $totavailvac = ' - ';
		        }
		        if($availsick == 0){
		          $availsick = ' - ';
		        }
		        $vlcount += $usedvl;
		        $scount += $usedsl;
		        if($scount == 0){
		          $scount = ' - ';
		        }
		        if($vlcount == 0){
		          $vlcount = ' - ';
		        }
		        if($sl <= 0){
		          $sl = ' - ';
		        }
		        if($vl <= 0){
		          $vl = ' - ';
		        }
		        if($row['empcatergory'] == 'Regular'){
		        	$regdate = '<br>Date: <font color = "green">'.date("M j, Y", strtotime($row['regdate']));
		        }else{
		        	$regdate = "";
		        }
		        echo '<tr>';
		        echo '<td>'.$accidd.'</td>';
		        echo '<td>'.$row['fname']. ' ' . $row['mname']. ' '.$row['lname'].'</td>'; 
		        echo '<td><b>' . $row['empcatergory'] . $regdate . '</td>';       
		        echo '<td style = "background-color: yellow;">'.$sl.'</td>';          
		        echo '<td>'.$scount.'</td>';        
		        echo '<td style = "background-color: #993333; color: white;">'.$availsick.'</td>';          
		        echo '<td style = "background-color: yellow;">'.$vl.'</td>';
		        echo '<td>'.$vlcount.'</td>';       
		        echo '<td style = "background-color: #993333; color: white;">'.$totavailvac.'</td>';
		        

		        
		        //echo '<td> '.$patwed.'</td>';
		        echo '</tr>';
			}
		}
	echo '</tbody></table>';
	echo '<div align = "center" style = "margin-top: 30px;"><a href = "acc-report.php" class = "btn btn-danger"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to Leave Report </a></div></div>';
	echo '</div>';

}
	?>
	

<div id = "userlist" <?php if(isset($_GET['sumar'])){ echo ' style = "display: none;" '; }?>>

<?php 
	include 'caloan/newfilter.php';
?>

</div>
<?php if(!isset($_GET['print'])){ include('emp-prof.php') ?>
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
<?php include("footer.php"); }?>