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
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?replenish&'.$_POST['stats'].'");</script>';
		}
		if(isset($_POST['represet'])){
			unset($_SESSION['dates']);
			unset($_SESSION['dates0']);
			unset($_SESSION['repleamount']);
			echo '<script type = "text/javascript">window.location.replace("accounting-petty.php?replenish");</script>';
		}
?>

<form action = "" method="post">
	<div class="container" id = "reports" style="margin-top: -20px;">
		<div class="row">
			<div class="col-xs-12">
				<h4 style="margin-left: -20px;"><u><i>Petty Replenish Report Filtering </i></u></h4>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-2" align="center">
				<label>Total Fund</label>
				<input type = "text" class="form-control input-sm" <?php if(isset($_SESSION['repleamount']) && $_SESSION['repleamount'] > 0){ echo ' value = "' . $_SESSION['repleamount'] . '" '; } else { echo ' value = "" '; }?>name = "repleamount" placeholder = "Enter amount"/>
			</div>			
			<div class="col-xs-3" align="center">
				<label>Status</label>
				<select name = "stats" class="form-control input-sm">
					<option value = ""> All </option>
					<option <?php if(isset($_GET['nopending'])){ echo ' selected '; } ?> value = "nopending"> Completed Petty Cash </option>
					<option <?php if(isset($_GET['spendliqui'])){ echo ' selected '; } ?> value = "spendliqui"> All Pending Petty Cash </option>
					<option <?php if(isset($_GET['bdochck'])){ echo ' selected '; } ?> value = "bdochck"> BDO Cheque </option>
					<option <?php if(isset($_GET['planterschck'])){ echo ' selected '; } ?> value = "planterschck"> Planters Cheque </option>
					<option <?php if(isset($_GET['pendingchck'])){ echo ' selected '; } ?> value = "pendingchck"> All Pending Cheque </option>
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
		<div class="col-xs-12" align="center" style = "<?php if(!isset($_GET['print'])){ echo ' margin-top: -40px;'; }else{echo 'font-size: 12px;'; } ?>">
			<i>
				<h3>Petty Replenish Report <?php if(isset($_GET['bdochck'])){ echo "<br>(BDO Cheque)"; }elseif(isset($_GET['planterschck'])){ echo "<br>(Planters Cheque)"; }?></h3>
			</i>
			
			<b style = "font-size: 12px;"><i>
				<?php echo date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2)); ?>
			</i></b>
		</div>
		<div class="col-xs-12" align="right" style="<?php if(isset($_GET['bdochck']) || isset($_GET['planterschck'])){ echo ' display: none; '; } echo ' font-size: 12px; ';?>">
			<i><b>
				<?php if(!isset($_SESSION['repleamount']) || $_SESSION['repleamount'] == ""){ $_SESSION['repleamount'] = 0; } echo 'Total Fund: <span class = "badge">₱ ' . number_format($_SESSION['repleamount'],2) . '</span><br>';?>
			</b></i>
		</div>
	</div>
