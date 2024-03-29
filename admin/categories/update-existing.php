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
  <title>Kwoodrado Interiors | Update Category</title>

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
            <h1>Update an Existing Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="panel.php">Categories</a></li>
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
            // Create connection to database
            require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
            $conn = get_connection();

            // Prepare category select statement
            $select_stmt = mysqli_prepare($conn, "SELECT * FROM product_category WHERE name = ?");

            // Bind category name
            mysqli_stmt_bind_param($select_stmt, "s", $_GET["name"]);

            // Execute select statement
            mysqli_stmt_execute($select_stmt);

            // Fetch the category
            $category_result = mysqli_stmt_get_result($select_stmt);

            // Get category row
            $category_row = mysqli_fetch_assoc($category_result);
        ?>

        <form method="POST" action="actions/update-category.php">
          <!-- Primary information card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <input type="hidden" name="current_name" value="<?= $category_row["name"] ?>">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" value="<?= $category_row["name"] ?>">
              </div>
              <div class="form-group">
                <label for="parent_category_name">Parent Category</label>
                <select name="parent_category_name" class="form-control" id="parent_category_name">
                  <option value=""<?= empty($category_row["parent_category_name"]) ? "selected" : "" ?>>None</option>
                  <?php
                    // Get all categories
                    $categories_result = mysqli_query($conn, "SELECT name FROM product_category WHERE name != '".$_GET["name"]."'");

                    while($row = mysqli_fetch_assoc($categories_result)):
                  ?>
                  <option value="<?= $row["name"] ?>"<?= $category_row["parent_category_name"] == $row["name"] ? "selected" : "" ?>><?= $row["name"] ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="summernote" id="description"><?= $category_row["description"] ?></textarea>
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
