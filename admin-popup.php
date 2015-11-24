<?php
	$stmt = "SELECT * FROM `petty`";
	$result = $conn->query($stmt);
		if($result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				if($row['state'] == 'UAPetty' || $row['state'] == 'AAPettyReceived'){
?>
<!-- Modal -->
  <div class="modal fade" id="adminpopup" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Pending Petty Request</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <div class="row">
          	<div class="col-xs-12" align="center">
          		<a href="admin-petty.php" class="btn btn-primary">Click to View List</a>
          	</div>
          </div>
        </div>
        <div class="modal-footer">
         
        </div>
      </div>
      
    </div>
  </div> 
</div>
<?php
				}
			}
?>
<script type="text/javascript">
$(document).ready(function(){	      
  $('#adminpopup').modal({
    backdrop: 'static',
    keyboard: false
  });
});
</script>
  <style>
  .modal-header, h4, .close {
      background-color: #5cb85c;
      color:white !important;
      text-align: center;
      font-size: 30px;
  }
  .modal-footer {
      background-color: #f9f9f9;
  }
  </style>
<?php
	}

?>
