  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <h4>Update Password</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" action = "" method = "post">
            <div class="form-group">
              <label for="usrname"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
              <input type="password" class="form-control" required id="psw" name = "pword" autocomplete="off"placeholder="Enter password">
            </div>
            <div class="form-group">
              <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Confirm Password</label>
              <input type="password" class="form-control" required id="psw1" name = "pword2" autocomplete="off"placeholder="Enter password">
            </div>
              <button type="submit" id = "submitss" name = "submitpw" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Update</button>
          </form>
        </div>
      </div>
    </div>
<script type="text/javascript">

  $(window).load(function(){   
      $('#myModal').modal({
          backdrop: 'static',
          keyboard: false
      })
  });

  $("#submitss").click(function(){
     if($("#psw").val() != $("#psw1").val()){
               alert("Password does not match");
               return false;
               //more processing here
     }
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
  if(isset($_POST['submitpw'])){
    include('conf.php');
    $pword = mysqli_real_escape_string($conn, $_POST['pword']);
    $pword2 = mysqli_real_escape_string($conn, $_POST['pword2']);
    $password = $_SESSION['pass'];
    $acc_id = $_SESSION['acc_id'];
    $sql ="UPDATE login set pword = '$pword' where account_id = '$acc_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['pass'] = null;
        echo '<script type = "text/javascript">alert("update successful")</script>';
        echo '<script type="text/javascript">window.location.replace("index.php"); </script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }  
}

?>

