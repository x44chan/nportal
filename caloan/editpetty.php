<?php
	$accid = $_SESSION['acc_id'];
	$petid = mysql_escape_string($_GET['editpetty']);
	include("conf.php");
	$sql = "SELECT * FROM petty,login where login.account_id = $accid and petty.account_id = $accid and petty_id = '$petid' and (state = 'UAPetty' or state = 'UATransfer') order by state ASC, source asc";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
<div class="container" style="padding: 2px 4px;">
	<div class="row">
		<div class="col-xs-12" align="center">
			<u><i><h3>Edit Petty</h3></i></u>
			<hr>
		</div>
	</div>
<?php
	while ($row = $result->fetch_assoc()) {
		$proj = "SELECT * FROM `project` where name = '$row[project]'";
		$resproj = $conn->query($proj)->fetch_assoc();
?>
<form action = "" method="post">
	<div class="row">
		<div class="col-xs-3">
			<label>Name</label>
			<i><p style="margin-left: 10px;"><?php echo $row['fname'] . ' ' . $row['lname'];?></p></i>
		</div>
		<div class="col-xs-3">
			<label>Particular</label>
			<select class="form-control" name = "upparti">
				<option value=""> ----------- </option>
				<option value = "Cash" <?php if($row['particular'] == "Cash"){ echo ' selected '; }?>> Cash </option>
				<option value="Check" <?php if($row['particular'] == "Check"){ echo ' selected '; }?>> Check </option>
				<option value="Transfer" <?php if($row['particular'] == "Transfer"){ echo ' selected '; }?>> Transfer </option>
				<option value="Transfer" <?php if($row['particular'] == "Auto Debit"){ echo ' selected '; }?>> Auto Debit </option>
			</select>
		</div>
		<div class="col-xs-3">
			<label>Update Amount</label>
			<input type="text" class="form-control" value="<?php echo $row['amount'];?>" name = "upamount" id = "uppet" pattern = "[0-9,.]*">
		</div>
		<div class="col-xs-3">
			<label>Reason</label>
			<textarea name = "upreason" class="form-control"><?php echo $row['petreason'];?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-3 col-xs-offset-3">
			<label>Type </label>
      		<select class="form-control" name = "pettype" required>
      			<option value=""> - - - - - - - </option>
      			<option <?php if($row['projtype'] == 'P.M.'){ echo ' selected '; } ?> value="P.M."> P.M. </option>
      			<option <?php if($row['projtype'] == 'Internet'){ echo ' selected '; } ?> value="Internet"> Internet </option>
      			<option <?php if($row['projtype'] == 'Project'){ echo ' selected ';} ?> value="Project"> Project </option>
      			<option <?php if($row['projtype'] == 'Support'){ echo ' selected ';} ?> value="Support"> Project Support </option>
      			<option <?php if($row['projtype'] == 'Service'){ echo ' selected ';} ?> value="Service"> Service </option>
                        <option <?php if($row['projtype'] == 'Email Hosting'){ echo ' selected ';} ?> value="Email Hosting"> Email Hosting </option>
      			<option <?php if($row['projtype'] == 'Combined'){ echo ' selected ';} ?> value="Combined"> P.M. & Internet </option>
      			<option <?php if($row['projtype'] == 'Commission Base'){ echo ' selected ';} ?> value="Commission Base"> Commission Base </option>
                        <option <?php if($row['projtype'] == 'Corporate'){ echo ' selected ';} ?> value="Corporate"> Corporate </option>
      			<option <?php if($row['projtype'] == 'Luwas'){ echo ' selected ';} ?> value="Luwas"> Luwas </option>
      			<option <?php if($row['projtype'] == 'Supplier'){ echo ' selected ';} ?> value="Supplier"> Supplier </option>
      			<option <?php if($row['projtype'] == 'Netlink'){ echo ' selected ';} ?> value="Netlink"> Netlink </option>
                        <option <?php if($row['projtype'] == 'Permit & Licenses Netlink'){ echo ' selected ';} ?> value="Permit & Licenses Netlink"> Permit & Licenses Netlink </option>
                        <option <?php if($row['projtype'] == 'ELMS Rental & Electric Bill'){ echo ' selected ';} ?> value="ELMS Rental & Electric Bill"> ELMS Rental & Electric Bill </option>
                        <option <?php if($row['projtype'] == 'Sotero Molino'){ echo ' selected ';} ?> value="Sotero Molino"> Sotero Molino </option>
                        <?php if($_SESSION['acc_id'] == '37') {  ?>
      				<option <?php if($row['projtype'] == 'House'){ echo ' selected ';} ?>value="House"> House </option>
      			<?php } ?>
      		</select>
		</div>
		<div <?php if($row['projtype'] != 'Combined'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "combined">
                  <div  class="form-group">
                        <label>P.M. & Internet <font color = "red">*</font></label>
                        <select class="form-control" name = "combined">
                              <option value = ""> - - - - - </option>
                              <?php
                                    $xsql = "SELECT * FROM `project` where type = 'Combined' and state = '1'";
                                    $xresult = $conn->query($xsql);
                                    if($xresult->num_rows > 0){
                                          while($xrow = $xresult->fetch_assoc()){
                                                if($row['project'] == $xrow['name']){
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
            <div <?php if($row['projtype'] != 'Commission Base'){ echo ' style = "display: none;" ';} ?> class="col-xs-2"  id = "comisiontype">
                  <div  class="form-group">
                        <label>(Bidding/Project) <font color = "red">*</font></label>
                        <select class="form-control" name = "comisiontype">
                              <option value = ""> - - - - - </option>
                              <option value="Bidding" <?php if($row['comtype'] == 'Bidding'){ echo ' selected '; }?>>Bidding</option>
                              <option value="Project" <?php if($row['comtype'] == 'Project'){ echo ' selected '; }?>>Project</option>
                        </select>
                  </div>
            </div>
            <div <?php if($row['projtype'] != 'Commission Base'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "comisionbid">
                  <div  class="form-group">
                        <label>Commission Base (Bidding)<font color = "red">*</font></label>
                        <select class="form-control" name = "comisionbid">
                              <option value = ""> - - - - - </option>
                              <?php
                                    $xsql = "SELECT * FROM `project` where type = 'Commission Base' and comtype = 'Bidding' and state = '1' order by CHAR_LENGTH(name)";
                                    $xresult = $conn->query($xsql);
                                    if($xresult->num_rows > 0){
                                          while($xrow = $xresult->fetch_assoc()){
                                                if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'Project'){ echo ' style = "display: none;" ';} ?> class="col-xs-2"  id = "project">
			<div  class="form-group">
            	<label>Project <font color = "red">*</font></label>
            	<select class="form-control" name = "loc" onchange="showUserx(this.value,'proj','')">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Project' and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				$loc = "";
            				while($xrow = $xresult->fetch_assoc()){
            					$xsql2 = "SELECT loc FROM `project` where type = 'Project' and name = '$row[project]'";
            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
            						$selecteds = ' selected ';		            						
            						$loc = $xresult2['loc'];
            					}else{
            						$selecteds = "";
            					}

            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div <?php if($row['projtype'] != 'Support'){ echo ' style = "display: none;" ';} ?> class="col-xs-2"  id = "support">
			<div  class="form-group">
            	<label>Project Support <font color = "red">*</font></label>
            	<select class="form-control" name = "locx" onchange="showUserx(this.value,'','sup')">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Support' and state = '1' group by loc order by CHAR_LENGTH(loc)";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				$locx = "";
            				while($xrow = $xresult->fetch_assoc()){
            					$xsql2 = "SELECT loc FROM `project` where type = 'Support' and name = '$row[project]'";
            					$xresult2 = $conn->query($xsql2)->fetch_assoc();
            					if($xrow['name'] == $row['project'] || $xresult2['loc'] == $xrow['loc']){
            						$selecteds = ' selected ';		            						
            						$locx = $xresult2['loc'];
            					}else{
            						$selecteds = "";
            					}

            					echo '<option '.$selecteds.' value = "' . $xrow['loc'] . '"> ' . $xrow['loc'] . '</option>';
            				}
            			}
            		?>
            	</select>
            </div>
		</div>
		<div class="col-xs-4" id = "locx">
			<?php if($row['projtype'] == 'Project' || $row['projtype'] == 'Support'){ ?>
		        	<td><b>PO <font color = "red"> * </font></b></td>
		        	<td>
		        		<select name = "otproject" class = "form-control">
		        			<?php
		        				$xtype = $row['projtype'];
		            			$xsql = "SELECT * FROM `project` where type = '$xtype' and state = '1' and (loc = '$loc' or loc = '$locx') order by CHAR_LENGTH(name)";
		            			$xresult = $conn->query($xsql);
		            			if($xresult->num_rows > 0){
		            				while($xrow = $xresult->fetch_assoc()){
		            					if($xrow['name'] == $row['project']){
		            						$selecteds = ' selected ';
		            					}else{
		            						$selecteds = "";
		            					}
		            					echo '<option '.$selecteds.' value = "' . $xrow['name'] . '"> ' . $xrow['name'] . '</option>';
		            				}
		            			}
		            		?>
		        		</select>
		        	</td>
		        	<?php } ?>
	    </div>
		<div <?php if($row['projtype'] != 'P.M.'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "pm">
			<div class="form-group">
            	<label>P.M. <font color = "red">*</font></label>
            	<select class="form-control" name = "pm">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'P.M.' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'Corporate'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "corpo">
                  <div class="form-group">
                  <label>Corporate <font color = "red">*</font></label>
                  <select class="form-control" name = "corpo">
                        <option value = ""> - - - - - </option>
                        <?php
                              $xsql = "SELECT * FROM `project` where type = 'Corporate' and state = '1'";
                              $xresult = $conn->query($xsql);
                              if($xresult->num_rows > 0){
                                    while($xrow = $xresult->fetch_assoc()){
                                          if($row['project'] == $xrow['name']){
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
            <div <?php if($row['projtype'] != 'Email Hosting'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "ehosting">
                  <div class="form-group">
                        <label>Email Hosting <font color = "red">*</font></label>
                        <select class="form-control" name = "ehosting">
                              <option value = ""> - - - - - </option>
                              <?php
                                    $xsql = "SELECT * FROM `project` where type = 'Email Hosting' and state = '1'";
                                    $xresult = $conn->query($xsql);
                                    if($xresult->num_rows > 0){
                                          while($xrow = $xresult->fetch_assoc()){
                                                if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'Supplier'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "supp">
			<div class="form-group">
            	<label>Supplier <font color = "red">*</font></label>
            	<select class="form-control" name = "supp">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Supplier' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'Service'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "oncallxx">
			<div class="form-group">
            	<label>Service <font color = "red">*</font></label>
            	<select class="form-control" name = "oncall">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'On Call' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'Internet'){ echo ' style = "display: none;" ';} ?> class="col-xs-4" id = "internet">
			<div  class="form-group">
            	<label>Internet <font color = "red">*</font></label>
            	<select class="form-control" name = "internet">
            		<option value = ""> - - - - - </option>
            		<?php
            			$xsql = "SELECT * FROM `project` where type = 'Internet' and state = '1'";
            			$xresult = $conn->query($xsql);
            			if($xresult->num_rows > 0){
            				while($xrow = $xresult->fetch_assoc()){
            					if($row['project'] == $xrow['name']){
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
		<div <?php if($row['projtype'] != 'House'){ echo ' style = "display: none;" ';} ?> class="col-xs-4"  id = "house">
				<div  class="form-group">
	            	<label>Project <font color = "red">*</font></label>
	            	<select class="form-control" name = "house">
	            		<option value = ""> - - - - - </option>
	            		<option <?php if($row['project'] == 'GROCERIES'){ echo ' selected ';} ?>value = "GROCERIES"> GROCERIES </option>
	            		<option <?php if($row['project'] == 'FOODS'){ echo ' selected ';} ?>value = "FOODS"> FOODS </option>
	            		<option <?php if($row['project'] == 'REPRESENTATION'){ echo ' selected ';} ?>value = "REPRESENTATION"> REPRESENTATION </option>
	            		<option <?php if($row['project'] == 'MEDICINES'){ echo ' selected ';} ?>value = "MEDICINES"> MEDICINES </option>
	            		<option <?php if($row['project'] == 'ANIMALS'){ echo ' selected ';} ?>value = "ANIMALS"> ANIMALS </option>
	            	</select>
	            </div>
			</div>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-xs-12" align="center">
			<button class="btn btn-primary" name = "uppetty"> Update Petty </button>
			<a href = "?ac=penpty" class="btn btn-danger"> Back </a>
		</div>
	</div>
</form>
<?php
	}

echo '</div>';
}else{
	echo '<script type="text/javascript">window.location.replace("?ac=penpty"); </script>';
}
	if(isset($_POST['uppetty'])){
		$sql = "SELECT * FROM petty,login where login.account_id = '$_SESSION[acc_id]' and login.position != 'House Helper' and petty.account_id = '$_SESSION[acc_id]' and (petty.state != 'DAPetty' and petty.state != 'CPetty') and petty_id != '$petid' order by state ASC, source asc";
		$result = $conn->query($sql);
		$count = 0;
		$day5 = 0;
		$projectcount = 0;
		if($result->num_rows > 0){	
			while($row = $result->fetch_assoc()){
			$petid = $row['petty_id'];
			$sql = "SELECT * FROM `petty`,`petty_liqdate` where petty.petty_id = '$petid' and petty_liqdate.petty_id = '$petid'";
			$data = $conn->query($sql)->fetch_assoc();
				if($data['petty_id'] == null){
					if($row['projtype'] == 'Commission Base' || $row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier' || $row['projtype'] == 'Support' || $row['projtype'] == 'Email Hosting' || $row['projtype'] == 'Permit & Licenses Netlink' || $row['projtype'] == 'ELMS Rental & Electric Bill'){
						$projectcount += 1;
					}					
					if($row['appdate'] != "0000-00-00 00:00:00" && date("Y-m-d",strtotime("+6 days", strtotime($row['appdate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+6 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
				}
				if($data['liqstate'] == 'LIQDATE'){
					if($row['appdate'] != "0000-00-00 00:00:00" && date("Y-m-d",strtotime("+6 days", strtotime($row['appdate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+6 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Commission Base' || $row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier' || $row['projtype'] == 'Support' || $row['projtype'] == 'Email Hosting' || $row['projtype'] == 'Permit & Licenses Netlink' || $row['projtype'] == 'ELMS Rental & Electric Bill'){
						$projectcount += 1;
					}
				}
				if($data['liqstate'] == 'EmpVal'){
					if($row['appdate'] != "0000-00-00 00:00:00" && date("Y-m-d",strtotime("+6 days", strtotime($row['appdate']))) <= date("Y-m-d")){
						$day5 += 1;
					}elseif(date("Y-m-d",strtotime("+6 days", strtotime($row['date']))) <= date("Y-m-d")){
						$day5 += 1;
					}
					if($row['projtype'] == 'Commission Base' || $row['projtype'] == 'Project' || $row['projtype'] == 'Support' || $row['projtype'] == 'Corporate' || $row['projtype'] == 'Netlink' || $row['projtype'] == 'Luwas' || $row['projtype'] == 'Supplier' || $row['projtype'] == 'Support' || $row['projtype'] == 'Email Hosting' || $row['projtype'] == 'Permit & Licenses Netlink' || $row['projtype'] == 'ELMS Rental & Electric Bill'){
						$projectcount += 1;
					}
				}
		   }
		}

		$upparti = mysqli_real_escape_string($conn, $_POST['upparti']);
		$upamount =  mysqli_real_escape_string($conn, $_POST['upamount']);
		$upreason = mysqli_real_escape_string($conn, $_POST['upreason']);
		$pettype = mysqli_real_escape_string($conn, $_POST['pettype']);
		if(isset($_POST['pettype'])){
			if($_POST['pettype'] == 'Project' || $_POST['pettype'] == 'Support'){
				$project = $_POST['otproject'];
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'Corporate'){
                        $project = $_POST['corpo'];
                        if($projectcount > 0){
                              $count = 1;
                        }else{
                              $count = 0;
                        }
                  }elseif($_POST['pettype'] == 'Email Hosting'){
                        $project = $_POST['ehosting'];
                        if($projectcount > 0){
                              $count = 1;
                        }else{
                              $count = 0;
                        }
                  }elseif($_POST['pettype'] == 'Supplier'){
				$project = $_POST['supp'];
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'P.M.'){
				$project = $_POST['pm'];	
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}			
			}elseif($_POST['pettype'] == 'Internet'){
				$project = $_POST['internet'];
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}elseif($_POST['pettype'] == 'House'){
				$project = $_POST['house'];
			}elseif($_POST['pettype'] == 'Combined'){
				if($day5 > 0){
					$count = 1;
				}else{
					$count = 0;
				}
				$project = $_POST['combined'];
			}elseif($_POST['pettype'] == 'Service'){
                        if($day5 > 0){
                              $count = 1;
                        }else{
                              $count = 0;
                        }
                        $project = $_POST['oncall'];
                  }elseif($_POST['pettype'] == 'Commission Base'){
                        if($projectcount > 0){
                              $count = 1;
                        }else{
                              $count = 0;
                        }
                        if(isset($_POST['comisionbid']) && !empty($_POST['comisionbid'])){
                              $_POST['project'] = $_POST['comisionbid'];
                        }elseif(isset($_POST['comisionproj']) && !empty($_POST['comisionproj'])){
                              $_POST['project'] = $_POST['comisionproj'];
                        }
                  }else{
				$project = null;
				if($projectcount > 0){
					$count = 1;
				}else{
					$count = 0;
				}
			}	
		}
		if($_POST['pettype'] == "" || ($_POST['pettype'] != 'Netlink' && $_POST['pettype'] != 'Luwas' && $_POST['pettype'] != 'Supplier' && $_POST['pettype'] != 'Permit & Licenses Netlink' && $project == "")){
			echo '<script>alert("Empty");window.location.href="?editpetty='.$petid.'";</script>';
			break;		
		}
		if($upparti == 'Transfer'){
			$state = 'UATransfer';	
		}else{
			$state = 'UAPetty';
		}
            if($_SESSION['level'] == 'ACC'){
                  $count = 0;
            }
		$petid = mysql_escape_string($_GET['editpetty']);
		$sql = "UPDATE `petty` set projtype = '$pettype', project = '$project', amount = '$upamount', particular = '$upparti', petreason = '$upreason', state = '$state' where account_id = '$accid' and petty_id = '$petid' and (state = 'UAPetty' or state = 'UATransfer')";
		if($count == 0){
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
		}else{
			if($_SESSION['level'] == 'EMP'){
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("employee.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'ACC') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("accounting.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'TECH') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("techsupervisor.php?ac=penpty"); </script>';
	    	}elseif ($_SESSION['level'] == 'HR') {
	    		echo '<script type="text/javascript">alert("You still have pending liquidate");window.location.replace("hr.php?ac=penpty"); </script>';
	    	}
		}
	}
?>