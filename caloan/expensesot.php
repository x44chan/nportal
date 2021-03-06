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
    	<?php if(isset($_GET['print'])){ ?>
			$("table tbody th, table tbody td").wrapInner("<div style = 'page-break-inside: avoid;'></div>");
		<?php } ?>
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
		$_SESSION['searchbox'] = "";
		$_SESSION['type'] = "";
		$_SESSION['edate'] = "";
		$_SESSION['datefr'] = "";
		$_SESSION['dateto'] = "";
		$_SESSION['loc'] = "";
		//echo '<script>window.location.replace("accounting-petty.php?expenses");</script>';
	}
	if(isset($_GET['pettype']) && $_GET['pettype'] == 'all'){
		$qsearch = "project is not null and ";
		$_SESSION['searchbox'] = "";
		$_SESSION['type'] = "";
	}elseif(isset($_GET['loc']) && $_GET['loc'] == 'all' && $_GET['pettype'] == 'Project'){
		$_SESSION['qsearch']= "projtype = 'Project' and ";
		$_SESSION['searchbox'] = "all";
		$_SESSION['type'] = "Project";
		$_GET['pettype'] = "all";
		$_SESSION['loc'] = mysqli_real_escape_string($conn, $_GET['loc']);
	}
	if(isset($_GET['pettype']) && ($_GET['pettype'] != "" && $_GET['pettype'] != 'all')){
		$_SESSION['type'] = mysqli_real_escape_string($conn, $_GET['pettype']);
		if($_SESSION['type'] == 'P.M.'){
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['pm']);
		}elseif($_SESSION['type'] == 'Internet'){
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['internet']);
		}else{
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['otproject']);
			$_SESSION['loc'] = mysqli_real_escape_string($conn, $_GET['loc']);
		}
		
		if($_SESSION['searchbox'] == 'all'){
			$qsearch = "projtype like '%$_SESSION[type]%' and overtime.project = name and ";
		}else{
			$qsearch = "projtype = '$_SESSION[type]' and project like '%$_SESSION[searchbox]%' and";
		}
		$_SESSION['qsearch'] = $qsearch;
	}
	if((isset($_GET['datefr']) && $_GET['datefr'] != "") && (isset($_GET['dateto']) && $_GET['dateto'] != "")){
		$_SESSION['datefr'] = mysqli_real_escape_string($conn, $_GET['datefr']);
		$_SESSION['dateto'] = mysqli_real_escape_string($conn, $_GET['dateto']);
		$_SESSION['edate'] = "completedate BETWEEN '$_SESSION[datefr]' and '$_SESSION[dateto]' and";
	}
	if(isset($_GET['pettype']) && $_GET['pettype'] == 'all'){
		$_SESSION['alls'] = 'All ';
	}else{
		$_SESSION['alls'] = "";
	}
	if(!isset($_SESSION['edate'])){
		$_SESSION['edate'] = "";
	}
