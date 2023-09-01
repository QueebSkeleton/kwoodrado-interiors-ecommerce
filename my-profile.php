<?php
  session_start();

  // If user is not yet logged in, redirect to login page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to login page.'); ".
    "window.location.replace('/login.php');</script></body></html>");
  }

  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | My Profile</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        include("include/navbar.php");
      ?>

      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">My Account</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">My Account</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar">
            <div id="customer-account" class="col-lg-9 clearfix">
              <p class="lead">Change your personal details or your password here.</p>
              <p class="text-muted">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
              <div class="box mt-5">
                <div class="heading">
                  <h3 class="text-uppercase">Change password</h3>
                </div>
                <form method="post" action="change_password.php">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="password_old">Old password</label>
                        <input id="password_old" type="password" name="password_old" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="password_1">New password</label>
                        <input id="password_1" type="password" name="password_new" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="password_2">Retype new password</label>
                        <input id="password_2" type="password" name="password_new_retype" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-template-outlined"><i class="fa fa-save"></i> Save new password</button>
                  </div>
                </form>
              </div>
              <div class="box">
                <div class="heading">
                  <h3 class="text-uppercase">Manage Addresses</h3>
                </div>
                <form method='post' action="add_address.php">
                  <div class="form-group">
                    <label for="address">Input a new address here:</label>
                    <textarea name="address" class="form-control" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-template-outlined"><i class="fa fa-save"></i> Save new address</button>
                </form>
                <div class="table-responsive mt-4">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Address</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        // fetch personal details of currently logged in customer
                        $email_address = $_SESSION["email_address"];
                        $address_result = mysqli_query($conn, "SELECT address FROM customer_address WHERE customer_email_address='$email_address'");
                        if(mysqli_num_rows($address_result) == 0):
                          echo "<tr><td colspan='2' class='text-center'>No addresses to display. Consider adding one.</td></tr>";
                        else:
                          while($row = mysqli_fetch_assoc($address_result)):
                      ?>
                      <tr>
                        <td><?= $row["address"] ?></td>
                        <td><a href="delete_address.php?address=<?= $row["address"] ?>" class="btn btn-template-outlined btn-sm">Delete</a></td>
                      </tr>
                      <?php endwhile; endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="box">
                <div class="heading">
                  <h3 class="text-uppercase">Personal details</h3>
                </div>
                <form method="post" action="change_credentials.php">
                  <?php
                    // fetch personal details of currently logged in customer
                    $email_address = $_SESSION["email_address"];
                    $customer_result = mysqli_query($conn, "SELECT first_name, last_name, phone_number FROM customer WHERE email_address='$email_address'");
                    $customer_row = mysqli_fetch_assoc($customer_result);
                  ?>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name">First name</label>
                        <input id="first_name" type="text" name="first_name" class="form-control" value="<?= $customer_row["first_name"] ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input id="last_name" type="text" name="last_name" class="form-control" value="<?= $customer_row["last_name"] ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input id="email_address" type="email" name="email_address" class="form-control" value="<?= $email_address ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input id="phone_number" type="text" name="phone_number" min="11" max="11" class="form-control" value="<?= $customer_row["phone_number"] ?>">
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-template-outlined"><i class="fa fa-save"></i> Save changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-md-3 mt-4 mt-md-0">
              <!-- CUSTOMER MENU -->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Customer section</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
                    <li class="nav-item"><a href="my-orders.php" class="nav-link"><i class="fa fa-list"></i> My orders</a></li>
                    <li class="nav-item"><a href="my-profile.php" class="nav-link active"><i class="fa fa-user"></i> My account</a></li>
                    <li class="nav-item"><a href="user_logout.php" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php /* Footer */ include('include/footer.php'); ?>
    </div>

    <?php /* All scripts */ include('include/scripts.php'); ?>

    <?php /* login modal */ if(!isset($_SESSION["email_address"])) include('include/login-register-modals.php'); ?>

  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>
