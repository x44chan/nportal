<script type="text/javascript">
$(document).ready(function(){
	// jQuery plugin to prevent double submission of forms
	jQuery.fn.preventDoubleSubmission = function() {
	  $(this).on('submit',function(e){
	    var $form = $(this);

	    if ($form.data('submitted') === true) {
	      // Previously submitted - don't submit again
	      e.preventDefault();
	    } else {
	      // Mark it so that the next submit can be ignored
	      $form.data('submitted', true);
	    }
	  });

	  // Keep chainability
	  return this;
	};
	$('form').preventDoubleSubmission();
});
</script>

<?php
	$_SESSION['exec'] = 0;
	if(isset($_GET['late_filing'])){
		echo '<div id = "latefiling">';
		include 'caloan/latefiling.php';
		echo '</div>';
	}
?>

<div id = "offb" style = "margin-top: -30px; display: none; ">
	<form role = "form"  align = "center"action = "ob-exec.php" method = "post">
		<div class = "form-group">
			<table width = "60%" align = "center">
				<tr>
					<td colspan = 3 align = center>
						<h2> Official Business Request </h2>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text"  class = "form-control" readonly name = "obdate" value = "<?php echo date('F j, Y');?>"/></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "obename"/></td>
				</tr>
				<tr>
					<td>ID No: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['acc_id'];?>" readonly name = "idnum"/></td>
				</tr>
				<tr>
					<td>Position: </td>
					<td><input readonly value = "<?php echo $_SESSION['post'];?>"required class = "form-control" type = "text" name = "obpost"/></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input readonly value = "<?php echo $_SESSION['dept'];?>"required class = "form-control" type = "text" name = "obdept"/></td>
				</tr>
				<tr>
					<td>Date Of Official Business: </td>
					<td><input required class = "form-control" type = "date" placeholder = "Click to set date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "obdatereq"/></td>
				</tr>				
				<tr>
					<td>Description of Work Order: </td>
					<td><textarea required name = "obreason" placeholder = "Enter your work order" class = "form-control col-sm-10"></textarea></td>
					<td></td>
				</tr>
				<div class = "ui-widget-content" style = "border: none;">			
				<tr>					
					<td style="float: right;">
						<label for="restday2" style="font-size: 15px;"><input type="checkbox" value = "restday" name="restday" id="restday2"/> Rest Day</label>
					</td>
				</tr>	
				<tr id = "rday2" class = "form-inline" >
					<td>Official Work Sched: <font color = "red">*</font></td>
					<td style="float:left;">
						<label for = "fr">From:</label><input placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "reqto"class = "form-control"  name = "obofficialworkschedfr"/>
						<label for = "to">To:</label><input placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "reqfr"  name = "obofficialworkschedto"/>
					</td>					
				</tr>	
				<tr id = "warning" style="display: none;">
					<td></td>
					<td>
						<div class="alert alert-danger fade in">
						  <strong>Warning!</strong> Fill out <b>Time In</b> or <b>Time Out</b>
						</div>
					</td>
				</tr>
				<script type="text/javascript">
					$(document).ready(function(){
					//	$('input[name="obtimein"]').ptTimeSelect();
						$('input[name="obofficialworkschedto"]').ptTimeSelect();
						$('input[name="obofficialworkschedfr"]').ptTimeSelect();							
					//	$('input[name="obtimeout"]').ptTimeSelect();
					});
				</script>
				</div>
				<tr>
					<td style = "padding: 3px;"colspan = "2" align = center>
						<input type = "submit" name = "submit" id = "submits" onclick = "return confirm('Are you sure? You can still review your application.');" class = "btn btn-default"/>					
						<input type = "button" id = "hideob" name = "submit" class = "btn btn-default" value = "Cancel">
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>
<div id = "undertime"style = "margin-top: -30px; display: none;">
	<?php include('undertime.php'); ?>
