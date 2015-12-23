<?php
	$petid = mysql_escape_string($_GET['editliqdate']);
	$accid = $_SESSION['acc_id'];
	$sql = "SELECT * FROM `petty_liqdate` where petty_id = '$petid' and account_id = '$accid' and liqstate = 'LIQDATE'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$sql2 = "SELECT * FROM `petty` where petty_id = '$petid' and account_id = '$accid'";
		$data = $conn->query($sql2)->fetch_assoc();
?>
	<div class = "container">
		<div class="row">
			<div class="col-xs-12" align="center">
				<u><i><h3>Edit Liquidation</h3></i></u>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2">
				<label>Petty Amount</label>
				<p style="margin-left: 10px;">₱ <?php echo $data['amount'];?></p>
			</div>
			<div class="col-xs-2">
				<label>Used Petty</label><br>
				₱ <span id = "xused"> 0 </span>
			</div>
			<div class="col-xs-2">
				<label>Change</label><br>
				₱ <span id = "xchange"> 0 </span>
			</div>
		<?php if($data['transfer_id'] == null){?>
			<div class="col-xs-6">
				<label>Classification</label>
				<p style="margin-left: 10px;"><?php echo $data['particular'];?></p>
			</div>
		<?php 
			}else{
		?>
			<div class="col-xs-3">
				<label>Classification</label>
				<p style="margin-left: 10px;"><?php echo $data['particular'];?></p>
			</div>
			<div class="col-xs-3">
				<label>Transfer Code</label>
				<p style="margin-left: 10px;"><?php echo $data['transfer_id'];?></p>
			</div>
		<?php } ?>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<hr>
			</div>
		</div>
		<form action = "" method="post">
	<div id="tab_logic"> 
