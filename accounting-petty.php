<?php session_start(); ?>
<?php  $title="Petty Report";
	include('header.php');	
	include('savelogs.php');
	date_default_timezone_set('Asia/Manila');
?>
<?php if($_SESSION['level'] != 'ACC'){	?>		
	<script type="text/javascript">	window.location.replace("index.php");</script>	
<?php	} ?>
<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTable').DataTable();
    	$('#myTableliq').DataTable({
    		"iDisplayLength": 50,
        	"order": [[ 1, "desc" ],[ 0, "desc" ]]

    	} );
    	 $('#myTablepet').DataTable( {
	        "order": [[ 1, "desc" ],[ 0, "desc" ]],
	        <?php 
		        if(isset($_GET['print'])){
		        	echo '"paging": false,';
		        }
	        ?>
	        "iDisplayLength": 12
	    });
	});
	
</script>
<style type="text/css">
	#bords tr, #bords td{border-top: 1px black solid !important;}
	<?php
		if(isset($_GET['print'])){
			echo 'body { visibility: hidden; }';
		}
		if(isset($_GET['detailedpetty'])){
			echo 'table { page-break-inside:auto }
    div   { page-break-inside:avoid; } /* This is the key */
    thead { display:table-header-group }
    tbody { display:table-footer-group }';
		}
	?>
	@media print {		
		body  {
	    	visibility: hidden;
	    
	  	}
	  	<?php if(isset($_GET['print'])){ ?>
	  	@page{
	  		margin-left: 2mm;
	  		margin-right: 2mm;
	  	}
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#report, #report * {
	    	visibility: visible;
	 	}
	 	#report h2{
	  		margin-bottom: 10px;
	  		margin-top: 10px;
	  		font-size: 17px;
	  		font-weight: bold;
	    }
	 	#report h4{
			font-size: 14px;
		}
		#report h3{
	  		margin-bottom: 10px;
		}
		#report th{
	  		font-size: 12px;
	  		width: 0;
		} 
		#report td{
	  		font-size: 12px;
	  		bottom: 0px;
	  		padding: 3px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 12px;
		}
		#report {
	   		position: absolute;
	    	left: 0;
	    	top: 0;
	    	right: 0;
	  	}
	  	#backs{
	  		display: none;
	  	}
	  	#show{
	  		display: table-cell !important;
	  	}
	  		.dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate  {
		display: none; 
		}
	}
	<?php } ?>
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
<div align = "center">
  <div class="alert alert-success"><br>
    Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
    <?php echo date('l jS \of F Y h:i A'); ?> <br><br>
    <div class="btn-group btn-group-lg">
      <a  type = "button"class = "btn btn-primary" href = "index.php">Home</a>
      <?php if($_SESSION['acc_id'] != '48'){ ?>
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
          <li><a href = "tech-sched.php">Tech Scheduling</a></li>
          <li><a href = "hr-emprof.php">Employee Profile</a></li>
          <li><a href = "hr-timecheck.php">In/Out Reference</a></li>
          <li class="divider"></li>
          <li><a href = "accounting-petty.php">Petty List</a></li>
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
            <li><a href = "rebate.php">Rebate</a></li>
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
      <?php } }?>
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
<div id = "needaproval">
<?php
	if(isset($_GET['pettydate'])){
		include 'caloan/pettydate.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['detailedpetty'])){
		include 'caloan/detailedpetty.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['expenses'])){
		include 'caloan/expenses.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['expsum'])){
		include 'caloan/expsum.php';
		echo '</div><div style = "display: none;">';
	}
	if(isset($_GET['transfer'])){
		include 'caloan/transferpty.php';
		echo '</div><div style = "display: none;">';
	}
	
	if(isset($_GET['replenish'])){
		include 'caloan/replenish.php';
		echo '</div><div style = "display: none;">';
	}
