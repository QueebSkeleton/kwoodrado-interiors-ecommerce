<?php 

  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(400);
    die("Only GET requests are allowed.");
  }

  // If id is not given, throw bad request
  if(!isset($_GET["id"]) || empty($_GET["id"])) {
    http_response_code(400);
    die("Invalid product.");
  }

  // Class placeholder for product details
  class Product {
    public $name;
    public $category_name;
    public $description;
    public $unit_price;
    public $image_filenames;
  }

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../config.ini");

  // Create connection to db
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Get product by id
  $result = mysqli_query($conn, "SELECT product.*, product_image.local_filesystem_location FROM product LEFT JOIN product_image ON product_image.product_id = product.id WHERE product.id = ".$_GET["id"]);

  // Create product placeholder
  $product = new Product();
  
  // Fetch product details from first row
  if($first_row = mysqli_fetch_assoc($result)) {
    $product -> name = $first_row["name"];
    $product -> category_name = $first_row["category_name"];
    $product -> description = $first_row["description"];
    $product -> unit_price = $first_row["unit_price"];
    $product -> image_filenames = [];

    if(!is_null($first_row["local_filesystem_location"])) {
      $product -> image_filenames[] = $first_row["local_filesystem_location"];

      while($row = mysqli_fetch_assoc($result))
        if(!is_null($row["local_filesystem_location"]))
          $product -> image_filenames[] = $row["local_filesystem_location"];
    }
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <title><?= $product -> name ?> | Kwoodrado Interiors</title>
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
      
      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2"><?= $product -> name ?></h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/shop.php?category=<?= $product -> category_name ?>"><?= $product -> category_name ?></a></li>
                <li class="breadcrumb-item active"><?= $product -> name ?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar">
            <!-- LEFT COLUMN _________________________________________________________-->
            <div class="col-lg-9">
              <div id="productMain" class="row">
                <div class="col-sm-6">
                  <div data-slider-id="1" class="owl-carousel shop-detail-carousel">
                    <?php if(empty($product -> image_filenames)): ?>
                    <div> <img src="/img/empty-image.png" alt="" class="img-fluid"></div>
                    <?php else: foreach($product -> image_filenames as $image_filename): ?>
                    <div> <img src="/product-images/<?= $image_filename ?>" alt="" class="img-fluid"></div>
                    <?php endforeach; endif; ?>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="box">
                    <form>
                      <h2 class="text-center"><?= $product -> name ?></h2>
                      <p class="price">Php<?= number_format($product -> unit_price, 2) ?></p>
                      <p class="text-center">
                        <button type="submit" class="btn btn-template-outlined"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                      </p>
                    </form>
                  </div>
                  <div data-slider-id="1" class="owl-thumbs">
                    <?php if(empty($product -> image_filenames)): ?>
                    <button class="owl-thumb-item"><img src="/img/empty-image.png" alt="" style="width: 100px; height: 100px;"></button>
                    <?php else: foreach($product -> image_filenames as $image_filename): ?>
                    <button class="owl-thumb-item"><img src="/product-images/<?= $image_filename ?>" alt="" class="img-fluid" style="width: 100px; height: 100px;"></button>
                    <?php endforeach; endif; ?>
                  </div>
                </div>
              </div>
              <div id="details" class="box mb-4 mt-4">
                <?= $product -> description ?>
              </div>
              <div id="product-social" class="box social text-center mb-5 mt-5">
                <h4 class="heading-light">Show it to your friends</h4>
                <ul class="social list-inline">
                  <li class="list-inline-item"><a href="#" data-animate-hover="pulse" class="external facebook"><i class="fa fa-facebook"></i></a></li>
                  <li class="list-inline-item"><a href="#" data-animate-hover="pulse" class="external gplus"><i class="fa fa-google-plus"></i></a></li>
                  <li class="list-inline-item"><a href="#" data-animate-hover="pulse" class="external twitter"><i class="fa fa-twitter"></i></a></li>
                  <li class="list-inline-item"><a href="#" data-animate-hover="pulse" class="email"><i class="fa fa-envelope"></i></a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3">
              <!-- MENUS AND FILTERS-->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Categories</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm category-menu">
                    <?php
                      // Retrieve categories
                      $categories_result = mysqli_query($conn, "SELECT product_category.name, COUNT(product.id) AS product_count FROM product_category LEFT JOIN product ON product.category_name = product_category.name GROUP BY product.category_name");

                      // Output as links
                      while($row = mysqli_fetch_assoc($categories_result)):
                    ?>
                    <li class="nav-item"><a href="shop.php?category=<?= $row["name"] ?>" class="nav-link d-flex align-items-center justify-content-between"><span><?= $row["name"] ?> </span><span class="badge badge-secondary"><?= $row["product_count"] ?></span></a>
                    </li>
                    <?php endwhile; ?>
                  </ul>
                </div>
              </div>
              <div class="banner"><a href="shop-category.html"><img src="img/banner.jpg" alt="sales 2014" class="img-fluid"></a></div>
            </div>
          </div>
        </div>
      </div>
      
      <?php /* Footer */ include('include/footer.php'); ?>
    </div>

    <?php /* All scripts */ include('include/scripts.php'); ?>
  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>