?>
	<div class="container" style="margin-bottom: -30px;">
		<form action="" method="get">
			<input type = "hidden" name = "expenses" value = "expenses">
			<div class="row">
				<div class="col-xs-2" style="margin-top: -15px;">
					<label>Date From: </label>
					<input <?php if(isset($_SESSION['datefr'])){ echo 'value = "' . $_SESSION['datefr'] . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "datefr" class="form-control input-sm">
				</div>
				<div class="col-xs-2" style="margin-top: -15px;">
					<label>Date To: </label>
					<input <?php if(isset($_SESSION['dateto'])){ echo 'value = "' . $_SESSION['dateto'] . '"'; } ?> max = '<?php echo date("Y-12-31");?>' type="date" name = "dateto" class="form-control input-sm">
				</div>
				<div class="col-xs-2"  style="margin-top: -15px;">
					<label>Type </label>
		      		<select class="form-control input-sm" name = "pettype">
		      			<option value="all"> All </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'Project'){ echo ' selected '; }  ?> value="Project"> Project </option>
		      		</select>
				</div>
				<div class="col-xs-2" style="margin-top: -15px; <?php if(!isset($_SESSION['type']) || $_SESSION['type'] != 'Project'){echo 'display: none;';}?>" id = "project">
					<label>Location</label>
					<select class="form-control input-sm" name = "loc" onchange="showUserx(this.value)">
						<option value = "all"> All </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(loc)";
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
				<div class="col-xs-4" style="margin-top: -15px; <?php if(!isset($_SESSION['type']) || $_SESSION['type'] != 'P.M.'){echo 'display: none;';}?>" id = "pm">
					<label>P.M. List</label>
					<select class="form-control input-sm" name = "pm">
						<option value = "all"> All </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1' order by name";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if(isset($_SESSION['searchbox']) && $_SESSION['searchbox'] == $xrow['name']){
	            						$select = ' selected ';
	            					}else{
	            						$select = "";
	            					}
	            					echo '<option '.$select.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>			
					</select>
				</div>
				<div class="col-xs-4" style="margin-top: -15px; <?php if(!isset($_SESSION['type']) || $_SESSION['type'] != 'Internet'){echo 'display: none;';}?>" id = "internet">
					<label>Internet List</label>
					<select class="form-control input-sm" name = "internet">
						<option value = "all"> All </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1' order by name";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if(isset($_SESSION['searchbox']) && $_SESSION['searchbox'] == $xrow['name']){
	            						$select = ' selected ';
	            					}else{
	            						$select = "";
	            					}
	            					echo '<option '.$select.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>			
					</select>
				</div>				
				<div class="col-xs-4" id = "locx" style="margin-top: -12px;">
					<?php
						if((isset($_GET['otproject']) && $_GET['otproject'] != "") || (isset($_SESSION['loc']) && $_SESSION['loc'] != "")) {
							echo '<b>PO <font color = "red"> * </font></b>';
							if(!isset($_GET['loc'])){
								$_GET['loc'] = $_SESSION['loc'];
							}
							if(!isset($_GET['otproject'])){
								$_GET['otproject'] = $_SESSION['searchbox'];
							}
							$otproject = mysqli_real_escape_string($conn, $_GET['loc']);
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
			</div>
			<div class="row">
				<div class="col-xs-12" align="center" style="margin-top: 5px;">
					<button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Search</button>
					<a href = "?expenses&clear" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-refresh"></span> Clear </a>
				</div>					
			</div>
		</form>
	</div>
	<div style="margin-bottom: 40px;"></div>
<?php if(isset($_GET['expenses'])){ ?>
<div id = "report">
	<h2 align="center" style="text-transform: uppercase;" id = "green"><?php if(isset($_SESSION['loc']) && $_SESSION['loc'] != ""){ echo $_SESSION['loc'];}?> Overtime Expenses </h2><h4 align="center" style="text-transform: capitalize;"><i><?php if(isset($_SESSION['searchbox']) && $_SESSION['searchbox'] != ""){ echo $_SESSION['type'].': ' .$_SESSION['searchbox'];}?></h4></i>
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
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expenses";});</script>';
		}
	?>
	<table class="table" <?php if(!isset($_GET['print'])){ echo 'id = "myTabledate"'; }?>>
		<?php
			if(!isset($_GET['print'])){
				echo '<thead style="border-bottom: 2px solid #ddd;">';
			}else{
				echo '<tbody>';
			}
		?>
			<tr>
				<th width="10%" style="border-bottom: 2px solid #ddd;"> Date </th>
				<th width="20%" style="border-bottom: 2px solid #ddd;"> Name </th>
				<th width="20%" style="border-bottom: 2px solid #ddd;"> Overtime </th>				
				<th width="18%" style="border-bottom: 2px solid #ddd;"> Type: Code/Company </th>
				<th width="28%" style="border-bottom: 2px solid #ddd;"> Reason </th>
			</tr>
		<?php if(!isset($_GET['print'])){ echo '</thead><tbody>'; } ?>	
<?php
	$totalpet = 0;
	$totalused = 0;
	if(isset($_SESSION['searchbox']) && isset($_SESSION['type']) && $_SESSION['searchbox'] != "" && $_SESSION['type'] != ""){
		$sql = "SELECT * FROM `overtime`,`login`,`project` where  $_SESSION[edate] $_SESSION[qsearch] login.account_id = overtime.account_id and (overtime.state = 'AAdmin' or overtime.state != 'CheckedHR') GROUP BY overtime.account_id ORDER BY datefile desc, projtype asc, project asc";
	}else{
		$sql = "SELECT * FROM `overtime`,`login`,`project` where  login.account_id = overtime.account_id and name = project and dateofot >= '2016-01-01' and overtime.state IN ('CheckedHR','AAdmin') GROUP BY overtime_id ORDER BY dateofot desc, projtype asc, project asc";	
	}
	$result = $conn->query($sql);
	if($result->num_rows > 0){	
		while($row = $result->fetch_assoc()){
			$explo = (explode(":",$row['approvedothrs']));
			if($row['oldot'] != null && ($row['state'] == 'AAdmin' || $row['state'] == 'CheckedHR')){
					$oldot = '<b><font color = "red">'. $row['oldot'] . '</font>';
					$hrot = '<b><i>';
					$hrclose = '<br>Based On: <i><font color = "green">'.$row['dareason']. "</font></i>";
			}else{
				$oldot = $row["startofot"] . ' - ' . $row['endofot'];
				$hrot = '<b><i>';
				$hrclose = '';
			}
			if($explo[1] > 0){
				$explo2 = '.5';
			}else{
				$explo2 = '.0';
			}
			if($row['otbreak'] != null){
				$otbreak = '<br><b><i>Break: <font color = "red">'. substr($row['otbreak'], 1) . '</font>	<i><b>';
			}else{
				$otbreak = "";
			}
			echo '<tr>';
			echo	'<td>' . date("M j, Y", strtotime($row['dateofot'])) . '</td>';
			echo	'<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
			echo '<td>'.$hrot .  $row["startofot"] . ' - ' . $row['endofot'] . $hrclose . $otbreak  . '<br><font color = "green">'.$explo[0].$explo2.'</td>';
			echo	'<td style= "text-align: left;"><b>' . $row['projtype'] .': </b>'. $row['project'] . '</td>';
			echo	'<td>' . $row['reason'] . '</td>';
			echo '</tr>';
		}
		
		//echo '<script type = "text/javascript">$(document).ready(function(){ $("#total").text("₱ '.number_format($totalused	,2).'");});</script>';
		if(isset($_GET['print'])){
		//	echo '<tr id = "bords"><td></td><td><b> Total </b></td><td><b>₱ ' . number_format($totalpet,2) . '</td><td><b>₱ ' . number_format($totalused,2) . '</td><td></td><td></td></tr>';
		}
	}

?>
		</tbody>
	</table>
</div>
	<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?expenses&print"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a></div>
<?php } ?>