?>
<?php
	if(isset($_GET['liqdate']) && $_GET['liqdate'] == ""){
		include 'conf.php';
		$sql = "SELECT * FROM `petty` where state != 'DAPetty' and state != 'CPetty'";
		$result = $conn->query($sql);
			echo '<div id = "report"><div align = "center"><i><h3>Liquidate List</h3></i></div>';
			echo '<div id = "backs" style = "margin-bottom: 50px;"><a class = "btn btn-primary pull-right" href = "acc-printallchange.php"/>Print All To Return Changes</a></div>';
			echo '<table class = "table" id = "myTableliq">';
			echo '<thead>';
				echo '<tr>';
				echo '<th>Petty ID</th>';
				echo '<th>Liquidation Date</th>';
				echo '<th>Name</th>';				
				echo '<th>Source</th>';
				echo '<th>Amount</th>';
				echo '<th>Total Used Petty</th>';
				echo '<th>Change</th>';
				echo '<th id = "backs" >Status</th>';
				echo '<th id = "show" style = "display: none;">Code</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data = $conn->query($query)->fetch_assoc();
				$query1 = "SELECT * FROM `login` where account_id = '$accid'";
				$data1 = $conn->query($query1)->fetch_assoc();				
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				
				if($data2['totalliq'] != ""){
					$tots = '<td>₱ ' . number_format($data2['totalliq'],2) . '</td>';
    				$a = str_replace(',', '', $row['amount']);
					$change =  $a - $data2['totalliq'];
					$change = '₱ ' . number_format($change);
					
				}else{
					$tots = '<td> - </td>'; 
					$change =  " - ";
				}
				$date1 = date("Y-m-d");
				if($row['appdate'] != '0000-00-00 00:00:00' && ($row['state'] != 'UAPetty' || $row['state'] != 'CPetty' || $row['state'] != 'DAPetty')){
					$date2 = date("Y-m-d", strtotime("+6 days", strtotime($row['appdate'])));
				}else{
					$date2 = date("Y-m-d", strtotime("+6 days", strtotime($row['date'])));
				}
				if($date1 >= $date2){
					$red = '<tr style = "color: red;">';
				}else{
					$red = '<tr>';
				}
				$liqdatess = date("M j, Y", strtotime($data['liqdate']));
				if($data['liqstate'] == 'LIQDATE'){
					$liqdatess = ' Pending <br>'. date("M j, Y", strtotime($data['liqdate']));
				}
				if($row['state'] == 'UAPetty'){
					continue;
				}elseif($data['liqdate'] == ""){
					$liqdatess = ' Pending ';
					$data['liqstate'] = "";
					echo $red;
				}elseif($data['liqstate'] != 'CompleteLiqdate'){
					echo $red;
				}elseif($change == " - "){
					echo '<tr id = "backs">';
				}else{
					echo '<tr>';
				}		
				if($change == '₱ 0'){
					$change = ' - ';
				}		
				echo '<td>'.$row['petty_id'].'</td>';
				echo '<td>'. $liqdatess .'</td>';
				echo '<td>'.$data1['fname'] . ' ' . $data1['lname'].'</td>';
				echo '<td>' . $row['source'] . '</td>';
				echo '<td>₱ ' . $row['amount'] . '</td>';
				echo $tots;
				echo '<td>' .  $change . '</td>';
				if($data['liqstate'] == 'CompleteLiqdate'){
					echo '<td id = "backs" ><b><font color = "green">Completed</font></b><br>';
					echo '<a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<td id = "backs" ><b><font color = "red">Pending for Completion (Emp Val)</font></b><br>';
					echo '<a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'LIQDATE'){
					echo '<td><b> Pending Completion</b><br><a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}else{
					echo '<td><b> Pending Liquidate <br>('.date("M j, Y", strtotime($row['date'])).')</td>';
				}
				echo '<td id = "show" style = "display: none;"></td>';
				echo '</tr>';	
			}	
			echo '</tbody></table></div>';
		}
	}elseif(isset($_GET['liqdate']) && $_GET['liqdate'] != ""){
		include 'conf.php';
		$petyid = mysql_escape_string($_GET['liqdate']);
		$sql = "SELECT * FROM `petty_liqdate` where petty_id = '$petyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$query1 = "SELECT * FROM `login` where account_id = '$_GET[acc]'";
			$data1 = $conn->query($query1)->fetch_assoc();
			$query15 = "SELECT * FROM `petty` where petty_id = '$petyid'";
			$amount = $conn->query($query15)->fetch_assoc();
			$amounts = $amount['amount'];
			if(isset($_GET['print'])){
				$aa = " style = 'font-size: 12px;' ";
			}else{
				$aa = "";
			}
			echo '<div id = "report" class = "container-fluid" style = "padding: 5px 10px; "><div class = "row" '. $aa .'>
					<div class = "col-xs-4">
						<label>Name: </label>
						<p>'.$data1['fname'] . ' ' . $data1['lname'] . '</p>
					</div>
					<div class = "col-xs-2">
						<label>Amount: </label>
						<p>₱ '.$amount['amount'] . '</p>
					</div>
					<div class = "col-xs-3">
						<label> Type / Project </label>
						<p>'.$amount['projtype']. ' / ' . $amount['project'].'</p>
					</div>
					<div class = "col-xs-3">
						<label> Petty # / Source </label>
						<p>'.$amount['petty_id']. ' / ' . $amount['source'] . '</p>
					</div>
				</div>';
			echo '<table class = "table">';
			echo '<thead>';
				echo '<tr>';
				echo '<th width="12%">Date</th>';
				echo '<th width="12%">Type</th>';
				echo '<th width="12%">Amount</th>';
				echo '<th width="12%">Receipt</th>';
				echo '<th width="40%">Particulars</th>';
				if(!isset($_GET['print'])){
					echo '<th width="12%">Code</th>';
				}
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$totalliq = 0;
			$meal = 0; $gasoline = 0; $transpo = 0; $officesupp = 0; $cpload = 0;
			$waterf = 0; $notary = 0; $toll = 0; $gatepass = 0; $housegood = 0; $materials = 0; $otherss = 0;
			$utilities = 0; $social = 0; $permit = 0; $services = 0; $profee = 0; $due = 0; $adver = 0;
			$repre = 0; $repmaint = 0; $bankc = 0; $misc = 0; $rental = 0; $viola = 0; $cashadv = 0; $bidoc = 0; $surety = 0;
			$parking = 0; $purchases = 0; $utidevit = 0; $payroll = 0; $inter = 0; $maintelabor = 0; $maintemater = 0; $delivercharge = 0;
			$bankdepo = 0;
			while($row = $result->fetch_assoc()){
				$petid = $row['liqdate_id'];
				$accid = $row['account_id'];
				$query = "SELECT * FROM `petty_liqdate` where liqdate_id = '$petid'";
				$data = $conn->query($query)->fetch_assoc();
				$query1 = "SELECT * FROM `login` where account_id = '$accid'";
				$data1 = $conn->query($query1)->fetch_assoc();
				if($data['rcpt'] != null){
					$rcpt = "<b><font color = 'green'>w/ </font></b> Receipt";
				}else{
					$rcpt = "<b><font color = 'red'>w/o</font></b> Receipt";
				}
				if($data['liqtype'] == 'Others'){
					$data['liqothers'] = ' : ' . $data['liqothers'];
				}
				echo '<tr>';
				echo '<td>'. date("M j, Y", strtotime($data['liqdate'])).'</td>';
				echo '<td>'. $data['liqtype']. $data['liqothers'] .'</td>';
				echo '<td>₱ '. number_format($data['liqamount'],2).'</td>';
				echo '<td>' . $rcpt . '</td>';
				echo '<td>'. $data['liqinfo'].'</td>';
				if(!isset($_GET['print'])){
					echo '<td>'. $data['liqcode'].'</td>';
				}
				echo '</tr>';	
				$totalliq += $data['liqamount'];
				if($row['liqstate'] == 'AdmnApp'){
					$litt = '<br><br> Approved Liquidation by the Admin';
				}else{
					$litt = "";
				}
				if($data['liqtype'] == 'Meal'){
					$meal += $data['liqamount'];
				}elseif($data['liqtype'] == 'Gasoline'){
					$gasoline += $data['liqamount'];
				}elseif($data['liqtype'] == 'Transportation'){
					$transpo += $data['liqamount'];
				}elseif($data['liqtype'] == 'Office Supplies'){
					$officesupp += $data['liqamount'];
				}elseif($data['liqtype'] == 'Cellfone Load'){
					$cpload += $data['liqamount'];
				}elseif($data['liqtype'] == 'Water Fill'){
					$waterf += $data['liqamount'];
				}elseif($data['liqtype'] == 'Notary Fee'){
					$notary += $data['liqamount'];
				}elseif($data['liqtype'] == 'Toll Gate'){
					$toll += $data['liqamount'];
				}elseif($data['liqtype'] == 'Gate Pass'){
					$gatepass += $data['liqamount'];
				}elseif($data['liqtype'] == 'House Goods'){
					$housegood += $data['liqamount'];
				}elseif($data['liqtype'] == 'Materials'){
					$materials += $data['liqamount'];
				}elseif($data['liqtype'] == 'Others'){
					$otherss += $data['liqamount'];
				}elseif($data['liqtype'] == 'Utilities'){
					$utilities += $data['liqamount'];
				}elseif($data['liqtype'] == 'Social Payments'){
					$social += $data['liqamount'];
				}elseif($data['liqtype'] == 'Permit & Licenses'){
					$permit += $data['liqamount'];
				}elseif($data['liqtype'] == 'Services'){
					$services += $data['liqamount'];
				}elseif($data['liqtype'] == 'Professional Fees'){
					$profee += $data['liqamount'];
				}elseif($data['liqtype'] == 'Dues & Subscriptions'){
					$due += $data['liqamount'];
				}elseif($data['liqtype'] == 'Advertising & Promotions'){
					$adver += $data['liqamount'];
				}elseif($data['liqtype'] == 'Representation'){
					$repre += $data['liqamount'];
				}elseif($data['liqtype'] == 'Repair & Maintenance'){
					$repmaint += $data['liqamount'];
				}elseif($data['liqtype'] == 'Bank Charges'){
					$bankc += $data['liqamount'];
				}elseif($data['liqtype'] == 'Miscellaneous'){
					$misc += $data['liqamount'];
				}elseif($data['liqtype'] == 'Rental'){
					$rental += $data['liqamount'];
				}elseif($data['liqtype'] == 'Violation Fee'){
					$viola += $data['liqamount'];
				}elseif($data['liqtype'] == 'Cash Advance'){
					$cashadv += $data['liqamount'];
				}elseif($data['liqtype'] == 'Bid Docs'){
					$bidoc += $data['liqamount'];
				}elseif($data['liqtype'] == 'Surety Bond'){
					$surety += $data['liqamount'];
				}elseif($data['liqtype'] == 'Parking Fee'){
					$parking += $data['liqamount'];
				}elseif($data['liqtype'] == 'Purchases'){
					$purchases += $data['liqamount'];
				}elseif($data['liqtype'] == 'Utilities Auto Debit'){
					$utidevit += $data['liqamount'];
				}elseif($data['liqtype'] == 'Payroll'){
					$payroll += $data['liqamount'];
				}elseif($data['liqtype'] == 'Inter'){
					$inter += $data['liqamount'];
				}elseif($data['liqtype'] == 'Repairs and Maintenance (Labor)'){
					$maintelabor += $data['liqamount'];
				}elseif($data['liqtype'] == 'Repairs and Maintenance (Materials)'){
					$maintemater += $data['liqamount'];
				}elseif($data['liqtype'] == 'Delivery Charge'){
					$delivercharge += $data['liqamount'];
				}elseif($data['liqtype'] == 'Bank Deposits'){
					$bankdepo += $data['liqamount'];
				}
			}
			$a = str_replace(',', '', $amount['amount']);
			echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: '.$litt.'</b></td><td>₱ '.number_format($totalliq, 2).'<br><br>₱ '.number_format($a - $totalliq, 2).'</td><td></td><td></td><td></td></tr>';
			echo '</tbody></table>';
			echo '<hr>';
			if(isset($_GET['print'])){
				echo '<div style = "margin-left: 10px; font-size: 11px;">';
			}else{
				echo '<div style = "margin-left: 10px; font-size: 13.5px;">';
			}
				echo '<label> <u>Summary of Expenses </u></label><br><br>';
			
				if($meal > 0){
					echo '<label> Meal: <i>₱ ' . number_format($meal,2) . '</label>';
				}
				if($bankdepo > 0){
					echo '<label> Bank Deposits: <i>₱ ' . number_format($bankdepo,2) . '</label>';
				}
				if($maintelabor > 0){
					echo '<label> Repairs and Maintenance (Labor): <i>₱ ' . number_format($maintelabor,2) . '</label>';
				}
				if($delivercharge > 0){
					echo '<label> Delivery Charge: <i>₱ ' . number_format($delivercharge,2) . '</label>';
				}
				if($maintemater > 0){
					echo '<label> Repairs and Maintenance (Materials): <i>₱ ' . number_format($maintemater,2) . '</label>';
				}
				if($inter > 0){
					echo '<label> Internet: <i>₱ ' . number_format($inter,2) . '</label>';
				}
				if($gasoline > 0){
					echo '<br><label> Gasoline: <i>₱ ' . number_format($gasoline,2) . '</i></label>';
				}
				if($transpo > 0){
					echo '<br><label> Transportation: <i>₱ ' . number_format($transpo,2) . '</i></label>';
				}
				if($officesupp > 0){
					echo '<br><label> Office Supplies: <i>₱ ' . number_format($officesupp,2) . '</i></label>';
				}
				if($cpload > 0){
					echo '<br><label> Cellphone Load: <i>₱ ' . number_format($cpload,2) . '</i></label>';
				}
				if($waterf > 0){
					echo '<br><label> Water Refill: <i>₱ ' . number_format($waterf,2) . '</i></label>';
				}
				if($notary > 0){
					echo '<br><label> Notary Fee: <i>₱ ' . number_format($notary,2) . '</i></label>';
				}
				if($toll > 0){
					echo '<br><label> Toll Gate: <i>₱ ' . number_format($toll,2) . '</i></label>';
				}
				if($gatepass > 0){
					echo '<br><label> Gate Pass: <i>₱ ' . number_format($gatepass,2) . '</i></label>';
				}
				if($housegood > 0){
					echo '<br><label> House Goods: <i>₱ ' . number_format($housegood,2) . '</i></label>';
				}
				if($materials > 0){
					echo '<br><label> Materials: <i>₱ ' . number_format($materials,2) . '</i></label>';
				}
				if($otherss > 0){
					echo '<br><label> Others: <i>₱ ' . number_format($otherss,2) . '</i></label>';
				}
				if($utilities > 0){
					echo '<br><label> Utilities: <i>₱ ' . number_format($utilities,2) . '</i></label>';
				}
				if($social > 0){
					echo '<br><label> Social Payments: <i>₱ ' . number_format($social,2) . '</i></label>';
				}
				if($permit > 0){
					echo '<br><label> Permit & Licenses: <i>₱ ' . number_format($permit,2) . '</i></label>';
				}
				if($services > 0){
					echo '<br><label> Services: <i>₱ ' . number_format($services,2) . '</i></label>';
				}
				if($profee > 0){
					echo '<br><label> Professional Fees: <i>₱ ' . number_format($profee,2) . '</i></label>';
				}
				if($due > 0){
					echo '<br><label> Dues & Subscriptions: <i>₱ ' . number_format($due,2) . '</i></label>';
				}
				if($adver > 0){
					echo '<br><label> Advertising & Promotions: <i>₱ ' . number_format($adver,2) . '</i></label>';
				}
				if($repre > 0){
					echo '<br><label> Representation: <i>₱ ' . number_format($repre,2) . '</i></label>';
				}
				if($repmaint > 0){
					echo '<br><label> Repair & Maintenance: <i>₱ ' . number_format($repmaint,2) . '</i></label>';
				}
				if($bankc > 0){
					echo '<br><label> Bank Charges: <i>₱ ' . number_format($bankc,2) . '</i></label>';
				}
				if($misc > 0){
					echo '<br><label> Miscellaneous: <i>₱ ' . number_format($misc,2) . '</i></label>';
				}
				if($rental > 0){
					echo '<br><label> Rental: <i>₱ ' . number_format($rental,2) . '</i></label>';
				}
				if($viola > 0){
					echo '<br><label> Violation Fee: <i>₱ ' . number_format($viola,2) . '</i></label>';
				}
				if($cashadv > 0){
					echo '<br><label> Cash Advance: <i>₱ ' . number_format($cashadv,2) . '</i></label>';
				}
				if($bidoc > 0){
					echo '<br><label> Bid Docs: <i>₱ ' . number_format($bidoc,2) . '</i></label>';
				}
				if($surety > 0){
					echo '<br><label> Surety Bond: <i>₱ ' . number_format($surety,2) . '</i></label>';
				}
				if($parking > 0){
					echo '<br><label> Parking Fee: <i>₱ ' . number_format($parking,2) . '</i></label>';
				}
				if($purchases > 0){
					echo '<br><label> Purchases: <i>₱ ' . number_format($purchases,2) . '</i></label>';
				}
				if($utidevit > 0){
					echo '<br><label> Utilities Auto Debit: <i>₱ ' . number_format($utidevit,2) . '</i></label>';
				}
				if($payroll > 0){
					echo '<br><label> Payroll: <i>₱ ' . number_format($payroll,2) . '</i></label>';
				}
			echo '</div>';
			if(isset($_GET['print'])){
				echo '<div class = "pull-right" style = "text-align: center; font-size: 11px; margin-right: 50px;">_________________________________<br> Checked/Verified By</div>';
			}
			echo '</div><div align="center">';			
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			echo '<a href = "'.$actual_link.'&print" class = "btn btn-primary"> Print </a> ';
			if(!isset($_GET['complete'])){
				echo '<a class = "btn btn-danger" href = "?liqdate">Back</a>';
			}else{
				echo '<a href = "?complete=1&petty_id='.$_GET['liqdate'].'" class = "btn btn-danger">Back</a>';
			}
			echo '</div>';
			if(isset($_GET['print'])){
				echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?liqdate='.$_GET['liqdate'].'&acc='.$_GET['acc'].'&complete";});</script>';
				savelogs("Print Liquidation", "Petty #: " . $petyid);
			}
		}
	}
