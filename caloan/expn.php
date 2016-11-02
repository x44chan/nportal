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
	  	.col-xs-2{
	  		font-size: 12px;
	  	}
	  	<?php if(isset($_GET['print'])){ ?>
	  
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#report, #report * {
	    	visibility: visible;
	 	}
	 	#report{
	 		font-size: 9px; 
	 	}
	 	#report h2{
	  		margin-bottom: 10px;
	  		margin-top: 10px;
	  		font-size: 17px;
	  		font-weight: bold;
	    }
	 	#report h4{
			font-size: 13px;
		}
		#report h3{
	  		margin-bottom: 9.5px;
	  		font-size: 13px;
		}
		#report th{
	  		font-size: 10px;
	  		width: 0;
		} 
		#report td{
	  		font-size: 9px;
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
	<?php } 
		if(isset($_GET['clear'])){
			$_SESSION['loc'] = "";
			$_SESSION['otproject'] = "";
			$_SESSION['edate'] = "";
			$_SESSION['bytype'] = "";
			$_SESSION['datefr'] = date("Y-m-01");
			$_SESSION['dateto'] = date("Y-m-t");
		}
		if((isset($_GET['datefr']) && $_GET['datefr'] != "") && (isset($_GET['dateto']) && $_GET['dateto'] != "")){
			$_SESSION['datefr'] = mysqli_real_escape_string($conn, $_GET['datefr']);
			$_SESSION['dateto'] = mysqli_real_escape_string($conn, $_GET['dateto']);
			$_SESSION['edate'] = " and completedate BETWEEN '$_SESSION[datefr]' and '$_SESSION[dateto]'";
		}
		if(empty($_SESSION['edate'])){
			$_SESSION['edate'] = "";
		}
		if(isset($_GET['loc']) && $_GET['loc'] != ""){
			$_SESSION['loc'] = mysqli_real_escape_string($conn, $_GET['loc']);
		}
		if(isset($_GET['otproject']) && $_GET['otproject'] != ""){
			$_SESSION['otproject'] = mysqli_real_escape_string($conn, $_GET['otproject']);
			$_SESSION['bytype'] = "";
		}
		if(isset($_GET['bytype']) && $_GET['bytype'] != ""){
			$_SESSION['loc'] = "";
			$_SESSION['otproject'] = "";
			$_SESSION['bytype'] = mysqli_real_escape_string($conn, $_GET['bytype']);
		}
	?>
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#checkxx').change(function(){
	    if($('#checkxx').is(":checked")){ 	        
	    	$("#bytypexx").show();
	    	$("#projectxx").hide();
	    	$("#loc").html("");
	    	$("#bytype").attr('required',true);
	    	$("#bytype").attr('disabled',false);
	    	$("#projectwasdx").attr('required',false);
	    	$("#projectwasdx").attr('disabled',true);
	    	$("#otproject").attr('disabled',true);
	    }else{
	    	$("#bytypexx").hide();
	    	$("#projectxx").show();
	    	$("#bytype").attr('required',false);
	    	$("#bytype").attr('disabled',true);
	    	$("#projectwasdx").attr('required',true);
	    	$("#projectwasdx").attr('disabled',false);
	    	showUser($("#projectwasdx").val());
	    }
	});
	<?php if(isset($_GET['print'])){ ?>
		document.title = "<?php if(isset($_SESSION['loc']) && $_SESSION['loc'] != ""){ echo $_SESSION['loc']. ': ' ; } if(isset($_SESSION['bytype']) && $_SESSION['bytype'] != ""){ echo $_SESSION['bytype']; } if(isset($_SESSION['otproject']) && $_SESSION['otproject'] != ""){ echo $_SESSION['otproject']. '' ; } if(isset($_SESSION['type'])){ echo $_SESSION['type'];}?> Expenses Report";
	<?php }?>
});
</script>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<u><i><h4 style="margin-left: -10px;"> Expenses Viewing </h4></i></u>
		</div>
	</div>
	<form action ="" method="get">
		<input type = "hidden" name = "expn"/>
		<div class="row">
			<div class="col-xs-2" style="margin-top: -15px;">
				<label>Date From: </label>
				<input <?php if(isset($_SESSION['datefr'])){ echo 'value = "' . $_SESSION['datefr'] . '"'; }else{ echo 'value = "' . date("Y-m-01") . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "datefr" class="form-control input-sm">
			</div>
			<div class="col-xs-2" style="margin-top: -15px;">
				<label>Date To: </label>
				<input <?php if(isset($_SESSION['dateto'])){ echo 'value = "' . $_SESSION['dateto'] . '"'; }else{ echo 'value = "' . date("Y-m-t") . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "dateto" class="form-control input-sm">
			</div>
			<div class="col-xs-3" style="margin-top: -15px; <?php if(isset($_SESSION['bytype']) && $_SESSION['bytype'] != ""){ echo ' display: none; ';}?>" id = "projectxx" >
				<label>Location</label>
				<select class="form-control input-sm" id = "projectwasdx" name = "loc" onchange="showUser(this.value)" <?php if(isset($_SESSION['bytype']) && $_SESSION['bytype'] == ""){ echo ' required ';}?>>
					<option value=""> - - - - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where (type = 'Project' or type = 'On Call') and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if((isset($_GET['loc']) && $_GET['loc'] == $xrow['loc']) || (isset($_SESSION['loc']) && $_SESSION['loc'] == $xrow['loc'])){
            						$select = ' selected ';
            					}else{
            						$select = "";
            					}
            					echo '<option '.$select.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
            				}
            			}
            		?>			
				</select>
			</div>			
			<div class="col-xs-3" id = "loc" style="margin-top: -12px;">
				<?php
					if((isset($_GET['otproject']) && $_GET['otproject'] != "" && $_GET['otproject'] != 'all') || (isset($_SESSION['loc']) && $_SESSION['loc'] != "" && $_SESSION['loc'] != 'all')){
						echo '<b>PO <font color = "red"> * </font></b>';
						if(!isset($_GET['loc'])){
							$_GET['loc'] = $_SESSION['loc'];
						}
						if(!isset($_GET['otproject'])){
							$_GET['otproject'] = $_SESSION['otproject'];
						}
						$otproject = mysqli_real_escape_string($conn, $_GET['loc']);
						$xx = "SELECT * FROM project where loc = '$otproject'";
						$xxx = $conn->query($xx);
						echo '<select name = "otproject" id = "otproject" class = "form-control input-sm">';
						if($xxx->num_rows > 0){
							while ($srow = $xxx->fetch_assoc()) {
								if($_GET['otproject'] == $srow['name']){
            						$select = ' selected ';
            					}else{
            						$select = "";
            					}
            					echo '<option '.$select.' value = "' . $srow['name'] . '"> ' . $srow['name'] . '</option>';
							}
						}
						echo '</select>';
					}
				?>
			</div>
			<div class="col-xs-3" style="margin-top: -15px; <?php if($_SESSION['bytype'] == ""){ echo ' display: none; ';}?>" id = "bytypexx">
				<label> Type </label>
				<select class="form-control input-sm" name = "bytype" <?php if(isset($_SESSION['bytype']) && $_SESSION['bytype'] == ""){ echo ' disabled ';}?> id = "bytype">
					<option value=""> - - - - - - - - </option>
					<?php
						$sql = "SELECT * FROM petty_type where type_id != 4 and type_id != 10";
						$result = $conn->query($sql);
						if($result->num_rows > 0){
							while ($row = $result->fetch_assoc()) {
								if((isset($_GET['bytype']) && $_GET['bytype'] == $row['type']) || (isset($_SESSION['bytype']) && $_SESSION['bytype'] == $row['type'])){
									$select = " selected ";
								}else{
									$select = "";
								}
								echo '<option '. $select .' value = "' . $row['type'] . '"> ' . $row['type'] . '</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-4">
				<input type = "checkbox" id = "checkxx" <?php if(isset($_SESSION['bytype']) && $_SESSION['bytype'] != ""){ echo ' checked ';}?>> <label for = "checkxx"> Switch to by Type </label>	</input>		
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" align="center"  style="margin-bottom: -25px;">
				<button class="btn btn-primary btn-sm"><span class = "glyphicon glyphicon-search"></span> Search </button>
				<a class="btn btn-danger btn-sm" href = "?expn&clear"><span class="glyphicon glyphicon-refresh"></span> Clear </a>
				<hr>
			</div>
		</div>
		<?php
		
		if(isset($_GET['otproject']) || isset($_GET['bytype']) || (isset($_SESSION['otproject']) && $_SESSION['otproject'] != "") || (isset($_SESSION['bytype']) && $_SESSION['bytype'] != "")) {
			if(isset($_GET['print'])) { 
				echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expn";});</script>';
			}
			if(isset($_GET['otproject'])){
				$project = mysqli_real_escape_string($conn, $_GET['otproject']);
				$_SESSION['otproject'] = $project;
			}
		?>
	</div>
	<div class = "container-fluid" id = "report" style="margin-left:">
		<div class="row" <?php if(!isset($_GET['print'])){ echo ' style="margin-left: 90px;" '; }?>>
			<div class="col-xs-12">
				<h3><i><u><?php if(isset($_SESSION['loc']) && $_SESSION['loc'] != ""){ echo $_SESSION['loc']. ': ' ; } if(isset($_SESSION['bytype']) && $_SESSION['bytype'] != ""){ echo $_SESSION['bytype']; } if(isset($_SESSION['otproject']) && $_SESSION['otproject'] != ""){ echo $_SESSION['otproject']. '' ; } if(isset($_SESSION['type'])){ echo $_SESSION['type'];}?></u></i></h3><i><h4 style="margin-left: 60px;"> <?php echo date("M j, Y",strtotime($_SESSION['datefr'])) . ' - ' . date("M j, Y",strtotime($_SESSION['dateto']));?></h4></i> <a id = "backs" href = "?expn&print" class = "btn btn-sm btn-success pull-right"><span class = "glyphicon glyphicon-print"></span> Print </a>
			</div>
		</div>
		<?php
			if(isset($_SESSION['otproject']) && $_SESSION['otproject'] != ""){
				$project = $_SESSION['otproject'];
			}else{
				$project = "";
			}
			if(isset($_SESSION['bytype']) && $_SESSION['bytype'] != ""){
				$bytype = $_SESSION['bytype'];
			}else{
				$bytype = "";
			}
			if(!empty($_GET['type'])){
				$type = mysqli_real_escape_string($conn, $_GET['type']);
				$query = "and liqtype = '$type' ";
			}else{
				$type = "";
				$query = "";
			}	
			$meal = 0;
			$gas = 0;
			$transpo = 0;
			$cpload = 0;
			$water = 0;
			$notary = 0;
			$toll = 0;
			$gate = 0;
			$material = 0;
			$others = 0;
			$utilities = 0; $social = 0; $permit = 0; $services = 0; $profee = 0; $due = 0; $adver = 0;
			$repre = 0; $repmaint = 0; $bankc = 0; $misc = 0; $rental = 0; $viola = 0; $cashadv = 0; $bidoc = 0; $surety = 0;
			$parking = 0; $purchases = 0; $utidevit = 0; $payroll = 0; $inter = 0;
			if($project != ""){		
				$sql = "SELECT * FROM `petty` where project = '$project' and state = 'AAPettyRep'";
				$result = $conn->query($sql);
				
				if($result->num_rows > 0){
					while ($row = $result->fetch_assoc()) {
						$sql2 = "SELECT * FROM petty_liqdate where petty_id = '$row[petty_id]' and liqstate = 'CompleteLiqdate' $query $_SESSION[edate]";
						$result2 = $conn->query($sql2);
						if($result2->num_rows > 0){
							while ($row = $result2->fetch_assoc()) {
								if($row['liqtype'] == 'Meal'){
									$meal += $row['liqamount'];
								}elseif($row['liqtype'] == "Gasoline"){
									$gas += $row['liqamount'];
								}elseif($row['liqtype'] == 'Transportation'){
									$transpo += $row['liqamount'];
								}elseif($row['liqtype'] == 'Cellfone Load'){
									$cpload += $row['liqamount'];
								}elseif($row['liqtype'] == 'Water Fill'){
									$water += $row['liqamount'];
								}elseif($row['liqtype'] == 'Notary Fee'){
									$notary += $row['liqamount'];
								}elseif($row['liqtype'] == 'Toll Gate'){
									$toll += $row['liqamount'];
								}elseif($row['liqtype'] == 'Gate Pass'){
									$gate += $row['liqamount'];
								}elseif($row['liqtype'] == 'Materials'){
									$material += $row['liqamount'];
								}elseif($row['liqtype'] == 'Others'){
									$others += $row['liqamount'];
								}elseif($row['liqtype'] == 'Utilities'){
									$utilities += $row['liqamount'];
								}elseif($row['liqtype'] == 'Social Payments'){
									$social += $row['liqamount'];
								}elseif($row['liqtype'] == 'Permit & Licenses'){
									$permit += $row['liqamount'];
								}elseif($row['liqtype'] == 'Services'){
									$services += $row['liqamount'];
								}elseif($row['liqtype'] == 'Professional Fees'){
									$profee += $row['liqamount'];
								}elseif($row['liqtype'] == 'Dues & Subscriptions'){
									$due += $row['liqamount'];
								}elseif($row['liqtype'] == 'Advertising & Promotions'){
									$adver += $row['liqamount'];
								}elseif($row['liqtype'] == 'Representation'){
									$repre += $row['liqamount'];
								}elseif($row['liqtype'] == 'Repair & Maintenance'){
									$repmaint += $row['liqamount'];
								}elseif($row['liqtype'] == 'Bank Charges'){
									$bankc += $row['liqamount'];
								}elseif($row['liqtype'] == 'Miscellaneous'){
									$misc += $row['liqamount'];
								}elseif($row['liqtype'] == 'Rental'){
									$rental += $row['liqamount'];
								}elseif($row['liqtype'] == 'Violation Fee'){
									$viola += $row['liqamount'];
								}elseif($row['liqtype'] == 'Cash Advance'){
									$cashadv += $row['liqamount'];
								}elseif($row['liqtype'] == 'Bid Docs'){
									$bidoc += $row['liqamount'];
								}elseif($row['liqtype'] == 'Surety Bond'){
									$surety += $row['liqamount'];
								}elseif($row['liqtype'] == 'Parking Fee'){
									$parking += $row['liqamount'];
								}elseif($row['liqtype'] == 'Purchases'){
									$purchases += $row['liqamount'];
								}elseif($row['liqtype'] == 'Utilities Auto Debit'){
									$utidevit += $row['liqamount'];
								}elseif($row['liqtype'] == 'Payroll'){
									$payroll += $row['liqamount'];
								}elseif($row['liqtype'] == 'Internet'){
									$inter += $row['liqamount'];
								}
							}
						} 
					}
				}
			}
			if($bytype != ""){
				$sql = "SELECT *,sum(liqamount) as amnt FROM `petty`,`project`,`petty_liqdate` where petty_liqdate.petty_id = petty.petty_id and petty.project = project.name and petty.state = 'AAPettyRep' and liqtype = '$_SESSION[bytype]' and liqstate = 'CompleteLiqdate' $_SESSION[edate] group by project,petty_liqdate.completedate order by project,completedate desc";
				$result = $conn->query($sql);
				if($result->num_rows > 0){		
					echo '<table class = "table table-hover">';
					echo '<thead><tr>';
						echo '<th width = "30%"> Date </th>';
						echo '<th width = "30%"> Amount </th>';
						echo '<th width = "50%" style = "text-align: left;"> Type / Company</th>';
					echo '</tr></thead>';			
					echo '<tbody>';
					while ($row = $result->fetch_assoc()) {
						$sql2 = "SELECT sum(liqamount) as amnt FROM petty_liqdate where petty_id = '$row[petty_id]' and liqtype = '$_SESSION[bytype]' and liqstate = 'CompleteLiqdate' $_SESSION[edate]";
						$result2 = $conn->query($sql2)->fetch_assoc();
						if($result2['amnt'] == ""){
							continue;
						}

						echo '<tr>';
						echo '<td>' . date("M d, Y", strtotime($row['completedate'])) . '</td>';
						echo '<td>' . number_format($row['amnt'],2) . '</td>';
						echo '<td style = "text-align: left;"><b>' . $row['projtype'] . ': </b>' .$row['project'] . '</td>';
						echo '</tr>';
					}
					echo '</tbody></table>';
				} 
			}
				
			
		?>
		<div class="col-xs-12">
			<b><i>
			<?php
				if($meal > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Meal </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"> <u>₱ '.	number_format($meal,2) .'</u></div></div>'; 
				}
				if($gas > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Gasoline </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($gas,2) .'</u></div></div>'; 
				}
				if($transpo > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Transportation </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($transpo,2) .'</u></div></div>';  
				}
				if($cpload > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Cellphone Load </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($cpload,2) .'</u></div></div>'; 
				}
				if($water > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Water Fill </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($water,2) .'</u></div></div>'; 
				}
				if($notary > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Notary Fee </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($notary,2) .'</u></div></div>';  
				}
				if($toll > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Toll Fee </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($toll,2) .'</u></div></div>'; 
				}
				if($gate > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Gate Pass </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($gate,2) .'</u></div></div>'; 
				}
				if($material > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Materials </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($material,2) .'</u></div></div>'; 
				}
				if($others > 0) {
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Others </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($others,2) .'</u></div></div>'; 
				}				
				if($utilities > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Utilities </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($utilities,2) .'</u></div></div>'; 
				}
				if($social > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Social Payments </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($social,2) .'</u></div></div>'; 
				}
				if($permit > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Permit & Licenses </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($permit,2) .'</u></div></div>'; 
				}
				if($services > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Services </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($services,2) .'</u></div></div>'; 
				}
				if($profee > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Professional Fee </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($profee,2) .'</u></div></div>'; 
				}
				if($due > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Dues & Subscriptions </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($due,2) .'</u></div></div>'; 
				}
				if($adver > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Advertising & Promotions </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($adver,2) .'</u></div></div>'; 
				}
				if($repre > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Representation </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($repre,2) .'</u></div></div>'; 
				}
				if($repmaint > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Repair & Maintenance </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($repmaint,2) .'</u></div></div>'; 
				}
				if($bankc > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Bank Charges </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($bankc,2) .'</u></div></div>'; 
				}
				if($misc > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Miscellaneous </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($misc,2) .'</u></div></div>'; 
				}
				if($rental > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Rental </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($rental,2) .'</u></div></div>'; 
				}
				if($viola > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Violation Fee </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($viola,2) .'</u></div></div>'; 
				}
				if($cashadv > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Cash Advance </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($cashadv,2) .'</u></div></div>'; 
				}
				if($bidoc > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Bid Docs </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($bidoc,2) .'</u></div></div>'; 
				}
				if($surety > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Surety Bond </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($surety,2) .'</u></div></div>'; 
				}
				if($parking > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Parking Fee </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($parking,2) .'</u></div></div>'; 
				}
				if($purchases > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Purchases </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($purchases,2) .'</u></div></div>'; 
				}
				if($utidevit > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Utilities Auto Debit </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($utidevit,2) .'</u></div></div>'; 
				}
				if($payroll > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Payroll </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($payroll,2) .'</u></div></div>'; 
				}
				if($inter > 0){
					echo '<div class="row"><div class = "col-xs-2 col-xs-offset-1">Internet </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.	number_format($inter,2) .'</u></div></div>'; 
				}
				$total = $meal + $gas + $transpo + $cpload + $water + $notary + $toll + $gate + $material + $others + $utilities + $social + $permit + $services + $profee + $due + $adver + $repre + $repmaint + $bankc + $misc + $rental + $viola + $cashadv + $utidevit + $bidoc + $surety + $purchases + $parking + $payroll + $inter;
				if($total > 0){
					echo '<div class="row"><div class = "col-xs-7"><hr style = "border-color: #a6a6a6;"></div></div>';
					echo '<div class="row" style = "margin-top: 1px solid;"><div class = "col-xs-2 col-xs-offset-1">Total </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.number_format($total,2).'</u></div></div>';
				}
			?>
			</i></b>
		</div>
	</div>
		<?php } ?>
	</form>
</div>