<?php
  session_start();

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

      <?php /* Topbar */ include('include/topbar.php'); ?>

      <!-- Login Modal-->
      <div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalLabel" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 id="login-modalLabel" class="modal-title">Customer Login</h4>
              <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <form action="customer-orders.html" method="get">
                <div class="form-group">
                  <input id="email_modal" type="text" placeholder="email" class="form-control">
                </div>
                <div class="form-group">
                  <input id="password_modal" type="password" placeholder="password" class="form-control">
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

      <section style="background: url('img/landing-page-carousel-bg.jpeg') center; background-size: cover;" class="bar background-white relative-positioned">
        <div class="container">
          <!-- Carousel Start-->
          <div class="home-carousel">
            <div class="dark-mask mask-primary"></div>
            <div class="container">
              <div class="homepage owl-carousel">
                <div class="item">
                  <div class="row">
                    <div class="col-md-5 text-right">
                      <p><img src="img/Logo-v2.png" alt="" class="ml-auto"></p>
                      <h1>Your wooden furniture store</h1>
                      <p>Crafstmanship. Elegance.<br>Tables. Chairs. E-commerce.</p>
                    </div>
                    <div class="col-md-7"><img src="img/carousel-1.png" alt="" class="img-fluid"></div>
                  </div>
                </div>
                <div class="item">
                  <div class="row">
                    <div class="col-md-7 text-center"><img src="img/carousel-2.png" alt="" class="img-fluid"></div>
                    <div class="col-md-5">
                      <h2>Wide selection range of furniture</h2>
                      <ul class="list-unstyled">
                        <li>Dining sets</li>
                        <li>Rocking chairs</li>
                        <li>Wooden dresser tables</li>
                        <li>+ many more</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Carousel End-->
        </div>
      </section>
      <section class="bar background-white">
        <div class="container text-center">
          <h1 class="h2 mb-4">NEW PRODUCTS</h1>
          <div class="row products products-big">
            <?php
              // Get new products (products added 5 days ago)
              $products_result = mysqli_query($conn,
                "SELECT product.id, product.name, product.unit_price, product.compare_to_price, ".
                "product_image.local_filesystem_location AS image_location FROM product ".
                "LEFT JOIN (SELECT DISTINCT(product_id), MIN(local_filesystem_location) AS local_filesystem_location ".
                "FROM product_image GROUP BY product_id) AS product_image ON product_image.product_id = product.id ".
                "WHERE DATE(product.added_on) >= SUBDATE(NOW(), 5) LIMIT 8");

              // Display all products
              while($row = mysqli_fetch_assoc($products_result)):
            ?>
            <div class="col-lg-3 col-md-6">
              <div class="product">
                <div class="image"><a href="product.php?id=<?= $row["id"] ?>"><img src="<?= is_null($row["image_location"]) ? "/img/empty-image.png" : "/product-images/".$row["image_location"] ?>" alt="" class="img-fluid image1"></a></div>
                <div class="text">
                  <h3 class="h5"><a href="product.php?id=<?= $row["id"] ?>"><?= $row["name"] ?></a></h3>
                  <p class="price">Php<?= number_format($row["unit_price"], 2) ?></p>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <section style="background: url(img/fixed-background-2.jpg) center top no-repeat; background-size: cover;" class="bar no-mb color-white text-center bg-fixed relative-positioned">
        <div class="dark-mask"></div>
        <div class="container">
          <div class="icon icon-outlined icon-lg"><i class="fa fa-shopping-cart"></i></div>
          <h3 class="text-uppercase">See our wide selection of products</h3>
          <p class="lead">We have prepared for you different wooden tables and chairs. Few come with sets in cheaper prices.</p>
          <p class="text-center"><a href="shop.php" class="btn btn-template-outlined-white btn-lg">Shop</a></p>
        </div>
      </section>
      <section class="bar background-white">
        <div class="container text-center">
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-desktop"></i></div>
                <h3 class="h4">Elegance</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-print"></i></div>
                <h3 class="h4">Aesthetic</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-globe"></i></div>
                <h3 class="h4">Craftsmanship</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-lightbulb-o"></i></div>
                <h3 class="h4">New ideas</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-envelope-o"></i></div>
                <h3 class="h4">Newsletter</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="box-simple">
                <div class="icon-outlined"><i class="fa fa-user"></i></div>
                <h3 class="h4">Creativity</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas odio ante, fringilla a rhoncus ut, mattis non massa. Sed arcu nisl, posuere id faucibus et, condimentum sed nunc. Proin vitae luctus enim, a volutpat risus. Nullam finibus, mauris quis venenatis viverra, quam purus imperdiet purus, ac bibendum magna eros vitae dolor. Fusce a ultricies leo, ut mattis arcu.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php /* Footer */ include('include/footer.php'); ?>
    </div>

    <?php /* All scripts */ include('include/scripts.php'); ?>

  </body>
</html>
