<?php
	include 'conf.php';
	session_start();
	date_default_timezone_set('Asia/Manila');
	if(isset($_SESSION['acc_id']) && isset($_GET['overtime_id'])){
		$oid = mysqli_real_escape_string($conn, $_GET['overtime_id']);
		$sql = "SELECT * FROM overtime where overtime_id = '$oid'";
		$data = $conn->query($sql)->fetch_assoc();
		if(substr($data['datehr'], 8, 2)){
			$data['datehr'] = date("Y-m-d H:i A", strtotime("-1 day", strtotime($data['datehr'])));
		}
		$sql2 = $conn->prepare("UPDATE overtime set datehr = ? where overtime_id = ?");
		$sql2->bind_param("si", $data['datehr'], $oid);
		if($sql2->execute()){
			echo '<script> alert("Approval Date adjusted to ' . $data['datehr'] . '"); window.close();</script>';
		}
	}
	if(isset($_SESSION['acc_id']) && isset($_GET['officialbusiness_id'])){
		$oid = mysqli_real_escape_string($conn, $_GET['officialbusiness_id']);
		$sql = "SELECT * FROM officialbusiness where officialbusiness_id = '$oid'";
		$data = $conn->query($sql)->fetch_assoc();
		if(substr($data['datehr'], 8, 2)){
			$data['datehr'] = date("Y-m-d H:i A", strtotime("-1 day", strtotime($data['datehr'])));
		}
		$sql2 = $conn->prepare("UPDATE officialbusiness set datehr = ? where officialbusiness_id = ?");
		$sql2->bind_param("si", $data['datehr'], $oid);
		if($sql2->execute()){
			echo '<script> alert("Approval Date adjusted to ' . $data['datehr'] . '"); window.close();</script>';
		}
	}