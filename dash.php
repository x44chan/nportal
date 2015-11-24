	<form role = "form">
		<table class = "table table-hover" align = "center">
			<thead>
				<tr>
					<td colspan = 7 align = center><h2> Admin Dashboard </h2></td>
				</tr>
				<tr>
					<th>Date File</th>					
					<th>Name of Employee</th>
					<th>Type</th>
					<th>Reason</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
		<?php
			
				
			include('header.php');
			include('conf.php');
			$sql = "SELECT dash.type_id, overtime.state, overtime.overtime_id as otid from dash,overtime where dash.type_id = overtime.overtime_id and overtime.state = 'UA' ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){			
				while($row = $result->fetch_assoc()){
					
					echo '<td width = "200">
							<a href = "approval.php?approve=A&ot='.$row['otid'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&ot='.$row['otid'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			/*
			$sql = "SELECT login.fname as xfname, login.lname as lname, 
						officialbusiness.obdate as obdate, officialbusiness.obreason as obreason,
						overtime.datefile as otdate, overtime.reason as otreason,
						
						overtime.account_id as otid, nleave.account_id, officialbusiness.account_id as obaccid ,undertime.account_id 
					FROM overtime,login,nleave,officialbusiness,undertime 
					where login.account_id = overtime.account_id 
						and login.account_id = nleave.account_id 
						and login.account_id = officialbusiness.account_id 
						and login.account_id = undertime.account_id 
						and undertime.state = 'UA'
						and nleave.state = 'UA'
						and officialbusiness.state = 'UA'
						and overtime.state = 'UA' ORDER BY obdate ASC, otdate DESC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					if($row['obdate'] != ""){
						$originalDate = date($row['obdate']);
						$newDate = date("F j, Y", strtotime($originalDate));
						echo '<tr><td>'. $newDate .'</td>';
						echo '<td>'.$row['obreason'].'</td>';
						echo '<td>Official Business</td>';
						echo '<td>'.$row['obreason'].'</td>';
						echo '<td width = "200">
							<a href = "approval.php?approve=A&officialbusiness_id='.$row['obaccid'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&officialbusiness_id='.$row['obaccid'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
					}
						
					if($row['otid'] != ""){
						$originalDate = date($row['otdate']);
						$newDate = date("F j, Y", strtotime($originalDate));
						echo '<tr><td>'. $newDate .'</td>';
						echo '<td>'.$row['otreason'].'</td>';
						echo '<td>OT</td>';
						echo '<td>'.$row['obreason'].'</td>';
						echo '<td width = "200">
							<a href = "approval.php?approve=A&officialbusiness_id='.$row['obaccid'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&officialbusiness_id='.$row['obaccid'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
					}
				}
			}
			/*
			$sql = "SELECT * from overtime,login where login.account_id = overtime.account_id and state = 'UA' ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				$datetoday = date("Y-m-d");
				if($datetoday >= $row['twodaysred'] ){
					echo '<tr style = "color: red">';
				}else{
					echo '<tr>';
				}
				while($row = $result->fetch_assoc()){
					$originalDate = date($row['obdate']);
					$newDate = date("F j, Y", strtotime($originalDate));	
					echo '<tr><td>'.$newDate .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>OT</td>';
					echo '<td>'.$row['reason'].'</td>';
					echo '<td width = "200">
							<a href = "approval.php?approve=A&overtime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&overtime='.$row['overtime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from undertime,login where login.account_id = undertime.account_id and state = 'UA' ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo '<tr><td>'.$row['datefile'] .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>Undertime</td>';
					echo '<td>'.$row['reason'].'</td>';
					echo '<td width = "200">
							<a href = "approval.php?approve=A&undertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&undertime='.$row['undertime_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from officialbusiness,login where login.account_id = officialbusiness.account_id and state = 'UA' ORDER BY obdate ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo '<tr><td>'.$row['obdate'] .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>Official Business</td>';
					echo '<td>'.$row['obreason'].'</td>';
					echo '<td width = "200">
							<a href = "approval.php?approve=A&officialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&officialbusiness_id='.$row['officialbusiness_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}
			$sql = "SELECT * from nleave,login where login.account_id = nleave.account_id and state = 'UA' ORDER BY datefile ASC";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					echo '<tr><td>'.$row['datefile'] .'</td>';
					echo '<td>'.$row['fname'] .' ' .$row['lname'] .'</td>';
					echo '<td>'.$row['typeoflea']. ' ' .$row['othersl']. '</td>';
					echo '<td>'.$row['reason'].'</td>';
					echo '<td width = "200">
							<a href = "approval.php?approve=A&leave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-info" role="button">Approve</a>
							<a href = "approval.php?approve=DA&leave='.$row['leave_id'].'"';?><?php echo'" class="btn btn-info" role="button">Disapprove</a>
						</td></tr>';
				}
			}*/
		?>
		</tbody>
		</table>
	</form>