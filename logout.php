<?php
	session_start();
	session_destroy();
?>	
	
	<script type="text/javascript"> 
		window.location.replace("/new");
		alert("You have been successfully logged out");
	</script>	
	