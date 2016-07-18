<div class="container-fluid" style="font-size: 13.5px;" align="center">
	<div class="row">
		<div class="col-xs-12" align="center">
			<h4> Aduit Trail </h4>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-2">
			<label><i><u> Username </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Name </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Transaction </u></i></label>
		</div>
		<div class="col-xs-2">
			<label><i><u> Date of Transaction </u></i></label>
		</div>
		<div class="col-xs-4">
			<label><i><u> Details </u></i></label>
		</div>
		<!--<div class="col-xs-1">
			<label><i><u> Unit Name </u></i></label>
		</div>-->
	</div>
	<?php
		include 'conf.php';
		$counter = "SELECT count(*) as total from audit_trail";
		$counter2 = $conn->query($counter)->fetch_assoc();
		$perpage = 25;
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
		$startArticle = ($_GET['page'] - 1) * $perpage;
		$stmtx = "SELECT * FROM audit_trail ORDER BY datetrans DESC LIMIT " . $startArticle . ', ' . $perpage;
		$resultx = $conn->query($stmtx);		
		if($resultx->num_rows > 0){
			while ($row = $resultx->fetch_assoc()) {
	?>
			<div class="row" style="font-size: 12.5px;">
				<div class="col-xs-2">
					<?php echo $row['username']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo $row['realname']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo $row['transaction']; ?>
				</div>
				<div class="col-xs-2">
					<?php echo date("M j, Y h:i:s", strtotime($row['datetrans'])); ?>
				</div>
				<div class="col-xs-4">
					<?php echo $row['transdetail']; ?>
				</div>
				<!--<div class="col-xs-1">
					<?php echo $row['pcname']; ?>
				</div>-->
			</div>
	<?php
			}
		}

	?>
	<div class="row">
		<div class="col-xs-12" align="center">
			<!--<label>Records <?php $startArticlex = $startArticle + 1; $perpagex = $perpage * $_GET['page']; if($perpagex > $counter2['total']){ $perpagex = $counter2['total'];} echo $startArticlex . ' - ' . $perpagex ?> </label><br>-->
			<label> Pages </label><br>
			<?php
				$prev = intval($_GET['page'])-1;					
				if($prev > 0){ echo '<a data-toggle="tooltip" title="Previous" class = "btn btn-default btn-sm" style = "margin: 5px;" href="?audit&page=' . $prev . '"> < </a>'; }
				foreach(range(1, $totalPages) as $page){
				    if($page == $_GET['page']){
				        echo '<b><span class="currentpage" style = "margin: 5px;">' . $page . '</span></b>';
				    }else if($page == 1 || $page == $totalPages || ($page >= $_GET['page'] - 2 && $page <= $_GET['page'] + 2)){
				    	if($page == 0){
				    		continue;
				    	}
				        echo '<a class = "btn btn-default btn-sm" data-toggle="tooltip" title="Page ' . $page . '" style = "margin: 5px;" href="?audit&page=' . $page . '">' . $page . '</a>';
				    }
				}
				$nxt = intval($_GET['page'])+1;
				if($nxt <= $totalPages){ echo '<a class = "btn btn-default btn-sm" data-toggle="tooltip" title="Next" style = "margin: 5px;" href="?audit&page=' . $nxt . '"> > </a>'; }
				
			?>
		</div>
	</div>
</div>