<?php
		if(isset($_GET['nopending'])){
			$xlink = "nopending";
		}elseif (isset($_GET['spendliqui'])){
			$xlink = 'spendliqui';
		}elseif(isset($_GET['bdochck'])){
			$xlink = "bdochck";
		}elseif(isset($_GET['planterschck'])){
			$xlink = "planterschck";
		}else{
			$xlink = "";
		}
		if(isset($_GET['print'])){
			if($_SESSION['repleamount'] <= 0) {
				if(isset($_GET['bdochck']) || isset($_GET['planterschck'])){
					
				}else{
					echo '<script> alert("Add petty fund first before printing report"); window.location.href = "?replenish";</script>';
				}				
			}
			echo '<table align = "center" class = "table table-hover" style="font-size: 14px;">';
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?replenish&'.$xlink.'";});</script>';
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
		if(isset($_GET['bdochck']) || isset($_GET['planterschck']) || isset($_GET['pendingchck'])){
			if(isset($_GET['bdochck'])){
				$query = " (source = 'Eliseo' or source = 'Sharon') and particular = 'Check' ";
			}
			if(isset($_GET['planterschck'])){
				$query = " source = 'Accounting' and particular = 'Check'";
			}
			if(isset($_GET['pendingchck'])){
				$query = " particular = 'Check' ";
			}
			$sql = "SELECT * from `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id and petty_liqdate.account_id = petty.account_id and completedate BETWEEN '$date1' and '$date2' and state = 'AApettyRep' and $query group by petty_liqdate.petty_id order by completedate desc";		
		}elseif(isset($_GET['spendliqui'])){
			$sql = "SELECT * from `petty`,`login` where login.account_id = petty.account_id and ( (date BETWEEN '$date1' and '$date2') or (releasedate BETWEEN '$date1' and '$date2') ) and state = 'AApettyRep' and source = 'Accounting' and particular = 'Cash' order by petty_id desc";
 		}else{
			$sql = "SELECT * from `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id and petty_liqdate.account_id = petty.account_id and completedate BETWEEN '$date1' and '$date2' and state = 'AApettyRep' and source = 'Accounting' and particular = 'Cash' group by petty_liqdate.petty_id order by completedate desc";		
		}
		$result = $conn->query($sql);
		$total = 0;
		$change = 0;
		$used = 0;
		$xchange = 0;
		if($result->num_rows > 0){			
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$query2 = "SELECT sum(liqamount) as totalliq,liqinfo,liqtype,liqstate,completedate FROM `petty_liqdate` where petty_id = '$row[petty_id]'";
				$data2 = $conn->query($query2)->fetch_assoc();
				if($data2['liqinfo'] == null || $data2['liqstate'] == 'LIQDATE'){
					$state = '<b>Pending Liquidation</b>';
					if($data2['liqstate'] == 'LIQDATE'){
						$state = '₱ ' . number_format($data2['totalliq'],2).'<br><b> Pending Completion </b>';
					}
					if(isset($_GET['nopending'])){
						continue;
					}
				}elseif($data2['totalliq'] <= 0){
					continue;
				}else{
					$state = '₱ ' . number_format($data2['totalliq'],2);
				}
				if($data2['liqinfo'] == null || $data2['liqstate'] == 'LIQDATE'){
					if(isset($_GET['nopending'])){
						continue;
					}
				}else{
					if(isset($_GET['spendliqui'])){
						continue;	
					} 
				}
				if(isset($_GET['pendingchck'])){
					if($row['liqstate'] != 'CompleteLiqdate'){
						$state = '₱ ' . number_format($data2['totalliq'],2).'<br><b> Pending Completion </b>';
					}else{
						continue;
					}
				}
				if(isset($_GET['bdochck']) || isset($_GET['planterschck'])){
					if($row['liqstate'] != 'CompleteLiqdate'){
						continue;
					}
				}
				$a = str_replace(',', '', $row['amount']);
				if($data2['liqinfo'] == null){
					$xchange += $a;
					$tchange = ' - ';
				}else{
					$tchange = '₱ '. number_format($a - $data2['totalliq'], 2);
				}
				if(!isset($row['completedate']) || $row['completedate'] == ""){
					$row['completedate'] = $row['date'];
				}else{
					$row['completedate'] = $row['completedate'];
				}
				$query24 = "SELECT * FROM `login` where account_id = '$row[account_id]'";
				$data24 = $conn->query($query24)->fetch_assoc();
				echo '<tr>';
				echo '<td>' . $row['petty_id'] . '</td>';
				echo '<td>' . date("M j, Y", strtotime($row['completedate'])). '</td>';
				echo '<td>' . $data24['fname'] . ' '. $data24['lname'] . '</td>';				
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
			if(isset($_GET['nopending'])){
				$statusss = " Completed ";
			}elseif(isset($_GET['spendliqui'])){
				$statusss = " All Pending Petty Cash ";
			}elseif(isset($_GET['bdochck'])){
				$statusss = " Completed BDO Check ";
			}elseif(isset($_GET['planterschck'])){
				$statusss = " Planters Check ";
			}elseif(isset($_GET['pendingchck'])){
				$statusss = " All Pending Check ";
			}else{
				$statusss = " All ";
			}
			savelogs("Print Replenish Report", "Total Fund: ₱ " . number_format($_SESSION['repleamount']) . " - Status: " . $statusss . " - Date Covered: " .  date("M j, Y", strtotime($date1)) . ' - ' . date("M j, Y", strtotime($date2)));
		}		
		
		echo "</tbody></table></div>";	
		echo '<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?replenish&print&'.$xlink.'"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a><a id = "backs" class = "btn btn-danger" href = "accounting-petty.php"><span id = "backs"class="glyphicon glyphicon-chevron-left"></span> Back to List</a></div>';

?>