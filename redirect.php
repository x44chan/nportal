<?php session_start();?>
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
}else{
?>
<script type="text/javascript"> window.location.replace("accounting.php?ac=penot"); </script>
<?php
}
}
?>