</div>
<div id = "formhidden"style = "margin-top: -30px;display: none;">
	<form role = "form"  align = "center"action = "ot-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center" width="50%">
				<tr>
					<td colspan = 2 align = center>
						<h2> Overtime Request </h2>
					</td>
				</tr>
				<tr style = "display: none;">
					<td colspan = 2 align = center>
						<h5><p style = "font-style: italic; color: red;">No over 30 Minutes or less than 30 Minutes. (Counted Overtime is 30 Minutes or Hour/s Only)<br>6:00 PM - 8.50 PM (Counted Overtime: 2 Hours and 30 Minutes)	</h5>
					</td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" class = "form-control" readonly name = "datefile" value = "<?php echo date('F j, Y');?>"/></td>
				</tr>
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "nameofemployee"/></td>
				</tr>
				<tr>
					<td>Date Of Overtime : <font color = "red">*</font></td>
					<td><input style="width: 100%;" required class = "form-control" type = "date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder = "YYYY-MM-DD" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' name = "dateofot"/></td>
				</tr>	
				<tr>
					<td>CSR #: </td>
					<td><input class = "form-control" type = "text" autocomplete = "off" placeholder = "Enter CSR Number" name = "csrnum"/></td>
				</tr>			
				<tr>
					<td>Reason (Work to be done): <font color = "red">*</font></td>
					<td><textarea required placeholder = "Enter your work to be done" name = "reason"class = "form-control"></textarea></td>
					
				</tr>
				<tr><div class = "ui-widget-content" style = "border: none;" >
					<td>Start (Time of OT): <font color = "red">*</font></td>
					<td>
						<input required onkeydown="return false;" class = "form-control"  name = "startofot" autocomplete ="off" placeholder = "Click to Set time"/>
					</td>					
				</tr>				
				<tr>
					<td>End (Time of OT): <font color = "red">*</font></td>
					<td><input required onkeydown="return false;" class = "form-control" name = "endofot" placeholder = "Click to Set time" autocomplete ="off" /></td>
				</tr>	
				<tr>
					<td>OT Break ( if applicable <font color = "red" style="font-size: 12px;"><i>*for less than 8 hrs *</i></font>):<br> <font color = "red" style="font-size: 12px;"><i>Automatic <b>1 Hr</b> Deduction for more than 8 OT Hrs.</i></font>  </td>
					<td>
						<select class = "form-control" name = "otbreak" id = "otbreak">
							<option value ="">--------</option>
							<option value = "30 Mins">30 Mins</option>
							<option value = "1 Hour">1 Hour</option>
						</select>
					</td>					
				</tr>
				<tr>					
					<td style="float: right;">
						<label for="restday" style="font-size: 15px;"><input type="checkbox" value = "restday" name="restday" id="restday"/> Rest Day</label>
					</td>
				</tr>	
				<tr id = "rday" class = "form-inline" >
					<td>Official Work Sched: <font color = "red">*</font></td>
					<td style="float:left;">
						<label for = "fr">From:</label><input onkeydown="return false;" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" id = "toasd"class = "form-control"  name = "officialworkschedfr"/>
						<label for = "to" style="margin-left: 5px;">To:</label><input onkeydown="return false;" placeholder = "Click to Set time" required style = "width: 130px;" autocomplete ="off" class = "form-control" id = "frasd"  name = "officialworkschedto"/>
					</td>					
				</tr>							
				<script type="text/javascript">
					$(document).ready(function(){
						$('input[name="startofot"]').ptTimeSelect();
						$('input[name="officialworkschedto"]').ptTimeSelect();
						$('input[name="officialworkschedfr"]').ptTimeSelect();							
						$('input[name="endofot"]').ptTimeSelect();
					});
				</script>
				</div>
				<tr>
					<td colspan = 2 align = center><input type = "submit" name = "otsubmit" class = "btn btn-default"/><input type = "button" id = "hideot" name = "submit" class = "btn btn-default" value = "Cancel"></td>
				</tr>
			</table>
		</div>
	</form>
</div>

