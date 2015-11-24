<?php
	$lqdate = mysql_escape_string($_GET['lqdate']);
	$sql = "SELECT * FROM `petty_liqdate` where petty_liqdate.petty_id = '$lqdate' and petty_liqdate.account_id = '$accid'";
	$data = $conn->query($sql)->fetch_assoc();
	if($data['petty_id'] != null){ echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';	}else{
		$sql = "SELECT * FROM `petty` where petty_id = '$lqdate' and account_id = '$accid'";
		$data = $conn->query($sql)->fetch_assoc();
?>
	<div id = "liqdate" class="container" style="padding: 5px 10px;">
		<div class="row">
			<div class="col-xs-12" align="center">
				<u><i><h3>Liquidate</h3></i></u>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<label>Petty Amount</label>
				<p style="margin-left: 10px;">₱ <?php echo $data['amount'];?></p>
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
	<form action = "aliquidate-exec.php" method="post">
	<input type = "hidden" name = "pet_id" value = "<?php echo $data['petty_id'];?>"/>
		<div class="row">
		<div class = "col-xs-12">
			
			<hr>
		</div>
	</div>
	<div id="tab_logic"> 
		<div class="row" id = "addr0">
			<div class="col-xs-4" id = "typediv">
				<label>Type</label>
				<select required class="form-control" id = "type0" name = "type0">
					<option value=""> - - - - - </option>
				<?php
					$sqls = "SELECT * FROM `petty_type` ORDER BY type_id";
					$results = $conn->query($sqls);
					if($results->num_rows > 0){
						while ($rows = $results->fetch_assoc()) {
							echo '<option value = "' . $rows['type'] . '">' .$rows['type'].'</option>';
						}
					}
				?>	
				</select>
			</div>
			<div class="col-xs-4">
				<label>Amount</label>
				<input required  autocomplete = 'off' class = "form-control" type = "text" id = "amount0" name = "amount0" placeholder = "Enter Amount"/>
			</div>
			<div class="col-xs-4">
				<label>Transaction</label>
				<input required  autocomplete = 'off' class="form-control" name = "trans0" placeholder = "Transaction Info"/>
			</div>			
		</div>
		<div class="row" id = "addr1"></div>
	</div>
	<div id = "anewtype" class="row" style="display: none;">
		
	</div>

		<!--<div class="row">
			<div class="col-xs-2 col-xs-offset-4">
				<b>Change</b>
			</div>
			<div class="col-xs-1">
				<input type = "text" value = "<?php echo  $data['amount'];?>" class = "form-control" readonly id = "xchange"/>
			</div>
		</div>-->
		<div class="row"><div class = "col-xs-12"><hr></div></div>
		<a id="add_row" class="btn btn-success pull-left">Add New Row</a><a id='delete_row' class="pull-right btn btn-danger">Delete New Row</a><br><br>
		<div class="row">
			<div class="col-xs-12" align="center">
				<button class="btn btn-primary" type = "submit" name = "lsub" onclick = "return confirm('Are your sure?');">Submit Liqudation</button>
				<a href = "?ac=penpty" class = "btn btn-danger">Back</a>
				<input type = "hidden" name = "counter" id = "counter" value = "0"/>
			</div>
		</div>
	</form>
	</div>

<?php
	if(isset($_POST['newtypesub'])){
		$newtype = mysql_escape_string($_POST['newtype']);
		$psql = "SELECT * FROM `petty_type` where type = '$newtype'";
		$presult = $conn->query($psql);
		if($presult->num_rows > 0){
			echo '<script type = "text/javascript">alert("Type already exist.");window.location.replace("?lqdate='.$_GET['lqdate'].'");</script>';
		}else{
			$stmt = $conn->prepare("INSERT INTO `petty_type` (type) VALUES (?)");
			$stmt->bind_param("s", $_POST['newtype']);
			if($stmt->execute()){
				echo '<script type = "text/javascript">alert("Successfully added.");window.location.replace("?lqdate='.$_GET['lqdate'].'");</script>';
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
$(document).ready(function(){
	var i=1;
	$("#add_row").click(function(){
		$('#addr'+i).html("<div class='col-xs-4'><label>Type</label><select required class='form-control' name = 'type"+ i +"' id = 'type"+ i +"'><option value=''> - - - - - </option><?php while ($rows = $results->fetch_assoc()) {echo '<option value = \"' . $rows['type'] . '\">' .$rows['type'].'</option>';}?></select></div><div class='col-xs-4'><label>Amount</label><input required placeholder = 'Enter Amount'  autocomplete = 'off' class = 'form-control' type = 'text' id = 'amount"+i+"' name = 'amount"+i+"'/></div><div class='col-xs-4'><label>Transaction</label><input required type = 'text' class='form-control' name = 'trans"+i+"' placeholder = 'Transaction Info' autocomplete = 'off'></div>");

<?php

	}
?>
		$('#tab_logic').append('<div class="row" id = "addr'+(i+1)+'"></div>');
  		i++;
  		$('#counter').val(i);
	});
	$("#delete_row").click(function(){
    	if(i>1){
    		$("#addr"+(i-1)).html('');
			i--;
			$('#counter').val(i);
		}
	});
/*
	var change = 0;
	b = 0;
	while(b <= $('#counter').val()){
		var addr = 'amount' + b;
		$('#' + addr).change(function() {
			alert(b);
			var amount = "<?php echo  $data['amount'];?>";
			var amount2 = amount.replace(/[^\d]/g, "");
			var amount1 = parseInt($('#' + addr).val());
   			var change =   (amount2 - amount1);
   			$("#xchange").val(change);
		});
		b++;
	}*/
});
</script>
<?php
}
?>