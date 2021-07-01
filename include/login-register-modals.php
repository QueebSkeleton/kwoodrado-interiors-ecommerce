<!-- Login Modal-->
<div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="login-modalLabel" class="modal-title">Customer Login</h4>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="user_login.php" method="post" id="login-form">
          <div class="form-group">
            <input id="email_address" name="email_address" type="email" placeholder="email" class="form-control">
          </div>
          <div class="form-group">
            <input id="password" type="password" name="password" placeholder="password" class="form-control">
          </div>
          <p class="text-center">
            <button class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Log in</button>
          </p>
        </form>
        <p class="text-center text-muted">Not registered yet?</p>
        <p class="text-center text-muted"><a href="customer-register.html"><strong>Register now</strong></a>! It is easy and done in 1 minute and gives you access to special discounts and much more!</p>
      </div>
    </div>
  </div>
</div>
<!-- Login modal end-->

<!-- Login Modal-->
<div id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modalLabel" aria-hidden="true" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="register-modalLabel" class="modal-title">Customer Signup</h4>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <form action="user_register.php" method="post" id="register-form">
          <div class="form-group">
            <input id="first_name" type="text" name="first_name" placeholder="first name" class="form-control" required>
          </div>
          <div class="form-group">
            <input id="last_name" type="text" name="last_name" placeholder="last name" class="form-control" required>
          </div>
          <div class="form-group">
            <input id="phone_number" type="text" name="phone_number" placeholder="contact" class="form-control" required>
          </div>
          <div class="form-group">
            <input id="email_address" name="email_address" type="email" placeholder="email" class="form-control" required>
          </div>
          <div class="form-group">
            <input id="password" type="password" name="password" placeholder="password" class="form-control" required>
          </div>
          <p class="text-center">
            <button class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Sign up</button>
          </p>
        </form>
        <p class="text-center text-muted">Already registered?</p>
        <p class="text-center text-muted"><a href="#"><strong>Login</strong></a>! It is easy and done in 1 minute and gives you access to special discounts and much more!</p>
      </div>
    </div>
  </div>
</div>
<!-- Login modal end-->

<script>
  $("#login-form").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr("action"),
      type: $(this).attr("method"),
      data: $(this).serialize(),
      statusCode: {
        200: function(data) {
          alert("Successfully logged in! Refreshing current page...");
          location.reload();
        },
        401: function(data) {
          alert("Invalid credentials. Please try again.");
          $("#login-form").find("input[name='email_address']").val("");
          $("#login-form").find("input[name='password']").val("");
        }
      }
    });
  });

  $("#register-form").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: $(this).attr("action"),
      type: $(this).attr("method"),
      data: $(this).serialize(),
      statusCode: {
        200: function(data) {
          alert("Successfully registered! Login after this redirect...");
          window.location.replace('/login.php');
        },
        400: function(data) {
          alert("Invalid credentials. Please try again.");
          $("#login-form").find("input[name='first_name']").val("");
          $("#login-form").find("input[name='last_name']").val("");
          $("#login-form").find("input[name='contact_number']").val("");
          $("#login-form").find("input[name='email_address']").val("");
          $("#login-form").find("input[name='password']").val("");
        }
      }
    });
  });
</script>
