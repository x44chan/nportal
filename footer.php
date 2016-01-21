		</div>
		<div style = "margin-bottom: 120px;"></div>
		<footer class="footer">
			<div class="container" align="center">
				<p class="text-muted"><i>Netlink Advance Solutions, Inc. (<?php echo date("Y");?>)</i></p>
			</div>
   		</footer>
	</body>
</html>
<?php if(isset($_SESSION['level']) && $_SESSION['level'] == 'Admin') { ?>
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
<?php } ?>
