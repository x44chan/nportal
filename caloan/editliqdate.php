<?php
	$petid = mysql_escape_string($_GET['editliqdate']);
	$accid = $_SESSION['acc_id'];
	$sql = "SELECT * FROM `petty_liqdate` where petty_id = '$petid' and account_id = '$accid' and (liqstate = 'LIQDATE' or liqstate = 'AdmnApp')";
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

		<form action = "" method="post">
		<div class="row">
			<div class="col-xs-3">
				<label>Type </label>
	      		<select class="form-control" name = "pettype" required>
	      			<option value=""> - - - - - - - </option>
	      			<option <?php if($data['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
	      			<option <?php if($data['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
	      			<option <?php if($data['projtype'] == 'Project'){ echo ' selected ';} ?>value="Project"> Project </option>
	      			<option <?php if($data['projtype'] == 'Support'){ echo ' selected ';} ?>value="Support"> Project Support </option>	      			
      				<option <?php if($data['projtype'] == 'Service'){ echo ' selected ';} ?> value="Service"> Service </option>	      			
	      			<option <?php if($data['projtype'] == 'Email Hosting'){ echo ' selected ';} ?> value="Email Hosting"> Email Hosting </option>
	      			<option <?php if($data['projtype'] == 'Combined'){ echo ' selected ';} ?>value="Combined"> P.M. & Internet </option>
	      			<option <?php if($data['projtype'] == 'Commission Base'){ echo ' selected ';} ?> value="Commission Base"> Commission Base </option>
	      			<option <?php if($data['projtype'] == 'Corporate'){ echo ' selected ';} ?> value="Corporate"> Corporate </option>
	      			<option <?php if($data['projtype'] == 'Luwas'){ echo ' selected ';} ?>value="Luwas"> Luwas </option>
	      			<option <?php if($data['projtype'] == 'Supplier'){ echo ' selected ';} ?>value="Supplier"> Supplier </option>
	      			<option <?php if($data['projtype'] == 'Netlink'){ echo ' selected ';} ?>value="Netlink"> Netlink </option>
	      			<option <?php if($data['projtype'] == 'Permit & Licenses Netlink'){ echo ' selected ';} ?>value="Permit & Licenses Netlink"> Permit & Licenses Netlink </option>
	      			<option <?php if($data['projtype'] == 'ELMS Rental & Electric Bill'){ echo ' selected ';} ?>value="ELMS Rental & Electric Bill"> ELMS Rental & Electric Bill </option>
	      			<option <?php if($data['projtype'] == 'Sotero Molino'){ echo ' selected ';} ?>value="Sotero Molino"> Sotero Molino </option>
	      			<option <?php if($data['projtype'] == 'Company Vehicle'){ echo ' selected ';} ?> value="Company Vehicle"> Company Vehicle </option>
                    <option <?php if($data['projtype'] == 'Social Payments'){ echo ' selected ';} ?> value="Social Payments"> Social Payments </option>
                    <?php if($_SESSION['acc_id'] == '37') {  ?>
	      			<option <?php if($data['projtype'] == 'House'){ echo ' selected ';} ?>value="House"> House </option>
	      			<?php } ?>
	      		</select>
			</div>
			<div <?php if($data['projtype'] != 'Company Vehicle'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "com_car">
                  <div  class="form-group">
                        <label>Company Vehicle <font color = "red">*</font></label>
                        <select class="form-control" name = "com_car">
                              <option value = ""> - - - - - </option>
                              <option <?php if($data['project'] == 'Mitsubishi L300-NJ 9174'){ echo ' selected '; } ?> value="Mitsubishi L300-NJ 9174"> Mitsubishi L300-NJ 9174 </option>
                              <option <?php if($data['project'] == 'Mitsubishi L300-WMO 916'){ echo ' selected '; } ?> value="Mitsubishi L300-WMO 916"> Mitsubishi L300-WMO 916 </option>
                              <option <?php if($data['project'] == "Mitsubishi L300-ABL3130"){ echo ' selected '; } ?> value="Mitsubishi L300-ABL3130"> Mitsubishi L300-ABL3130 </option>
                              <option <?php if($data['project'] == "Honda City DV 0616"){ echo ' selected '; } ?> value="Honda City DV 0616"> Honda City DV 0616 </option>
                              <option <?php if($data['project'] == "Ford Everest- UQZ 974"){ echo ' selected '; } ?> value="Ford Everest- UQZ 974"> Ford Everest- UQZ 974 </option>
                        </select>
                  </div>
            </div>
            <div <?php if($data['projtype'] != 'Social Payments'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "social">
                  <div  class="form-group">
                        <label>Social Payments <font color = "red">*</font></label>
                        <select class="form-control" name = "social">
                              <option value = ""> - - - - - </option>
                              <option <?php if($data['project'] == 'SSS'){ echo ' selected '; } ?>value="SSS"> SSS </option>
                              <option <?php if($data['project'] == 'Phic'){ echo ' selected '; } ?>value="Phic"> Phic </option>
                              <option <?php if($data['project'] == 'HDMF'){ echo ' selected '; } ?>value="HDMF"> HDMF </option>
                        </select>
                  </div>
            </div>
			<div <?php if($data['projtype'] != 'Project'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "project">
				<div  class="form-group">
	            	<label>Project <font color = "red">*</font></label>
	            	<select class="form-control" name = "project">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Commission Base'){ echo ' style = "display: none;" ';} ?> class="col-xs-2"  id = "comisiontype">
                  <div  class="form-group">
                        <label>(Bidding/Project) <font color = "red">*</font></label>
                        <select class="form-control" name = "comisiontype">
                              <option value = ""> - - - - - </option>
                              <option value="Bidding" <?php if($data['comtype'] == 'Bidding'){ echo ' selected '; }?>>Bidding</option>
                              <option value="Project" <?php if($data['comtype'] == 'Project'){ echo ' selected '; }?>>Project</option>
                        </select>
                  </div>
            </div>
            <div <?php if($data['projtype'] == 'Commission Base' && $data['comtype'] == 'Bidding'){ }else{echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "comisionbid">
                  <div  class="form-group">
                        <label>Commission Base <font color = "red">*</font></label>
                        <select class="form-control" name = "comisionbid">
                              <option value = ""> - - - - - </option>
                              <?php
                                    $xsql = "SELECT * FROM `project` where type = 'Commission Base' and comtype = 'Bidding' and state = '1' order by CHAR_LENGTH(name)";
            						$xresult = $conn->query($xsql);
                                    if($xresult->num_rows > 0){
                                          while($xrow = $xresult->fetch_assoc()){
                                                if($data['project'] == $xrow['name']){
                                                      $selected = ' selected ';
                                                }else{
                                                      $selected = "";
                                                }
                                                echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
                                          }
                                    }
                              ?>
                        </select>
                  </div>
            </div>
            <div <?php if($data['projtype'] == 'Commission Base' && $data['comtype'] == 'Project'){ }else{echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "comisionproj">
                  <div  class="form-group">
                        <label>Commission Base <font color = "red">*</font></label>
                        <select class="form-control" name = "comisionproj">
                              <option value = ""> - - - - - </option>
                              <?php
                                    $xsql = "SELECT * FROM `project` where type = 'Commission Base' and comtype = 'Project' and state = '1' order by CHAR_LENGTH(name)";
            						$xresult = $conn->query($xsql);
                                    if($xresult->num_rows > 0){
                                          while($xrow = $xresult->fetch_assoc()){
                                                if($data['project'] == $xrow['name']){
                                                      $selected = ' selected ';
                                                }else{
                                                      $selected = "";
                                                }
                                                echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
                                          }
                                    }
                              ?>
                        </select>
                  </div>
            </div>
			<div <?php if($data['projtype'] != 'Service'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "oncallxx">
			<div class="form-group">
            	<label>Service <font color = "red">*</font></label>
            	<select class="form-control" name = "oncall">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($data['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div <?php if($data['projtype'] != 'Email Hosting'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "ehosting">
			<div class="form-group">
            	<label>Email Hosting <font color = "red">*</font></label>
            	<select class="form-control" name = "ehosting">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Email Hosting' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($data['project'] == $xrow['name']){
            						$selected = ' selected ';
            					}else{
            						$selected = "";
            					}
            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
			<div <?php if($data['projtype'] != 'Combined'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "combined">
				<div  class="form-group">
	            	<label>Project <font color = "red">*</font></label>
	            	<select class="form-control" name = "combined">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Combined' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Support'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "support">
				<div  class="form-group">
	            	<label>Project Support<font color = "red">*</font></label>
	            	<select class="form-control" name = "support">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Support' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<?php if($_SESSION['acc_id'] == '37'){ ?>
			<div <?php if($data['projtype'] != 'House'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "house">
				<div  class="form-group">
	            	<label>House <font color = "red">*</font></label>
	            	<select class="form-control" name = "house">
	            		<option value = ""> - - - - - </option>
	            		<option <?php if($data['project'] == 'GROCERIES'){ echo ' selected ';} ?>value = "GROCERIES"> GROCERIES </option>
	            		<option <?php if($data['project'] == 'FOODS'){ echo ' selected ';} ?>value = "FOODS"> FOODS </option>
	            		<option <?php if($data['project'] == 'REPRESENTATION'){ echo ' selected ';} ?>value = "REPRESENTATION"> REPRESENTATION </option>
	            		<option <?php if($data['project'] == 'MEDICINES'){ echo ' selected ';} ?>value = "MEDICINES"> MEDICINES </option>
	            		<option <?php if($data['project'] == 'ANIMALS'){ echo ' selected ';} ?>value = "ANIMALS"> ANIMALS </option>
	            	</select>
	            </div>
			</div>
			<?php } ?>
			<div <?php if($data['projtype'] != 'P.M.'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "pm">
				<div class="form-group">
	            	<label>P.M. <font color = "red">*</font></label>
	            	<select class="form-control" name = "pm">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Combined'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "combined">
				<div class="form-group">
	            	<label>P.M. & Internet <font color = "red">*</font></label>
	            	<select class="form-control" name = "combined">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Combined' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Corporate'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "corpo">
				<div class="form-group">
	            	<label>Corporate <font color = "red">*</font></label>
	            	<select class="form-control" name = "corpo">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Corporate' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Supplier'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "supp">
				<div class="form-group">
	            	<label>Supplier <font color = "red">*</font></label>
	            	<select class="form-control" name = "supp">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Supplier' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
			<div <?php if($data['projtype'] != 'Internet'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "internet">
				<div  class="form-group">
	            	<label>Internet <font color = "red">*</font></label>
	            	<select class="form-control" name = "internet">
	            		<option value = ""> - - - - - </option>
	            		<?php
	            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1'";
	            			$xresult = $conn->query($xsql);
	            			if($xresult->num_rows > 0){
	            				while($xrow = $xresult->fetch_assoc()){
	            					if($data['project'] == $xrow['name']){
	            						$selected = ' selected ';
	            					}else{
	            						$selected = "";
	            					}
	            					echo '<option '.$selected .'value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<hr>
			</div>
		</div>
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
					$sqls = "SELECT * FROM `petty_type` ORDER BY type ASC";
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
			<!--<div class="col-xs-3">
				<label>Others</label>
				<input type = "text" class="form-control input-md" id = "others<?php echo $i;?>" name = "others<?php echo $i;?>" value = "<?php echo $row['liqothers'];?>" placeholder = "Others" <?php if($row['liqtype'] != "Others"){ echo ' disabled ';}?>>
			</div>-->
			<div class="col-xs-4">
				<label>Amount</label>
				<input required  autocomplete = 'off' pattern = "[0-9.]*" value = "<?php echo $row['liqamount'];?>"  class = "form-control input-md" type = "text" id = "amount<?php echo $i;?>" name = "amount<?php echo $i;?>" placeholder = "Enter Amount"/>
			</div>
			<div class="col-xs-5">
				<label>Transaction</label>
				<textarea required  autocomplete = 'off' class="form-control input-md" value = "<?php echo $row['liqinfo'];?>" name = "trans<?php echo $i;?>" placeholder = "Transaction Info"><?php echo $row['liqinfo'];?></textarea>
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
					<small><strong>Not enought Petty Fund!</strong> check your liquidation.</small>
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
		$_GET['editliqdate'] = mysqli_real_escape_string($conn, $_GET['editliqdate']);
		$sql = "SELECT * FROM `petty` where petty_id = '$petid' and account_id = '$_SESSION[acc_id]'";
		$data = $conn->query($sql)->fetch_assoc();
		$amount = 0;
		for($i = 1; $i <= $_POST['counter']; $i++){
			if(!isset($_POST['amount'.$i])){

			}else{
				$amount += $_POST['amount'.$i];
			}
		}
		if(str_replace(",","", number_format($amount,2)) > str_replace(",","", $data['amount'])) {
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
						where petty_id = '$petid' and account_id = '$accid' and (liqstate = 'LIQDATE' or liqstate = 'AdmnApp') and liqdate_id = '$liqid'";
				if($conn->query($stmt) == TRUE){
			    	$count += 1;		    	
				}
			}
			
			//echo $project . ' ' . $_POST['pettype'] . ' ' . $_GET['editliqdate'];
			if($count>0){
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
				if(!empty($_POST['pettype'])){
					$pettype = mysqli_real_escape_string($conn, $_POST['pettype']);
					if(isset($_POST['pettype'])){
						if($_POST['pettype'] == 'Project'){
							$project = $_POST['project'];
						}elseif($_POST['pettype'] == 'Support'){
							$project = $_POST['support'];
						}elseif($_POST['pettype'] == 'P.M.'){
							$project = $_POST['pm'];
						}elseif($_POST['pettype'] == 'Internet'){
							$project = $_POST['internet'];
						}elseif($_POST['pettype'] == 'House'){
							$project = $_POST['house'];
						}elseif($_POST['pettype'] == 'Combined'){
							$project = $_POST['combined'];
						}elseif($_POST['pettype'] == 'Service'){
							$project = $_POST['oncall'];
						}elseif($_POST['pettype'] == 'Corporate'){
							$project = $_POST['corpo'];
						}elseif($_POST['pettype'] == 'Supplier'){
							$project = $_POST['supp'];
						}elseif($_POST['pettype'] == 'Commission Base'){
	                        if(isset($_POST['comisionbid']) && !empty($_POST['comisionbid'])){
	                              $project = $_POST['comisionbid'];
	                        }elseif(isset($_POST['comisionproj']) && !empty($_POST['comisionproj'])){
	                              $project = $_POST['comisionproj'];
	                        }
		                }elseif($_POST['pettype'] == 'Email Hosting'){
							$project = $_POST['ehosting'];
						}elseif($_POST['pettype'] == 'Company Vehicle'){		                        
		                    $project = $_POST['com_car'];
		                }elseif($_POST['pettype'] == 'Social Payments'){		                       
		                    $project = $_POST['social'];
		                }else{
							$project = null;
						}
					}
					$stmt2 = $conn->prepare("UPDATE `petty` set project = ?, projtype = ?, comtype = ? where petty_id = ?");
					$stmt2->bind_param("sssi", $project, $pettype, $_POST['comisiontype'], $_GET['editliqdate']);
					if($stmt2->execute()){
						$count += 1;
					}else{
						$conn->error();
					}
				}
				echo '<script type="text/javascript">alert("Edit successful"); window.location.replace("?ac=penpty"); </script>';
			}
		}
	}
?>

<script type="text/javascript">
<?php
	$sqls = "SELECT * FROM `petty_type` ORDER BY type ASC";
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
        	$("#xchange").text(number_format(amount2,2));
	    	$("#xused").text(number_format(sum, 2));
        	n = sum.toFixed(2);
	    	if(amount.replace(',', "") < parseFloat(n)){			    	    		
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
		$('#addr'+i).html("<div class='col-xs-3'><label>Type</label><select required class='form-control' name = 'type"+ i +"' id = 'type"+ i +"'><option value=''> - - - - - </option><?php while ($rows = $results->fetch_assoc()) {echo '<option value = \"' . $rows['type'] . '\">' .$rows['type'].'</option>';}?></select></div><div class='col-xs-4'><label>Amount</label><input required placeholder = 'Enter Amount'  autocomplete = 'off' class = 'form-control input-md' type = 'text' id = 'amount"+i+"' name = 'amount"+i+"' pattern = '[0-9.]*'/></div><div class='col-xs-5'><label>Transaction</label><textarea required type = 'text' class='form-control' name = 'trans"+i+"' placeholder = 'Transaction Info' autocomplete = 'off'></textarea></div>");
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
    	    	$("#xchange").text(number_format(amount2,2));
    	    	$("#xused").text(number_format(sum, 2));
    	    	n = sum.toFixed(2);
    	    	if(amount.replace(',', "") < parseFloat(n)){		    	    		
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
			    	    	$("#xchange").text(number_format(amount2,2));
	    	    			$("#xused").text(number_format(sum, 2));
			    	    	n = sum.toFixed(2);
			    	    	if(amount.replace(',', "") < parseFloat(n)){			    	    		
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
	    	    	$("#xchange").text(number_format(amount2,2));
	    	    	$("#xused").text(number_format(sum, 2));
	    	    	n = sum.toFixed(2);
	    	    	if(amount.replace(',', "") < parseFloat(n)){			    	    		
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
			    	    	$("#xchange").text(number_format(amount2,2));
	    	    			$("#xused").text(number_format(sum, 2));
			    	    	n = sum.toFixed(2);
			    	    	if(amount.replace(',', "") < parseFloat(n)){			    	    		
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
			    	    	$("#xchange").text(number_format(amount2,2));
			     		})(b);
			   	 	}			   	 	
				});
			}
		});
	});
</script>