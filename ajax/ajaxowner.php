<?php
include('../conf.php');
if(isset($_GET['q'])){
	$q = mysqli_real_escape_string($conn, $_GET['q']);
	if(isset($_GET['project']) && $_GET['project'] != ""){
		$type = ' and type = "Project" ';
	}elseif(isset($_GET['support']) && $_GET['support'] != ""){
		$type = ' and type = "Support" ';
	}else{
		$type = "";
	}
	$sql = "SELECT * FROM project where state = 1 and loc = '$q' and state = '1' $type order by CHAR_LENGTH(name)";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		echo '<td id = "projproj"> <b>PO <font color = "red"> * </font></b> </td><td><select name = "otproject" id = "otproject" class = "form-control" required>';
		if(isset($_GET['x'])){
			echo '<option value = "all">All</option>';
		}
		while ($row = $result->fetch_assoc()) {
			echo '<option value = "' . $row['name'] . '"> ' . $row['name'] . '</option>';
		}
		echo '</select></td>';
	}
}
?>

