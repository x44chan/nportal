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
	  	<?php if(isset($_GET['print'])){ ?>
	  	#reportg #red {
	  		color: red !important;
	  	}
	  	#reportg #green{
	  		color: green !important;
	  	}
	  	#reportg h4{
	  		font-size: 14px;
	  	}
	  	#datepr{
	  		margin-top: 25px;
	  	}
	  	#reportg, #reportg * {
	    	visibility: visible;
	 	}
		#reportg th{
	  		font-size: 10px;
	  		width: 0;
		} 
		#reportg td{
	  		font-size: 9px;
	  		bottom: 0px;
	  		padding: 1px;
	  		max-width: 210px;
		}
		#reportg p{
	  		font-size: 9px;
		}
		#totss{
			font-size: 11px;
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
	  		font-size: 11px;
	  	}
	}
	<?php } ?>
	#myTablelea td, #myTablelea th{
		font-size: 13px;
	}

</style>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<u><h4 style="margin-left: -20px; margin-top: -10px;"><i>Correction Report</i></h4></u>
		</div>
	</div>
	<div class="row">
		<form action = "hr-emprof.php?correctionrep" method="get">
			<input type="hidden" name = "correctionrep">
			<div class="col-xs-3">
				<label style="font-size: 13px;">Date From:</label>
				<input type = "date" name = "datefr" <?php if(isset($_GET['datefr'])){ echo ' value = "' . $_GET['datefr'] . '" '; }?> required class="form-control input-sm">
			</div>
			<div class="col-xs-3">
				<label style="font-size: 13px;">Date To:</label>
				<input type = "date" name = "dateto" <?php if(isset($_GET['dateto'])){ echo ' value = "' . $_GET['dateto'] . '" '; }?> required class="form-control input-sm">
			</div>
			<div class="col-xs-3">
				<br>
				<button class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-search"></span> Search </button>
				<a href = "hr-emprof.php?correctionrep" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-refresh"></span> Clear </a>
				<a href = "?correctionrep<?php if(isset($_GET['datefr'])){ echo '&datefr=' . $_GET['datefr']; }?><?php if(isset($_GET['dateto'])){ echo '&dateto=' . $_GET['dateto']; }?>&print" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-print"></span> Print </a>
			</div>
		</form>
	</div>

	<?php
		if(isset($_GET['datefr']) && isset($_GET['dateto']) && $_GET['datefr'] != "" && $_GET['dateto'] != ""){
			$datefr = mysqli_real_escape_string($conn, $_GET['datefr']);
			$dateto = mysqli_real_escape_string($conn, $_GET['dateto']);
			$stmt = "SELECT * FROM login,overtime where overtime.account_id = login.account_id and correction is not null and active = 1 and dateofot BETWEEN '$datefr' and '$dateto' and state IN('CheckedHR','AAdmin') GROUP BY login.fname";
			$result = $conn->query($stmt);
			if($result->num_rows > 0){
	?>
		<hr>
	<div id = "reportg">
		<h4 align="center"><i><u>Correction Report</u></i></h4>
		<h5 align="center"> <?php echo date("M j, Y",strtotime($datefr)) . ' - ' . date("M j, Y",strtotime($dateto)); ?></h5>
		<table class="table">
			<tbody>
				<tr>
					<th>Employee Name</th>
					<th>Total Correction (Overtime)</th>
				</tr>
				<?php
					while ($row = $result->fetch_assoc()) {
						$sql = "SELECT sum(correction) as count from overtime where  correction is not null and  dateofot BETWEEN '$datefr' and '$dateto' and state IN('CheckedHR','AAdmin') and account_id = '$row[account_id]'";
						$data = $conn->query($sql)->fetch_assoc();
						echo '<tr>';
						echo	'<td>'.$row['fname'] . ' ' . $row['lname'].'</td>';
						echo	"<td>" . $data['count'] . '</td>';
						echo '</tr>';
					}

				?>
			</tbody>
		</table>
	</div>
	<?php
			}
		}
	?>
	<?php
		$sby = ""; $search = "";
		if(isset($_GET['datefr'])){ $sby = '&datefr=' . $_GET['datefr'];} 
		if(isset($_GET['dateto'])){ $search = '&dateto=' . $_GET['dateto'];}
		if(isset($_GET['print'])){
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?correctionrep'.$sby.$search.'"});</script>';
		}
	?>
</div>