<div id = "leave" style = "margin-top: -30px; display: none;">
<?php
	include('conf.php');
	$accid = $_SESSION['acc_id'];
	$sql = "SELECT * from `login` where account_id = '$accid' and empcatergory = 'Regular'";
	$result = $conn->query($sql);
	$datey = date("Y");
	$availsick = 0;
	$totavailvac = 0;
	if($result->num_rows > 0){		
		while($row = $result->fetch_assoc()){
			$cstatus = $row['ecstatus'];
			$accidd = $row['account_id'];
			$egender = $row['egender'];
			if(date("Y") == 2015){	
				$sl = $row['sickleave'] - $row['usedsl'];
				$vl = $row['vacleave'] - $row['usedvl'];
				$usedsl = $row['usedsl'];
				$usedvl = $row['usedvl'];
			}else{				
				$leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and CURDATE() BETWEEN startdate and enddate and state = 'AAdmin'";
				$datalea = $conn->query($leaveexec)->fetch_assoc();
				$sl = $datalea['sleave'];
				$vl = $datalea['vleave'];
				$usedsl = 0;
				$usedvl = 0;
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Sick Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$availsick = $sl - $row1['count'];
					$scount = $row1['count'];						
					}
			}		
			if($scount == null){
				$scount = " - ";
			}			
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea = 'Vacation Leave'  and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$availvac = $vl - $row1['count'];
					$count = $row1['count'];
					}
			}		
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Other%' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$totavailvac = $availvac - $row1['count'];
					$count = $row1['count'];
					}
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Paternity Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$patternity = 7 - $row1['count'];
					$count = $row1['count'];
					}
			}
			$sql1 = "SELECT SUM(numdays) as count  FROM nleave where nleave.account_id = $accidd and typeoflea like 'Wedding Leave' and leapay = 'wthpay' and state = 'AAdmin' and YEAR(dateofleavfr) = $datey";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$wedding = 7 -  $row1['count'];
					$count = $row1['count'];
					}
			}				
			
		}
	}

?>
	<form role = "form"  align = "center"action = "oleave-exec.php" method = "post">
		<div class = "form-group">
			<table align = "center" width = "60%">
				<tr>
					<td colspan = 3 align = center>
						<h2> Leave Request </h2>
					</td>
				</tr>
				<tr>
					<td colspan = 3 align = center>
						<h5><p style = "font-style: italic; color: red;">For scheduled leave, submit Leave request to Human Resources Department seven(7) days prior to leave date. </h5>
					</td>
				</tr>		
				<tr class = "form-inline" >
					<td>Type of Leave</td>
					<td align = "left">
						<select style = "width: 60%; float: left;" required class="form-control" id = "typeoflea" name="typeoflea">
							<option value = ""> ---- </option>							
							<option value = "Sick Leave">Sick Leave</option>
							<option value = "Vacation Leave">Vacation Leave</option>
							<?php //if($patternity > 0 && $egender == "Male"){ echo '<option value = "Paternity Leave">Paternity Leave </option>'; }?>
							<?php //if($wedding  > 0 && $cstatus != 'Married' && $egender == "Female"){ echo '<option value = "Wedding Leave">Wedding Leave </option>'; echo $cstatus;}?>
							<option value = "Others">Others(Pls. Specify)</option>
						</select>						
						<input disabled type = "text" name = "othersl" class = "form-control" id = "othersl" style = "width: 40%;"/>
					</td>
				</tr>	
				<div style = "display: none;">
				<tr>
					<td>Name of Employee: </td>
					<td><input required class = "form-control" type = "text" value = "<?php echo $_SESSION['name'];?>" readonly name = "nameofemployee"/></td>
				</tr>
				<tr>
					<td>Date File: </td>
					<td><input type = "text" class = "form-control" readonly name = "datefile" required value = "<?php echo date('F j, Y');?>"/></td>
				</tr>				
				<tr>
					<td>Date Hired: </td>
					<td><input readonly value = "<?php echo date('F j, Y', strtotime($_SESSION['datehired'])); ?>"type = "text" class = "form-control"  name = "datehired" required /></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input readonly value = "<?php echo $_SESSION['dept'];?>" type = "text" class = "form-control"  name = "deprt" required/></td>
				</tr>
				<tr>
					<td>Position Title: </td>
					<td><input readonly value = "<?php echo $_SESSION['post'];?>" type = "text" class = "form-control"  name = "posttile" required/></td>
				</tr>
				<tr>
					<td colspan = 3 align = "center">
					<h3>LEAVE DETAILS</h3>
				</tr>
				<tr>
					<td align = center>	Sick Leave Balance: </td>
					<td><input readonly="" id = "sickleave" value = "<?php echo $availsick;?>" class = "form-control"/></td>
				</tr>	
				<tr>
					<td align = center>	Vacation Leave Balance: </td>
					<td><input readonly="" id = "vacleave" value = "<?php echo $totavailvac;?>" type = "number" class = "form-control"/></td>
				</tr>
				<tr class = "form-inline">
					<td>Inclusive Dates: </td>
					<td style="float:left;">
						From: <input required class = "form-control" type = "date" placeholder = "Click to set date"required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "dateofleavfr"/>
						To: <input required class = "form-control" type = "date" placeholder = "Click to set date"required="" data-date='{"startView": 2, "openOnMouseFocus": true}' min = "<?php echo date('m/d/Y'); ?>" name = "dateofleavto"/>
						Number of Days: <input maxlength = "3" style = "width: 90px;"type = "text" pattern = '[0-9.]+' required name = "numdays"class = "form-control"/>
					</td>
				</tr>					

				<tr>
					<td>Reason: </td>
					<td><textarea placeholder = "Enter leave reason" class = "form-control" name = "leareason"required></textarea></td>
				</tr>
				<tr>
					<td colspan = 4 align = center><input type = "submit" name = "leasubmit" class = "btn btn-default"/><input type = "button" onclick = "$('#leave').hide(); $('#dash').show(); location.reload();" id = "hideot" name = "submit" class = "btn btn-default" value = "Cancel"></td>					
				</tr>
			</table>
		</div>
	</form>
