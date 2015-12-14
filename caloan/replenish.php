<?php
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
			$_SESSION['dates'] = $_POST['repfr'];
			$_SESSION['dates0'] = $_POST['repto'];
			$_SESSION['repleamount'] = $_POST['repleamount'];
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?replenish");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['dates']);
			unset($_SESSION['dates0']);
			unset($_SESSION['repleamount']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?replenish");</script>';
		}
?>
<style type="text/css">
	#bords tr, #bords td{border-top: 1px black solid !important; border-width: 1px;}

	@media print {		
		

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
	  		font-size: 12pt;
	  		font-weight: bold;
	    }
	    #report h3{
	    	font-size: 12pt;
	    }
	 	#report h4{
			font-size: 10pt;
		}
		#report h3{
	  		margin-bottom: 10px;
		}
		#report th{
	  		font-size: 7pt;
	  		width: 0;
		} 
		#report td{
	  		font-size: 6pt;
	  		bottom: 0px;
	  		padding: 3px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 8pt;
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

</style>
<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Petty Replenish Report Filtering </i></u></h4>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-3 col-xs-offset-1" align="center">
				<label>Total Fund</label>
				<input type = "text" class="form-control input-sm" required <?php if(isset($_SESSION['repleamount'])){ echo ' value = "' . $_SESSION['repleamount'] . '" '; } else { echo ' value = "0" '; }?>name = "repleamount" placeholder = "Enter amount"/>
			</div>
			<div class="col-xs-2" align="center">
				<label>Date From</label>
				<input class="form-control input-sm" name ="repfr" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date'] . '" '; }else{ echo ' value = "' .date("Y-m-01") . '" '; } ?> />
			</div>
			<div class="col-xs-2" align="center">
				<label>Date To</label>
				<input class="form-control input-sm" name = "repto" type = "date" <?php if(isset($_SESSION['date'])){ echo 'value = "'. $_SESSION['date0'] . '" '; }else{ echo ' value = "' .date("Y-m-t") . '" '; } ?> />
			</div>
			<div class="col-xs-4">
				<label style="margin-left: 50px;">Action</label>
				<div class="form-group" align="left">
					<button type="submit" name = "repfilter" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Submit</button>
					<button type="" class="btn btn-danger btn-sm" name ="represet"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
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
		<div class="col-xs-12" align="center" style = "<?php if(!isset($_GET['print'])){ echo ' margin-top: -40px;'; }else{echo 'font-size: 8pt;'; } ?>">
			<i><h3>Petty Replenish Report</h3></i>
			<b ><i>
				<?php echo date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2)); ?>
			</i></b>
		</div>
		<div class="col-xs-12" align="right" style="<?php echo ' font-size: 9pt; ';?>">
			<i><b>
				<?php if(!isset($_SESSION['repleamount'])){ $_SESSION['repleamount'] = 0; } echo 'Total Fund: <span class = "badge">₱ ' . number_format($_SESSION['repleamount'],2) . '</span><br>';?>
			</b></i>
		</div>
	</div>
<?php
		if(isset($_GET['print'])){
			echo '<table align = "center" class = "table table-hover" style="font-size: 14px;">';
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?replenish";});</script>';
		}else{
			echo '<table id = "myTablepet" align = "center" class = "table table-hover" style="font-size: 14px;">';
		}
		
		echo '<thead>
				<tr>
					<th width = "8%">Petty#</th>
					<th width = "10%">Date</th>
					<th width = "10%">Received By</th>
					<th width = "40%">Description</th>
					<th width = "8%">Petty Amount</th>
					<th width = "8%">Total Used Petty</th>
					<th width = "6%">Change</th>
				</tr>
			  </thead>
			  <tbody>';
		include("conf.php");

		$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and date BETWEEN '$date1' and '$date2' and state = 'AApettyRep' and source = 'Accounting' and particular = 'Cash' order by date desc";
		$result = $conn->query($sql);
		$total = 0;
		$change = 0;
		$used = 0;
		$xchange = 0;
		if($result->num_rows > 0){			
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$query2 = "SELECT sum(liqamount) as totalliq,liqinfo,liqtype FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				if($data2['liqinfo'] == null){
					$state = '<b>Pending Liquidation</b>';
				}elseif($data2['totalliq'] <= 0){
					continue;
				}else{
					$state = '₱ ' . number_format($data2['totalliq'],2);
				}
				$a = str_replace(',', '', $row['amount']);
				if($data2['liqinfo'] == null){
					$xchange += $a;
					$tchange = ' - ';
				}else{
					$tchange = '₱ '. number_format($a - $data2['totalliq']);
				}
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['date'])). '</td>';
				echo '<td>' . $row['fname'] . ' '. $row['lname'] . '</td>';				
				echo '<td>' . $row['petreason'] . '</td>';
				echo '<td>₱ ';if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); } ;echo '</td>';
				echo '<td>';
					
					echo $state;
				echo '</td>';
					
				echo '<td>'.$tchange.'</td>';
				echo '</tr>';
				$used += $data2['totalliq'];
				$total += $a;
				$change += ($a - $data2['totalliq']);
			}
		}
		if(isset($_GET['print'])){
			echo '<tr id = "bords"><td></td><td></td><td></td><td><b> Total: </td><td>₱ '.number_format($total,2).'</td><td>₱ '.number_format($used,2).'</td><td>₱ '.number_format($change - $xchange,2).'</td></tr>';
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td><b>Balance: </td><td>₱ '.number_format($_SESSION['repleamount'] - $total,2).'</td><td></td></tr>';
			echo '<tr id = "bords"><td></td><td></td><td></td><td></td><td></td><td><b>Cash On Hand: </td><td>₱ '.number_format(($_SESSION['repleamount'] - $total) + ($change - $xchange), 2).'</td><td></td></tr>';
			echo '<tr><td colspan = 10 style = "border-top: 0px;"><br><br><br><br><br> -- Nothing Follows -- </td></tr>';
		}		
		echo "</tbody></table></div>";	
		echo '<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?replenish&print"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a><a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';

?>