<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach($system as $k => $v){
  $_SESSION['system'][$k] = $v;
}
ob_end_flush();
?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");
?>
<?php include 'header.php' ?>
<body class="hold-transition login-page bg-yellow">
<div class="login-box">
  <div class="login-logo">
    <a href="#" class="text-white"><b><?php echo $_SESSION['system']['name'] ?> [Judiciary] </b></a>
  </div>
  <div class="card bg-white rounded-lg shadow-lg">
  <div class="card-body login-card-body">
    <form action="" id="login-form">
      <div class="input-group mb-3">
        <input type="email" class="form-control rounded-pill" name="email" required placeholder="Email" style="font-size: 1.1rem;">
        <div class="input-group-append">
          <div class="input-group-text rounded-right-pill">
            <span class="fas fa-envelope"></span>
          </div>
        </div>
      </div>
      <div class="input-group mb-3">
        <input type="password" class="form-control rounded-pill" name="password" required placeholder="Password" style="font-size: 1.1rem;">
        <div class="input-group-append">
          <div class="input-group-text rounded-right-pill">
            <span class="fas fa-lock"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-8">
          <div class="icheck-primary">
            <input type="checkbox" id="remember" checked>
            <label for="remember" style="color: #007bff;">
              Remember Me!
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block rounded-pill" style="background-color: #007bff; border-color: #007bff; font-weight: bold;">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <p class="mb-1">
      <a href="#" id="forgot-pwd" style="color: #6c757d; font-size: 0.9rem;">Forgot Password?</a>
    </p>
  </div>
  <!-- /.login-card-body -->
</div>
 

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgot-password-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="forgot-password-form">
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#login-form').submit(function(e){
      e.preventDefault()
      start_load()
      if($(this).find('.alert-danger').length > 0 )
        $(this).find('.alert-danger').remove();
      $.ajax({
        url:'ajax.php?action=login',
        method:'POST',
        data:$(this).serialize(),
        error:err=>{
          console.log(err)
          end_load();
        },
        success:function(resp){
          if(resp == 1){
            location.href ='index.php?page=home';
          }else{
            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
            end_load();
          }
        }
      })
    })

    $('#forgot-pwd').click(function() {
      $('#forgot-password-modal').modal('show');
    });

    $('#forgot-password-form').submit(function(e) {
      e.preventDefault();
      var email = $('#email').val();
      $.ajax({
        url: 'ajax.php?action=forgot_password',
        method: 'POST',
        data: { email: email },
        success: function(resp) {
          if (resp == 1) {
            alert('Password reset instructions have been sent to your email.');
            $('#forgot-password-modal').modal('hide');
          } else {
            alert('Error: Unable to reset password. Please try again later.');
          }
        },
        error: function(err) {
          console.log(err);
          alert('An error occurred. Please try again later.');
        }
      });
    });
  })
</script>
<?php include 'footer.php' ?>

</body>
</html>