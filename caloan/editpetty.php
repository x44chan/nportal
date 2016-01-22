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
      		<select class="form-control" name = "pettype">
      			<option value=""> - - - - - - - </option>
      			<option <?php if($resproj['type'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
      			<option <?php if($resproj['type'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
      			<option <?php if($resproj['type'] == 'Project'){ echo ' selected ';} ?>value="Project"> Project </option>
      		</select>
		</div>
		<div <?php if($resproj['type'] != 'Project'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "project">
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
		<div <?php if($resproj['type'] != 'P.M.'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "pm">
			<div class="form-group">
            	<label>Project <font color = "red">*</font></label>
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
		<div <?php if($resproj['type'] != 'Internet'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "internet">
			<div  class="form-group">
            	<label>Project <font color = "red">*</font></label>
            	<select class="form-control" name = "internet">
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
		$upparti = mysqli_real_escape_string($conn, $_POST['upparti']);
		$upamount =  mysqli_real_escape_string($conn, $_POST['upamount']);
		$upreason = mysqli_real_escape_string($conn, $_POST['upreason']);
		if(isset($_POST['pettype'])){
			if($_POST['pettype'] == 'Project'){
				$project = $_POST['project'];
			}elseif($_POST['pettype'] == 'P.M.'){
				$project = $_POST['pm'];
			}elseif($_POST['pettype'] == 'Internet'){
				$project = $_POST['internet'];
			}	
		}
		if($upparti == 'Transfer'){
			$state = 'UATransfer';	
		}else{
			$state = 'UAPetty';
		}
		$sql = "UPDATE `petty` set project = '$project', amount = '$upamount', particular = '$upparti', petreason = '$upreason', state = '$state' where account_id = '$accid' and petty_id = '$petid' and (state = 'UAPetty' or state = 'UATransfer')";
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
	}
?>