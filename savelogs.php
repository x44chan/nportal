<?php
function savelogs($transaction,$transdetails){
	$hostname = 'localhost';
	$username = 'root';
	$password =  '';
	$database = 'testnew';
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if (mysqli_connect_errno()){
		die ('Unable to connect to database '. mysqli_connect_error());
	}
	$pcname = gethostname();
	
    $username = $_SESSION['username'];
	$realname = $_SESSION['name'];
    $sqllogs = "insert into audit_trail(username,realname,transaction,datetrans,transdetail,pcname) 
	values('$username','$realname','$transaction',now(),'$transdetails','$pcname')";            
    $result = mysqli_query($conn, $sqllogs);
}
?>
