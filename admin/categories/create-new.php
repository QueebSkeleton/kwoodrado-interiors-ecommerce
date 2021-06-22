<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kwoodrado Interiors | Add Category</title>

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
            <h1>Create a Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="panel.php">Categories</a></li>
              <li class="breadcrumb-item active">Add</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <form method="POST" action="actions/create-category.php">
          <!-- Details card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
              </div>
              <div class="form-group">
                <label for="thumbnail">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control" id="thumbnail">
              </div>
              <div class="form-group">
                <label for="parent_category_name">Parent Category</label>
                <select name="parent_category_name" class="form-control" id="parent_category_name">
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
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Finalize</button>
            </div>
            <!-- /.card-footer -->
          </div> <!-- /Details card -->
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