?>
<?php if(!isset($_GET['pettyac']) && !isset($_GET['report']) && !isset($_GET['release']) && !isset($_GET['liqdate']) && !isset($_GET['complete']) && !isset($_GET['validate'])){ ?>
<h2 align = "center"><i> Pending Petty Request </i></h2>
	<form role = "form">
		<table id="myTable" style = "width: 100%;"class = "table table-hover " align = "center">
			<thead>
				<tr>
					<th ><i>Date File</i></th>					
					<th ><i>Name of Employee</i></th>
					<th ><i>Type</i></th>
					<th ><i>Amount</i></th>
					<th ><i>Transfer ID</i></th>
					<th ><i>Action</i></th>
				</tr>
			</thead>
			<tbody>
			
<?php
	include("conf.php");
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'AAPetty' and source = 'Accounting'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td><?php echo '<a class = "btn btn-primary" href = "?pettyac=a&petty_id='.$row['petty_id'].'">Approve</a> ';
						echo '<a class = "btn btn-primary" onclick = "return confirm(\'Are you sure?\');" href = "petty-exec.php?pettyac=d&petty_id='.$row['petty_id'].'"">Disapprove</a>';?></td>
				</tr>
	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state like 'AAPettyReceived' and source = 'Accounting'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?release=1&petty_id='.$row['petty_id'].'">Release</a>';?>
				</td>
				</tr>
	<?php
		}
	
	}
	$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and state = 'UATransfer'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
	?>
				<tr>
				<td><?php echo date("M j, Y", strtotime($row['date']));?></td>			
				<td><?php echo $row['fname']. ' '.$row['lname'];?></td>
				<td><?php echo $row['particular'];?></td>
				<td>₱ <?php if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); }?></td>
				<td><?php if($row['transfer_id'] == null){echo 'N/A';}else{echo $row['transfer_id'];} ?></td>
				<td>
					<?php echo '<a class = "btn btn-success" style = "width: 100px" href = "?transfer=1&petty_id='.$row['petty_id'].'"> Process </a>';?>
				</td>
				</tr>
	<?php
		}
	
	}
		$sql = "SELECT * from `petty_liqdate` where petty_liqdate.liqstate = 'LIQDATE' or petty_liqdate.liqstate = 'AdmnApp' group by petty_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query2 = "SELECT * FROM `login` where account_id = '$accid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$query3 = "SELECT * FROM `petty` where petty_id = '$petid'";
				$data3 = $conn->query($query3)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . date("M j, Y", strtotime($data3['date'])). '</td>';
				echo '<td>' . $data2['fname'] . ' '. $data2['lname'] . '</td>';				
				echo '<td>' . $data3['particular'] . '</td>';
				echo '<td>₱ ' . $data3['amount'] . '</td>';
				echo '<td>';
				if($data3['transfer_id'] == null){echo 'N/A';}else{echo $data3['transfer_id'];} 
				echo '</td>';
				$query2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				echo '<td>';
				echo '<a class = "btn btn-success" href = "?complete=1&petty_id='.$row['petty_id'].'">Complete Petty</a>';
				echo '</td>';
				echo '</tr>';
			}
		}

	$sql = "SELECT * from `petty_liqdate` where petty_liqdate.accval = 'AdminRcv' group by petty_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query2 = "SELECT * FROM `login` where account_id = '$accid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$query3 = "SELECT * FROM `petty` where petty_id = '$petid'";
				$data3 = $conn->query($query3)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . date("M j, Y", strtotime($data3['date'])). '</td>';
				echo '<td>' . $data2['fname'] . ' '. $data2['lname'] . '</td>';				
				echo '<td>' . $data3['particular'] . '</td>';
				echo '<td>₱ ' . $data3['amount'] . '</td>';
				echo '<td>';
				if($data3['transfer_id'] == null){echo 'N/A';}else{echo $data3['transfer_id'];} 
				echo '</td>';
				$query2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				echo '<td>';
				echo '<a class = "btn btn-success" href = "?validate=1&petty_id='.$row['petty_id'].'">Validate Admin Code</a>';
				echo '</td>';
				echo '</tr>';
			}
		}

}
	?>			
			</tbody>
		</table>
