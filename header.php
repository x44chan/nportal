<?php date_default_timezone_set("Asia/Manila"); ?>
<?php
	if(isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title> Employee Portal <?php if(isset($title)){echo ' - ' . $title; }?> </title>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<!-- jQuery library 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Latest compiled JavaScript 
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/css.css">
		
		<!-- jQuery library -->
		<script src="js/jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/js.js"></script>
		<!--- for time/hour picker -->
		<link rel="stylesheet" type="text/css" href="css/jquery.ptTimeSelect.css" />		
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<script type="text/javascript" src="js/jquery.ptTimeSelect.js"></script>
		<script type="text/javascript" src="js/hrsmask.js"></script>
		
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/> 
		<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
	</head>
	<body>
		<div class = "container-fluid" >
		