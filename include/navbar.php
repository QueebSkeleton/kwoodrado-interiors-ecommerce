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