</div>
  <!-- Modal -->
  <div class="modal fade" id="petty" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Petty Cash Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
              <label for="usrname"> Particular <font color = "red">*</font></label>
              <select name = "particularpet" required class = "form-control">
             	<option value = "">----------</option>
              	<option value = "Cash">Cash</option>
              	<option value = "Check">Check</option>
              	<option value = "Transfer">Transfer</option>
              </select>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[.0-9,]*" id = "petamount" required name = "amountpet" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	 <label for="usrname"> Reason <font color = "red">*</font></label>
            	<input type = "text" id = "petamount" required name = "petreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason">
          	</div>
              <button type="submit" name = "submitpet" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
<?php
	if(isset($_POST['submitpet']) && isset($_SESSION['acc_id'])){
		$acc_id = mysql_escape_string($_SESSION['acc_id']);
		$particularpet = mysql_escape_string($_POST['particularpet']);
		$amountpet = mysql_escape_string($_POST['amountpet']);
		if($particularpet == 'Transfer'){
			$state = 'UATransfer';	
		}else{
			$state = 'UAPetty';
		}
		$datefile = date("Y-m-d");
		$sql = "SELECT * FROM petty,login where login.account_id = '$acc_id' and login.position != 'House Helper' and petty.account_id = '$acc_id' and (petty.state != 'DAPetty' and petty.state != 'CPetty') order by state ASC, source asc";
		$result = $conn->query($sql);
		$count = 0;
		if($result->num_rows > 0){	
			while($row = $result->fetch_assoc()){
			$petid = $row['petty_id'];
			$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
			$data = $conn->query($sql)->fetch_assoc();
				if($data['petty_id'] == null){
					$count += 1;
				}
				if($data['liqstate'] == 'LIQDATE'){
					$count += 1;
				}
				if($data['liqstate'] == 'EmpVal'){
					$count += 1;
				}
		   }
		}
		if($count > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
		}else{
			$stmt = $conn->prepare("INSERT INTO petty (`account_id`,`date`, `particular`, `amount`, `state`, `petreason`) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("isssss", $acc_id, $datefile, $particularpet, $amountpet, $state, $_POST['petreason']);
			$stmt->execute();		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
			$conn->close();
		}

	}
?>
<?php 
	if($_SESSION['category'] == "Regular"){?>
  <!-- caModal -->
  <div class="modal fade" id="cashadv" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Cash Advance Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[0-9]*" required name = "amountca" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	 <label for="usrname"> Reason <font color = "red">*</font></label>
            	<input type = "text" required name = "careason" class ="form-control" autocomplete = "off" placeholder = "Enter reason">
          	</div>
              <button type="submit" name = "submitca" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
 <script type="text/javascript">
  $("button[name='submitca']").click(function(){
     if($("input[name='amountca'").val() > 3000){
               alert("You can't request more than ₱ 3,000.");
               return false;
     }
});
 </script>
<?php
	if(isset($_POST['submitca']) && isset($_SESSION['acc_id'])){
		$date = date("Y-m-d");
		if($date > date('Y-m-16')){
			$date = date("Y-m-01", strtotime("next month"));
			$date2 = date("Y-m-16");
		}else{
			$date = date("Y-m-16");
			$date2 = date("Y-m-01");
		}
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and  ( CURDATE() <= enddate and (loan_cutoff.state != 'DALoan' and loan_cutoff.state != 'Full') ) and loan.state != 'DECLoan'";
		$resquery = $conn->query($query);
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate <= '$date' and cadate >= '$date2'";
		$resquery2 = $conn->query($query2);
		if($resquery2->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("employee.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance");window.location.replace("hr.php?ac=penca"); </script>';
	    	}
		}elseif($resquery->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("employee.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have Loan Balance.");window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
		}else{
			$datefile = date("Y-m-d");
			$state = 'UACA';
			$stmt = $conn->prepare("INSERT INTO cashadv (`account_id`,`cadate`, `caamount`, `careason`, state) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("issss", $_SESSION['acc_id'], $datefile, $_POST['amountca'], $_POST['careason'], $state);
			$stmt->execute();		
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">window.location.replace("employee.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penca"); </script>';
	    	}
			$conn->close();
		}
	}

?>

<?php
	if(isset($_POST['loanpet']) && isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		$state = "UALoan";
		$loandate = date("Y-m-d"); 
		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and ( ( loan.state != 'DECLoan' ) and (CURDATE() <= enddate and loan_cutoff.state != 'Full' and loan_cutoff.state != 'Cancel' and loan_cutoff.state != 'Advance') )";
		$resquery = $conn->query($query);
		$sqlxx = "SELECT * FROM loan where account_id = '$accid' and state = 'UALoan'";
		$resqueryxx = $conn->query($sqlxx);
		$date = date("Y-m-d");
		if($date > date('Y-m-16')){
			$date = date("Y-m-01", strtotime("next month"));
			$date2 = date("Y-m-16");
		}else{
			$date = date("Y-m-16");
			$date2 = date("Y-m-01");
		}
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate < '$date' and cadate > '$date2'";
		$resquery2 = $conn->query($query2);
		if($_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'] < date("Y-m-d")){
			echo '<script type="text/javascript">alert("Wrong date.");window.location.replace("employee.php?ac=penloan"); </script>';
		}elseif(($resquery->num_rows > 0) || ($resqueryxx->num_rows > 0)){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("employee.php?ac=penloan"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending loan.");window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
		}elseif($resquery2->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("employee.php?ac=penca"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("hr.php?ac=penca`"); </script>';
	    	}
		}else{
			if($_POST['loanduration'] == 'Others'){
				$duration = $_POST['loanothers'] . ' Months';
			}else{
				$duration = $_POST['loanduration'] . ' Months';
			}	   		
	   		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
			$sql = $conn->prepare("INSERT INTO `loan` (account_id, loanamount, loanreason, state, loandate, duration, startdate) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$sql->bind_param("issssss", $accid, $_POST['loanamount'], $_POST['loanreason'], $state, $loandate, $duration, $date);
			if($sql->execute()){
				if($_SESSION['level'] == 'EMP'){
	    			echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
		    	}
			}
		}

	}

?>
   <!-- loanModal -->
  <div class="modal fade" id="loan" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Loan Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[0-9]*" required name = "loanamount" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	 <label for="usrname"> Reason <font color = "red">*</font></label>
            	<input type = "text" id = "petamount" required name = "loanreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason">
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Duration <font color = "red">*</font></label>
            	<select name = "loanduration" class="form-control" id = "loanduration" required>
					<option value = ""> ----------- </option>
					<option value = "1"> 1 Month </option>
					<option value = "2"> 2 Months </option>
					<option value = "3"> 3 Months </option>
					<option value = "Others"> Others </option>
				</select>
            </div>
            <div class="form-group">
            	<label>Others</label>
				<input type =  "text" maxlength="2" class="form-control" name = "loanothers" disabled=""/>
            </div>
            <div class="form-group">
				<label>Payment Start (Month)</label>
				<select class="form-control" name = "cutoffmonth" required >
					<option value="">-----------</option>
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Day)</label>
				<select class="form-control" name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="01">01</option>
					<option value="16">16</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Year)</label>
				<select class="form-control" required name = "cutofyr">
					<option value=""> - - - - - - - </option>
					<option value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
					<option value="<?php echo date("Y", strtotime("+1 year"));?>"><?php echo date("Y", strtotime("+1 year"));?></option>
				</select>
			</div>
			<div class="form-group">
				<p style="color: red; font-size: 12px;">
					<b>Example: <br>
						Your Payment Start is Feb 01, 2016 <br>
						Covered Cutoff is Feb 01 - Feb 15, 2016</b>
				</p>
			</div>
              <button type="submit" name = "loanpet" onclick="return confirm('Are you sure?');" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
  
  <script type="text/javascript">
	$(document).ready(function(){
		$('#loanduration').change(function() {
		    var selected = $(this).val();			
			if(selected == 'Others'){
				$('input[ name = "loanothers" ]').attr('disabled',false);
				$('input[ name = "loanothers" ]').attr("placeholder", "# of Months");
			}else{
				$('input[ name = "loanothers" ]').attr('disabled',true);
				$('input[ name = "loanothers" ]').attr("placeholder", "");
			}
		});
	});
</script>
 <?php } ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#restday').change(function(){
	    if($('#restday').is(":checked")){ 	        
	    	$("#rday").hide();
	    	$("#toasd").attr('required',false);
	    	$("#frasd").attr('required',false);
	    	$("#upoffr").attr('required',false);
	    	$("#upoffto").attr('required',false);
	    }else{
	    	$("#toasd").attr('required',true);
	    	$("#frasd").attr('required',true);
	    	$("#upoffto").attr('required',true);
	    	$("#upoffr").attr('required',true);
	        $("#rday").show();
	    }
	});
});
$(document).ready(function(){
	    $('#restday2').change(function(){
	    if($('#restday2').is(":checked")){ 	        
	    	$("#rday2").hide();
	    	$("#reqto").attr('required',false);
	    	$("#reqfr").attr('required',false);
	    }else{
	    	$("#to").attr('required',true);
	    	$("#fr").attr('required',true);
	        $("#rday2").show();
	    }
	});
	$('#restday3').change(function(){
	    if($('#restday3').is(":checked")){ 	        
	    	$("#rday3").hide();
	    	$("#reqto3").attr('required',false);
	    	$("#reqfr3").attr('required',false);
	    	$("#reqfr3").val("");
	    	$("#reqto3").val("");

	    }else{
	    	$("#reqto3").attr('required',true);
	    	$("#reqfr3").attr('required',true);
	        $("#rday3").show();
	    }
	});
});
</script>
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshims.setOptions('forms-ext', {types: 'date'});
  webshims.polyfill('forms forms-ext');
