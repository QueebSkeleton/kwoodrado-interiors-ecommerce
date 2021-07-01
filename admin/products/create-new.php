<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kwoodrado Interiors | Add Product</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="/admin/plugins/summernote/summernote-bs4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/admin/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("../include/navbar.php"); ?>

  <!-- Sidebar -->
  <?php include("../include/sidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create a Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="panel.php">Products</a></li>
              <li class="breadcrumb-item active">Add</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <form method="POST" action="actions/create-product.php" enctype="multipart/form-data">
          <!-- Primary information card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Primary Information</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
              </div>
              <div class="form-group">
                <label for="category_name">Category</label>
                <select name="category_name" class="form-control" id="category_name">
                  <option value="">None</option>
                  <?php
                    // Get all categories from database then output as option tags
                    // Parse config.ini file then get db credentials
                    $config = parse_ini_file("../../../config.ini");
                    // Create connection to db
                    $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);
                    // Get all categories
                    $categories_result = mysqli_query($conn, "SELECT name FROM product_category");

                    while($row = mysqli_fetch_assoc($categories_result)):
                  ?>
                  <option value="<?= $row["name"] ?>"><?= $row["name"] ?></option>
                  <?php endwhile; mysqli_close($conn); ?>
                </select>
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="summernote" id="description"></textarea>
              </div>
              <div class="form-group">
                <label for="is_enabled">Show in store</label>
                <select name="is_enabled" class="form-control" id="is_enabled">
                  <option value="0">Don't show</option>
                  <option value="1">Show</option>
                </select>
              </div>
              <div class="form-group">
                <label for="images">Images</label>
                <input type="file" multiple name="images[]" class="form-control" id="images">
              </div>
            </div>
          </div> <!-- /Primary information card -->

          <!-- Stock and pricing information card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Stock and Pricing Information</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="stock_keeping_unit">Stock Keeping Unit</label>
                <input type="text" name="stock_keeping_unit" class="form-control" id="stock_keeping_unit" placeholder="Enter SKU">
              </div>
              <div class="form-group">
                <label for="units_in_stock">Units in Stock</label>
                <input type="number" name="units_in_stock" class="form-control" id="units_in_stock" placeholder="Enter initial stock">
              </div>
              <div class="form-group">
                <label for="cost_per_item">Cost per item</label>
                <input type="number" step="any" name="cost_per_item" class="form-control" id="cost_per_item" placeholder="Enter initial costs for this item">
              </div>
              <div class="form-group">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" id="unit_price" placeholder="Enter unit price">
              </div>
              <div class="form-group">
                <label for="compare_to_price">Compare To Price</label>
                <input type="number" name="compare_to_price" class="form-control" id="compare_to_price" placeholder="Enter price to compare to">
              </div>
              <div class="form-group">
                <label for="is_taxable">Is this product taxable?</label>
                <input type="checkbox" name="is_taxable" checked data-bootstrap-switch>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Finalize</button>
            </div>
            <!-- /.card-footer -->
          </div> <!-- /Stock and pricing information card -->
        </form>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Footer -->
  <?php include("../include/footer.php"); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin/dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="/admin/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Page specific script -->
<script>
  $("textarea.summernote").summernote();
  $("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });
</script>
</body>
</html>
