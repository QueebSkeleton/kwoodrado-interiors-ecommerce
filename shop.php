<?php

  session_start();

  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(400);
    die("Only GET requests are allowed.");
  }

  $category = isset($_GET["category"]) && !empty($_GET["category"]) ? $_GET["category"] : NULL;
  $limit = 9;
  $page = isset($_GET["page"]) ? $_GET["page"] : 1;
  $offset = $limit * ($page - 1);

  // Create connection to db
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  if($category != NULL) {
    $category_result = mysqli_query($conn, "SELECT product_category.*, COALESCE(COUNT(product.id), 0) AS total_product_count FROM product_category LEFT JOIN product ON product.category_name = product_category.name WHERE product.is_enabled AND product.units_in_stock > 0 AND product_category.name = '".$category."'");
    $category_row = mysqli_fetch_assoc($category_result);
    $total_product_count = $category_row["total_product_count"];
  } else {
    $category_result = mysqli_query($conn, "SELECT COALESCE(COUNT(product.id), 0) AS total_product_count FROM product WHERE product.is_enabled AND product.units_in_stock > 0");
    $category_row = mysqli_fetch_assoc($category_result);
    $total_product_count = $category_row["total_product_count"];
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | Shop</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        include("include/topbar.php");
        include("include/navbar.php");
      ?>

      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2"><?= $category == NULL ? "All Products" : $category; ?></h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active"><?= $category == NULL ? "All Products" : $category; ?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar">
            <div class="col-md-9">
              <?php if($total_product_count > 0): ?>
              <?= $category == NULL ? "<p class=\"text-muted lead\">You are currently browsing a list of all our offered products. Find a furniture that fits your liking.</p>" : $category_row["description"] ?>
              <?php else: ?>
              <p class="text-muted lead">You're viewing a category that does not contain any products yet. Choose another.</p>
              <?php endif ?>
              <div class="row products products-big">
                <?php
                  // Get products
                  if($category == NULL) {
                    $products_result = mysqli_query($conn, "SELECT product.id, product.name, product.unit_price, product.compare_to_price, product_image.local_filesystem_location AS image_location FROM product LEFT JOIN (SELECT DISTINCT(product_id), MIN(local_filesystem_location) AS local_filesystem_location FROM product_image GROUP BY product_id) AS product_image ON product_image.product_id = product.id WHERE product.is_enabled AND product.units_in_stock > 0 LIMIT ".$limit." OFFSET ".$offset);
                  } else {
                    $products_result = mysqli_query($conn, "SELECT product.id, product.name, product.unit_price, product.compare_to_price, product_image.local_filesystem_location AS image_location FROM product LEFT JOIN (SELECT DISTINCT(product_id), MIN(local_filesystem_location) AS local_filesystem_location FROM product_image GROUP BY product_id) AS product_image ON product_image.product_id = product.id WHERE product.is_enabled AND product.units_in_stock > 0 AND product.category_name = '".$category."' LIMIT ".$limit." OFFSET ".$offset);
                  }

                  while($row = mysqli_fetch_assoc($products_result)):
                ?>
                <div class="col-lg-4 col-md-6">
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
              <div class="pages">
                <nav aria-label="Page navigation example" class="d-flex justify-content-center">
                  <ul class="pagination">
                    <li class="page-item<?= $page == 1 ? " disabled" : "" ?>">
                      <a href="<?= $page > 1 ? "/shop.php?".($category == NULL ? "" : "category=".$category."&")."page=".($page - 1) : "#"  ?>" class="page-link">
                        <i class="fa fa-angle-double-left"></i>
                      </a>
                    </li>
                    <?php
                      $total_number_of_pages = ceil(floatval($total_product_count) / $limit);

                      for($i = 1; $i <= $total_number_of_pages; $i++):
                    ?>
                    <li class="page-item<?= $page == $i ? " active" : "" ?>"><a href="/shop.php?<?= $category == NULL ? "" : "category=".$category."&" ?>page=<?= $i ?>" class="page-link"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item<?= $page == $total_number_of_pages ? " disabled" : "" ?>">
                      <a href="<?= $page < $total_number_of_pages ? "/shop.php?".($category == NULL ? "" : "category=".$category."&")."page=".($page + 1) : "#"  ?>" class="page-link">
                        <i class="fa fa-angle-double-right"></i>
                      </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
            <div class="col-md-3">
              <!-- MENUS AND FILTERS-->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Categories</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm category-menu">
                    <?php
                      // Retrieve categories
                      $categories_result = mysqli_query($conn, "SELECT product_category.name, COALESCE(product_results.total_count, 0) AS total_count FROM product_category LEFT JOIN (SELECT category_name, COUNT(id) AS total_count FROM product WHERE is_enabled AND units_in_stock > 0 GROUP BY category_name) AS product_results ON product_results.category_name = product_category.name");

                      // Output as links
                      while($row = mysqli_fetch_assoc($categories_result)):
                    ?>
                    <li class="nav-item"><a href="shop.php?category=<?= $row["name"] ?>" class="nav-link d-flex align-items-center justify-content-between"><span><?= $row["name"] ?> </span><span class="badge badge-secondary"><?= $row["total_count"] ?></span></a>
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

    <?php /* login modal */ if(!isset($_SESSION["email_address"])) include('include/login-register-modals.php'); ?>
  </body>
</html>

<?php
  // Close resources
  mysqli_close($conn);
?>
