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
<script type="text/javascript">
	function showUser(str,param,param1) {
		if (str == "") {
		    document.getElementById("loc").innerHTML = "";
		    return;
		} else { 
		    if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("loc").innerHTML = xmlhttp.responseText;	
		            <?php 
			        	if(isset($_GET['expn'])){
			        		echo "$('#otproject').addClass('input-sm');"; 
			        	}
		        	?>	  
		        }
		    };
		    xmlhttp.open("GET","ajax/ajaxowner.php?q="+str+"&project="+param+"&support="+param1,true);
		    xmlhttp.send();
		}
	}
	function showUserx(str,param,param1) {
		if (str == "" || str == 'all') {
		    document.getElementById("locx").innerHTML = "";
		    return;
		} else { 
		    if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("locx").innerHTML = xmlhttp.responseText;
		            <?php 
			        	if(isset($_GET['expenses']) || isset($_GET['expn'])){
			        		echo "$('#otproject').addClass('input-sm');"; 
			        	}
		        	?>
		        }
		    };
		    <?php
		    	if(isset($_GET['expenses']) || isset($_GET['expn'])){
		    		$x = "x=1";
		    	}else{
		    		$x = "b";
		    	}
		    ?>
		    xmlhttp.open("GET","ajax/ajaxowner.php?<?php echo $x;?>&q="+str+"&project="+param+"&support="+param1,true);
		    xmlhttp.send();
		}
	}
</script>