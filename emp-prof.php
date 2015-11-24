<?php 
    include 'conf.php';
    $acc_id = $_SESSION['acc_id'];
    $sql = "SELECT * FROM `login` where account_id = '$acc_id' limit 1";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
?>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog modal-lg">    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding:25px 50px; ">
        <?php if($row['201date'] != null && $row['201date'] != '0000-00-00'){ echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';}?>
        <h4><span class="glyphicon glyphicon-user"></span> Employee Profile</h4>
        <?php if($row['201date'] == null){ echo '<i><font color = "#FF1919">Update your profile first to activate your account </font></i><br><a href = "logout.php" class="btn btn-danger" onclick="return confirm(\'Do you really want to log out?\');"  role="button">Logout</a>';}?>
      </div>
      <div class="modal-body" style="padding:20px 50px;">
        <form role="form" action = "" method = "post">
         <div class = "row">
         
        </div>
        <h3>Personal Information</h3>
        <div style="border-bottom: 1px solid #eee;"></div>
        <div class = "row">

        </div>
        <div class="row">
        <div class = "col-lg-5 col-md-5 col-sm-5 col-xs-5" align="center">
            <img style = "margin: auto;"src="images/<?php echo $_SESSION['acc_id'];?>.jpg" class="img-rounded" onerror="if (this.src != 'images/default.jpg') this.src = 'images/default.jpg';"alt="Cinque Terre" width="200" height="180"><br><br>
            <a href = "takephoto.php" class="btn btn-primary"><span class="glyphicon glyphicon-camera"></span> Take Photo </a>
         </div>
          <div class="col-md-7">
            <label for="esname"> Surname <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"autofocus value = "<?php echo $row['lname']; ?>"name = "esname" id = "esname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter surname">
          </div>
          <div class="col-md-7">
            <label for="efname"> First Name <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"name = "efname" value = "<?php echo $row['fname']; ?>" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter first name">
          </div>
          <div class="col-md-7">
            <label for="emname"> Middle Name <font color = "red">*</font></label>
            <input type="text" pattern="[a-zA-ZñÑ\s]+"name = "emname" value = "<?php echo $row['mname']; ?>" id = "emname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter middle name">
          </div>
        </div>
        <div class="row">
         <div class="col-md-8">
            <label for="usrname"> Home Address <font color = "red">*</font></label>
            <textarea type="textarea" name = "eaddress" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Enter complete address"><?php echo $row['eaddress']; ?></textarea>
          </div>
          <div class="col-md-4">
            <label for="usrname"> Contact # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['econt']; ?>"name = "econt" pattern = '[0-9-]+' required style = "font-weight:normal;text-transform:capitalize;" class="form-control" placeholder="091234567890">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="esname"> Position <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['position']; ?>"name = "epost"id = "esname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter position">
          </div>
          <div class="col-md-4">
            <label for="efname"> Duration <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['eduration']; ?>"name = "eduration" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter duration">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Tel. #</label>
            <input type="text" value = "<?php echo $row['etel']; ?>"name = "etel" pattern = '[0-9-]+' style = "font-weight:normal;text-transform:capitalize;"  class="form-control" placeholder="091234567890">
          </div>
        </div>
       <div class="row">
        <div class="col-md-4">
          <label for="esname"> Civil Status <font color = "red">*</font></label>
           <select class = "form-control" id = "cstatus" name  = "ecstatus" required>
            <option value = "">-------------</option>
            <option <?php if($row['ecstatus'] == 'Single'){echo ' selected="selected"';}?>value="Single">Single</option>
            <option <?php if($row['ecstatus'] == 'Married'){echo ' selected="selected"';}?>value="Married">Married</option>
          </select>
          </div>
        <div class="col-md-4">
          <label for="efname"> Date Hired <font color = "red">*</font></label>
          <input type="date" data-date='{"startView": 1, "openOnMouseFocus": true}'  value = "<?php echo $row['edatehired']; ?>" name = "edatehired" style = "font-weight:normal;" class="form-control" required autocomplete="off"placeholder="Enter middle name">
        </div>
        <div class="col-md-4">
          <label for="usrname"> Gender <font color = "red">*</font></label>
          <select class = "form-control" required name = "egender">
            <option value = "">-------------</option>
            <option <?php if($row['egender'] == 'Male'){echo ' selected="selected"';}?> value="Male">Male</option>
            <option <?php if($row['egender'] == 'Female'){echo ' selected="selected"';}?> value="Female">Female</option>
          </select>
        </div>
      </div>
      <div id = "marriedform" style="<?php if($row['ecstatus'] == 'Married'){ echo 'display: inline';} else{ echo 'display: none;"';}?>">
          <div>
            <div style="border-bottom: 1px solid #eee;"></div>
            <h3>For Married</h3>          
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Spouse Name <font color = "red">*</font></label>
            </div> 
            <div class="col-md-6">
              <label for="efname"> Number of Children <font color = "red">*</font></label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text"value = "<?php echo $row['espouse']; ?>" id = "spousename" name = "espouse" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter Spouse Name">
            </div>
            <div class="col-md-6">            
              <input type="number" value = "<?php echo $row['enumchild']; ?>" id = "numofchildren" name = "enumchild" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter number of children">
            </div>
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Name of Children <font color = "red">*</font></label>
            </div> 
            <div class="col-md-6">
              <label for="esname"> Birthdate <font color = "red">*</font></label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname1']; ?>"id = "childname" name = "childname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3" >
              <input type="date"  data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday1'];?>" id = "childbday" name = "childbday" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname2']; ?>"id = "childname" name = "childname2" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday2'];?>" id = "childbday" name = "childbday2" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname3']; ?>"id = "childname" name = "childname3" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday3'];?>" id = "childbday" name = "childbday3" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname4']; ?>"id = "childname" name = "childname4" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}'value="<?php echo $row['childbday4'];?>" id = "childbday" name = "childbday4" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">            
              <input type="text" value = "<?php echo $row['childname5']; ?>"id = "childname" name = "childname5" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of child">
            </div>
            <div class = "col-md-3">
              <input type="date" data-date='{"startView": 2, "openOnMouseFocus": true, "calculateWidth": false}' value="<?php echo $row['childbday5'];?>" id = "childbday" name = "childbday5" style = "font-weight:normal;" class="form-control" autocomplete="off"placeholder="Enter birthdate of child">
            </div>
          </div>
        </div>
      <div class="row">
          <div class="col-md-4">
            <label for="esname"> Blood Type <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['eblood']; ?>" id = "esname" name = "eblood" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter Blood Type">
          </div>
          <div class="col-md-4">
            <label for="efname"> Religion <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ereligion']; ?>" id = "efname" name = "ereligion" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter religion">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Dep./Sec. <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['department']; ?>" name = "edept" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Enter Dept./Sec.">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="esname"> Birth Date <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['ebday']; ?>" name = "ebday" id = "esname" style = "font-weight:normal;" class="form-control" required autocomplete="off"placeholder="Enter birthdate">
          </div>
          <div class="col-md-4">
            <label for="efname"> Birth Place <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ebirth']; ?>" name = "ebirth" id = "efname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required autocomplete="off"placeholder="Enter birthplace">
          </div>
         <div class="col-md-4">
            <label for="usrname"> Nationality <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['enationality']; ?>" name = "enationality" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Nationality">
          </div>
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Mother's Name <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['emothern']; ?>" name = "emothern" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Mother's Name">
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['emontherb']; ?>" name = "emontherb" style = "font-weight:normal;" class="form-control" required placeholder="Birthdate">
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Father's Name <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['efathern']; ?>" name = "efathern" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Father's Name">
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate <font color = "red">*</font></label>
            <input type="date" value = "<?php echo $row['efatherb']; ?>" name = "efatherb" style = "font-weight:normal;" class="form-control" required placeholder="Birthdate">
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-4">
            <label for="esname"> SSS # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['esss']; ?>" name = "esss" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="SSS">
          </div> 
          <div class="col-md-4">
            <label for="efname"> Philhealth # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['ephilhealth']; ?>" name = "ephilhealth" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Philhealth">
          </div>
          <div class="col-md-4">
            <label for="efname"> T.I.N # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['etin']; ?>" name = "etin" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="T.I.N">
          </div>  
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="epagibig"> Pagibig # <font color = "red">*</font></label>
            <input type="text" value = "<?php echo $row['epagibig']; ?>" name = "epagibig" style = "font-weight:normal;text-transform:capitalize;" class="form-control" required placeholder="Pagibig">
          </div>  
        </div>
        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Educational Background</h3>          
        </div>
         <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> Name of School <font color = "red">*</font></label>           
            <input type="text" value = "<?php echo $row['enameofschool']; ?>" name = "enameofschool" required id = "childname" style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter name of school">
         </div>
        </div>
        <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> School Address <font color = "red">*</font></label>           
            <textarea id = "childname" name = "eschooladd" required style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Enter school address"><?php echo $row['eschooladd']; ?></textarea>
         </div>
        </div>
        <div class="row">
          <div class="col-md-4"> 
            <label for="esname"> Highest Attainment <font color = "red">*</font></label>           
            <select class = "form-control" required name = "egrad">
              <option value = "">----------</option>
              <option <?php if($row['egrad'] == 'High School Graduate'){echo ' selected="selected"';}?>value = "High School Graduate">High School Graduate</option>
              <option <?php if($row['egrad'] == 'College Undergraduate'){echo ' selected="selected"';}?>value = "College Undergraduate">College Undergraduate</option>
              <option <?php if($row['egrad'] == 'Vocational Degree'){echo ' selected="selected"';}?>value = "Vocational Degree">Vocational Degree</option>
              <option <?php if($row['egrad'] == 'Bachelor\'s Degree'){echo ' selected="selected"';}?>value = "Bachelor's Degree">Bachelor's Degree</option>
              <option <?php if($row['egrad'] == 'Masteral/Doctoral Degree'){echo ' selected="selected"';}?>value = "Masteral/Doctoral Degree">Masteral/Doctoral Degree</option>
            </select>
          </div>
          <div class="col-md-5"> 
           <label for="esname"> Course <font color = "red">*</font></label>   
           <input type="text" value = "<?php echo $row['ecourse']; ?>"id = "childname" name = "ecourse" required style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Course">
          </div>
          <div class="col-xs-3"> 
           <label for="esname"> Year Graduated <font color = "red">*</font></label>   
           <input type="text" value = "<?php echo $row['eyrgrad']; ?>"id = "childname" name = "eyrgrad" required  style = "font-weight:normal;text-transform:capitalize;" class="form-control" autocomplete="off"placeholder="Year Graduated">
          </div>
        </div>
        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Employment History</h3>          
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="esname"> Position <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Company Name <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Start <font color = "red">*</font></label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> End <font color = "red">*</font></label>   
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost']; ?>"name = "empost" placeholder = "Position"/>
          </div>
          <div class="col-md-3">
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany" placeholder = "Company Name"><?php echo $row['emcompany']; ?></textarea>
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr']; ?>"type = "date" name = "empdatefr" placeholder = "Start"/> 
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdateto']; ?>"type = "date" name = "empdateto" placeholder = "End"/>             
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">            
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost2']; ?>"name = "empost2" placeholder = "Position"/>
          </div>
          <div class="col-md-3"> 
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany2" placeholder = "Company Name"><?php echo $row['emcompany2']; ?></textarea>
          </div>
          <div class="col-md-3"> 
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr2']; ?>"type = "date" name = "empdatefr2" placeholder = "Start"/>
          </div>
          <div class="col-md-3"> 
            <input  class = "form-control" data-date='{"startView": 2, "openOnMouseFocus": true}' value = "<?php echo $row['empdateto2']; ?>"type = "date" name = "empdateto2" placeholder = "End"/>           
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <input  pattern="[a-zA-Z\s]+" class = "form-control" type = "text" value = "<?php echo $row['empost3']; ?>"name = "empost3" placeholder = "Position"/> 
          </div>
          <div class="col-md-3">
            <textarea pattern="[a-zA-Z\s]+" id = "textareaaa" class = "form-control" name = "emcompany3" placeholder = "Company Name"><?php echo $row['emcompany3']; ?></textarea>
          </div>
          <div class="col-md-3">
            <input  class = "form-control" data-date='{"startView": 1, "openOnMouseFocus": true}' value = "<?php echo $row['empdatefr3']; ?>"type = "date" name = "empdatefr3" placeholder = "Start"/>  
          </div>
          <div class="col-md-3">
            <input  class = "form-control"data-date='{"startView": 1, "openOnMouseFocus": true}'  value = "<?php echo $row['empdateto3']; ?>"type = "date" name = "empdateto3" placeholder = "End"/>
          </div>
        </div> 
        <div style="margin-top: 15px;">
          <button type="submit" style = "width: 50%; margin: auto;"name = "submitprof" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Update</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
}
}
?>
<script type="text/javascript">
  $('#textareaaa').keyup(validateTextarea);

