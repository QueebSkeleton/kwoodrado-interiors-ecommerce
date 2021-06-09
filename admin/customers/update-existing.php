<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kwoodrado Interiors | Update Customer</title>

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
            <h1>Update a Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="panel.php">Customers</a></li>
              <li class="breadcrumb-item active">Update</li>
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
            $config = parse_ini_file("../../../config.ini");

            // Create connection to database
            $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

            // Prepare category select statement
            $select_stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE email_address = ?");

            // Bind category name
            mysqli_stmt_bind_param($select_stmt, "s", $_GET["email_address"]);

            // Execute select statement
            mysqli_stmt_execute($select_stmt);

            // Fetch the customer
            $customer_result = mysqli_stmt_get_result($select_stmt);

            // Get customer row
            $customer_row = mysqli_fetch_assoc($customer_result);
        ?>

        <form method="POST" action="actions/update-customer.php">
          <!-- Details card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="<?= $customer_row["first_name"] ?>" required>
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name" value="<?= $customer_row["last_name"] ?>" required>
              </div>
              <div class="form-group">
                <label for="phone_number">Contact Number</label>
                <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Enter 11-digit phone number" value="<?= $customer_row["phone_number"] ?>" required>
              </div>
              <div class="form-group">
                <label for="email_address">Email Address</label>
                <input type="hidden" name="current_email_address" value="<?= $customer_row["email_address"] ?>">
                <input type="email" name="email_address" class="form-control" id="email_address" placeholder="Enter email address" value="<?= $customer_row["email_address"] ?>" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
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
