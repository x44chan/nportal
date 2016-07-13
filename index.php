<?php session_start(); ?>
<?php
$title="Login Page";
include('header.php');
?>
<?php if(isset($_SESSION['acc_id'])){?>
<?php
if($_SESSION['level'] == 'Admin'){
?>
<script type="text/javascript">window.location.replace("admin.php"); </script>
<?php
}else if($_SESSION['level'] == 'EMP'){
?>
<script type="text/javascript">	window.location.replace("employee.php?ac=penot"); </script>
<?php
}else if($_SESSION['level'] == 'HR'){
?>
<script type="text/javascript"> window.location.replace("hr.php?ac=penot"); </script>
<?php
}else if($_SESSION['level'] == 'TECH'){
echo '<script type="text/javascript"> window.location.replace("techsupervisor.php?ac=penot"); </script>';
}else if($_SESSION['level'] == 'ACC'){
?>
<script type="text/javascript"> window.location.replace("accounting.php?ac=penot"); </script>
<?php
}
}
?>
<style type="text/css">
	.table {border-bottom:0px !important;}
	.table th, .table td {border: 0px !important;}
</style>
<div align = "center" style = "margin-top: 10px;">
			<img class="img-rounded" src = "img/netlink.jpg" height = "200">
		</div>
		<form role = "form" action = "" method = "post">	
			<table align = "center" class = "table form-horizontal" style = "margin-top: 0px; width: 800px;" >
				<thead>
					<tr style = "border: none;">
						<td colspan = 2 align = center><h2><i><span class="glyphicon glyphicon-lock"></span> Login Form</i></h2></td> 
					</tr>
				</thead>
				<tr >
					<td><label for = "uname"><span class="glyphicon glyphicon-user"></span>  Username: </label><input <?php if(isset($_POST['uname'])){ echo 'value ="' . $_POST['uname'] . '"'; }else{ echo 'autofocus ';}?>placeholder = "Enter Username" id = "uname" title = "Input your username." type = "text" class = "form-control" required name = "uname"/></td>
				
					<td><label for = "pword"><span class="glyphicon glyphicon-eye-open"></span>  Password: </label><input  <?php if(isset($_POST['uname'])){ echo 'autofocus '; }?>placeholder = "Enter Password" id = "pword" title = "Input your password." type = "password" class = "form-control" required name = "password"/></td>
				</tr>
				<tr >
					<td colspan = 4 align = "center" ><button style = "width: 150px; margin: auto;" type="submit" name = "submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button></td>
				</tr>
			</table>
		</form>

<?php
	if(isset($_POST['submit'])){
		include('conf.php');
		$uname = mysqli_real_escape_string($conn, $_POST['uname']);
		$password =  mysqli_real_escape_string($conn, $_POST['password']);
		
		$sql = "SELECT * FROM `login` where uname = '$uname' or pword = '$password' and active != '0'";
		$result = $conn->query($sql);
		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){	
				$_SESSION['username'] = $row['uname'];						
				$_SESSION['name'] = $row['fname'] . ' ' . $row['lname'];				
				$_SESSION['level'] = $row['level'];
				$_SESSION['acc_id'] = $row['account_id'];
				$_SESSION['pass'] = $row['pword'];
				$_SESSION['201date'] = $row['201date'];
				$_SESSION['post'] = $row['position'];
				$_SESSION['dept'] = $row['department'];
				$_SESSION['datehired'] = $row['edatehired'];
				if($row['hrchange'] != 0){
					$_SESSION['category'] = $row['oldpost'];
				}else{
					$_SESSION['category'] = $row['empcatergory'];
				}
				if($_SESSION['category'] == 'Regular' && $row['regdate'] <= date('Y-m-d')){
					$_SESSION['category'] = $row['empcatergory'];
				}else{
					$_SESSION['category'] = $row['oldpost'];
				}
				echo	'<div class="alert alert-success" align = "center">						
							<strong>Logging in ~!</strong>
						</div>';
				if($_SESSION['level'] == 'HR' || $_SESSION['level'] == 'TECH'){	
					$datetime = date("M j, Y g:i:s A");
					$in = "in";
					$stmt = $conn->prepare("INSERT INTO `login_log` (account_id, `datetime`, logintype) VALUES (?, ?, ?)");
					$stmt->bind_param("iss", $_SESSION['acc_id'], $datetime, $in);	
					$stmt->execute();
				}
				if($_SESSION['level'] == 'Admin'){
					echo '<script type="text/javascript">setTimeout(function() {window.location.href = "admin.php"},600);</script>';
				}else if($_SESSION['level'] == 'EMP'){
					echo '<script type="text/javascript">setTimeout(function() {window.location.href = "employee.php?ac=penot"},600);</script>';
				}else if($_SESSION['level'] == 'HR'){
					echo '<script type="text/javascript">setTimeout(function() {window.location.href = "hr.php?ac=penot"},600);</script>';
				}else if($row['level'] == 'TECH'){
					echo '<script type="text/javascript">setTimeout(function() {window.location.href = "techsupervisor.php?ac=penot"},600);</script>';
				}else{
					echo '<script type="text/javascript">setTimeout(function() {window.location.href = "accounting.php?ac=penot"},600);</script>';
				}				
			}
		}else{
	echo  '<div class="alert alert-warning" align = "center">
						
						<strong>Warning!</strong> Incorrect Login.
					</div>';
			
			}
		$conn->close();
	}
	
	include('footer.php');
?>