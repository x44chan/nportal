<?php
	include 'conf.php';
	if(isset($_SESSION['acc_id']) && isset($_GET['overtime_id'])){
		$oid = mysqli_real_escape_string($conn, $_GET['overtime_id']);
		$sql = "SELECT * FROM overtime where overtime_id = '$oid' and account_id = '$_SESSION[acc_id]'";
		$data = $conn->query($sql)->fetch_assoc();
		echo $data['datehr'];
	}