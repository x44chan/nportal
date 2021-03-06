<?php 

include 'header.php';
include 'conf.php';
session_start();
include('savelogs.php');
		$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = petty_liqdate.petty_id and (source = 'Eliseo' or source = 'Sharon') and (particular NOT IN ('Check','Auto Debit'))  group by petty_liqdate.petty_id order by completedate desc,petty.petty_id desc";
		$result = $conn->query($sql);
			echo '<div id = "report"><div align = "center"><i><h3>Liquidate List</h3></i></div>';
			echo '<table class = "table" id = "myTableliq">';
			echo '<thead>';
				echo '<tr>';
				echo '<th>Petty ID</th>';
				echo '<th>Date</th>';
				echo '<th>Name</th>';
				echo '<th>Petty Amount</th>';
				echo '<th>Total Used Petty</th>';
				echo '<th>Change</th>';
				echo '<th id = "backs" >Status</th>';
				echo '<th id = "show" style = "display: none;">Code</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
		$tchange = 0;
		$tamount = 0;
		$tused = 0;
		if($result->num_rows > 0){
			savelogs("Print Return All Changes", date("M j, Y"));
			while($row = $result->fetch_assoc()){
				$petid = $row['petty_id'];
				$accid = $row['account_id'];
				$query = "SELECT * FROM `petty_liqdate` where petty_id = '$petid'";
				$data = $conn->query($query)->fetch_assoc();
				$query1 = "SELECT * FROM `login` where account_id = '$accid'";
				$data1 = $conn->query($query1)->fetch_assoc();
				if($data1['position'] == 'House Helper'){
					continue;
				}		
				$query2 = "SELECT sum(liqamount) as totalliq FROM `petty_liqdate` where petty_id = '$petid'";
				$data2 = $conn->query($query2)->fetch_assoc();
				if($data2['totalliq'] != ""){
					$tots = '<td>₱ ' . number_format($data2['totalliq'],2) . '</td>';
    				$a = str_replace(',', '', $row['amount']);
					$change =  $a - $data2['totalliq'];
					if($change == 0){
						$change =  " - ";
					}
				}else{
					$tots = '<td> - </td>'; 
					$change =  " - ";
				}
				if($data['liqdate'] == ""){
					echo '<tr style = "display: none;">';
				}elseif($row['source'] == 'Accounting'){
					echo '<tr id = "backs">';
				}elseif($data['accval'] != null){
					echo '<tr id = "backs">';
				}elseif($data['liqcode'] == null){
					echo '<tr id = "backs">';
				}elseif($change == " - "){
					echo '<tr id = "backs">';
				}else{
					echo '<tr>';
					if(is_numeric($change)){
						$change = number_format($change,2);
					}else{
						$change = $change;
					}
					$tamount += $a;
					$tused += $data2['totalliq'];
					$tchange += ($a - $data2['totalliq']);
				}
					

				echo '<td>'.$row['petty_id'].'</td>';
				echo '<td>'.date("M j, Y", strtotime($data['completedate']));
				echo '<td>'.$data1['fname'] . ' ' . $data1['lname'].'</td>';
				echo '<td>₱ ';if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo '</td>';
				echo $tots;
				echo '<td>₱ ' . $change . '</td>';
				if($data['liqstate'] == 'CompleteLiqdate'){
					echo '<td id = "backs" ><a href = "?liqdate='.$data['petty_id'].'&acc='.$row['account_id'].'" class = "btn btn-primary">View Liquidate</a></td>';
				}elseif($data['liqstate'] == 'EmpVal'){
					echo '<td id = "backs" ><b><font color = "red">For Employee Validation</font></b><br>';
				}elseif($data['liqstate'] == 'LIQDATE'){
					echo '<td><b> Pending Completion</b></td>';
				}else{
					echo '<td><b> Pending Liquidate</td>';
				}
				echo '<td id = "show" style = "display: none;"></td>';
				echo '</tr>';
				
			}	
			echo '<tr ><td style = "border-top: 1px solid;"></td><td style = "border-top: 1px solid;"></td><td style = "border-top: 1px solid;"><b>Total: </td><td style = "border-top: 1px solid;">₱ '.number_format($tamount,2).'</td><td style = "border-top: 1px solid;">₱ '.number_format($tused,2).'<td style = "border-top: 1px solid;">₱ '. number_format($tchange,2).'</td><td style = "border-top: 1px solid;"></td></tr>';
			echo '</tbody></table></div>';
		}


?>

<script type="text/javascript">
	$(window).load(function() {
    	window.print();
    	window.location.href = "accounting-petty.php?liqdate";
	});
</script>

<style type="text/css">
	#bords tr, #bords td{border-top: 1px black solid !important;}	
	body * {
    	visibility: hidden;
    
  	}
	@media print {		
		body * {
	    	visibility: hidden;
	    
	  	}
	  	@page{
	  		margin-left: 5mm;
	  		margin-right: 5mm;
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
	  		font-size: 20px;
	  		font-weight: bold;
	    }
	 	#report h4{
			font-size: 15px;
		}
		#report h3{
	  		margin-bottom: 10px;
		}
		#report th{
	  		font-size: 12px;
	  		width: 0;
		} 
		#report td{
	  		font-size: 11px;
	  		bottom: 0px;
	  		padding: 3px;
	  		max-width: 210px;
		}
		#totss{
			font-size: 14px;
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
teqz
d3uk
axgw