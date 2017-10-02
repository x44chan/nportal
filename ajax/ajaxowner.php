<?php
include('../conf.php');
if(isset($_GET['q'])){
	$q = mysqli_real_escape_string($conn, $_GET['q']);
	if($_GET['project'] == "sup"){
		$type = ' and type = "Support" ';
	}elseif($_GET['project'] == "proj"){
		$type = ' and type = "Project" ';
	}else{
		$type = "";
	}
	if(isset($_GET['state']) && $_GET['state'] != ""){
		$state = 'and state >= 0 ';
	}else{
		$state = ' and state = "1" ';
	}
	if(isset($_GET['oncall']) && $_GET['oncall'] == "1"){
		$type = " and type = 'On Call' ";
	}
	if(isset($_GET['tag']) && $_GET['tag'] == "1"){
		$state = "";
	}
	$sql = "SELECT * FROM project where loc = '$q' $state $type order by CHAR_LENGTH(name)";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		echo '<td id = "projproj"> <b>PO <font color = "red"> * </font></b> </td><td><select name = "otproject" id = "otproject" class = "form-control" required>';
		if(isset($_GET['x'])){
			echo '<option value = "all">All</option>';
		}
		while ($row = $result->fetch_assoc()) {
			if(isset($_GET['tag']) && $_GET['tag'] == "1"){
				$tag = " ( " . $row['type'] . " ) ";
				$sname = ':' . $row['type'];
			}else{
				$tag = "";
				$sname = "";
			}
			echo '<option value = "' . $row['name']. $sname . '"> ' . $row['name'] . $tag . '</option>';
		}
		echo '</select></td>';
	}
}
?>

