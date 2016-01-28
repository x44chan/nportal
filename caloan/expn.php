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
			$_SESSION['sproj'] = "";
		}
	?>
</style>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<u><i><h4 style="margin-left: -10px;"> Expenses Viewing </h4></i></u>
		</div>
	</div>
	<form action ="" method="get">
		<input type = "hidden" name = "expn"/>
		<div class="row">
			<div class="col-xs-4 col-xs-offset-2" style="margin-top: -20px;">
				<label> Project </label>
				<select class="form-control input-sm" name = "sproj" required>
					<option value=""> - - - - - - - </option>
					<?php
						include('conf.php');
						$sqlx = "SELECT * FROM `project` where type = 'Project'";
						$resultx = $conn->query($sqlx);
						if($resultx->num_rows > 0){
							while($row = $resultx->fetch_assoc()){
								if((isset($_GET['sproj']) && $_GET['sproj'] == $row['name']) || (isset($_SESSION['sproj']) && $_SESSION['sproj'] == $row['name'])){
									$select = " selected ";
								}else{
									$select = "";
								}
								echo '<option '.$select.' value = "' . $row['name'] . '"> ' . $row['name'] . '</option>';
							}
						}
					?>
				</select>
			</div>
			<!--<div class="col-xs-4" style="margin-top: -20px;">
				<label> Type </label>
				<select class="form-control input-sm" name = "type">
					<option value=""> - - - - - - - - </option>
					<?php
						$sql = "SELECT * FROM petty_type where type_id != 4 and type_id != 10 and type_id != 11";
						$result = $conn->query($sql);
						if($result->num_rows > 0){
							while ($row = $result->fetch_assoc()) {
								if(isset($_GET['type']) && $_GET['type'] == $row['type']){
									$select = " selected ";
								}else{
									$select = "";
								}
								echo '<option '. $select .' value = "' . $row['type'] . '"> ' . $row['type'] . '</option>';
							}
						}
					?>
				</select>
			</div>-->
		</div>
		<div class="row">
			<div class="col-xs-12" align="center"  style="margin-bottom: -25px;">
				<button class="btn btn-primary btn-sm"><span class = "glyphicon glyphicon-search"></span> Search </button>
				<a class="btn btn-danger btn-sm" href = "?expn&clear"><span class="glyphicon glyphicon-refresh"></span> Clear </a>
				<hr>
			</div>
		</div>
		<?php
		
		if(isset($_GET['sproj']) || (isset($_SESSION['sproj']) && $_SESSION['sproj'] != "")) {
			if(isset($_GET['print'])) { 
				echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expn";});</script>';
			}
			if(isset($_GET['sproj'])){
				$project = mysqli_real_escape_string($conn, $_GET['sproj']);
				$_SESSION['sproj'] = $project;
			}
		?>
	<div id = "report">
		<div class="row">
			<div class="col-xs-12">
				<h3><i><u><?php if(isset($_SESSION['sproj'])){ echo $_SESSION['sproj']. '' ; } if(isset($_GET['type'])){ echo $_GET['type'];}?> <a id = "backs" href = "?expn&print" class = "btn btn-sm btn-success pull-right"><span class = "glyphicon glyphicon-print"></span> Print </a></u></i></h3>
			</div>
		</div>
		<?php
			if(isset($_SESSION['sproj']) && $_SESSION['sproj'] != ""){
				$project = $_SESSION['sproj'];
			}
			if(!empty($_GET['type'])){
				$type = mysqli_real_escape_string($conn, $_GET['type']);
				$query = "and liqtype = '$type' ";
			}else{
				$type = "";
				$query = "";
			}			
			$sql = "SELECT * FROM `petty` where project = '$project' and state = 'AAPettyRep'";
			$result = $conn->query($sql);
			$meal = 0;
			$gas = 0;
			$transpo = 0;
			$cpload = 0;
			$water = 0;
			$notary = 0;
			$toll = 0;
			$gate = 0;
			$material = 0;
			if($result->num_rows > 0){
				while ($row = $result->fetch_assoc()) {
					$sql2 = "SELECT * FROM petty_liqdate where petty_id = '$row[petty_id]' and liqstate = 'CompleteLiqdate' $query";
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
							}elseif(stristr($row['liqothers'], 'material')){
								$material += $row['liqamount'];
							}
						}
					} 
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
				$total = $meal + $gas + $transpo + $cpload + $water + $notary + $toll + $gate + $material;
				if($total > 0){
					echo '<div class="row"><div class = "col-xs-6"><hr></div></div>';
					echo '<div class="row" style = "margin-top: 1px solid;"><div class = "col-xs-2 col-xs-offset-1">Total </div><div class = "col-xs-1" style = "text-align: center;">:</div><div style = "text-align: right;" class = "col-xs-2"><u>₱ '.number_format($total,2).'</u></div></div>';
				}
			?>
			</i></b>
		</div>
	</div>
		<?php } ?>
	</form>
</div>