</form>
<?php
	if(isset($_GET['pettyac']) && $_GET['pettyac'] == 'a'){
		echo '<form action = "petty-exec.php" method = "post">';
		echo '<table align = "center" class = "table table-hover table-bordered" style = "width: 65%;">';
		echo '<thead><th colspan = 2><h2>Petty Voucher</h2></th></thead>';
		include("conf.php");
		$pettyid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$pettyid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				echo '<tr><td style = "width: 30%;">Date: </td><td style = "width: 50%;">' . date("F j, Y", strtotime($row['date'])).'</td></tr>';
				echo '<tr><td style = "width: 30%;">Petty Number: </td><td style = "width: 50%;"><input name = "petty_id"type = "hidden" value = "' . $row['petty_id'].'"/>' . $row['petty_id'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Name : </td><td style = "width: 50%;">' . $row['fname'] . ' ' . $row['lname'].'</td></tr>';
				echo '<tr><td style = "width: 30%;">Particular: </td><td style = "width: 50%;">' . $row['particular'].'</td></tr>';	
				echo '<tr><td style = "width: 30%;">Amount: </td><td style = "width: 50%;">₱ '; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'</td></tr>';
				if($row['particular'] == "Check"){ echo '<tr><td>Check #: <font color = "red">*</font></td><td><input placeholder = "Enter reference #" required class = "form-control" type = "text" name = "transct"/></tr></td>'; }		
				echo '<input class = "form-control" type = "hidden" name = "pettyamount" value ="' ; if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo'"/></td></tr>';
		
				echo '<input name = "appart" value = "' . $row['particular'] . '" type="hidden"/>';
				echo '<tr><td colspan = 2><button class = "btn btn-primary" name = "submitpetty">Submit</button><br><br><a href = "accounting-petty.php" class = "btn btn-danger" name = "backpety">Back</a></td></tr>';
			}
		}
		echo "</table></form>";
	}
	if(isset($_GET['report']) && $_GET['report'] == '1'){
		if(isset($_SESSION['dates'])){
			$date1 = $_SESSION['dates'];
			$date2 = $_SESSION['dates0'];
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));			
		}else{
			$date1 = date("Y-m-01");
			$date2 = date("Y-m-t");
			$cutoffdate11 = date("M j", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2));
		}
		if(isset($_POST['repfilter'])){
			$_SESSION['dates'] = mysql_escape_string($_POST['repfr']);
			$_SESSION['dates0'] = mysql_escape_string($_POST['repto']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?report=1&'.$_POST['reptype'].'&'.$_POST['status'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['dates']);
			unset($_SESSION['dates0']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?report=1");</script>';
		}
?>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Petty Report Filtering </i></u></h4>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-3" align="center">
				<label>Select Source</label>
				<select class="form-control input-sm" name ="reptype">
					<option <?php if(isset($_GET['all'])){ echo ' selected '; } ?> value="all">All</option>				
					<option <?php if(isset($_GET['Eliseo'])){ echo ' selected '; } ?> value="Eliseo">Eliseo</option>					
					<option <?php if(isset($_GET['Sharon'])){ echo ' selected '; } ?> value="Sharon">Sharon</option>
					<option <?php if(isset($_GET['Accounting'])){ echo ' selected '; } ?> value="Accounting">Accounting</option>
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label> Status </label>
				<select class="form-control input-sm" name = "status">
					<option <?php if(isset($_GET['sall'])){ echo ' selected '; } ?> value = "sall"> All </option>
					<option <?php if(isset($_GET['scompleted'])){ echo ' selected '; } ?> value = "scompleted"> Completed </option>
					<option <?php if(isset($_GET['sliqui'])){ echo ' selected '; } ?> value = "sliqui"> Pending Emp. Code </option>
					<option <?php if(isset($_GET['spendingliq'])){ echo ' selected '; } ?> value="spendingliq"> Pending Liquidation </option>
					<option <?php if(isset($_GET['spendingcomp'])){ echo ' selected '; } ?> value="spendingcomp"> Pending Completion </option>
				</select>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['dates'])){ echo 'value = "'. $_SESSION['dates'] . '" '; }else{ echo ' value = "' .date("Y-m-01") . '" '; } ?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['dates0'])){ echo 'value = "'. $_SESSION['dates0'] . '" '; }else{ echo ' value = "' .date("Y-m-t") . '" '; } ?> />
			</div>
			<div class="col-xs-3">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="submit" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="container-fluid" style="margin-top: -10px;">
	<div class="row">
		<div class="col-xs-12">
			<hr>
		</div>
	</div>
