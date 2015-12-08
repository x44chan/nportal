<?php
	$accid = $_SESSION['acc_id'];
	$petid = mysql_escape_string($_GET['editpetty']);
	include("conf.php");
	$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid and petty_id = '$petid' and state = 'UAPetty' order by state ASC, source asc";
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
			<input type="text" class="form-control" value="<?php echo $row['amount'];?>" name = "upamount" id = "uppet" pattern = "[0-9,]*">
		</div>
		<div class="col-xs-3">
			<label>Reason</label>
			<textarea name = "upreason" class="form-control"><?php echo $row['petreason'];?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12" align="center">
			<button class="btn btn-primary" name = "uppetty"> Update Petty </button>
			<a href = "employee.php?ac=penpty" class="btn btn-danger"> Back </a>
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
		$sql = "UPDATE `petty` set amount = '$upamount', particular = '$upparti', petreason = '$upreason' where account_id = '$accid' and petty_id = '$petid' and state = 'UAPetty'";
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