<?php if(isset($_POST['submit'])){
		include('conf.php');
		$uname = mysqli_real_escape_string($conn, $_POST['uname']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		
		$sql = "SELECT * FROM `login` where uname = '$uname' and pword = '$password'";
		$result = $conn->query($sql);
		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){					
				$_SESSION['name'] = $row['fname'] . ' ' . $row['lname'];				
				$_SESSION['level'] = $row['level'];
				$_SESSION['acc_id'] = $row['account_id'];
								
			}
		}else{
		echo  '<div class="alert alert-warning" align = "center">
			<a href="#"  class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			<strong>Warning!</strong> Incorrect Login.
		</div>';
			
			}
		$conn->close();
	}
	?>