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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
            <h1>View Processing Orders</h1>
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
            <h3 class="card-title">List of Processing Orders</h3>
          </div>
          <!-- /.card-header -->
          <?php
            // Parse configuration file
            $config = parse_ini_file("../../../config.ini");

            // Create connection to database
            $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

            // Retrieve orders
            $orders_result = mysqli_query($conn, "SELECT placed_order.id, customer.first_name, customer.".
              "last_name, placed_order.placed_in, placed_order.status FROM placed_order ".
              "LEFT JOIN customer ON customer.email_address = placed_order.customer_email_address WHERE placed_order.status = 'PROCESSING'");
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
                        <a href="/invoice.php?id=<?= $row["id"] ?>" target="_blank" class="btn btn-sm btn-warning">
                          <i class="fas fa-print"></i> Invoice
                        </a>
                        <button class="btn btn-sm btn-success btn-success btn-deliver-order"
                          data-toggle="modal" data-target="#deliver-order-modal" data-id="<?= $row["id"] ?>">
                          <i class="fas fa-truck"></i> Deliver
                        </button>
                      </div>
                    </td>
                  </tr>
                  <?php
                    endwhile;
                    else: ?>
                  <tr>
                    <td colspan="5" class="text-center">There are no processing orders as of the moment.</td>
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

<!-- Deliver Order Modal -->
<div class="modal fade" id="deliver-order-modal">
  <div class="modal-dialog">
    <div class="modal-content">

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- jQuery -->
<script src="/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
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
<!-- Page specific script -->
<script>
  // Deliver order button click
  $("button.btn-deliver-order").click(function() {
    $.ajax({
      url: "/admin/orders/modals/deliver-order-modal.php?id=" + $(this).data("id"),
      dataType: "html",
      success: function(data) {
        $("#deliver-order-modal > .modal-dialog > .modal-content").empty();
        $("#deliver-order-modal > .modal-dialog > .modal-content").append(data);
      }
    });
  });

  // Initialize a sweet alert toast
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  // Final process button click
  $(document).on("click", "button#btn-final-deliver", function() {
    $.ajax({
      url: $(this).data("href"),
      success: function(data) {
        // show a success toast
        Toast.fire({
          icon: 'success',
          title: 'Order is now set to delivered. Sales reports have been updated.'
        });
        // hide the modal
        $("#deliver-order-modal").modal("hide");
        // reload the page after 2 seconds
        setTimeout(function() {
          location.reload();
        }, 2000);
      },
      error: function() {
        // show a success toast
        Toast.fire({
          icon: 'error',
          title: 'An error occured while processing your request. Please try again after a while.'
        });
        // hide the modal
        $("#process-order-modal").modal("hide");
      }
    });
  });
</script>
</body>
</html>
