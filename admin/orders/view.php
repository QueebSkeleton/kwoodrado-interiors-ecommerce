<?php
  // Only allow GET method
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(400);
    die("Error: Invalid request method. Only GET method is allowed.");
  }

  // Check if order id request parameter exists and is valid
  if(!(isset($_GET["id"]) && is_numeric($_GET["id"]))) {
    http_response_code(400);
    die("Error: No valid order id was specified in the request parameters.");
  }
  
  require("classes/Order.php");

  // Placeholder for fetched order
  $placed_order = new PlacedOrder();

  // Parse configuration file
  $config = parse_ini_file("../../../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Fetch order by id
  $order_result = mysqli_query($conn, "SELECT customer.first_name, customer.last_name, customer.phone_number,".
    " customer.email_address, placed_order.billing_address, placed_order.shipping_address, placed_order.additional_notes,".
    " placed_order.status, product.name, placed_order_item.quantity, placed_order_item.final_unit_price,".
    " placed_order_item.is_taxable FROM placed_order LEFT JOIN customer ON customer.email_address = placed_order.customer_email_address".
    " LEFT JOIN placed_order_item ON placed_order_item.order_id = placed_order.id".
    " LEFT JOIN product ON product.id = placed_order_item.product_id WHERE placed_order.id = ".$_GET["id"]);

  // Check if there is an order with that id
  // by checking if the number of rows returned is 0
  // If so, don't proceed and return 400
  if(mysqli_num_rows($order_result) == 0) {
    mysqli_close($conn);
    http_response_code(400);
    die("Error: The given order id does not correspond any existing order.");
  }

  // Parse the order from the result set
  // First row always contains order data + 1st order item, so hardcode
  $first_row = mysqli_fetch_assoc($order_result);

  $placed_order -> id = intval($_GET["id"]);
  $placed_order -> customer_name = $first_row["first_name"]." ".$first_row["last_name"];
  $placed_order -> customer_phone_number = $first_row["phone_number"];
  $placed_order -> customer_email_address = $first_row["email_address"];
  $placed_order -> billing_address = $first_row["billing_address"];
  $placed_order -> shipping_address = $first_row["shipping_address"];
  $placed_order -> additional_notes = $first_row["additional_notes"];
  $placed_order -> status = $first_row["status"];

  // If order item quantity is not null, then there is at least 1 item in this order
  // in that case, initialize the $items variable in $placed_order as an array
  if(!empty($first_row["quantity"])) {
    $first_order_item = new PlacedOrderItem();
    $first_order_item -> product_name = $first_row["name"];
    $first_order_item -> quantity = intval($first_row["quantity"]);
    $first_order_item -> final_unit_price = floatval($first_row["final_unit_price"]);
    $first_order_item -> is_taxable = boolval($first_row["is_taxable"]);
    $first_order_item -> subtotal = $first_order_item -> quantity * $first_order_item -> final_unit_price;
    $placed_order -> items = [$first_order_item];

    // If product is taxable, calculate tax of this product then add to total tax
    // TODO: currently this tax scheme is fixed, please change soon
    if($first_order_item -> is_taxable) {
      $placed_order -> tax += $first_order_item -> final_unit_price * $first_order_item -> quantity * 0.12;
    }

    // Calculate then add subtotal to order total
    $placed_order -> total += $first_order_item -> subtotal;
  }

  // Fetch the remaining order items
  while($row = mysqli_fetch_assoc($order_result)) {
    $order_item = new PlacedOrderItem();
    $order_item -> product_name = $row["name"];
    $order_item -> quantity = intval($row["quantity"]);
    $order_item -> final_unit_price = floatval($row["final_unit_price"]);
    $order_item -> is_taxable = boolval($row["is_taxable"]);
    $order_item -> subtotal = $order_item -> quantity * $order_item -> final_unit_price;
    $placed_order -> items[] = $order_item;

    // If product is taxable, calculate tax of this product then add to total tax
    // TODO: currently this tax scheme is fixed, please change soon
    if($order_item -> is_taxable) {
      $placed_order -> tax += $order_item -> final_unit_price * $order_item -> quantity * 0.12;
    }

    // Calculate then add subtotal to order total
    $placed_order -> total += $order_item -> subtotal;
  }

  // Close the database connection
  mysqli_close($conn);

  // Finalize order data
  // Calculate sales without tax
  $placed_order -> sale_without_tax = $placed_order -> total - $placed_order -> tax;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kwoodrado Interiors | View Order</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
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
            <h1>View Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="all-orders.php">Orders</a></li>
              <li class="breadcrumb-item active">View Order</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Order Details Card -->
        <div class="card">
          <div class="ribbon-wrapper ribbon-lg">
            <?php if($placed_order -> status == "NEW"): ?>
            <div class="ribbon bg-primary text-lg">
              New Order
            </div>
            <?php elseif($placed_order -> status == "PROCESSING"): ?>
            <div class="ribbon bg-info text-lg">
              Processing
            </div>
            <?php elseif($placed_order -> status == "DELIVERED"): ?>
            <div class="ribbon bg-success text-lg">
              Delivered
            </div>
            <?php elseif($placed_order -> status == "DENIED"): ?>
            <div class="ribbon bg-danger text-lg">
              Denied
            </div>
            <?php elseif($placed_order -> status == "CANCELLED"): ?>
            <div class="ribbon bg-warning text-lg">
              Cancelled
            </div>
            <?php endif; ?>
          </div>
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-table"></i>
              Details
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="id">Order #:</label>
                  <input type="number" class="form-control" id="id" value="<?= $placed_order -> id ?>" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="customer_name">Customer Name:</label>
                  <input type="text" class="form-control" id="customer_name" value="<?= $placed_order -> customer_name ?>" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="email_address">Email Address:</label>
                  <input type="text" class="form-control" id="email_address" value="<?= $placed_order -> customer_email_address ?>" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="phone_number">Phone Number:</label>
                  <input type="text" class="form-control" id="phone_number" value="<?= $placed_order -> customer_phone_number ?>" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="billing_address">Billing Address:</label>
                  <textarea readonly class="form-control" id="billing_address" rows="3"><?= $placed_order -> billing_address ?></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="shipping_address">Shipping Address:</label>
                  <textarea readonly class="form-control" id="shipping_address" rows="3"><?= $placed_order -> shipping_address ?></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="additional_notes">Additional Notes:</label>
                  <textarea readonly class="form-control" id="additional_notes"><?= $placed_order -> additional_notes ?></textarea>
                </div>
              </div>
              <div class="col-md-12 table-responsive">
                <label>Items in this Order</label>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Is product taxable?</th>
                      <th>Unit price</th>
                      <th>Quantity ordered</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($placed_order -> items != null): foreach($placed_order -> items as $item): ?>
                      <tr>
                        <td><?= $item -> product_name ?></td>
                        <td>
                          <?php if($item -> is_taxable): ?>
                          <span class="badge badge-success">Yes</span>
                          <?php else: ?>
                          <span class="badge badge-danger">No</span>
                          <?php endif; ?>
                        </td>
                        <td><?= number_format($item -> final_unit_price, 2) ?></td>
                        <td><?= $item -> quantity ?></td>
                        <td><?= number_format($item -> subtotal, 2)?></td>
                      </tr>
                    <?php endforeach; else: ?>
                      <tr>
                        <td colspan="5" class="text-center">No items available for view on this order</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="sale_without_tax">Sales Made with this Order:</label>
                  <input type="text" class="form-control" id="sale_without_tax" value="<?= number_format($placed_order -> sale_without_tax, 2) ?>" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="tax">Tax from Taxable Items:</label>
                  <input type="text" class="form-control" id="tax" value="<?= number_format($placed_order -> tax, 2) ?>" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="total">Total:</label>
                  <input type="text" class="form-control" id="total" value="<?= number_format($placed_order -> total, 2) ?>" readonly>
                </div>
              </div>
            </div>

          </div>
          <!-- /.card-body -->
        </div>
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
<!-- AdminLTE App -->
<script src="/admin/dist/js/adminlte.min.js"></script>
</body>
</html>
