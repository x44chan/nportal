<?php 
    include 'conf.php';
    $acc_id = $_SESSION['acc_id'];
    $sql = "SELECT * FROM `login` where account_id = '$acc_id' limit 1";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
?>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog modal-lg" >    
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header" style="padding:25px 50px; font-size: 20px; text-align: left;">
      <?php if($row['edatehired'] != ""){ ?>
       <button type="button" class="close" data-dismiss="modal"><font color = "#CC0000">&times;</font></button>
      <?php }else{
        echo '<h4 align = "center"> Profile Not Active <a href = "logout.php" class = "btn btn-danger"> Logout</a>';
      }?>
          <div class="row" style="margin-left: 30px;">           
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label for="esname"> Name: </label>
                <i><p style = "margin-left: 10px;" id = "esname"><?php echo $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']; ?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label for="usrname"> Contact # </label>
                <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['econt']?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                   <label for="emname"> Position </label>
                   <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['position']?></p></i>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <label for="usrname"> Dep./Sec. </label>
                <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['department']?></p></i>
              </div>
            </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <img style = "margin: auto;"src="images/<?php echo $acc_id;?>.jpg" onerror="if (this.src != 'images/default.jpg') this.src = 'images/default.jpg';" class="img-rounded" alt="Cinque Terre" width="250" height="200"><br><br>
             		<a href = "takephoto.php" class="btn btn-primary" style="margin-left: 60px;"><span class="glyphicon glyphicon-camera"></span> Take Photo </a>
              </div>
            </div>
    </div>
      </div>
      <div class="modal-body" style="padding:20px 50px; font-size: 17px; overflow-y: auto;">
      <?php
            $leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row[account_id]' and CURDATE() <= enddate and state = 'AAdmin'";
            $datalea = $conn->query($leaveexec)->fetch_assoc();
            $sl = $datalea['sleave'];
            $vl = $datalea['vleave'];
            if($sl <= 0){
              $sl = 0;
            }
            if($vl <= 0){
              $vl = 0;
            }
      ?>
      <div class="row">
         <div class="col-xs-4">
           <label>Employee ID</label>
           <p style="margin-left: 10px"><i><?php echo $row['phoenix_empid'];?></i></p>
         </div>
         <div class="col-xs-4">
           <label>Chrono #</label>
           <p style="margin-left: 10px"><i><?php echo $row['phoenix_chrono'];?></i></p>
         </div>
      </div>
      <div class="row">
         <div class="col-md-8">
            <label for="usrname"> Home Address </label>
        <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eaddress']?></p></i>  
          </div>
          <div class="col-md-4">
            <label for="usrname"> Tel. #</label>
              <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['etel']?></p></i>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
              <label for="efname"> Date Hired </label>
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date('F j, Y', strtotime($row['edatehired']));?></p></i>
          </div>
          <div class="col-md-4">
            <label for="efname"> Category </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empcatergory'] . ' / ' . $row['payment'];?></p></i>
          </div>
          <div class="col-md-4">
            <label for="efname"> Expiry </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php $expiry = date('F j, Y', strtotime($row['eduration'], strtotime($row['edatehired']))); if($expiry != 'January 1, 1970'){echo $expiry; }else{echo "";}?></p></i>
          </div>
        </div>
       <div class="row">
        <div class="col-md-4">
          <label for="esname"> Civil Status </label>
           <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ecstatus']?></p></i>
          </div>

        <div class="col-md-4">
          <label for="usrname"> Gender </label>
          <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['egender']?></p></i>
        </div>
        <div class="col-md-4">
            <label for="esname"> Blood Type </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eblood']?></p></i>
        </div>
      </div>
              <div class="row">
          <div class="col-md-4">
            <label for="esname"> Birth Date </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date('F j, Y', strtotime($row['ebday']));?></p></i>
           </div>
          <div class="col-md-4">
            <label for="efname"> Birth Place </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ebirth']?></p></i>
           </div>
         <div class="col-md-4">
            <label for="usrname"> Nationality </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enationality']?></p></i>
          </div>
        </div>
      <div id = "marriedform" style="<?php if($row['ecstatus'] == 'Married'){ echo 'display: inline';} else{ echo 'display: none;"';}?>">
          <div>
            <div style="border-bottom: 1px solid #eee;"></div>
            <h3>For Married</h3>          
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Spouse Name </label>
              <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['espouse']?></p></i>
            </div> 
            <div class="col-md-6">
              <label for="efname"> Number of Children </label>
              <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enumchild']?></p></i>
            </div>  
          </div>
          <div class = "row">
            <div class="col-md-6">
              <label for="esname"> Name of Children </label>
            </div> 
            <div class="col-md-6">
              <label for="esname"> Birthdate </label>
            </div>  
          </div>
          <div class="row">
            <div class="col-md-6">            
              <?php if($row['childname1'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname1'] .'</p></i>';}?>
              <?php if($row['childname2'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname2'] .'</p></i>';}?>
              <?php if($row['childname3'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname3'] .'</p></i>';}?>
              <?php if($row['childname4'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname4'] .'</p></i>';}?>
              <?php if($row['childname5'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. $row['childname5'] .'</p></i>';}?>
            </div>
            <div class="col-md-4">           
              <?php if($row['childbday1'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday1'])) .'</p></i>';}?>
              <?php if($row['childbday2'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday2'])) .'</p></i>';}?>
              <?php if($row['childbday3'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday3'])) .'</p></i>';}?>
              <?php if($row['childbday4'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday4'])) .'</p></i>';}?>
              <?php if($row['childbday5'] != null){ echo '<i><p style = "margin-left: 10px;" id = "usrname">'. date("F j, Y", strtotime($row['childbday5'])) .'</p></i>';}?>
            </div>
            </div>
            <div style="border-bottom: 1px solid #eee;"></div>
        </div>
      <div class="row">
          <div class="col-md-4">
            <label for="efname"> Religion </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ereligion']?></p></i> 
           </div>
         <div class="col-md-4">
            
          </div>
        </div>

        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Mother's Name </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emothern'];?></p></i> 
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date("F j, Y", strtotime($row['emontherb']));?></p></i> 
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-6">
            <label for="esname"> Father's Name </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['efathern']?></p></i> 
          </div> 
          <div class="col-md-6">
            <label for="efname"> Birthdate </label>
           <i><p style = "margin-left: 10px;" id = "usrname"><?php echo date("F j, Y", strtotime($row['efatherb']));?></p></i> 
          </div>  
        </div>
        <div class = "row">
          <div class="col-md-4">
            <label for="esname"> SSS # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['esss']?></p></i> 
          </div> 
          <div class="col-md-4">
            <label for="efname"> Philhealth # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ephilhealth']?></p></i> 
          </div>
          <div class="col-md-4">
            <label for="efname"> T.I.N # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['etin']?></p></i> 
          </div>  
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="efname"> Pagibig # </label>
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['epagibig']?></p></i> 
          </div>  
        </div>
        <div>
          <div style="border-bottom: 2px solid #eee;"></div>
          <h3>Educational Background</h3>          
        </div>
         <div class="row">

          <div class="col-md-12"> 
            <label for="esname"> Name of School </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['enameofschool']?></p></i>
         </div>
        </div>
                <div class="row">
          <div class="col-md-12"> 
            <label for="esname"> School Address </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eschooladd']?></p></i>
         </div>
        </div>
        <div class="row">
        <div class="col-md-3"> 
            <label for="esname"> Highest Attainment </label>           
             <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['egrad']?></p></i>
          </div>
          <div class="col-md-6"> 
           <label for="esname"> Course </label>   
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['ecourse']?></p></i>
          </div>
          <div class="col-xs-3"> 
           <label for="esname"> Year Graduated </label>   
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['eyrgrad']?></p></i>
          </div>
        </div>

        <div>
          <div style="border-bottom: 1px solid #eee;"></div>
          <h3>Employment History</h3>          
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="esname"> Position </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Company Name </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> Start </label>   
          </div>
          <div class="col-md-3">
            <label for="esname"> End </label>   
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr']!= null){echo date("F j, Y", strtotime($row['empdatefr']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto']!= null){echo date("F j, Y", strtotime($row['empdateto']));}?></p></i>            
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost2']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany2']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr2']!= null){echo date("F j, Y", strtotime($row['empdatefr2']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto2']!= null){echo date("F j, Y", strtotime($row['empdateto2']));}?></p></i>            
          </div>
        </div>
                <div class="row" >
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['empost3']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php echo $row['emcompany3']?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdatefr3']!= null){echo date("F j, Y", strtotime($row['empdatefr3']));}?></p></i>
          </div>
          <div class="col-md-3">
            <i><p style = "margin-left: 10px;" id = "usrname"><?php if($row['empdateto3']!= null){echo date("F j, Y", strtotime($row['empdateto3']));}?></p></i>            
          </div>
        </div>

       </div>
      </div>
    </div>
  </div>
</div>
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

<?php
}
}
?>


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
    $_SESSION['name'] = $efname . ' ' . $esname;  
    $_SESSION['post'] = $epost;
    $_SESSION['dept'] = $edept;
    $_SESSION['datehired'] = $edatehired;
    echo '<script type = "text/javascript">alert("update successful")</script>';
    echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
  }else {
    echo "Error updating record: " . $conn->error;
  }  
}
?>