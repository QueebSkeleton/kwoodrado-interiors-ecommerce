<?php
  session_start();

  // If user is not yet logged in, redirect to login page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to login page.'); ".
    "window.location.replace('/login.php');</script></body></html>");
  }

  // Parse configuration file
  $config = parse_ini_file("../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | My Profile</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <!-- Top bar-->
      <div class="top-bar">
        <div class="container">
          <div class="row d-flex align-items-center">
            <div class="col-md-6 d-md-block d-none">
              <p>Contact us on +420 777 555 333 or hello@universal.com.</p>
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-md-end justify-content-between">
                <ul class="list-inline contact-info d-block d-md-none">
                  <li class="list-inline-item"><a href="#"><i class="fa fa-phone"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-envelope"></i></a></li>
                </ul>
                <?php if(!isset($_SESSION["email_address"])): ?>
                <div class="login">
                  <a href="my-cart.php" class="login-btn">
                    <i class="fa fa-shopping-cart"></i><span class="d-none d-md-inline-block">My cart</span>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#login-modal" class="login-btn">
                    <i class="fa fa-sign-in"></i><span class="d-none d-md-inline-block">Sign In</span>
                  </a>
                  <a href="#" data-toggle="modal" data-target="#register-modal" class="signup-btn">
                    <i class="fa fa-user"></i><span class="d-none d-md-inline-block">Sign Up</span>
                  </a>
                </div>
                <?php else: ?>
                <div class="login">
                  <a href="my-cart.php" class="login-btn">
                    <i class="fa fa-shopping-cart"></i><span class="d-none d-md-inline-block">My cart</span>
                  </a>
                  <a href="my-orders.php" class="login-btn">
                    <i class="fa fa-truck"></i><span class="d-none d-md-inline-block">My orders</span>
                  </a>
                  <a href="my-profile.php" class="signup-btn">
                    <i class="fa fa-user"></i><span class="d-none d-md-inline-block">Profile</span>
                  </a>
                </div>
                <?php endif; ?>
                <ul class="social-custom list-inline">
                  <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-envelope"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Top bar end-->

      <!-- Navbar Start-->
      <header class="nav-holder make-sticky">
        <div id="navbar" role="navigation" class="navbar navbar-expand-lg">
          <div class="container"><a href="/" class="navbar-brand home"><img src="img/Logo-v2.png" alt="Universal logo" class="d-inline-block" style="height: 40px;"><span class="sr-only">Universal - go to homepage</span></a>
            <button type="button" data-toggle="collapse" data-target="#navigation" class="navbar-toggler btn-template-outlined"><span class="sr-only">Toggle navigation</span><i class="fa fa-align-justify"></i></button>
            <div id="navigation" class="navbar-collapse collapse">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                  <a href="/">Home</a>
                </li>
                <li class="nav-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Products<b class="caret"></b></a>
                  <ul class="dropdown-menu megamenu">
                    <li>
                      <div class="row">
                        <div class="col-lg-6"><img src="/img/table-products-navbar.png" alt="" class="img-fluid d-none d-lg-block"></div>
                        <div class="col-lg-6 col-md-6">
                          <h5>Categories</h5>
                          <ul class="list-unstyled mb-3">
                            <li class="nav-item"><a href="shop.php" class="nav-link">All products</a></li>
                            <?php
                              // Retrieve categories
                              $categories_result = mysqli_query($conn, "SELECT name FROM product_category");

                              // Output as links
                              while($row = mysqli_fetch_assoc($categories_result)):
                            ?>
                            <li class="nav-item"><a href="shop.php?category=<?= $row["name"] ?>" class="nav-link"><?= $row["name"] ?></a></li>
                            <?php endwhile; ?>
                          </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="nav-item"><a href="/about.php">About Us</a></li>
                <li class="nav-item"><a href="/blog.php">Blog</a></li>
                <li class="nav-item"><a href="/contact.php">Contact</a></li>
              </ul>
            </div>
            <div id="search" class="collapse clearfix">
              <form role="search" class="navbar-form">
                <div class="input-group">
                  <input type="text" placeholder="Search" class="form-control"><span class="input-group-btn">
                    <button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button></span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </header>
      <!-- Navbar End-->

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
