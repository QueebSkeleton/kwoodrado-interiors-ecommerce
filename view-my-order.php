<?php
  session_start();

  // If user is not yet logged in, redirect to login page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to login page.'); ".
    "window.location.replace('/login.php');</script></body></html>");
  }

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

  require("admin/orders/classes/Order.php");

  // Placeholder for fetched order
  $placed_order = new PlacedOrder();

  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

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

  // Finalize order data
  // Calculate sales without tax
  $placed_order -> sale_without_tax = $placed_order -> total - $placed_order -> tax;

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kwoodrado Interiors | View an Order</title>
    <?php include('include/head-tags.php'); ?>
  </head>
  <body>
    <div id="all">
      <?php
        include("include/navbar.php");
      ?>

      <div id="heading-breadcrumbs">
        <div class="container">
          <div class="row d-flex align-items-center flex-wrap">
            <div class="col-md-7">
              <h1 class="h2">Order # <?= $placed_order -> id ?></h1>
            </div>
            <div class="col-md-5">
              <ul class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="my-orders.php">My Orders</a></li>
                <li class="breadcrumb-item active">Order # <?= $placed_order -> id ?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div id="content">
        <div class="container">
          <div class="row bar">
            <div id="customer-order" class="col-lg-9">
              <p class="lead">Order #1735 was placed on <strong>22/06/2013</strong> and is currently <strong>Being prepared</strong>.</p>
              <p class="lead text-muted">If you have any questions, please feel free to <a href="contact.html">contact us</a>, our customer service center is working for you 24/7.</p>
              <div class="box">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th colspan="2" class="border-top-0">Product</th>
                        <th class="border-top-0">Quantity</th>
                        <th class="border-top-0">Unit price</th>
                        <th class="border-top-0">Taxable</th>
                        <th class="border-top-0">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if($placed_order -> items != null): foreach($placed_order -> items as $item): ?>
                        <tr>
                          <td colspan="2"><?= $item -> product_name ?></td>
                          <td><?= $item -> quantity ?></td>
                          <td>Php<?= number_format($item -> final_unit_price, 2) ?></td>
                          <td>
                            <?php if($item -> is_taxable): ?>
                            <span class="badge badge-success">Yes</span>
                            <?php else: ?>
                            <span class="badge badge-danger">No</span>
                            <?php endif; ?>
                          </td>
                          <td><?= number_format($item -> subtotal, 2)?></td>
                        </tr>
                      <?php endforeach; else: ?>
                        <tr>
                          <td colspan="5" class="text-center">No items available for view on this order</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                          <th colspan="5" class="text-right">Subtotal (without tax)</th>
                          <th>Php<?= number_format($placed_order -> sale_without_tax, 2) ?></th>
                        </tr>
                      <tr>
                        <th colspan="5" class="text-right">Value Added Tax</th>
                        <th>Php<?= number_format($placed_order -> tax, 2) ?></th>
                      </tr>
                      <tr>
                        <th colspan="5" class="text-right">Delivery Fees</th>
                        <th>Php100.00</th>
                      </tr>
                      <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>Php<?= number_format($placed_order -> total + 100, 2) ?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="row addresses">
                  <div class="col-md-6 text-right">
                    <h3 class="text-uppercase">Invoice address</h3>
                    <p><?= $placed_order -> billing_address ?></p>
                  </div>
                  <div class="col-md-6 text-right">
                    <h3 class="text-uppercase">Shipping address</h3>
                    <p><?= $placed_order -> shipping_address ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 mt-4 mt-md-0">
              <!-- CUSTOMER MENU -->
              <div class="panel panel-default sidebar-menu">
                <div class="panel-heading">
                  <h3 class="h4 panel-title">Customer section</h3>
                </div>
                <div class="panel-body">
                  <ul class="nav nav-pills flex-column text-sm">
                    <li class="nav-item"><a href="my-orders.php" class="nav-link active"><i class="fa fa-list"></i> My orders</a></li>
                    <li class="nav-item"><a href="my-profile.php" class="nav-link"><i class="fa fa-user"></i> My account</a></li>
                    <li class="nav-item"><a href="user_logout.php" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a></li>
                  </ul>
                </div>
              </div>
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
