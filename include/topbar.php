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
