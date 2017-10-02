<?php
	$sql = "SELECT * from `login` where (empcatergory is null or empcatergory = '') and active = 1 and level != 'Admin' and uname != 'accounting' and uname != 'hradmin' and uname != 'mitchortiz' order by lname ASC";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
?>
<div class="modal fade" id="pendingcateg" role="dialog">
	<div class="modal-dialog">    
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="padding:35px 50px;">
				<button type="button" class="close" data-dismiss="modal"><font color = "#CC0000">&times;</font></button>
				<h4>Pending For Categorization</h4>
			</div>
			<div class="modal-body" style="padding:40px 50px;">
				<table class="table table-hover">
				<?php while($row = $result->fetch_object()){ ?>
				<tr>
					<?php echo '<td><label>'. $row->fname . ' ' .$row->lname . '</label></td>'; echo '<td><a href = "hr-emprof.php?modify='.$row->account_id.'" class = "btn btn-primary"> View </a></td>';?>
				</tr>					
				<?php } ?>
				</label>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){	      
		$('#pendingcateg').modal({
			//backdrop: 'static',
			//keyboard: false
		});
	});
</script>
<?php
	}
?>