</div>
<?php
	
?>
<div id = "report">
	<div class="row" >
		<div class="col-xs-12" align="center" <?php if(!isset($_GET['print'])){ echo 'style="margin-top: -40px;"'; } ?>>
			<i><h3>Petty Report</h3></i>
			<b><i>
				<?php echo date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2)); ?>
				<?php if(isset($_GET['Eliseo'])){ echo '<br> Source: '; echo ' Eliseo ';}elseif(isset($_GET['Sharon'])){ echo '<br> Source: '; echo ' Sharon '; }elseif(isset($_GET['Accounting'])){ echo '<br> Source: '; echo ' Accounting '; } ?>
			</i></b>
		</div>
		<div class="col-xs-12" align="right" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>>
			<i><b>Total Amount: <span class = "badge" id = "total" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>></span><br>
			Total Used Petty: <span class = "badge" id = "used" <?php if(isset($_GET['print'])){ echo 'style="font-size: 12px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>></span></b></i>
		</div>
	</div>
<?php
		if(isset($_GET['Eliseo'])){
			$link = "&Eliseo";
		}elseif(isset($_GET['Sharon'])){
			$link = "&Sharon";
		}elseif(isset($_GET['Accounting'])){
			$link = "&Accounting";
		}elseif(isset($_GET['all'])){
			$link = "&all";
		}else{
			$link = "";
		}
		if(isset($_GET['sall'])){
			$link2 = "&sall";
		}elseif(isset($_GET['scompleted'])){
			$link2 = "&scompleted";
		}elseif(isset($_GET['spendingliq'])){
			$link2 = "&spendingliq";
		}elseif(isset($_GET['spendingcomp'])){
			$link2 = "&spendingcomp";
		}elseif(isset($_GET['sliqui'])){
			$link2 = "&sliqui";
		}else{
			$link2 = "";
		}
		if(isset($_GET['print'])){
			echo '<table id = "myTablepet" align = "center" class = "table table-hover" style="font-size: 14px;">';
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?report=1'.$link.$link2.'";});</script>';
		}else{
			echo '<table id = "myTablepet" align = "center" class = "table table-hover" style="font-size: 14px;">';
		}
		
		echo '<thead style="border-bottom: 2px solid #ddd;">
				<tr>
					<th>Petty#</th>
					<th>Date</th>
					<th>Name</th>
					<th>Particular</th>
					<th>Source</th>
					<th>Reference #</th>
					<th>Amount</th>
					<th>Used Petty</th>
					<th>Liquidation Status</th>
				</tr>
			  </thead>
			  <tbody>';
		include("conf.php");
		if(isset($_GET['Sharon'])){
			$filt = "and source = 'Sharon' ";
		}elseif(isset($_GET['Eliseo'])){
			$filt = "and source = 'Eliseo' ";
		}elseif(isset($_GET['all'])){
			$filt = "";
		}elseif(isset($_GET['Accounting'])){
			$filt = "and source = 'Accounting'";
		}else{
			$filt = "";
		}
		if(isset($_GET['scompleted']) && '2015-12-22' < $date2){
			$between = "petty_liqdate.completedate between '$date1' and '$date2'";
		}else{
			$between = "petty.date between '$date1' and '$date2'";
		}
		if(isset($_GET['spendingliq']) || isset($_GET['sliqui'])){
			$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and date between '$date1' and '$date2' and (state = 'AApettyRep' or state = 'AAAPettyReceive' or state = 'AAPettyReceived' or state = 'AAPetty') $filt order by petty_id desc";
		}elseif(isset($_GET['scompleted'])){
			$sql = "SELECT * from `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id and petty_liqdate.account_id = petty.account_id and $between and (petty.state = 'AApettyRep' or petty.state = 'AAAPettyReceive' or petty.state = 'AAPettyReceived' or petty.state = 'AAPetty') $filt GROUP BY petty_liqdate.petty_id ORDER BY petty_liqdate.completedate asc ";	
		}else{
			$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and date between '$date1' and '$date2' and (state = 'AApettyRep' or state = 'AAAPettyReceive' or state = 'AAPettyReceived' or state = 'AAPetty') $filt order by petty_id desc";
		}
		$result = $conn->query($sql);
		$total = 0;
		$change = 0;
		$used = 0;
		if($result->num_rows > 0){
			
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
				$data = $conn->query($sql)->fetch_assoc();
				if(!isset($row['completedate'])){
					$row['completedate'] = $row['date'];
				}
				if(isset($data['completedate'])){
					$row['completedate'] = $data['completedate'];
				}
				if($row['completedate'] == null || date('Y-m-d', strtotime('2015-12-22')) > $date2){
					$row['completedate'] = $data['date'];
				}else{
					$row['completedate'] = $row['completedate'];
				}
				if(isset($_GET['scompleted'])){
					if($data['liqstate'] != 'CompleteLiqdate'){
						continue;
					}
				}
				elseif(isset($_GET['sliqui'])){
					if($row['state'] == 'AAAPettyReceive' || $row['state'] == 'AAPettyReceived'){
						
					}else{
						continue;
					}
				}
				elseif(isset($_GET['spendingliq'])){
					if($data['liqtype'] != ''){
						continue;
					}
					if($row['state'] == 'AAAPettyReceive'){
						continue;
					}
					if($row['state'] == 'AAPettyReceived'){
						continue;
					}
				}
				elseif(isset($_GET['spendingcomp'])){
					if($data['liqstate'] == 'LIQDATE' || $data['liqstate'] == 'EmpVal'){
						
					}else{
						continue;
					}
				}
				
				$sql33 = "SELECT * FROM `login` where account_id = '$row[account_id]'";
				$data33 = $conn->query($sql33)->fetch_assoc();
				if($data33['fname'] == strtoupper($data33['fname'])){
					$data33['fname'] = ucfirst(strtolower($data33['fname']));
					$data33['lname'] = ucfirst(strtolower($data33['lname']));
				}
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['completedate'])). '</td>';
				echo '<td>' . $data33['fname'] . ' '. $data33['lname'] . '</td>';				
				echo '<td>' . $row['particular'] . '</td>';
				echo '<td>' . $row['source'] . '</td>';
				echo '<td>';
				if($row['transfer_id'] == null){echo ' - ';}else{echo $row['transfer_id'];} 
				echo '</td>';
				echo '<td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
				echo '</td>';
				echo '<td>₱ ';
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo number_format($data2['totalliq'],2) . '</td>';
				$used += $data2['totalliq'];
				
				echo '<td><b>';
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
				$data = $conn->query($sql)->fetch_assoc();
				if($row['state'] == 'AAAPettyReceive'){
					echo 'Pending for Employee Code';
				}elseif($row['state'] == 'AAPettyReceived'){
					echo 'Pending for Employee Code';
				}elseif($row['state'] == 'AAPetty'){
					echo '<font color = "green"><b>Pending to Accounting</font>';
				}elseif($data['petty_id'] == null){
					echo '<b>Pending Liquidation</b>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<b>Pending Completion</b><br>';
				}elseif($data['liqstate'] == 'CompleteLiqdate'){
					echo '<font color = "green"><b>Completed</font>';
				}elseif($data['liqstate'] == 'LIQDATE'){
					echo '<b>Pending Completion</b><br>';
				}
				echo '</td>';
				echo '</tr>';
				$total += $a;
				$change += $a - $data2['totalliq'];
			}
		}
		echo '<script type = "text/javascript">$(document).ready(function(){ $("#total").text("₱ '.number_format($total,2).'");  $("#used").text("₱ '.number_format($used,2).'"); });</script>';
		if(isset($_GET['print'])){
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td><b> Total: </td><td>₱ '.number_format($total,2).'</td><td>₱ '.number_format($used,2).'</td><td></td></tr>';
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td></td><td><b>Change: </td><td>₱ '.number_format($change,2).'</td><td></td></tr>';
		}		
		echo "</tbody></table></div>";	
		echo '<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?report=1&print'.$link.$link2.'"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a><a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';
}
?>
<?php
	if(isset($_GET['release']) && $_GET['release'] == 1){
		include("conf.php");
		$petid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyReceived'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				unset($_SESSION['err']);
			}else{
				$err = "";
			}
			echo '<div id = "report" align = "center"><h2 align = "center">Validate Code</h2>'.$err.'<hr>';
			echo '<form action = "petty-exec.php" method = "post"><table id = "myTable" align = "center" class = "table table-hover tbl" style="font-size: 14px; border: 0 !important; width: 50%;">
				  <tbody>';
			while($row = $result->fetch_assoc()){
				echo '<tr><td><label>Petty #</label></td><td>' . $row['petty_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['date'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				echo '<tr><td><label>Particular</label></td><td>' . $row['particular'] . '</td></tr>';
				echo '<tr><td><label>Source</label></td><td>' . $row['source'] . '</td></tr>';				
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
				echo '</td></tr>';
				if($row['transfer_id'] != null){echo '<tr><td><label>Check #</td><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Receive Code</label></td><td><input type = "text" class = "form-control" name = "rcve_code" placeholder = "Enter Code" required/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "codesub">Release Petty</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['complete']) && $_GET['complete'] == 1){
		include("conf.php");
		$petid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyRep'";
		$result = $conn->query($sql);
		$sql2 = "SELECT * FROM `petty_liqdate` where petty_id = '$petid' and liqstate != 'LIQDATE' and liqstate != 'AdmnApp'";
		$result2 = $conn->query($sql2);
		if($result2->num_rows > 0){
			echo '<script type="text/javascript">window.location.replace("accounting-petty.php"); </script>';
		}
		if($result->num_rows > 0){
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				unset($_SESSION['err']);
			}else{
				$err = "";
			}
			echo '<div id = "report" align = "center"><h2 align = "center">Validate Code</h2>'.$err.'<hr>';
			echo '<form action = "petty-exec.php" method = "post"><table id = "myTable" align = "center" class = "table table-hover tbl" style="font-size: 14px; border: 0 !important; width: 50%;">
				  <tbody>';
			while($row = $result->fetch_assoc()){
				echo '<tr><td><label>Petty #</label></td><td>' . $row['petty_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['date'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				echo '<tr><td><label>Particular</label></td><td>' . $row['particular'] . '</td></tr>';
				echo '<tr><td><label>Source</label></td><td>' . $row['source'] . '</td></tr>';				
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;
				echo '</td></tr>';
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. number_format($a - $data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Liquidation:</label></td><td><a href = "?liqdate='.$row['petty_id'].'&acc='.$row['account_id'].'&complete" class = "btn btn-primary">View Liquidate</a></td></tr>';
				
				if($row['transfer_id'] != null){echo '<tr><td><label>Transfer Code</td><td>';echo $row['transfer_id'];echo '</td></tr>';}
				function random_string($length) {
				    $key = '';
				    $keys = array_merge(range(0, 9), range('a', 'z'));

				    for ($i = 0; $i < $length; $i++) {
				        $key .= $keys[array_rand($keys)];
				    }

				    return $key;
				}
				$str = random_string(4);
				$str2 = $str;
				echo '<tr><td><label>Code</td><td><b><i>' . $str . '</td></tr>';
				echo '<input type = "hidden" value = "' . $str2 . '" name = "liqcode"/>'; 
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "liqsubmit">Complete Liquidate</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}	
			echo "</tbody></table></form></div>";
			
		}
	}
?>
<?php
	if(isset($_GET['validate']) && $_GET['validate'] == 1){
		include("conf.php");
		$petid = mysql_escape_string($_GET['petty_id']);
		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and petty_id = '$petid' and state = 'AAPettyRep'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			if(isset($_SESSION['err'])){
				$err = $_SESSION['err'];
				unset($_SESSION['err']);
			}else{
				$err = "";
			}
			echo '<div id = "report" align = "center"><h2 align = "center">Validate Code</h2>'.$err.'<hr>';
			echo '<form action = "petty-exec.php" method = "post"><table id = "myTable" align = "center" class = "table table-hover tbl" style="font-size: 14px; border: 0 !important; width: 50%;">
				  <tbody>';
			while($row = $result->fetch_assoc()){
				echo '<tr><td><label>Petty #</label></td><td>' . $row['petty_id'] . '</td></tr>';
				echo '<tr><td><label>Date</label></td><td>' . date("M j, Y", strtotime($row['date'])). '</td></tr>';
				echo '<tr><td><label>Name</label></td><td>' . $row['fname'] . ' '. $row['lname'] . '</td></tr>';				
				echo '<tr><td><label>Particular</label></td><td>' . $row['particular'] . '</td></tr>';
				echo '<tr><td><label>Source</label></td><td>' . $row['source'] . '</td></tr>';				
				echo '<tr><td><label>Amount</label></td><td>₱ ';
				if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount']); } ;
				echo '</td></tr>';
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				$a = str_replace(',', '', $row['amount']);
				echo '<tr><td><label>Total Used Petty</label></td><td>₱ '.number_format($data2['totalliq'], 2).'</td></tr>';
				echo '<tr><td><label>Change</label></td><td>₱ '. number_format($a - $data2['totalliq'], 2).'</td></tr>';
				if($row['transfer_id'] != null){echo '<tr><td>';echo $row['transfer_id'];echo '</td></tr>';}
				echo '<tr><td><label>Admin Status</label></td><td><i><u><b>Change Received by Admin</td></tr>';
				echo '<tr><td><label>Validate Code</label></td><td><input type = "text" name = "avalcode" class = "form-control" placeholder = "Enter Validation Code"/></td></tr>';
				echo '<input type = "hidden" value = "' . $row['petty_id'] . '" name = "pet_id"/>';
				echo '<tr><td colspan = "2"><button class = "btn btn-primary" type = "submit" name = "excesssubmit">Complete Transaction</button> <a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></td></tr>';
			}	
			echo "</tbody></table></form></div>";
			
		}
	}
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