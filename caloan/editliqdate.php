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
			<div class="col-xs-4">
				<label>Petty Amount</label>
				<p style="margin-left: 10px;">₱ <?php echo $data['amount'];?></p>
			</div>
			<!--<div class="col-xs-2">
				<label>Change</label><br>
				₱ <span id = "xchange"> 0 </span>
			</div>-->
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
<?php
		$i = 0;
		while($row = $result->fetch_assoc()){
			$i += 1;
?>
		<div class="row" id = "addr">
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
		<div class="row">
			<div class="col-xs-4">
				<label><input type = "checkbox" <?php if($row['rcpt'] != null){ echo ' checked '; } ?> name = "wthrcpt<?php echo $i;?>" id = "wthrcpt"/> Check if With Receipt</label>
			</div>
		</div>
		<input type="hidden" name = "asd<?php echo $i;?>" value = "<?php echo $row['liqdate_id'];?>">
<?php
			
		}
	echo '<div class = "row">';
		echo '<div class = "col-xs-12" align = "center"><hr>';
			echo '<button class = "btn btn-primary" name = "upliqdate"> Update Liquidation </button> ';
			echo '<a href = "?ac=penpty" class = "btn btn-danger"> Back </a>';
		echo '</div>';
	echo '</div></form>';
	}else{
		echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';
	}
	if(isset($_POST['upliqdate'])){
		$len = $i;
		echo $len;
		for($i = 1; $i <= $len; $i++){
			$liqtype = $_POST['type'.$i];
			$liqamount = $_POST['amount'.$i];
			$liqinfo = $_POST['trans'.$i];
			$liqid = $_POST['asd'.$i];
			if(empty($_POST['others'.$i])){
				$liqothers = "";
			}else{
				$liqothers = $_POST['others'.$i];
			}
			if(!isset($_POST['wthrcpt'.$i])){
				$rcpt = "";
			}else{
				$rcpt = $_POST['wthrcpt'.$i];
			}
			$stmt = "UPDATE `petty_liqdate` set 
						liqtype = '$liqtype', liqothers = '$liqothers', liqamount = '$liqamount', liqinfo = '$liqinfo', rcpt = '$rcpt'
					where petty_id = '$petid' and account_id = '$accid' and liqstate = 'LIQDATE' and liqdate_id = '$liqid'";
			if($conn->query($stmt) == TRUE){
		    	echo '<script type="text/javascript">alert("Edit successful"); window.location.replace("?ac=penpty"); </script>';		    	
			}
		}
	}
?>
<script type="text/javascript">
	/*$(document).ready(function(){
		var i = <?php echo $i;?>;
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
					var amount = "<?php echo  $data['amount'];?>";
					var amount2 = amount.replace(/[^\d]/g, "");
					var sum = 0;
			    	for(b = 1; b < i; b++) {
			    	    (function (b) {
			    	    	var amount1 = $('#amount' + b).val();
			    	    	amount2 = amount2 - amount1;			    	    	
			    	    	$("#xchange").text((amount2 + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			     		})(b);
			   	 	}			   	 	
				});
			}
		});
	});*/
</script>