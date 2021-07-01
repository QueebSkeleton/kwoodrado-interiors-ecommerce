<?php
  session_start();

  if(isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re already logged in. Redirecting you to home page.'); ".
    "window.location.replace('/');</script></body></html>");
  }

  // Parse configuration file
  $config = parse_ini_file("../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | Welcome</title>
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
                  <a href="#" data-toggle="modal" data-target="#cart-modal" class="login-btn">
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
                  <a href="#" data-toggle="modal" data-target="#cart-modal" class="login-btn">
                    <i class="fa fa-shopping-cart"></i><span class="d-none d-md-inline-block">My cart</span>
                  </a>
                  <a href="my-orders.php" class="login-btn">
                    <i class="fa fa-truck"></i><span class="d-none d-md-inline-block">My orders</span>
                  </a>
                  <a href="profile.php" class="signup-btn">
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
          <div class="container"><a href="index.html" class="navbar-brand home"><img src="img/Logo-v2.png" alt="Universal logo" class="d-inline-block" style="height: 40px;"><span class="sr-only">Universal - go to homepage</span></a>
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
              <h1 class="h2">Sign In</h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Sign In</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="box">
                <h2 class="text-uppercase">Login</h2>
                <p class="lead">Already our customer?</p>
                <p class="text-muted">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
                <hr>
                <form action="user_login.php" method="post" id="user-login-form">
                  <div class="form-group">
                    <label for="email_address">Email</label>
                    <input id="email_address" type="text" name="email_address" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-control">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-template-outlined"><i class="fa fa-sign-in"></i> Log in</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php /* Footer */ include('include/footer.php'); ?>
    </div>

    <?php /* All scripts */ include('include/scripts.php'); ?>

    <script>
      $("#user-login-form").submit(function(e) {
        e.preventDefault();
        $.ajax({
          url: $(this).attr("action"),
          type: $(this).attr("method"),
          data: $(this).serialize(),
          statusCode: {
            200: function(data) {
              alert("Successfully logged in! Redirecting you to home page.");
              window.location.replace("/");
            },
            401: function(data) {
              alert("Invalid credentials. Please try again.");
              $("#login-form").find("input[name='email_address']").val("");
              $("#login-form").find("input[name='password']").val("");
            }
          }
        });
      });
    </script>

    <?php /* login modal */ if(!isset($_SESSION["email_address"])) include('include/login-register-modals.php'); ?>
  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>
