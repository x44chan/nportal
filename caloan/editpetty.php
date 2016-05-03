<?php
	$accid = $_SESSION['acc_id'];
	$petid = mysql_escape_string($_GET['editpetty']);
	include("conf.php");
	$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid and petty_id = '$petid' and (state = 'UAPetty' or state = 'UATransfer') order by state ASC, source asc";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
<div class="container" style="padding: 2px 4px;">
	<div class="row">
		<div class="col-xs-12" align="center">
			<u><i><h3>Edit Petty</h3></i></u>
			<hr>
		</div>
	</div>
<?php
	while ($row = $result->fetch_assoc()) {
		$proj = "SELECT * FROM `project` where name = '$row[project]'";
		$resproj = $conn->query($proj)->fetch_assoc();
?>
<form action = "" method="post">
	<div class="row">
		<div class="col-xs-3">
			<label>Name</label>
			<i><p style="margin-left: 10px;"><?php echo $row['fname'] . ' ' . $row['lname'];?></p></i>
		</div>
		<div class="col-xs-3">
			<label>Particular</label>
			<select class="form-control" name = "upparti">
				<option value=""> ----------- </option>
				<option value = "Cash" <?php if($row['particular'] == "Cash"){ echo ' selected '; }?>> Cash </option>
				<option value="Check" <?php if($row['particular'] == "Check"){ echo ' selected '; }?>> Check </option>
				<option value="Transfer" <?php if($row['particular'] == "Transfer"){ echo ' selected '; }?>> Transfer </option>
			</select>
		</div>
		<div class="col-xs-3">
			<label>Update Amount</label>
			<input type="text" class="form-control" value="<?php echo $row['amount'];?>" name = "upamount" id = "uppet" pattern = "[0-9,.]*">
		</div>
		<div class="col-xs-3">
			<label>Reason</label>
			<textarea name = "upreason" class="form-control"><?php echo $row['petreason'];?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-3 col-xs-offset-3">
			<label>Type </label>
      		<select class="form-control" name = "pettype" required>
      			<option value=""> - - - - - - - </option>
      			<option <?php if($row['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
      			<option <?php if($row['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
      			<option <?php if($row['projtype'] == 'Project'){ echo ' selected ';} ?>value="Project"> Project </option>
      			<option <?php if($row['projtype'] == 'Combined'){ echo ' selected ';} ?>value="Combined"> P.M. & Internet </option>
      			<option <?php if($row['projtype'] == 'Others'){ echo ' selected ';} ?>value="Others"> Others </option>
      			<?php if($_SESSION['acc_id'] == '37') {  ?>
      				<option <?php if($row['projtype'] == 'House'){ echo ' selected ';} ?>value="House"> House </option>
      			<?php } ?>
      		</select>
		</div>
		<div <?php if($row['projtype'] != 'Combined'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "combined">
			<div  class="form-group">
            	<label>P.M. & Internet <font color = "red">*</font></label>
            	<select class="form-control" name = "combined">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Combined' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div <?php if($row['projtype'] != 'Project'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "project">
			<div  class="form-group">
            	<label>Project <font color = "red">*</font></label>
            	<select class="form-control" name = "project">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div <?php if($row['projtype'] != 'P.M.'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "pm">
			<div class="form-group">
            	<label>P.M. <font color = "red">*</font></label>
            	<select class="form-control" name = "pm">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div <?php if($row['projtype'] != 'Internet'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "internet">
			<div  class="form-group">
            	<label>Internet <font color = "red">*</font></label>
            	<select class="form-control" name = "internet">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<?php if($_SESSION['acc_id'] == '37'){ ?>
		<div <?php if($row['projtype'] != 'House'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "house">
				<div  class="form-group">
	            	<label>Project <font color = "red">*</font></label>
	            	<select class="form-control" name = "house">
	            		<option value = ""> - - - - - </option>
	            		<option <?php if($row['project'] == 'GROCERIES'){ echo ' selected ';} ?>value = "GROCERIES"> GROCERIES </option>
	            		<option <?php if($row['project'] == 'FOODS'){ echo ' selected ';} ?>value = "FOODS"> FOODS </option>
	            		<option <?php if($row['project'] == 'REPRESENTATION'){ echo ' selected ';} ?>value = "REPRESENTATION"> REPRESENTATION </option>
	            		<option <?php if($row['project'] == 'MEDICINES'){ echo ' selected ';} ?>value = "MEDICINES"> MEDICINES </option>
	            		<option <?php if($row['project'] == 'ANIMALS'){ echo ' selected ';} ?>value = "ANIMALS"> ANIMALS </option>
	            	</select>
	            </div>
			</div>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-xs-12" align="center">
			<button class="btn btn-primary" name = "uppetty"> Update Petty </button>
			<a href = "?ac=penpty" class="btn btn-danger"> Back </a>
		</div>
	</div>
</form>
<?php
	}

echo '</div>';
}else{
	echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';
}
	if(isset($_POST['uppetty'])){
		$sql = "SELECT * FROM petty,login where login.account_id = '$_SESSION[acc_id]' and login.position != 'House Helper' and petty.account_id = '$_SESSION[acc_id]' and (petty.state != 'DAPetty' and petty.state != 'CPetty') and petty_id != '$petid' order by state ASC, source asc";
		$result = $conn->query($sql);
		$count = 0;
		$day5 = 0;
		$projectcount = 0;
		if($result->num_rows > 0){	
			while($row = $result->fetch_assoc()){
			$petid = $row['petty_id'];
			$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
			$data = $conn->query($sql)->fetch_assoc();
				if($data['petty_id'] == null){
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Others'){
						$projectcount += 1;
					}
					
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
				}
				if($data['liqstate'] == 'LIQDATE'){
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Others'){
						$projectcount += 1;
					}
				}
				if($data['liqstate'] == 'EmpVal'){
					if($row['releasedate'] != "" && date("Y-m-d",strtotime("+5 days", strtotime($row['releasedate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+5 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Project' || $row['projtype'] == 'Others'){
						$projectcount += 1;
					}
				}
		   }
		}

		$upparti = mysqli_real_escape_string($conn, $_POST['upparti']);
		$upamount =  mysqli_real_escape_string($conn, $_POST['upamount']);
		$upreason = mysqli_real_escape_string($conn, $_POST['upreason']);
		$pettype = mysqli_real_escape_string($conn, $_POST['pettype']);
		if(isset($_POST['pettype'])){
			if($_POST['pettype'] == 'Project'){
				$project = $_POST['project'];
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'P.M.'){
				$project = $_POST['pm'];	
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}			
			}elseif($_POST['pettype'] == 'Internet'){
				$project = $_POST['internet'];
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'Others'){
				$project = null;
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'House'){
				$project = $_POST['house'];
			}elseif($_POST['pettype'] == 'Combined'){
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$project = $_POST['combined'];
			}	
		}
		if($_POST['pettype'] == "" || ($_POST['pettype'] != 'Others' && $project == "")){
			echo '<script>alert("Empty");window.location.href="?editpetty='.$petid.'";</script>';
			break;		
		}
		if($upparti == 'Transfer'){
			$state = 'UATransfer';	
		}else{
			$state = 'UAPetty';
		}

		$petid = mysql_escape_string($_GET['editpetty']);
		$sql = "UPDATE `petty` set projtype = '$pettype', project = '$project', amount = '$upamount', particular = '$upparti', petreason = '$upreason', state = '$state' where account_id = '$accid' and petty_id = '$petid' and (state = 'UAPetty' or state = 'UATransfer')";
		if($count == 0){
			if($conn->query($sql) == TRUE){
				if($_SESSION['level'] == 'EMP'){
		    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
		    	}
			}
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
		}
	}
?>