</script>
<?php if($_SESSION['level'] == 'HR'){
	?>
  <!-- Modal -->
  <div class="modal fade" id = "newAcc" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>New Account</h4>

        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "newuser-exec.php" method = "post">
          	<div class="form-group">
	           <?php
	          	if(isset($_SESSION['err']) && $_SESSION['err'] == 'ex'){
	          		echo '<div class="alert alert-danger" align="center"><strong>Warning!</strong> Username already exist.</div>';
	          		$_SESSION['err'] = "";
	          	}
	          ?>
	         </div>
            <div class="form-group">
              <label for="usrname"> Username <font color = "red">*</font></label>
              <input pattern=".{4,}" placeholder = "Enter you desired username" title="Four or more characters"required class ="form-control"type = "text" name = "reguname"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Password <font color = "red">*</font></label><br>
            	<input required pattern=".{6,}" placeholder = "Enter your desired password" title="Six or more characters" class ="form-control"type = "password" id = "psw" name = "regpword"/>
            </div>
             <div class="form-group">
            	<label for="usrname"> Confirm Password <font color = "red">*</font></label><br>
            	<input required pattern=".{6,}" placeholder = "Enter again your password" title="Six or more characters" class ="form-control"type = "password" id = "psw1" name = "regcppword"/>
            </div>
            <div class="form-group">
            	<label for="usrname"> Account Level <font color = "red">*</font></label><br>
            	<select name = "level" class ="form-control">
						<option value = "EMP">Employee
						<?php if($_SESSION['level'] == 'Admin'){ ?>
							<option value = "HR">HR
							<option value = "ACC">Accounting						
							<option value = "Admin">Admin
						<?php } ?>
				</select>
            </div>
              <button type="submit" id = "submita" name = "hreg" class="btn btn-success btn-block" onclick = "">Submit</button>
          </form>
        </div>
        <div class="modal-footer">          
        </div>
      </div>      
    </div>
  </div> 
  <script type="text/javascript">
$("#submita").click(function(){
    if($("#psw").val() != $("#psw1").val()){
        alert("Password does not match");
	    return false;
        //more processing here
    }
});
</script>
 <?php } ?>
 <!-- loanModal -->
  <div class="modal fade" id="penalty" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Penalty Loan Form</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"> Name</label>
              <input type = "text" readonly class = "form-control" value = "<?php echo $_SESSION['name'];?>"/>
            </div>
            <div class="form-group">
            	 <label for="usrname"> Amount <font color = "red">*</font></label>
            	<input type = "text" pattern = "[0-9]*" required name = "loanamount" class ="form-control" autocomplete = "off" placeholder = "Enter amount">
          	</div>
          	<div class="form-group">
            	 <label for="usrname"> Reason <font color = "red">*</font></label>
            	<input type = "text" id = "petamount" required name = "loanreason" class ="form-control" autocomplete = "off" placeholder = "Enter reason">
          	</div>
          	<div class="form-group">
            	<label for="usrname"> Duration <font color = "red">*</font></label>
            	<select name = "loanduration" class="form-control" id = "loanduration" required>
					<option value = ""> ----------- </option>
					<option value = "1"> 1 Month </option>
					<option value = "2"> 2 Months </option>
					<option value = "3"> 3 Months </option>
					<option value = "Others"> Others </option>
				</select>
            </div>
            <div class="form-group">
            	<label>Others</label>
				<input type =  "text" maxlength="2" class="form-control" name = "loanothers" disabled=""/>
            </div>
            <div class="form-group">
				<label>Payment Start (Month)</label>
				<select class="form-control" name = "cutoffmonth" required >
					<option value="">-----------</option>
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Day)</label>
				<select class="form-control" name = "cutoffday">
					<option value=""> - - - - - - - </option>
					<option value="01">01</option>
					<option value="16">16</option>
				</select>
			</div>
			<div class="form-group">
				<label>Payment Start (Year)</label>
				<select class="form-control" required name = "cutofyr">
					<option value=""> - - - - - - - </option>
					<option value="<?php echo date("Y");?>"><?php echo date("Y");?></option>
					<option value="<?php echo date("Y", strtotime("+1 year"));?>"><?php echo date("Y", strtotime("+1 year"));?></option>
				</select>
			</div>
			<div class="form-group">
				<p style="color: red; font-size: 12px;">
					<b>Example: <br>
						Your Payment Start is Feb 01, 2016 <br>
						Covered Cutoff is Feb 01 - Feb 15, 2016</b>
				</p>
			</div>
              <button type="submit" name = "penaltysub" onclick="return confirm('Are you sure?');" class="btn btn-success btn-block">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>      
    </div>
  </div> 
  <?php
	if(isset($_POST['penaltysub']) && isset($_SESSION['acc_id'])){
		$accid = $_SESSION['acc_id'];
		$state = "UALoan";
		$loandate = date("Y-m-d"); 
		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
		$query = "SELECT * FROM loan_cutoff,loan where loan_cutoff.account_id = '$accid' and loan.account_id = '$accid' and loan_cutoff.loan_id = loan.loan_id and ( ( loan.state != 'DECLoan' ) and (CURDATE() <= enddate and loan_cutoff.state != 'Full' and loan_cutoff.state != 'Cancel' and loan_cutoff.state != 'Advance') ) and loan.penalty = 1";
		$resquery = $conn->query($query);
		$sqlxx = "SELECT * FROM loan where account_id = '$accid' and state = 'UALoan'";
		$resqueryxx = $conn->query($sqlxx);
		$date = date("Y-m-d");
		if($date > date('Y-m-16')){
			$date = date("Y-m-01", strtotime("next month"));
			$date2 = date("Y-m-16");
		}else{
			$date = date("Y-m-16");
			$date2 = date("Y-m-01");
		}
		$query2 = "SELECT * FROM cashadv where account_id = '$accid' and state != 'DACA' and cadate < '$date' and cadate > '$date2'";
		$resquery2 = $conn->query($query2);
		if($_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'] < date("Y-m-d")){
			echo '<script type="text/javascript">alert("Wrong date.");window.location.replace("employee.php?ac=penloan"); </script>';
		}elseif(($resquery->num_rows > 0) || ($resqueryxx->num_rows > 0)){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending penalty loan.");window.location.replace("employee.php?ac=penloan"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending penalty loan.");window.location.replace("accounting.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending penalty loan.");window.location.replace("techsupervisor.php?ac=penloan"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending penalty loan.");window.location.replace("hr.php?ac=penloan"); </script>';
	    	}
		}elseif($resquery2->num_rows > 0){
			if($_SESSION['level'] == 'EMP'){
    			echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("employee.php?ac=penca"); </script>';
	 	  	}elseif ($_SESSION['level'] == 'ACC') {
	     		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("accounting.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("techsupervisor.php?ac=penca"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending Cash Advance.");window.location.replace("hr.php?ac=penca`"); </script>';
	    	}
		}else{
			if($_POST['loanduration'] == 'Others'){
				$duration = $_POST['loanothers'] . ' Months';
			}else{
				$duration = $_POST['loanduration'] . ' Months';
			}	
			$penalty = 1;
	   		$date = $_POST['cutofyr'] . '-' . $_POST['cutoffmonth'] . '-' . $_POST['cutoffday'];
			$sql = $conn->prepare("INSERT INTO `loan` (account_id, loanamount, loanreason, state, loandate, duration, startdate, penalty) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$sql->bind_param("issssssi", $accid, $_POST['loanamount'], $_POST['loanreason'], $state, $loandate, $duration, $date, $penalty);
			if($sql->execute()){
				if($_SESSION['level'] == 'EMP'){
	    			echo '<script type="text/javascript">window.location.replace("employee.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'ACC') {
		    		echo '<script type="text/javascript">window.location.replace("accounting.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'TECH') {
		    		echo '<script type="text/javascript">window.location.replace("techsupervisor.php?ac=penloan"); </script>';
		    	}elseif ($_SESSION['level'] == 'HR') {
		    		echo '<script type="text/javascript">window.location.replace("hr.php?ac=penloan"); </script>';
		    	}
			}
		}

	}

?>