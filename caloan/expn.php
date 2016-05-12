<script type="text/javascript">
	$(document).ready(function(){$("table tbody th, table tbody td").wrapInner("<div style = 'page-break-inside: avoid;'></div>");});
</script>
<style type="text/css">
	#bords tr, #bords td{border-top: 1px black solid !important;}
	<?php
		if(isset($_GET['print'])){
			echo 'body { visibility: hidden; }';
		}
		if(isset($_GET['detailedpetty'])){
			echo '	table { page-break-inside:auto }
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
	  		margin-left: 3mm;
	  		margin-right: 3mm;
	  	}
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
				<label> Location </label>
				<select class="form-control input-sm" name = "sproj" required onchange="showUserx(this.value)">
					<option value=""> - - - - - - - </option>
					<?php
            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if((isset($_GET['sproj']) && $_GET['sproj'] == $xrow['loc']) || (isset($_SESSION['loc']) && $_SESSION['loc'] == $xrow['loc'])){
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
			<div class="col-xs-4" id = "locx" style="margin-top: -15px;">
					<?php
						if((isset($_GET['otproject']) && $_GET['otproject'] != "") || (isset($_SESSION['sproj']) && $_SESSION['sproj'] != "")){
							echo '<b>PO <font color = "red"> * </font></b>';
							if(!isset($_GET['sproj'])){
								$_GET['sproj'] = $_SESSION['loc'];
							}
							$otproject = mysqli_real_escape_string($conn, $_GET['sproj']);
							$xx = "SELECT * FROM project where loc = '$otproject'";
							$xxx = $conn->query($xx);
							echo '<select name = "otproject" id = "otproject" class = "form-control input-sm">';
							if($_GET['otproject'] == 'all'){
								echo '<option selected value = "all">All</option>';		
							}else{
								echo '<option value = "all">All</option>';
							}	
							if($xxx->num_rows > 0){
								while ($srow = $xxx->fetch_assoc()) {
									if($_GET['otproject'] == $srow['name'] || $_SESSION['sproj'] == $srow['name']){
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
</div>
		<?php
		
		if(isset($_GET['otproject']) || (isset($_SESSION['sproj']) && $_SESSION['sproj'] != "")) {
			if(isset($_GET['print'])) { 
				echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expn";});</script>';
			}
			if(isset($_GET['otproject'])){
				$project = mysqli_real_escape_string($conn, $_GET['otproject']);
				$_SESSION['sproj'] = $project;
			}
			if(isset($_GET['sproj'])){
				$_SESSION['loc'] = $_GET['sproj'];
			}
		?>
	<div id = "report">
		<div class="row">
			<div class="col-xs-12">
				<i><u><h3 align="center"><?php if(isset($_SESSION['loc'])){ echo $_SESSION['loc'].'<br>'; } ?></h3> <h4 align="center" style="text-transform: capitalize;"><?php if(isset($_SESSION['sproj'])){ echo $_SESSION['sproj'];}?></h4></u></i><a style = "margin-top: -50px; margin-right: 50px;"id = "backs" href = "?expn&print" class = "btn btn-sm btn-success pull-right"><span class = "glyphicon glyphicon-print"></span> Print </a>
			</div>
		</div>
		<?php
			if(isset($_SESSION['sproj']) && !empty($_SESSION['sproj'])){	
				if($_SESSION['sproj'] == 'all'){
					$_SESSION['q'] = " project is not null ";
				}else{
					$_SESSION['q'] = " project = '$_SESSION[sproj]' ";
				}
				$sql = "SELECT * FROM `petty`,`login`,`petty_liqdate` where petty.account_id = petty_liqdate.account_id and petty.petty_id = petty_liqdate.petty_id and petty.account_id = login.account_id  and $_SESSION[q] and position != 'House Helper' and state = 'AAPettyRep' and liqstate = 'CompleteLiqdate' group by liqdate_id order by petty.date desc";
				$result = $conn->query($sql);
				if($result->num_rows > 0){
					$totalliq = 0;
					$a = 0;
					$_SESSION['last_id'] = "";
					echo '<table align = "center" class = "table" style="font-size: 14px;">';
					echo '<tbody>';	
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="5%">#</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="10%">Name</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="10%">Date</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="10%">Type</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="10%">Amount</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="25%">Info</th>';
						echo '<th style = "border-top: none; border-bottom: 2px solid #ddd;" width="25%">Reasons</th>';
						while ($row = $result->fetch_assoc()) {
							$reason = $row['petreason'];
							$sql2 = "SELECT * FROM project where loc = '$_SESSION[loc]'";
							$dataxx = $conn->query($sql2);
							if($dataxx->num_rows > 0){
								while ($xxrow = $dataxx->fetch_assoc()) {
									if($xxrow['name'] == $row['project']){
										if($_SESSION['last_id'] != ""){	
											if($_SESSION['last_id'] == $row['petreason']){
												if(stristr($row['petreason'], ' ')){
													$ex = explode(" ", $row['petreason']);
													$reason = $ex[0] . ' ' . $ex[1] . '......';
												}else{
													$reason = $row['petreason'];
												}						
											}else{
												$reason = $row['petreason'];
											}
										}
										$_SESSION['last_id'] = $row['petreason'];
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
										
										echo '<tr>';
										echo '<td>' . $row['petty_id'] . '</td>';
										echo '<td>' . ucfirst(strtolower($row['fname'])) . ' ' . ucfirst(strtolower($row['lname'])) . '</td>';
										echo '<td>'. date("m/d/Y", strtotime($row['liqdate'])).'</td>';
										echo '<td>'. $row['liqtype'] . ': ' . $row['liqothers'] .'</td>';
										echo '<td>₱ '. number_format($row['liqamount'],2).'</td>';
										echo '<td>'. $row['liqinfo'].'</td>';
										echo '<td>'. $reason .'</td>';
										echo '</tr>';	
										$totalliq += $row['liqamount'];
										$a += str_replace(',', '', $row['amount']);
									}else{
										continue;
									}
								}
							}	
							
						}
					echo '</tbody>';
					echo '</table>';
				}
			}
		?>
	</div>
		<?php } ?>
	</form>