<?php
		$i = 0;
		while($row = $result->fetch_assoc()){
			$i += 1;
?>
		<div class="row" id = "addr<?php echo $i;?>">
			<div class="col-xs-3" id = "typediv">
				<label>Type</label>
				<select required class="form-control input-md" id = "type<?php echo $i;?>" name = "type<?php echo $i;?>">
					<option value=""> - - - - - </option>
				<?php
					$sqls = "SELECT * FROM `petty_type` ORDER BY type_id";
					$results = $conn->query($sqls);
					if($results->num_rows > 0){
						while ($rows = $results->fetch_assoc()) {
							if($row['liqtype'] == $rows['type']){
								$slected = " selected ";
							}else{
								$slected = "";
							}
							echo '<option value = "' . $rows['type'] . '" ' . $slected . '>' .$rows['type'].'</option>';
						}
					}
				?>	
				</select>
			</div>
			<div class="col-xs-3">
				<label>Others</label>
				<input type = "text" class="form-control input-md" id = "others<?php echo $i;?>" name = "others<?php echo $i;?>" value = "<?php echo $row['liqothers'];?>" placeholder = "Others" <?php if($row['liqtype'] != "Others"){ echo ' disabled ';}?>>
			</div>
			<div class="col-xs-3">
				<label>Amount</label>
				<input required  autocomplete = 'off' pattern = "[0-9.]*" value = "<?php echo $row['liqamount'];?>"  class = "form-control input-md" type = "text" id = "amount<?php echo $i;?>" name = "amount<?php echo $i;?>" placeholder = "Enter Amount"/>
			</div>
			<div class="col-xs-3">
				<label>Transaction</label>
				<input required  autocomplete = 'off' class="form-control input-md" value = "<?php echo $row['liqinfo'];?>" name = "trans<?php echo $i;?>" placeholder = "Transaction Info"/>
			</div>			
		</div>
		<div class="row" id = "rcpt<?php echo $i;?>">
			<div class="col-xs-4">
				<label><input type = "checkbox" <?php if($row['rcpt'] != null){ echo ' checked '; } ?> name = "wthrcpt<?php echo $i;?>" id = "wthrcpt"/> Check if With Receipt</label>
			</div>
		</div>
		<input style = "display: none;" type="text" name = "asd<?php echo $i;?>" value = "<?php echo $row['liqdate_id'];?>">		
<?php
			
		}
		echo '<div class="row" id = "addr'.($i+1).'"></div>
		<div class="row" id = "rcpt'.($i+1).'"></div></div><input  style = "display: none;" type = "text" name = "counter" id = "counter" value = "'.$i.'"/><input  style = "display: none;" type = "text" name = "pet_id" id = "counter" value = "'.$_GET['editliqdate'].'"/>';
	echo '<div class = "row" id = "alertss" style = "display: none;">';
		echo '<div class = "col-xs-12" align="center">';
			echo '<div class="alert alert-danger">
					<small><strong>Danger!</strong> Indicates a dangerous or potentially negative action.</small>
				</div>';
		echo '</div>';
	echo '</div>';
	echo '<div class = "row">';
		echo '<div class = "col-xs-12" align = "center"><hr>';
		echo '<a id="add_row" class="btn btn-success pull-left">Add New Row</a><a id="delete_row" class="pull-right btn btn-danger">Delete New Row</a><br><br>';
			echo '<button class = "btn btn-primary" name = "upliqdate"> Update Liquidation </button> ';
			echo '<a href = "?ac=penpty" class = "btn btn-danger"> Back </a>';
		echo '</div>';
	echo '</div></form>';
	}else{
		echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';
	}
	if(isset($_POST['upliqdate']) && isset($_SESSION['acc_id'])){
		$len = $i;
		$count = 0;
		$petid = mysqli_real_escape_string($conn, $_POST['pet_id']);
		$sql = "SELECT * FROM `petty` where petty_id = '$petid' and account_id = '$_SESSION[acc_id]'";
		$data = $conn->query($sql)->fetch_assoc();
		$amount = 0;
		for($i = 1; $i <= $_POST['counter']; $i++){
			if(!isset($_POST['amount'.$i])){

			}else{
				$amount += $_POST['amount'.$i];
			}
		}
		if($amount > str_replace(",","", $data['amount'])){
			echo '<script type="text/javascript"> alert("Ooops. Huli ka!");  window.location.replace("?ac=penpty"); </script>';
		}else{
			for($i = 1; $i <= $len; $i++){
				$liqtype = mysqli_real_escape_string($conn, $_POST['type'.$i]);
				$liqamount = mysqli_real_escape_string($conn, $_POST['amount'.$i]);
				$liqinfo = mysqli_real_escape_string($conn, $_POST['trans'.$i]);
				$liqid = mysqli_real_escape_string($conn, $_POST['asd'.$i]);
				if(empty($_POST['others'.$i])){
					$liqothers = "";
				}else{
					$liqothers = mysqli_real_escape_string($conn, $_POST['others'.$i]);
				}
				if(!isset($_POST['wthrcpt'.$i])){
					$rcpt = "";
				}else{
					$rcpt = mysqli_real_escape_string($conn, $_POST['wthrcpt'.$i]);
				}
				$stmt = "UPDATE `petty_liqdate` set 
							liqtype = '$liqtype', liqothers = '$liqothers', liqamount = '$liqamount', liqinfo = '$liqinfo', rcpt = '$rcpt'
						where petty_id = '$petid' and account_id = '$accid' and liqstate = 'LIQDATE' and liqdate_id = '$liqid'";
				if($conn->query($stmt) == TRUE){
			    	$count += 1;		    	
				}
			}
			$len += 1;
			if($_POST['counter'] > $len){
				$liqstate = 'LIQDATE';
				$counter = $_POST['counter'];
				$date = date("Y-m-d");			
				for($i = $len; $i < $counter; $i++){
					$stmt = $conn->prepare("INSERT INTO `petty_liqdate` (petty_id, account_id, liqdate, liqtype, liqamount, liqinfo, liqstate, rcpt, liqothers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("iisssssss", $_GET['editliqdate'], $_SESSION['acc_id'], $date, $_POST['type'.$i], $_POST['amount'.$i], $_POST['trans'.$i], $liqstate, $_POST['wthrcpt'.$i], $_POST['others'.$i]);
					if($_POST['type'.$i]!= null){
						if($stmt->execute()){
							$count += 1;
						}else{
							$conn->error();
						}
					}
				}
			}
			if($count>0){
				echo '<script type="text/javascript">alert("Edit successful"); window.location.replace("?ac=penpty"); </script>';
			}
		}
	}
?>

<script type="text/javascript">
<?php
	$sqls = "SELECT * FROM `petty_type`";
	$results = $conn->query($sqls);
	if($results->num_rows > 0){
		
?>
$(window).load(function(){
  	var i = <?php echo $i+1;?>;
  	var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
	var amount2 = amount.replace(",", "");
	var sum = 0;
    for(b = 1; b < i; b++) {
        (function (b) {
        	var amount1 = $('#amount' + b).val();
        	if($('#amount' + b).val() == ""){
        		amount1 = 0;
        	}
        	amount2 = amount2 - amount1;
        	(amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        	sum = parseFloat(amount1) + parseFloat(sum);
        	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
        	$("#xused").text((sum + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
        	if(amount.replace(',', "") < sum){			    	    		
	    		$("button[name = 'upliqdate']").attr("disabled","disabled");
	    		$("#alertss").show();
	    	}else{
	    		$("button[name = 'upliqdate']").attr("disabled",false);
	    		$("#alertss").hide();
	    	}
    	})(b);
   	}
});

$(document).ready(function(){
	var i = <?php echo $i+1;?>;
	$("#add_row").click(function(){
		$('#addr'+i).html("<div class='col-xs-3'><label>Type</label><select required class='form-control' name = 'type"+ i +"' id = 'type"+ i +"'><option value=''> - - - - - </option><?php while ($rows = $results->fetch_assoc()) {echo '<option value = \"' . $rows['type'] . '\">' .$rows['type'].'</option>';}?></select></div><div class='col-xs-3'><label>Others</label><input type = 'text' class='form-control' id = 'others"+i+"' name = 'others"+i+"' placeholder = 'Others' disabled></div><div class='col-xs-3'><label>Amount</label><input required placeholder = 'Enter Amount'  autocomplete = 'off' class = 'form-control input-md' type = 'text' id = 'amount"+i+"' name = 'amount"+i+"' pattern = '[0-9.]*'/></div><div class='col-xs-3'><label>Transaction</label><input required type = 'text' class='form-control' name = 'trans"+i+"' placeholder = 'Transaction Info' autocomplete = 'off'></div>");
		$('#rcpt'+i).html('<div class="col-xs-4"><label><input type = "checkbox" name = "wthrcpt'+i+'" id = "wthrcpt'+i+'"/> Check if With Receipt</label></div>');
		var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
		var amount2 = amount.replace(",", "");
		var sum = 0;
    	for(b = 1; b < i; b++) {
    	    (function (b) {
    	    	var amount1 = $('#amount' + b).val();
    	    	if($('#amount' + b).val() == ""){
	        		amount1 = 0;
	        	}
    	    	amount2 = amount2 - amount1;
    	    	(amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    	    	sum = parseFloat(amount1) + parseFloat(sum);
    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    	    	$("#xused").text((sum + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
    	    	if(amount.replace(',', "") < sum){			    	    		
    	    		$("button[name = 'upliqdate']").attr("disabled","disabled");
    	    		$("#alertss").show();
    	    	}else{
    	    		$("button[name = 'upliqdate']").attr("disabled",false);
    	    		$("#alertss").hide();
    	    	}
     		})(b);
   	 	}
<?php
	}
?>
		$('#tab_logic').append('<div class="row" id = "addr'+(i+1)+'"></div>');
		$('#tab_logic').append('<div class="row" id = "rcpt'+(i+1)+'"></div>');
  		i++;
  		$('#counter').val(i);
		$(function () {
	    	for(b = 1; b < i; b++) {
	    	    (function (b) {
	    	    	$('select[id$="type' + b + '"]').change(function() {
					    var selected = $(this).val();	
						if(selected == 'Others'){
							$("#others"+b).attr("disabled", false);
							$("#others"+b).attr("required", true);
						}else{
							$("#others"+b).attr("disabled", true);
							$("#others"+b).attr("required", false);
						}
					});
	     		})(b);
	   	 	}
		});
		$(function () {
	    	for(b = 1; b < i; b++) {
				$("#amount"+b).change(function() {
					var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
					var amount2 = amount.replace(",", "");
					var sum = 0;
			    	for(b = 1; b < i; b++) {
			    	    (function (b) {
			    	    	var amount1 = $('#amount' + b).val();
			    	    	if($('#amount' + b).val() == ""){
				        		amount1 = 0;
				        	}
			    	    	amount2 = amount2 - amount1;
			    	    	sum = parseFloat(amount1) + parseFloat(sum);			    	    	
			    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			    	    	$("#xused").text((sum + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			    	    	if(amount.replace(',', "") < sum){			    	    		
			    	    		$("button[name = 'upliqdate']").attr("disabled","disabled");
			    	    		$("#alertss").show();
			    	    	}else{
			    	    		$("button[name = 'upliqdate']").attr("disabled",false);
			    	    		$("#alertss").hide();
			    	    	}
			     		})(b);
			   	 	}			   	 	
				});
			}
		});
	});
	$("#delete_row").click(function(){
    	if(i><?php echo $i+1;?>){
    		$("#addr"+(i-1)).html('');
    		$('#rcpt'+(i-1)).html('');
			i--;
			$('#counter').val(i);
			var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
			var amount2 = amount.replace(",", "");
			var sum = 0;
	    	for(b = 1; b < i; b++) {
	    	    (function (b) {
	    	    	var amount1 = $('#amount' + b).val();
	    	    	if($('#amount' + b).val() == ""){
		        		amount1 = 0;
		        	}
	    	    	amount2 = amount2 - amount1;
	    	    	(amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	    	    	sum = parseFloat(amount1) + parseFloat(sum);
	    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
	    	    	$("#xused").text((sum + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
	    	    	if(amount.replace(',', "") < sum){			    	    		
	    	    		$("button[name = 'upliqdate']").attr("disabled","disabled");
	    	    		$("#alertss").show();
	    	    	}else{
	    	    		$("button[name = 'upliqdate']").attr("disabled",false);
	    	    		$("#alertss").hide();
	    	    	}
	     		})(b);
	   	 	}

		}
	});
});
	$(document).ready(function(){
		var i = <?php echo $i;?>;
		$(function () {
	    	for(b = 1; b <= i; b++) {
	    	    (function (b) {
	    	    	$('select[id$="type' + b + '"]').change(function() {
					    var selected = $(this).val();	
						if(selected == 'Others'){
							$("#others"+b).attr("disabled", false);
							$("#others"+b).attr("required", true);
						}else{
							$("#others"+b).attr("disabled", true);
							$("#others"+b).attr("required", false);
						}
					});
	     		})(b);
	   	 	}
		});
		$(function () {
	    	for(b = 1; b <= i; b++) {
				$("#amount"+b).change(function() {
					var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
					var amount2 = amount.replace(",", "");
					var sum = 0;
			    	for(b = 1; b <= i; b++) {
			    	    (function (b) {
			    	    	var amount1 = $('#amount' + b).val();
			    	    	if($('#amount' + b).val() == ""){
				        		amount1 = 0;
				        	}
			    	    	amount2 = amount2 - amount1;
			    	    	sum = parseFloat(amount1) + parseFloat(sum);			    	    	
			    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			    	    	$("#xused").text((sum + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			    	    	if(amount.replace(',', "") < sum){			    	    		
			    	    		$("button[name = 'upliqdate']").attr("disabled","disabled");
			    	    		$("#alertss").show();
			    	    	}else{
			    	    		$("button[name = 'upliqdate']").attr("disabled",false);
			    	    		$("#alertss").hide();
			    	    	}
			     		})(b);
			   	 	}			   	 	
				});
			}
		});
		$(function () {
	    	for(b = 1; b <= i; b++) {
				$("#amount"+b).change(function() {
					var amount = "<?php echo  str_replace(',', '', $data['amount']);?>";
					var amount2 = amount.replace(",", "");
					var sum = 0;
			    	for(b = 1; b <= i; b++) {
			    	    (function (b) {
			    	    	var amount1 = $('#amount' + b).val();
			    	    	if($('#amount' + b).val() == ""){
				        		amount1 = 0;
				        	}
			    	    	amount2 = amount2 - amount1;			    	    	
			    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			     		})(b);
			   	 	}			   	 	
				});
			}
		});
	});
</script>