<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 25,
    		<?php 
		        if(isset($_GET['print'])){
		        	echo '"paging": false,';
		        }
	        ?>
        	"order": [[ 0, "desc" ],[ 4, 'asc']]

    	});
    	$("#myTabledate_filter").hide();
    	$("#myTabledate_length").hide();
	});
</script>
<style type="text/css">
	#myTabledate tr, #myTabledate td{
		font-size: 14px;
	}
</style>
<?php
	include 'conf.php';
	if(isset($_GET['clear'])){
		$_SESSION['datefr'] = date("Y-m-01");
		$_SESSION['dateto'] = date("Y-m-t");
		//echo '<script>window.location.replace("accounting-petty.php?expenses");</script>';
	}
	if((isset($_GET['datefr']) && $_GET['datefr'] != "") && (isset($_GET['dateto']) && $_GET['dateto'] != "")){
		$_SESSION['datefr'] = mysqli_real_escape_string($conn, $_GET['datefr']);
		$_SESSION['dateto'] = mysqli_real_escape_string($conn, $_GET['dateto']);
		$_SESSION['edate'] = "completedate BETWEEN '$_SESSION[datefr]' and '$_SESSION[dateto]' and";
	}
	if(!isset($_SESSION['datefr']) && !isset($_SESSION['dateto'])){
		$_SESSION['datefr'] = date("Y-m-01");
		$_SESSION['dateto'] = date("Y-m-t");
	}
	$_SESSION['edate'] = "completedate BETWEEN '$_SESSION[datefr]' and '$_SESSION[dateto]' and";
?>
	<div class="container" style="margin-bottom: -30px;">
		<form action="" method="get">
			<input type = "hidden" name = "expsum" value = "expsum">
			<div class="row">
				<div class="col-xs-2" style="margin-top: -15px;">
					<label>Date From: </label>
					<input <?php if(isset($_SESSION['datefr'])){ echo 'value = "' . $_SESSION['datefr'] . '"'; }else{ echo 'value = "' . date("Y-m-01") . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "datefr" class="form-control input-sm">
				</div>
				<div class="col-xs-2" style="margin-top: -15px;">
					<label>Date To: </label>
					<input <?php if(isset($_SESSION['dateto'])){ echo 'value = "' . $_SESSION['dateto'] . '"'; }else{ echo 'value = "' . date("Y-m-t") . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "dateto" class="form-control input-sm">
				</div>
			</div>	
			<div class="row">
				<div class="col-xs-12" align="center" style="margin-top: 5px;">
					<button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Search</button>
					<a href = "?expsum&clear" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-refresh"></span> Clear </a>
				</div>					
			</div>
		</form>
	</div>
	<div style="margin-bottom: 40px;"></div>
<?php if(isset($_GET['expsum'])){ ?>
<div id = "report">
	<h2 align="center" style="text-transform: uppercase;" id = "green"><?php if(isset($_SESSION['loc'])){ echo $_SESSION['loc'];}?> Expenses </h2><h4 align="center" style="text-transform: capitalize;"><i><?php if(isset($_SESSION['searchbox']) && $_SESSION['searchbox'] != ""){ echo 'Project: ' .$_SESSION['searchbox'];}?></h4></i>
	<i><h5 align="center" style="margin-top: -20px; font-size: 12px;"><?php if(isset($_SESSION['datefr']) && $_SESSION['datefr'] != ""){ echo '<br>'; if(substr($_SESSION['datefr'], 0 , 4) == substr($_SESSION['dateto'], 0 , 4)){ echo date("M j", strtotime($_SESSION['datefr'])); }else{echo date("M j, Y", strtotime($_SESSION['datefr']));} echo ' - ' . date("M j, Y",strtotime($_SESSION['dateto'])); } ?></h5></i>
	<div class="row">
		<div class="col-xs-12" align="right" <?php if(isset($_GET['print'])){ echo 'style="font-size: 11px;"'; }?>>
			<i><b>
				Total Expenses:<span class = "badge" id = "total" <?php if(isset($_GET['print'])){ echo 'style="font-size: 11px;"'; } else{ echo 'style = "font-size: 14px;"';} ?>>₱ 0</span><br>
			</b></i>
		</div>
	</div>
	<?php 
		if(isset($_GET['print'])) { 
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expsum";});</script>';
		}
	?>
	<table class="table" <?php if(!isset($_GET['print'])){ echo 'id = "myTabledate"'; }?>>
		<thead style="border-bottom: 2px solid #ddd;">
			<tr>
				<th width="5%"> # </th>
				<th width="10%"> Date </th>
				<th width="20%"> Name </th>
				<th width="12%"> Category </th>
				<th width="12%"> Amount </th>
				<th width="18%"> Type: Code/Company </th>
				<!--<th width="28%"> Reason </th>-->
			</tr>
		</thead>
		<tbody>	
<?php
	$totalpet = 0;
	$totalused = 0;
	$sql = "SELECT * FROM `petty`,`project`,`petty_liqdate` where $_SESSION[edate] petty.petty_id = petty_liqdate.petty_id and petty.state = 'AAPettyRep' and completedate is not null GROUP BY petty_liqdate.liqtype,petty_liqdate.petty_id ORDER BY completedate desc, projtype asc, project asc";	
	$result = $conn->query($sql);
	if($result->num_rows > 0){	
		while($row = $result->fetch_assoc()){
			$sql2 = "SELECT * FROM login where account_id = '$row[account_id]'";
			$data = $conn->query($sql2)->fetch_assoc();			
			$sql23 = "SELECT sum(liqamount) as sumxx,petty_liqdate.* from petty_liqdate where petty_id = '$row[petty_id]' and liqtype = '$row[liqtype]'";
			$data23 = $conn->query($sql23)->fetch_assoc();
			
			$sql234 = "SELECT * from project where name = '$row[project]'";
			$data234 = $conn->query($sql234)->fetch_assoc();
			
			if(!is_numeric($row['amount'])){
				$row['amount'] = str_replace(',', "", $row['amount']);
			}else{
				$row['amount'] = $row['amount'];
			}
			if($data234['loc'] == null){
				$asd = null;
			}else{
				$asd = '<br><b>Location: </b>'.$data234['loc'];
			}
			$totalpet += $row['amount'];
			$totalused += $data23['sumxx'];
			
			echo '<tr>';
			echo	'<td>' . $row['petty_id'] . '</td>';
			echo	'<td>' . date("M j, Y", strtotime($row['completedate'])) . '</td>';
			echo	'<td>' . $data['fname'] . ' ' . $data['lname'] . '</td>';
			echo	'<td> ' . $row['liqtype'] . '</td>';		
			echo	'<td>₱ ' . number_format($data23['sumxx'], 2) . '</td>';
			echo	'<td style= "text-align: left;"><b>' . $row['projtype'] .': </b>'. $row['project'] . $asd.'</td>';
			//echo	'<td>' . $row['petreason'] . '</td>';
			echo '</tr>';
		}
		
		echo '<script type = "text/javascript">$(document).ready(function(){ $("#total").text("₱ '.number_format($totalused	,2).'");});</script>';
		if(isset($_GET['print'])){
			echo '<tr id = "bords"><td></td><td></td><td><b> Total </b></td><td></td><td><b>₱ ' . number_format($totalused,2) . '</td><td></td><td></td></tr>';
		}
	}

?>
		</tbody>
	</table>
</div>
	<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?expsum&print"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a></div>
<?php } ?>