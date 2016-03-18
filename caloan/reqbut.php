<div class="btn-group btn-group-lg">
	<button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown">New Request <span class="caret"></span></button>
	<ul class="dropdown-menu" role="menu">
		<li><a href="#" id = "newovertime">Overtime Request</a></li>
		<li><a href="#" id = "newoffb">Official Business Request</a></li>
		<li><a href="#" id = "newleave">Leave Of Absence Request</a></li>				  
		<li><a href="#" id = "newundertime">Undertime Request Form</a></li>
		<li><a href="#"  data-toggle="modal" data-target="#petty">Petty Cash Form</a></li>
		<li><a href="#"  data-toggle="modal" data-target="#penalty">Loan Form (All Employees)</a></li>
	  <?php
	  	if($_SESSION['category'] == "Regular"){
	  ?>
		<li class="divider"></li>
	  	<li><a href="#"  data-toggle="modal" data-target="#cashadv">Cash Advance Form</a></li>
	  	<li><a href="#"  data-toggle="modal" data-target="#loan">Salary Loan Form</a></li>
	  <?php
	  	}
	  ?>
	</ul>
</div>