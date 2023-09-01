<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | Welcome</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        include("include/topbar.php");
        include("include/navbar.php");
      ?>

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
                "WHERE DATE(product.added_on) >= SUBDATE(NOW(), 5) AND product.units_in_stock > 0 AND product.is_enabled LIMIT 8");

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

    <?php /* login modal */ if(!isset($_SESSION["email_address"])) include('include/login-register-modals.php'); ?>

  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>
