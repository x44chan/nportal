<?php
	include('header.php');
	date_default_timezone_set('Asia/Manila');
	session_start();
	include("conf.php");
	if(isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		if($_SESSION['level'] == 'Employee'){
			header("location: index.php");
		}
	}else{
				header("location: index.php");
	
	}
?>
<div align = "center">
	<div class="alert alert-success"><br>
		Welcome <strong><?php echo $_SESSION['name'];?> !</strong> <br>
		<?php echo date('l jS \of F Y h:i A'); ?> <br><br>
		<div class="btn-group btn-group-lg">
			<a  type = "button"class = "btn btn-primary"  href = "?ac=penot">Home</a>
			<a type = "button" class = "btn btn-primary" href = "acc-report-app.php" id = "showapproveda">Cutoff Summary</a>	
			<a type = "button" class = "btn btn-primary" href = "acc-req-app.php" id = "showapproveda">Approved Request</a>
			<a type = "button" class = "btn btn-primary" href = "acc-req-dapp.php"  id = "showdispproveda">Dispproved Request</a>
			<a type = "button" class = "btn btn-danger" href = "logout.php"  role="button">Logout</a>
		</div><br><br>
		<div class = "btn-group btn-group-justified" style = "width: 80%">
			<a  type = "button"class = "btn btn-success" id = "forpndot" href = "?ac=penot"> Pending Overtime Request </a>
			<a  type = "button"class = "btn btn-success" id = "forpndob" href = "?ac=penob"> Pending Official Business Request </a>			
			<a  type = "button"class = "btn btn-success" id = "forpnlea" href = "?ac=penlea"> Pending Leave Request </a>		
			<a  type = "button"class = "btn btn-success" id = "fordpndun" href = "?ac=penundr"> Pending Undertime Request </a>	
		</div> 
	</div>
</div>

<?php
	
	include('conf.php');
	if(isset($_GET['overtime'])){	
		$id = mysqli_real_escape_string($conn, $_GET['overtime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 3><input type = "submit" class = "btn btn-primary" name = "subda"/></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "overtime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['ac'].'"/></td>
					</tr>
				</table>
			</form>';
			
	}
?>

<?php
	include('conf.php');
	if(isset($_GET['officialbusiness_id'])){
		$id = mysqli_real_escape_string($conn, $_GET['officialbusiness_id']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 3><input type = "submit" class = "btn btn-primary" name = "subda"/></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "officialbusiness_id" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['ac'].'"/></td>
					</tr>
				</table>
			</form>';		
	}
?>


<?php
	include('conf.php');
	if(isset($_GET['undertime'])){
		$id = mysqli_real_escape_string($conn, $_GET['undertime']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 3><input type = "submit" class = "btn btn-primary" name = "subda"/></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "undertime" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['ac'].'"/></td>
					</tr>
				</table>
			</form>';	
}
?>


<?php
	include('conf.php');
	if(isset($_GET['leave'])){
		$id = mysqli_real_escape_string($conn, $_GET['leave']);
		$state = mysqli_real_escape_string($conn, $_GET['approve']);
		echo '<form action = "approval.php" method = "get" class = "form-group">
				<table class = "table table-hover" align = "center">
					<thead>
						<tr>
							<th colspan  = 3><h3> Disapproval Reason </h3></th>
						</tr>
					</thead>
					<tr>
						<td align = "right"><labe for = "dareason">Input Disapproval reason</labe></td>
						<td><textarea id = "dareason" class = "form-control" type = "text" name = "dareason" required ></textarea></td>
					</tr>
					<tr>
						<td colspan = 3><input type = "submit" class = "btn btn-primary" name = "subda"/></td>
					</tr>
					<tr>
						<td><input type = "hidden" name = "leave" value = "'.$id.'"/></td>
						<td><input type = "hidden" name = "approve" value = "'.$state.'"/></td>
						<td><input type = "hidden" name = "ac" value = "'.$_GET['ac'].'"/></td>
					</tr>
				</table>
			</form>';			
	}
?>