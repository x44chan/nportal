<script type="text/javascript">		
    $(document).ready( function () {
    	$('#myTabledate').DataTable({
    		"iDisplayLength": 30,
    		<?php 
		        if(isset($_GET['print'])){
		        	echo '"paging": false,';
		        }
	        ?>
        	"order": [[ 4, "asc" ],[ 0, 'desc']]

    	});
    	$("#myTabledate_filter").hide();
    	$("#myTabledate_length").hide();
	});
</script>
<?php
	include 'conf.php';
	if(isset($_GET['clear'])){
		$_SESSION['searchbox'] = "";
		$_SESSION['type'] = "";
		//echo '<script>window.location.replace("accounting-petty.php?expenses");</script>';
	}
	if(isset($_GET['pettype']) && $_GET['pettype'] == 'all'){
		$qsearch = "project is not null and ";
		$_SESSION['searchbox'] = "";
		$_SESSION['type'] = "";
	}
	if(isset($_GET['pettype']) && ($_GET['pettype'] != "" && $_GET['pettype'] != 'all')){
		$_SESSION['type'] = mysqli_real_escape_string($conn, $_GET['pettype']);
		if($_SESSION['type'] == 'P.M.'){
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['pm']);
		}elseif($_SESSION['type'] == 'Internet'){
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['internet']);
		}else{
			$_SESSION['searchbox'] = mysqli_real_escape_string($conn, $_GET['project']);
		}
		
		if($_SESSION['searchbox'] == 'all'){
			$qsearch = "project = name and ";
		}else{
			$qsearch = "type = '$_SESSION[type]' and project like '%$_SESSION[searchbox]%' and";
		}
	}
	if(isset($_SESSION['searchbox']) && isset($_SESSION['type']) && $_SESSION['searchbox'] != "" && $_SESSION['type'] != ""){
		$sql = "SELECT * FROM `petty`,`project` where  $qsearch (petty.state != 'DAPetty' and petty.state != 'CPetty') GROUP BY petty_id ORDER BY project asc, petty_id desc";
	}else{
		$sql = "SELECT * FROM `petty`,`project` where name = project and petty.state = 'AAPettyRep' GROUP BY petty_id ORDER BY name asc, petty_id desc";	
	}
	$result = $conn->query($sql);
?>
	<div class="container" style="margin-bottom: -30px;">
		<form action="" method="get">
			<input type = "hidden" name = "expenses" value = "expenses">
			<div class="row">
				<div class="col-xs-3"  style="margin-top: -15px;">
					<label>Type </label>
		      		<select class="form-control input-sm" name = "pettype">
		      			<option value="all"> All </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
		      			<option <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'Project'){ echo ' selected '; }  ?> value="Project"> Project </option>
		      		</select>
				</div>
				<div class="col-xs-4" style="margin-top: -15px; <?php if(!isset($_SESSION['type']) || $_SESSION['type'] != 'Project'){echo 'display: none;';}?>" id = "project">
					<label>Search</label>
					<select class="form-control input-sm" name = "project">
						<option value = "all"> All </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' order by name";
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
				<div class="col-xs-4" style="margin-top: -15px; <?php if(!isset($_SESSION['type']) || $_SESSION['type'] != 'P.M.'){echo 'display: none;';}?>" id = "pm">
					<label>Search</label>
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
					<label>Search</label>
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
				<div class="col-xs-2" style="margin-top: -15px;">
					<label>Action</label>
					<div class="form-inline">
						<button class="btn btn-primary btn-sm">Search</button>
						<a href = "accounting-petty.php?expenses&clear" class="btn btn-danger btn-sm"> Clear </a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<hr>
				</div>
			</div>
		</form>
	</div>
<?php if(isset($_GET['expenses'])){ ?>
<div id = "report">
	<h2 align="center" style="text-transform: uppercase;" id = "green"><?php if(isset($_SESSION['searchbox'])){echo $_SESSION['searchbox']; } ?> Expenses </h2>
	<?php 
		if(isset($_GET['print'])) { 
			echo '<script type = "text/javascript">	$(window).load(function() {window.print();window.location.href = "?expenses";});</script>';
		}
	?>
	<table class="table" <?php if(!isset($_GET['print'])){ echo 'id = "myTabledate"'; }?>>
		<thead style="border-bottom: 2px solid #ddd;">
			<tr>
				<th width="10%"> Petty# </th>
				<th width="20%"> Name </th>
				<th width="12%"> Amount </th>
				<th width="12%"> Total Used </th>
				<th width="18%"> Project / Type </th>
				<th width="28%"> Reason </th>
			</tr>
		</thead>
		<tbody>		
<?php
	$totalpet = 0;
	$totalused = 0;
	if($result->num_rows > 0){	
		while($row = $result->fetch_assoc()){
			$sql2 = "SELECT * FROM login where account_id = '$row[account_id]'";
			$data = $conn->query($sql2)->fetch_assoc();			
			$sql23 = "SELECT sum(liqamount) as sumliq,petty_liqdate.* from petty_liqdate where petty_id = '$row[petty_id]'";
			$data23 = $conn->query($sql23)->fetch_assoc();
			
			if(!is_numeric($row['amount'])){
				$row['amount'] = str_replace(',', "", $row['amount']);
			}else{
				$row['amount'] = $row['amount'];
			}
			$totalpet += $row['amount'];
			$totalused += $data23['sumliq'];
			echo '<tr>';
			echo	'<td>' . $row['petty_id'] . '</td>';
			echo	'<td>' . $data['fname'] . ' ' . $data['lname'] . '</td>';
			echo	'<td>₱ ' . number_format($row['amount'], 2) . '</td>';		
			echo	'<td>₱ ' . number_format($data23['sumliq'], 2) . '</td>';
			echo	'<td>' . $row['project'] . '</td>';
			echo	'<td>' . $row['petreason'] . '</td>';
			echo '</tr>';
		}
		if(isset($_GET['print'])){
			echo '<tr id = "bords"><td></td><td><b> Total </b></td><td><b>₱ ' . number_format($totalpet,2) . '</td><td><b>₱ ' . number_format($totalused,2) . '</td><td></td><td></td></tr>';
		}
		echo '</tbody>';
	}

?>
		
	</table>
</div>
	<div align = "center"><br><a id = "backs" style = "margin-right: 10px;"class = "btn btn-primary" href = "?expenses&print"><span id = "backs"class="glyphicon glyphicon-print"></span> Print Report</a></div>
<?php } ?>