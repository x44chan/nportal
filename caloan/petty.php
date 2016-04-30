<?php 
	if(isset($_GET['ac']) && $_GET['ac'] == 'penpty'){
		include("conf.php");
		$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid order by state ASC, source asc";
		$result = $conn->query($sql);
		
	?>	
		<form role = "form" action = "approval.php"    method = "get">
			<table class = "table table-hover" align = "center">
				<thead>
					<tr>
						<td colspan = 9 align = center><h2> Pending Petty Request </h2></td>
					</tr>
					<tr>
						<th>Petty #</th>
						<th>Date File</th>
						<th>Name</th>
						<th>Particular</th>
						<th>Source</th>
						<th>Reference #</th>
						<th>Amount</th>
						<th width="20%">Reason</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
	<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				
				$originalDate = date($row['date']);
				$newDate = date("M j, Y", strtotime($originalDate));
				$datetoday = date("Y-m-d");
				$petid = $row['petty_id'];
				if($row['state'] == 'AAPettyRep'){
					$transcode = $row['transfer_id'];
				}else{
					$transcode = "";
				}
				$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid' and petty_liqdate.liqstate = 'CompleteLiqdate'";
				$data = $conn->query($sql)->fetch_assoc();	
				if(date("Y-m-d") > date("Y-m-d", strtotime("+12 days", strtotime($row['date'])))){
					if($data['liqstate'] == 'CompleteLiqdate' || $row['state'] == 'CPetty' || $row['state'] == 'DAPetty'){
						continue;
					}
				}
				if($row['releasedate'] != ""){
					$date2 = date("Y-m-d", strtotime("+5 days", strtotime($row['releasedate'])));
				}else{
					$date2 = date("Y-m-d", strtotime("+5 days", strtotime($row['date'])));
				}
				if(date("Y-m-d") >= $date2 && ($data['liqstate'] == "LIQDATE" || ($data['liqstate'] == null && $row['state'] != 'CPetty'))){
					$red = ' style = "color: red;" ';
				}else{
					$red = "";
				}
				echo 
					'<tr ' . $red . '>
						<td>'.$row['petty_id'].'</td>
						<td>'.$newDate.'</td>
						<td>'.$row['fname'] . ' '. $row['lname'].'</td>
						<td>'.$row['particular'].'</td>
						<td>'.$row['source'].'</td>
						<td>'.$transcode.'</td>
						<td>&#8369; ';if(!is_numeric($row['amount'])){ echo $row['amount']; }else{ echo number_format($row['amount'],2); };echo '</td>
						<td>'.$row['petreason'].'
						<td>';
							if($row['state'] == "CPetty"){
								echo '<b><font color = "red">Canceled Petty</font></b>';
							}elseif($row['state'] == "UAPetty"){
								echo '<b>Pending to Admin <br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'AAAPettyReceive'){
								echo '<a href = "petty-exec.php?o='.$row['petty_id'].'&acc='.$_GET['ac'].'" class = "btn btn-success">Receive Petty</a>';
							}elseif($row['state'] == 'DAPetty'){
								echo '<b><font color = "red">Disapproved request';
							}elseif($row['state'] == 'AAPettyReceived'){
								echo '<font color = "green"><b>Received ';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPetty'){
								echo '<font color = "green"><b>Pending to Accounting</font>';
							}elseif($row['state'] == 'UATransfer'){
								echo '<b> Pending for Processing</b><br>';
								echo '<a href = "?editpetty='.$row['petty_id'].'" class = "btn btn-danger"> Edit Petty </a> <a onclick = "return confirm(\'Are you sure?\');" href = "cancel-req.php?canpetty='.$row['petty_id'].'" class = "btn btn-danger"> Cancel </a>';
							}elseif($row['state'] == 'TransProc'){
								echo '<b><font color = "green"> Proccessed by the Accounting </font></b><br>';
								echo '<a href = "?getcode='.$row['petty_id'].'" class = "btn btn-success">Get Code</a>';
							}elseif($row['state'] == 'TransProcCode'){
								echo '<font color = "green"><b>For Admin Verification & Releasing';
								echo '</font></br>Code: ' . $row['rcve_code'];
							}elseif($row['state'] == 'AAPettyRep'){
								$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
								$data = $conn->query($sql)->fetch_assoc();
								if($data['petty_id'] == null){
									echo '<a class = "btn btn-danger" href = "?lqdate=' . $row['petty_id'] . '"/> To Liquidate </a>';
								}elseif($data['liqstate'] == 'EmpVal'){
									echo '<font color = "green"><b>Liquidated</font><br>';
									echo '<a href = "?validate=' . $petid . '" class = "btn btn-success">Validate Code</a>';
								}elseif($data['liqstate'] == 'CompleteLiqdate'){
									echo '<font color = "green"><b>Completed</font>';
								}elseif($data['liqstate'] == 'LIQDATE'){
									echo '<b>Pending Completion</b><br>';
									echo '<a href = "?editliqdate='.$row['petty_id'].'" class = "btn btn-danger"> Edit Liquidation </a>';
								}elseif($data['liqstate'] == 'AdmnApp'){
									echo '<b>Pending Completion <br> App by Admin</b><br>';
									echo '<a href = "?editliqdate='.$row['petty_id'].'" class = "btn btn-danger"> Edit Liquidation </a>';
								}
							}
				echo '</td></tr>';

		}
		
	}echo '</tbody></table></form>';$conn->close();
}
if(isset($_GET['editpetty']) && $_GET['editpetty'] > 0){
	include("caloan/editpetty.php");
}
if(isset($_GET['editliqdate']) && $_GET['editliqdate'] > 0){
	include("caloan/editliqdate.php");
}
if(isset($_GET['getcode'])){
	$code = mysql_escape_string($_GET['getcode']);
	$sql = "UPDATE `petty` set state = 'TransProcCode' where petty_id = '$code' and state = 'TransProc'";
	if($conn->query($sql) == TRUE){
	 	if($_SESSION['level'] == 'EMP'){
    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'ACC') {
    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'TECH') {
    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
    	}elseif ($_SESSION['level'] == 'HR') {
    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
    	}
	}
}
?> 
<?php if(isset($_GET['servicereport']) && $_GET['servicereport'] == "add" && ($_SESSION['acc_id'] == 2)){ ?>
	<div class="container">
		<form action="" method="post">
			<div class="row">
				<div class="col-xs-12">
					<h4 style="margin-left: -30px;"><u><i>Service Report</i></u></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
					<label style="font-size: 13px;">Company <font color="red"> * </font></label>
					<select class="form-control input-sm" required name="servicecomp">
						<option value=""> - - - - - - - - - </option>
						<option value="new" <?php if(isset($_GET['cid']) && $_GET['cid'] == 'new'){ echo ' selected '; } ?> >New</option>
						<?php
	            			$xsql = "SELECT * FROM `servicecomp` order by CHAR_LENGTH(name)";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if(isset($_GET['cid']) && $_GET['cid'] == $xrow['servicecomp_id']){
	            						$sele = " selected ";
	            					}else{
	            						$sele = "";
	            					}
	            					echo '<option '.$sele.' value = "' . $xrow['servicecomp_id'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
					</select>	
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<hr>
				</div>
			</div>
			<div id = "sernew" <?php if(isset($_GET['cid']) && $_GET['cid'] == 'new'){ echo ' style="display: block;" '; }else{ ?> style="display: none;" <?php } ?>>
				<div class="row">
					<div class="col-xs-12" style="margin-top: -30px;">
						<h4 style="margin-left: -30px;"><u><i>Add New Company</i></u></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<label style="font-size: 13px;">Company Name <font color = "red"> * </font></label>
						<textarea type = "email" name = "cname" class="input-sm form-control" placeholder = "Enter Company Name"></textarea>
					</div>
					<div class="col-xs-3">
						<label style="font-size: 13px;">Email <font color = "red"> * </font></label>
						<input type = "email" name = "email" class="input-sm form-control" placeholder = "Enter Email">
					</div>
					<div class="col-xs-3">
						<label style="font-size: 13px;">Alternative Email #1 </label>
						<input type = "email" name = "email2" class="input-sm form-control" placeholder = "Enter Email">
					</div>
					<div class="col-xs-3">
						<label style="font-size: 13px;">Alternative Email #2 </label>
						<input type = "email" name = "email3" class="input-sm form-control" placeholder = "Enter Email">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<hr>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12" style="margin-top: -30px;">
					<h4 style="margin-left: -30px;"><u><i>Reported Concern</i></u></h4>
				</div>
			</div>
			<div class="row">
				<div id="tab_logic"> 
					<div class="row" id = "addr0">
						<div class="col-xs-3" id = "typediv">
							<label style="font-size: 13px;">Type</label>
							<select class="form-control input-sm" id = "type0" name = "type0" required>
								<option value=""> - - - - - - - - </option>
								<option value="Terminal"> Terminal </option>								
								<option value="new"> New Terminal </option>
								<option value="Internet"> Internet </option>
								<option value="Others"> Others </option>
							</select>
						</div>
						<div class="col-xs-4">
							<label style="font-size: 13px;">Terminal / Others</label>
							<input type = "text" class="form-control input-sm" placeholder = "Others" id = "others0" name = "others0" disabled autocomplete = "off">
							<select class="form-control input-sm" id = "terminal0" name = "terminal0" style="display: none;">
								<option value=""> - - - - - - - - </option>
								<?php									
									if(isset($_GET['cid'])){
										$sort = " and servicerep.company_id = '" . $_GET['cid'] . "' ";
									}else{
										$_GET['cid'] = "";
										$sort = " and servicerep.company_id = '" . $_GET['cid'] . "' ";
									}
									$pc = "SELECT * FROM `servicerep` where type = 'Terminal' $sort group by info";
									$result = $conn->query($pc);
									if($result->num_rows > 0){
										while ($row = $result->fetch_assoc()) {
								?>
											<option value = "<?php echo $row['info'];?>"> <?php echo $row['info'];?> </option>
								<?php
										}
									}
								?>
							</select>
							<input type = "text" name = "newtermi0" id = "newtermi0" autocomplete = "off" placeholder = "Enter New Terminal" class="form-control input-sm" style="display: none;">
						</div>
						<div class="col-xs-5">
							<label style="font-size: 13px;">Reported Concern</label>
							<textarea class="form-control input-sm" id = "repcon0" name = "repcon0" placeholder = "Enter Reported Concern"  required></textarea>
						</div>		
					</div>
					<div class="row" id = "addr1"></div>
				</div>
				<a id="add_row" class="btn btn-success pull-left btn-sm">Add New Row</a><a id='delete_row' class="pull-right btn btn-danger btn-sm">Delete New Row</a>
			</div>
			<div class="row">
				<div class="col-xs-12" align="center">
					<input type = "hidden" name = "counter" id = "counter" value = "0"/>
					<button class="btn btn-primary btn-sm" name = "servicereport" onclick="confirm('Are you sure?');"> Submit </button>
				</div>
			</div>
		</form>
	</div>
	
<?php 
	if(isset($_POST['servicereport'])){
		if($_POST['servicecomp'] == 'new'){
			$sql = $conn->prepare("INSERT INTO servicecomp (name, email, email2, email3) VALUES (?, ?, ?, ?)");		
			$sql->bind_param("ssss", $_POST['cname'], $_POST['email'], $_POST['email2'], $_POST['email2']);
			$sql->execute();
			$company_id = $conn->insert_id;
		}else{
			$company_id = $_POST['servicecomp'];
		}
		$sql = $conn->prepare("INSERT INTO service (sales_id, company_id, state) VALUES (?, ?, 1)");		
		$sql->bind_param("ss", $_SESSION['acc_id'], $company_id);
		if($sql->execute()){
			$service_id = $conn->insert_id;
			for($i = 0; $i <= $_POST['counter']; $i++){
				$type = $_POST['type'.$i];
				if(isset($_POST['type'.$i]) && $_POST['type'.$i] == 'new'){
					$type = "Terminal";
				}
				if(isset($_POST['others'.$i]) && !empty($_POST['others'.$i])){
					$info = $_POST['others'.$i];
				}elseif(isset($_POST['terminal'.$i]) && !empty($_POST['terminal'.$i])){
					$info = $_POST['terminal'.$i];
				}elseif (isset($_POST['newtermi'.$i]) && !empty($_POST['newtermi'.$i])) {
					$info = $_POST['newtermi'.$i];
				}
				$stmt = $conn->prepare("INSERT INTO `servicerep` (service_id, type, info, concern, company_id) VALUES (?, ?, ?, ?, ?)");
				$stmt->bind_param("isssi", $service_id, $type, $info, $_POST['repcon'.$i], $company_id);
				if($_POST['type'.$i]!= null){
					if($stmt->execute()){
						$company = "SELECT * FROM servicecomp where servicecomp_id = $company_id";
						$email = $conn->query($company)->fetch_assoc();
						if($email['email2'] != ""){
							$email2 = ','. $email['email2'];
						}else{
							$email2 = "";
						}
						if($email['email3'] != ""){
							$email3 = ','. $email['email3'];
						}else{
							$email3 = "";
						}
						$mail_To = $email['email']. $email2 . $email3;
				        $mail_Subject = "Service Report Notifcation from Netlink Advance Solutions, Inc.";
				        $headers = "From: donotreply@netlinkph.net" . "\r\n";
				        $headers .= 'Cc: c.aquino_programmer@yahoo.com' . "\r\n";
				        $mail_Body = "Helo Sir/Maam, \n\n\n".
						"We have posted new service report for your Company: ".$email['name']."\n\n".
						"Just click the link below to view the service report.  \n\n".
						"http://uplinkph.net/t2raf/?id=". $service_id . "\n\n".
						"(This is a automated email from Netlink Advance Solutions Inc.):  \n ";
				         
				        mail($mail_To, $mail_Subject, $mail_Body,$headers);

				    	echo '<script type="text/javascript">alert("Successfull"); </script>';
				    }else{
						$conn->error();
					}
				}
			}
		}
		
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("select[name='servicecomp']").change(function(){
			window.location.href = "?servicereport=add&cid=" + $(this).val();
		});
		var i=1;
		$("#add_row").click(function(){
			$('#addr'+i).html('<div class="col-xs-3" id = "typediv">'+
									'<label style="font-size: 13px;">Type</label>'+
									'<select class="form-control input-sm" id = "type"'+i+' name = "type'+i+'"  required>'+
									'	<option value=""> - - - - - - - - </option>'+
									'	<option value="new"> New Terminal </option>'+
									'	<option value="Terminal"> Terminal </option>'+
									'	<option value="Internet"> Internet </option>'+
									'	<option value="Others"> Others </option>'+
									'</select>'+
								'</div>'+
								'<div class="col-xs-4">'+
								'	<label style="font-size: 13px;">Terminal / Others</label>'+
								'	<input type = "text" class="form-control input-sm" placeholder = "Others" id = "others'+i+'" name = "others'+i+'" disabled autocomplete = "off">'+
								'	<select class="form-control input-sm" id = "terminal'+i+'" name = "terminal'+i+'" disabled style="display:none;">'+
								'		<option value=""> - - - - - - - - </option>'+
								<?php
									$pc = "SELECT * FROM `servicerep` where type = 'Terminal' $sort group by info";
									$result = $conn->query($pc);
									if($result->num_rows > 0){
										while ($row = $result->fetch_assoc()) {
								?>
											' <option value = "<?php echo $row['info'];?>"> <?php echo $row['info'];?> </option>' +
								<?php
										}
									}
								?>
								'	</select>'+
								'	<input type = "text" name = "newtermi'+i+'" id = "newtermi'+i+'" autocomplete = "off" placeholder = "Enter New Terminal" class="form-control input-sm" style="display: none;">'+
								'</div>'+
								'<div class="col-xs-5">'+
								'	<label style="font-size: 13px;">Reported Concern</label>'+
								'	<textarea class="form-control input-sm" required placeholder = "Enter Reported Concern" id = "repcon'+i+'" name = "repcon'+i+'"></textarea>'+
								'</div>');
		
			$('#tab_logic').append('<div class="row" id = "addr'+(i+1)+'"></div>');
	  		i++;
	  		$('#counter').val(i);
	  		$(function () {
			for(b = 1; b < i; b++){
				(function (b) {
					$('select[name = "type'+b+'"]').change(function() {
						$('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
				    	if($(this).val() == 'Others'){
				    		$('#terminal'+b).attr('required',false);
				    		$('#terminal'+b).hide(); 				    		
				    		$('input[name = "others'+b+'"]').show(); 
				    		$('input[name = "others'+b+'"]').attr('required',true); 
				    		$('input[name = "others'+b+'"]').attr('disabled',false);  				    		
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}else if($(this).val() == 'Terminal'){
				    		$('input[name = "others'+b+'"]').attr('required',false); 
				    		$('input[name = "others'+b+'"]').hide(); 
				    		$('#terminal'+b).attr('required',true); 
				    		$('#terminal'+b).attr('disabled',false); 
				    		$('#terminal'+b).show(); 				    		
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}else if($(this).val() == 'new'){				      		
				    		$('#terminal'+b).attr('required',false);
				    		$('#terminal'+b).hide(); 				    		
				    		$('input[name = "others'+b+'"]').hide(); 
				    		$('input[name = "others'+b+'"]').attr('required',false); 
				    		$('input[name = "others'+b+'"]').attr('disabled',true);
				    		$('input[name = "newtermi'+b+'"]').show(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',true); 
				      	}else{
				      		$('#terminal'+b).attr('required',false); 
				    		$('#terminal'+b).hide(); 
				    		$('input[name = "others'+b+'"]').attr('required',true); 
				    		$('input[name = "others'+b+'"]').attr('disabled',true); 
				    		$('input[name = "others'+b+'"]').show(); 				    		
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}
				    });
				})(b);
			}
		});
	  });
	  	$("#delete_row").click(function(){
	    	if(i>1){
	    		$("#addr"+(i-1)).html('');
				i--;
				$('#counter').val(i);
			}
		});
		$(function () {
			for(b = 0; b < 1; b++){
				(function (b) {
					$('select[name = "type'+b+'"]').change(function() {
						$('option:selected', this).attr('selected',true).siblings().removeAttr('selected');
				    	if($(this).val() == 'Others'){
				    		$('#terminal'+b).attr('required',false);				    		
				    		$('input[name = "others'+b+'"]').show(); 
				    		$('#terminal'+b).hide(); 
				    		$('input[name = "others'+b+'"]').attr('required',true); 
				    		$('input[name = "others'+b+'"]').attr('disabled',false);
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}else if($(this).val() == 'Terminal'){
				    		$('input[name = "others'+b+'"]').attr('required',false); 
				    		$('input[name = "others'+b+'"]').hide(); 
				    		$('#terminal'+b).attr('required',true); 
				    		$('#terminal'+b).attr('disabled',false); 
				    		$('#terminal'+b).show(); 				    		
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}else if($(this).val() == 'new'){				      		
				    		$('#terminal'+b).attr('required',false);
				    		$('#terminal'+b).hide(); 				    		
				    		$('input[name = "others'+b+'"]').hide(); 
				    		$('input[name = "others'+b+'"]').attr('required',false); 
				    		$('input[name = "others'+b+'"]').attr('disabled',true);
				    		$('input[name = "newtermi'+b+'"]').show(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',true); 
				      	}else{
				      		$('#terminal'+b).attr('required',false); 
				    		$('#terminal'+b).hide(); 
				    		$('input[name = "others'+b+'"]').attr('required',true); 
				    		$('input[name = "others'+b+'"]').attr('disabled',true); 
				    		$('input[name = "others'+b+'"]').show(); 
				    		$('input[name = "newtermi'+b+'"]').hide(); 
				    		$('input[name = "newtermi'+b+'"]').attr('required',false); 
				      	}
				    });
				})(b);
			}
		});
	});

</script>
	<div style = "display: none;">
<?php	}elseif(isset($_GET['servicereport']) && $_GET['servicereport'] == "" && ($_SESSION['acc_id'] == 2 || stristr($_SESSION['post'], 'technician') == true)){ ?>
	<?php if(!isset($_GET['id'])){ 
		$counter = "SELECT count(*) as total from service";
		$counter2 = $conn->query($counter)->fetch_assoc();
		$perpage = 10;
		$totalPages = ceil($counter2['total'] / $perpage);
		if(!isset($_GET['page'])){
		    $_GET['page'] = 0;
		}else{
		    $_GET['page'] = (int)$_GET['page'];
		}
		if($_GET['page'] < 1){
		    $_GET['page'] = 1;
		}else if($_GET['page'] > $totalPages){
		    $_GET['page'] = $totalPages;
		}
?>
	<div class="container-fluid" style="margin-left: 20px; font-size: 14px;">
		<div class="row">
			<div class="col-xs-12">
				<?php if($_SESSION['acc_id'] == 2){ ?>
				<a href = "?servicereport=add" class="btn btn-primary pull-right btn-sm" style="margin-right: 10px; margin-top: 5px;"> Add New </a>
				<?php } ?>
				<h4><i><u>Service Reports</u></i></h4>
			</div>
		</div>
		<div class="row" style="text-align: center;">
			<div class="col-xs-3">
				<label><u>Date Posted</label>
			</div>
			<div class="col-xs-3">
				<label><u>Posted by </label>
			</div>
			<div class="col-xs-3">
				<label><u>Status</label>
			</div>		
			<div class="col-xs-3">
				<label><u>Action</label>
			</div>
		</div>
		<?php
			$startArticle = ($_GET['page'] - 1) * $perpage;
			$stmt = "SELECT * FROM `service`,`login` where sales_id = account_id ORDER BY state ASC, date DESC, service_id ASC LIMIT " . $startArticle . ', ' . $perpage;
			$result = $conn->query($stmt);
			if($result->num_rows > 0){
				while ($row = $result->fetch_assoc()) {
		?>
				<div class="row" style="text-align: center;">
					<div class="col-xs-3">
						<?php echo date("M j, Y h:i:s A",strtotime($row['date'])); ?>
					</div>
					<div class="col-xs-3">
						<?php echo $row['fname'] . ' ' . $row['lname']; ?>
					</div>
					<div class="col-xs-3">
						<?php 
							if($row['state'] == 1){
								echo '<b><font color = "green"> Ongoing </font></b>';
							}else{
								echo '<b><font color = "green"> Done </font></b>';
							}
						?>
					</div>
					<div class="col-xs-3">
						<a href = "?servicereport&id=<?php echo $row['service_id']?>" class = "btn btn-primary btn-sm"> View Details </a>
					</div>
				</div>
				<hr>
		<?php
				}
			}
		?>
		<div class="row">
			<div class="col-xs-12" align="center">
				<label> Pages </label><br>
				<?php
					foreach(range(1, $totalPages) as $page){
					    if($page == $_GET['page']){
					        echo '<span class="currentpage">' . $page . '</span>';
					    }else if($page == 1 || $page == $totalPages || ($page >= $_GET['page'] - 2 && $page <= $_GET['page'] + 2)){
					        echo '<a class = "btn btn-default btn-sm" style = "margin: 5px;" href="?servicereport&page=' . $page . '">' . $page . '</a>';
					    }
					}
				?>
			</div>
		</div>
	</div>
<?php }else{ ?>
	<div class="container-fluid" style="margin-left: 20px; margin-right: 20px; font-size: 14px;">
		<div class="row">
			<div class="col-xs-12">
				<h4><i><u>Service Reports</u></i></h4>
				<hr>
			</div>
		</div>
		<div class="row" style="text-align: center;">
			<div class="col-xs-2">
				<label><u>Type</label>
			</div>
			<div class="col-xs-2">
				<label><u>Type Details</label>
			</div>
			<div class="col-xs-4">
				<label><u>Reported Concern</label>
			</div>
			<div class="col-xs-4">
				<label><u>Action Taken</label>
			</div>
		</div>
		<?php
			$id = mysqli_real_escape_string($conn, $_GET['id']);
			$stmt = "SELECT * FROM `service`,`servicerep` where service.service_id = $id and servicerep.service_id = $id ORDER BY date";
			$result = $conn->query($stmt);
			$state = 0;
			$conf = "";
			$confdate = "";
			$company_id = 0;
			if($result->num_rows > 0){
				while ($row = $result->fetch_assoc()) {
					$company_id = $row['company_id'];
					if($row['tech_id'] == ""){
						$state = 0;
					}else{
						$state = $row['state'];
					}
					$conf = $row['conforme'];
					$confdate = $row['confdate'];
		?>
				<div class="row" style="text-align: center;">
					<div class="col-xs-2">
						<?php echo $row['type'] ?>
					</div>
					<div class="col-xs-2">
						<?php if($row['info'] == ""){ echo ' - '; }else{ echo $row['info']; } ?>
					</div>
					<div class="col-xs-4">
						<?php echo nl2br($row['concern']).'<br><br>'.date("M j, Y h:i:s A",strtotime($row['enc_date'])); ?>
					</div>
					<div class="col-xs-4">
						<?php
						if($row['acttaken'] == "" && stristr($_SESSION['post'], 'technician') == true){
							echo '<form action = "" method = "post">';
								echo '<textarea class = "form-control"  placeholder = "Enter Action Taken" name = "acttaken" required></textarea>';
								echo '<button class = "btn btn-primary btn-sm" onclick = "confirm(\'Are you sure?\')" name = "updateac"> Update </button>';
								echo '<input type = "hidden" name = "id" value ="' . $row['servicerep_id'] . '">';
							echo '</form>';
						}elseif($row['acttaken'] == "" && (stristr($_SESSION['post'], 'technician') == false)){
							echo ' <b><font color = "red"> Pending </font></b>';
						}else{
							echo nl2br($row['acttaken']).'<br><br>'.date("M j, Y h:i:s A",strtotime($row['tech_date']));
						}
						?>
					</div>
					<div class="col-xs-12">
						<hr>
					</div>
				</div>
		<?php
				}
			}
		?>
		<div class="row" style="text-align:center">
			<div class="col-xs-4">
				<label><u>Prepared by:</u></label>
				<p>
					<?php 
						$stmt = "SELECT * FROM `service`,`login` where sales_id = account_id and service_id = $id ORDER BY date";
						$result = $conn->query($stmt)->fetch_assoc();
						echo $result['fname'] . ' ' . $result['lname'];
					?>
				</p>
			</div>
			<div class="col-xs-4 col-xs-offset-3">
				<label><u>Supported by:</u></label>
				<p>
					<?php 
						$stmtx = "SELECT * FROM `servicerep`,`login` where tech_id is not null and tech_id = account_id and service_id = $id";
						$resultx = $conn->query($stmtx)->fetch_assoc();
						if($state == 0){ 
							echo '<font color = "red"><b><i> Waiting for Technician </i></b></font>';
						}else{
							echo $resultx['fname'] . ' ' . $resultx['lname'];
						}
					?>
				</p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-12" align="center">
				<a href = "?servicereport" class="btn btn-danger btn-sm"><span class="icon-exit"></span> Back </a>
			</div>
		</div>
	</div>
<?php } ?>

<?php
	if(isset($_POST['conformebut'])){
		$stmt = $conn->prepare("UPDATE service set conforme = ?, confdate = now(), state = 2 where service_id = ?");
		$stmt->bind_param("si", $_POST['confirmby'], $id);
		if($stmt->execute()){
			$company = "SELECT * FROM servicecomp where servicecomp_id = '$company_id'";
			$email = $conn->query($company)->fetch_assoc();
			if($email['email2'] != ""){
				$email2 = ','. $email['email2'];
			}else{
				$email2 = "";
			}
			if($email['email3'] != ""){
				$email3 = ','. $email['email3'];
			}else{
				$email3 = "";
			}
			$mail_To = $email['email']. $email2 . $email3;
	        $mail_Subject = "Service Report Notifcation from Netlink Advance Solutions, Inc.";
	        $headers = "From: donotreply@netlinkph.net" . "\r\n";
	        $headers .= 'Cc: c.aquino_programmer@yahoo.com' . "\r\n";
	        $mail_Body = "Helo Sir/Maam, \n\n\n".
			"Report Confirmed by:  ".$_POST['confirmby']."\n\n".
			"Just click the link below to view the service report.  \n\n".
			"http://uplinkph.net/t2raf/?id=". $id . "\n\n".
			"(This is a automated email from Netlink Advance Solutions Inc.):  \n ";
	         
	        mail($mail_To, $mail_Subject, $mail_Body,$headers);
			echo '<script>alert("Report Confirmed");window.location.href="?id=' . $id . '";</script>';
		}
	}
	if(isset($_POST['updateac'])){
		$stmt = $conn->prepare("UPDATE servicerep set acttaken = ?, tech_id = ?, tech_date = now() where servicerep_id = ? and service_id = ?");
		$stmt->bind_param("siii", $_POST['acttaken'], $_SESSION['acc_id'], $_POST['id'], $id);
		if($stmt->execute()){
			$company = "SELECT * FROM servicecomp where servicecomp_id = '$company_id'";
			$email = $conn->query($company)->fetch_assoc();
			if($email['email2'] != ""){
				$email2 = ','. $email['email2'];
			}else{
				$email2 = "";
			}
			if($email['email3'] != ""){
				$email3 = ','. $email['email3'];
			}else{
				$email3 = "";
			}
			$mail_To = $email['email']. $email2 . $email3;
	        $mail_Subject = "Service Report Notifcation from Netlink Advance Solutions, Inc.";
	        $headers = "From: donotreply@netlinkph.net" . "\r\n";
	        $headers .= 'Cc: c.aquino_programmer@yahoo.com' . "\r\n";
	        $mail_Body = "Helo Sir/Maam, \n\n\n".
			"Our techinician updated the action taken on service report of your Company: ".$email['name']."\n\n".
			"Just click the link below to view the service report.  \n\n".
			"http://uplinkph.net/t2raf/?id=". $id . "\n\n".
			"(This is a automated email from Netlink Advance Solutions Inc.):  \n ";
	         
	        mail($mail_To, $mail_Subject, $mail_Body,$headers);
			echo '<script>alert("Updated");window.location.href="?servicereport&id=' . $id . '";</script>';
		}
	}
?>
	</div>

	<div style = "display: none;">
<?php } ?>
