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
  <title>Kwoodrado Interiors | Orders Panel</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
            <h1>View Cancelled Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Orders</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">List of Cancelled Orders</h3>
          </div>
          <!-- /.card-header -->
          <?php
            // Create connection to database
            require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
            $conn = get_connection();

            // Retrieve orders
            $orders_result = mysqli_query($conn, "SELECT placed_order.id, customer.first_name, customer.".
              "last_name, placed_order.placed_in, placed_order.status FROM placed_order ".
              "LEFT JOIN customer ON customer.email_address = placed_order.customer_email_address WHERE placed_order.status = 'CANCELLED'");
          ?>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover text-nowrap datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Placed by</th>
                    <th>Date Made</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if(mysqli_num_rows($orders_result) > 0):
                      while($row = mysqli_fetch_assoc($orders_result)): ?>
                  <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["first_name"]." ".$row["last_name"] ?></td>
                    <td><?= date_format(date_create($row["placed_in"]), "M d, Y | h:m A") ?></td>
                    <td><?= $row["status"] ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="view.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-info">
                          <i class="fas fa-eye"></i> View
                        </a>
                        <a href="actions/delete-product.php?id=<?= $row["id"] ?>"
                          class="btn btn-sm btn-danger btn-delete-product"
                          onclick="return confirm('Proceed to delete order by: <?= $row["first_name"]." ".$row["last_name"] ?>?');">
                          <i class="fas fa-trash"></i> Delete
                        </a>
                      </div>
                    </td>
                  </tr>
                  <?php
                    endwhile;
                    else: ?>
                  <tr>
                    <td colspan="5" class="text-center">There are no cancelled orders as of the moment.</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
          </div>
        </div>
        <?php mysqli_close($conn); ?>
        <!-- /.card -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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
<!-- DataTables  & Plugins -->
<script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/admin/plugins/jszip/jszip.min.js"></script>
<script src="/admin/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/admin/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/admin/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/admin/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/admin/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin/dist/js/adminlte.min.js"></script>
<script>
  $(".datatable").DataTable();
</script>
</body>
</html>
