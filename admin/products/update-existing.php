<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | General Form Elements</title>

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
            <h1>Update an Existing Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="panel.php">Products</a></li>
              <li class="breadcrumb-item active">Update Existing</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <?php
            // Parse config file, get db credentials
            $config = parse_ini_file("../../config.ini");

            // Create connection to database
            $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

            // Prepare product select statement
            $select_stmt = mysqli_prepare($conn, "SELECT * FROM product WHERE id = ?");

            // Bind product id
            mysqli_stmt_bind_param($select_stmt, "i", $_GET["id"]);

            // Execute select statement
            mysqli_stmt_execute($select_stmt);

            // Fetch the product
            $product_result = mysqli_stmt_get_result($select_stmt);

            // Get product row
            $product_row = mysqli_fetch_assoc($product_result);
        ?>

        <form method="POST" action="actions/update-product.php">
          <!-- Primary information card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Primary Information</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="hidden" name="id" value="<?= $product_row["id"] ?>">
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" value="<?= $product_row["name"] ?>">
              </div>
              <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" class="form-control" id="category_id">
                  <?php
                    // Get all categories
                    $categories_result = mysqli_query($conn, "SELECT id, name FROM product_category");

                    while($row = mysqli_fetch_assoc($categories_result)):
                  ?>
                  <option value="<?= $row["id"] ?>"<?= $row["id"] == $product_row["category_id"] ? "selected" : "" ?>><?= $row["name"] ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="summernote" id="description"><?= $product_row["description"] ?></textarea>
              </div>
              <div class="form-group">
                <label for="is_enabled">Show in store</label>
                <select name="is_enabled" class="form-control" id="is_enabled">
                  <option value="0" <?= $product_row["is_enabled"] ? "" : "selected"?>>Don't show</option>
                  <option value="1" <?= $product_row["is_enabled"] ? "selected" : ""?>>Show</option>
                </select>
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
                <input type="text" name="stock_keeping_unit" class="form-control" id="stock_keeping_unit" placeholder="Enter SKU" value="<?= $product_row["stock_keeping_unit"] ?>">
              </div>
              <div class="form-group">
                <label for="units_in_stock">Units in Stock</label>
                <input type="number" name="units_in_stock" class="form-control" id="units_in_stock" placeholder="Enter stock" value="<?= $product_row["units_in_stock"] ?>">
              </div>
              <div class="form-group">
                <label for="cost_per_item">Cost per item</label>
                <input type="number" step="any" name="cost_per_item" class="form-control" id="cost_per_item" placeholder="Enter initial costs for this item" value="<?= $product_row["cost_per_item"] ?>">
              </div>
              <div class="form-group">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" id="unit_price" placeholder="Enter unit price" value="<?= $product_row["unit_price"] ?>">
              </div>
              <div class="form-group">
                <label for="compare_to_price">Compare To Price</label>
                <input type="number" name="compare_to_price" class="form-control" id="compare_to_price" placeholder="Enter price to compare to" value="<?= $product_row["compare_to_price"] ?>">
              </div>
              <div class="form-group">
                <label for="is_taxable">Is this product taxable?</label>
                <input type="checkbox" name="is_taxable" <?= $product_row["is_taxable"] ? "checked" : "" ?> data-bootstrap-switch>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Finalize</button>
            </div>
            <!-- /.card-footer -->
          </div> <!-- /Stock and pricing information card -->
        </form>

        <?php
          // Close all db resources
          mysqli_stmt_close($select_stmt);
          mysqli_close($conn);
        ?>

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
