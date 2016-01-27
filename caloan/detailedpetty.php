<a href = "?detailedpetty&print" class="btn btn-sm"> Print </a>
<script type="text/javascript">
	$(document).ready(function(){$("table tbody th, table tbody td").wrapInner("<div style = 'page-break-before: always;page-break-inside: avoid;'></div>");});
</script>
<?php
	if(isset($_GET['print'])){
		echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?detailedpetty";});</script>';
	}
?>
<div id = "report">
<?php
	$date = date("Y-m-d", strtotime('2016-01-01'));
	include 'conf.php';
		$sql = "SELECT * FROM `petty`,`login`,`petty_liqdate` where petty.account_id = petty_liqdate.account_id and petty.petty_id = petty_liqdate.petty_id and petty.account_id = login.account_id  and particular != 'Check' and position != 'House Helper' and login.account_id != '3' and login.account_id != '4' and login.account_id != '8' and login.account_id != '34' and login.active = '1' and state = 'AAPettyRep' and liqstate = 'CompleteLiqdate' and petty.date >= '2016-01-01' group by liqdate_id order by petty.date asc";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo '<table align = "center" class = "table" style="font-size: 14px;">';
			echo '<thead>';
				echo '<tr>';
				echo '<th width="10%">Name</th>';
				echo '<th width="10%">Date</th>';
				echo '<th width="10%">Type</th>';
				echo '<th width="10%">Amount</th>';
				echo '<th width="25%">Info</th>';
				echo '<th width="25%">Reaons</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$totalliq = 0;
			$a = 0;
			$_SESSION['last_id'] = "";
			while($row = $result->fetch_assoc()){	
				$reason = $row['petreason'];
				if($_SESSION['last_id'] != ""){	
					if($_SESSION['last_id'] == $row['petreason']){
						$ex = explode(" ", $row['petreason']);
						$reason = $ex[0] . ' ' . $ex[1] . '......';
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
				if($row['liqtype'] == 'Others'){
					$row['liqothers'] = ': ' . $row['liqothers'];
				}
				echo '<tr>';
				echo '<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
				echo '<td>'. date("M j, Y", strtotime($row['date'])).'</td>';
				echo '<td>'. $row['liqtype']. $row['liqothers'] .'</td>';
				echo '<td>₱ '. number_format($row['liqamount'],2).'</td>';
				echo '<td>'. $row['liqinfo'].'</td>';
				echo '<td>'. $reason .'</td>';
				echo '</tr>';	
				$totalliq += $row['liqamount'];
				$a += str_replace(',', '', $row['amount']);
			}
			//echo '<tr id = "bords"><td></td><td align = "right"><b>Total: <br><br>Change: </b></td><td>₱ '.number_format($totalliq, 2).'<br><br>₱ '.number_format($a - $totalliq, 2).'</td><td></td><td></td><td></td><td></td></tr>';
			echo '</tbody></table>';
			echo '<hr>';

		}
?>
</div>
