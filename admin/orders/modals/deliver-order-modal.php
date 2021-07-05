<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }

  // Only allow GET requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only GET requests are allowed.");
  }

  // Check if order id exists in request parameters
  if(!(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"]))) {
    header("Status: 400");
    die("Invalid order id given.");
  }

  require("../classes/Order.php");

  // Parse configuration file
  $config = parse_ini_file("../../../../config.ini");

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

  // Placeholder for fetched order
  $placed_order = new PlacedOrder();

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
    $placed_order -> items[] = $first_order_item;

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
<div class="modal-header">
  <h4 class="modal-title">Finalize and Deliver</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
 <div class="callout callout-info">
    <h5><i class="fas fa-info-circle"></i> Deliver orders</h5>
    <p>After flagging an order to delivered, sales will be finalized and recorded, and will be shown in your reports panel.</p>
  </div>

  <!-- Order details -->
  <div class="form-group">
    <label>Customer:</label>
    <input type="text" readonly value="<?= $placed_order -> customer_name ?>" class="form-control">
  </div>
  <div class="form-group">
    <label>Email Address:</label>
    <input type="text" readonly value="<?= $placed_order -> customer_email_address ?>" class="form-control">
  </div>
  <div class="form-group">
    <label>Billing Address:</label>
    <input type="text" readonly value="<?= $placed_order -> billing_address ?>" class="form-control">
  </div>
  <div class="form-group">
    <label>Shipping Address:</label>
    <input type="text" readonly value="<?= $placed_order -> shipping_address ?>" class="form-control">
  </div>

  <!-- Items -->
  <div class="table-responsive">
    <table class="table table-sm table-hover">
      <thead>
        <tr>
          <th>Product</th>
          <th>Unit Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Taxable</th>
        </tr>
      </thead>
      <tbody>
        <?php if($placed_order -> items != null): foreach($placed_order -> items as $order_item): ?>
          <tr>
            <td><?= $order_item -> product_name ?></td>
            <td><?= number_format($order_item -> final_unit_price, 2) ?></td>
            <td><?= $order_item -> quantity ?></td>
            <td><?= number_format($order_item -> subtotal, 2) ?></td>
            <td>
              <?php if($order_item -> is_taxable): ?>
              <span class="badge badge-success">Yes</span>
              <?php else: ?>
              <span class="badge badge-danger">No</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr>
            <td colspan="5" class="text-center">Order is empty</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="form-group">
    <label>Taxes:</label>
    <input type="text" readonly value="<?= number_format($placed_order -> tax, 2) ?>" class="form-control">
  </div>
  <div class="form-group">
    <label>Sales Income:</label>
    <input type="text" readonly value="<?= number_format($placed_order -> sale_without_tax, 2) ?>" class="form-control">
  </div>
  <div class="form-group">
    <label>Delivery Fee:</label>
    <input type="text" readonly value="100.00" class="form-control">
  </div>
  <div class="form-group">
    <label>Customer Total:</label>
    <input type="text" readonly value="<?= number_format($placed_order -> total + 100, 2) ?>" class="form-control">
  </div>
</div>
<div class="modal-footer justify-content-between">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button data-href="/admin/orders/actions/deliver-order.php?id=<?= $placed_order -> id ?>" class="btn btn-success" id="btn-final-deliver">Finalize</a>
</div>