function validateTextarea() {
        var errorMsg = "Please match the format requested.";
        var textarea = this;
        var pattern = new RegExp('^' + $(textarea).attr('pattern') + '$');
        // check each line of text
        $.each($(this).val().split("\n"), function () {
            // check if the line matches the pattern
            var hasError = !this.match(pattern);
            if (typeof textarea.setCustomValidity === 'function') {
                textarea.setCustomValidity(hasError ? errorMsg : '');
            } else {
                // Not supported by the browser, fallback to manual error display...
                $(textarea).toggleClass('error', !!hasError);
                $(textarea).toggleClass('ok', !hasError);
                if (hasError) {
                    $(textarea).attr('title', errorMsg);
                } else {
                    $(textarea).removeAttr('title');
                }
            }
            return !hasError;
        });
    }
</script>
<script type="text/javascript">
  $('#cstatus').change(function() {
    var selected = $(this).val();  
    if(selected == 'Married'){
        $('#marriedform').show();
        $("#spousename").attr('required',true);
        $("#numofchildren").attr('required',true);
      }else{
        $('#marriedform').hide();
        $("#spousename").attr('required',false);
        $("#numofchildren").attr('required',false);
      }
  });

</script>
<style>
.row {
  margin-bottom: 10px;
}
.modal-header h4, .modal-header, .close {
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
if(isset($_POST['submitprof'])){
  include('conf.php');
  //emp info
  $esname = ucwords(mysql_escape_string($_POST['esname']));
  $efname = ucwords(mysql_escape_string($_POST['efname']));
  $emname = ucwords(mysql_escape_string($_POST['emname']));
  $epost = ucwords(mysql_escape_string($_POST['epost']));
  $edept = ucwords(mysql_escape_string($_POST['edept']));

  $eaddress = ucwords(mysql_escape_string($_POST['eaddress']));
  $econt = ucwords(mysql_escape_string($_POST['econt']));
  $eduration = ucwords(mysql_escape_string($_POST['eduration']));
  $etel = ucwords(mysql_escape_string($_POST['etel']));
  $ecstatus = ucwords(mysql_escape_string($_POST['ecstatus']));
  $edatehired = ucwords(mysql_escape_string($_POST['edatehired']));
  $egender = ucwords(mysql_escape_string($_POST['egender']));
  $eblood = ucwords(mysql_escape_string($_POST['eblood']));
  $ereligion = ucwords(mysql_escape_string($_POST['ereligion']));
  $ebday = ucwords(mysql_escape_string($_POST['ebday']));
  $ebirth = ucwords(mysql_escape_string($_POST['ebirth']));
  $enationality = ucwords(mysql_escape_string($_POST['enationality']));
  $emothern = ucwords(mysql_escape_string($_POST['emothern']));
  $emontherb = ucwords(mysql_escape_string($_POST['emontherb']));
  $efathern = ucwords(mysql_escape_string($_POST['efathern']));
  $efatherb = ucwords(mysql_escape_string($_POST['efatherb']));
  $esss = ucwords(mysql_escape_string($_POST['esss']));
  $ephilhealth = ucwords(mysql_escape_string($_POST['ephilhealth']));
  $etin = ucwords(mysql_escape_string($_POST['etin']));
  $epagibig = ucwords(mysql_escape_string($_POST['epagibig']));
  $acc_id = $_SESSION['acc_id'];
  //education prof
  $enameofschool = ucwords(mysql_escape_string($_POST['enameofschool']));
  $eschooladd = ucwords(mysql_escape_string($_POST['eschooladd']));
  $egrad = ucwords(mysql_escape_string($_POST['egrad']));
  $ecourse = ucwords(mysql_escape_string($_POST['ecourse']));
  $eyrgrad = ucwords(mysql_escape_string($_POST['eyrgrad']));
  $dates = date("Y-m-d");
  if($ecstatus == 'Married'){ 
    $espouse = mysql_escape_string($_POST['espouse']);
    $enumchild = mysql_escape_string($_POST['enumchild']);
    $childname = mysql_escape_string($_POST['childname']);
    $childname2 = mysql_escape_string($_POST['childname2']);
    $childname3 = mysql_escape_string($_POST['childname3']);
    $childname4 = mysql_escape_string($_POST['childname4']);
    $childname5 = mysql_escape_string($_POST['childname5']);
    $childbday = mysql_escape_string($_POST['childbday']);
    $childbday2 = mysql_escape_string($_POST['childbday2']);
    $childbday3 = mysql_escape_string($_POST['childbday3']);
    $childbday4 = mysql_escape_string($_POST['childbday4']);
    $childbday5 = mysql_escape_string($_POST['childbday5']);
    $sql2 ="UPDATE login set 
      espouse = '$espouse', enumchild = '$enumchild', childname1 = '$childname', childname2 = '$childname2', childname3 = '$childname3',
      childname4 = '$childname4', childname5 = '$childname5', childbday1 = '$childbday', childbday2 = '$childbday2', childbday3 = '$childbday3',
      childbday4 = '$childbday4', childbday5 = '$childbday5'
    where account_id = '$acc_id'";
    if ($conn->query($sql2) === TRUE) {

    }else{
      echo "Error updating record: " . $conn->error;
    }
  }

  $empost = mysql_escape_string($_POST['empost']);
  $empost2 = mysql_escape_string($_POST['empost2']);
  $empost3 = mysql_escape_string($_POST['empost3']);
  $emcompany = mysql_escape_string($_POST['emcompany']);
  $emcompany2 = mysql_escape_string($_POST['emcompany2']);
  $emcompany3 = mysql_escape_string($_POST['emcompany3']);
  $empdatefr = mysql_escape_string($_POST['empdatefr']);
  $empdatefr2 = mysql_escape_string($_POST['empdatefr2']);
  $empdatefr3 = mysql_escape_string($_POST['empdatefr3']);
  $empdateto = mysql_escape_string($_POST['empdateto']);
  $empdateto2 = mysql_escape_string($_POST['empdateto2']);
  $empdateto3 = mysql_escape_string($_POST['empdateto3']);

  $sql ="UPDATE login set 
    fname = '$efname', lname = '$esname', mname = '$emname', position = '$epost', department = '$edept',
    eaddress = '$eaddress', econt = '$econt', eduration = '$eduration', edatehired = '$edatehired', edatehired = '$edatehired',
    edatehired = '$edatehired', egender = '$egender', eblood = '$eblood', ereligion = '$ereligion', ebday = '$ebday', ebirth = '$ebirth',
    enationality = '$enationality', emothern = '$emothern', emontherb = '$emontherb', efathern = '$efathern', efatherb = '$efatherb',
    esss = '$esss', ephilhealth = '$ephilhealth', etin = '$etin', epagibig = '$epagibig', enameofschool = '$enameofschool', eschooladd = '$eschooladd', egrad = '$egrad',
    ecourse = '$ecourse', eyrgrad = '$eyrgrad',  etel = '$etel', ecstatus = '$ecstatus', 201date = '$dates', empost = '$empost', empost2 = '$empost2',
    empost3 = '$empost3', emcompany = '$emcompany', emcompany2 = '$emcompany2', emcompany3 = '$emcompany3', empdatefr = '$empdatefr', empdatefr2 = '$empdatefr2', empdatefr3 = '$empdatefr3',
    empdateto = '$empdateto', empdateto2 = '$empdateto2', empdateto3 = '$empdateto3'
    where account_id = '$acc_id'"; 
  if ($conn->query($sql) === TRUE) {
    $_SESSION['pass'] = null;
    $_SESSION['201date'] = date("Y-m-d");
    echo '<script type = "text/javascript">alert("update successful")</script>';
    echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
  }else {
    echo "Error updating record: " . $conn->error;
  }  
}
?>