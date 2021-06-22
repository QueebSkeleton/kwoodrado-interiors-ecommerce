<?php
  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only GET requests are allowed.");
  }

  // Check if order id exists in request parameters
  if(!(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"]))) {
    header("Status: 400");
    die("Invalid order id given.");
  }

  // Structure for storing order item quantities
  class OrderItemQuantity {
    public $product_id;
    public $quantity;
  }

  // Tell mysqli to throw exceptions
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  // Parse configuration file
  $config = parse_ini_file("../../../../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Select order items of this order
  $order_item_result = mysqli_query($conn, "SELECT `placed_order_item`.`product_id`, `placed_order_item`.`quantity` FROM `placed_order`".
    " LEFT JOIN `placed_order_item` ON `placed_order_item`.`order_id` = `placed_order`.`id` WHERE `placed_order`.`id` = ".$_GET["id"]);

  // Parse all order item rows into an array of order item quantity objects
  $order_item_quantities = [];
  while($row = mysqli_fetch_assoc($order_item_result)) {
    $order_item_quantity = new OrderItemQuantity();
    $order_item_quantity -> product_id = $row["product_id"];
    $order_item_quantity -> quantity = $row["quantity"];
    $order_item_quantities[] = $order_item_quantity;
  }

  // Begin database transaction
  mysqli_begin_transaction($conn);

  try {

    // Update the status of the order to PROCESSING
    $update_status_stmt = mysqli_prepare($conn, "UPDATE `placed_order` SET `status` = 'PROCESSING' WHERE `id` = ?");
    // Bind the id
    mysqli_stmt_bind_param($update_status_stmt, "i", $_GET["id"]);
    // Execute the update statement
    mysqli_stmt_execute($update_status_stmt);
    // Close the statement
    mysqli_stmt_close($update_status_stmt);

    // Update the inventory of the products
    $update_inventory_stmt = mysqli_prepare($conn, "UPDATE `product` SET `units_in_stock` = `units_in_stock` - ? WHERE `id` = ?");
    foreach($order_item_quantities as $order_item_quantity) {
      mysqli_stmt_bind_param($update_inventory_stmt, "ii", $order_item_quantity -> quantity, $order_item_quantity -> product_id);
      mysqli_stmt_execute($update_inventory_stmt);
    }
    // Close the statement
    mysqli_stmt_close($update_inventory_stmt);

    // Commit the transaction
    mysqli_commit($conn);
  } catch(mysqli_sql_exception $exception) {
    mysqli_rollback($conn);

    header("Status: 500");
    die();
  }

  // Close the connection
  mysqli_close($conn);

  // Set header to success
  header("Status: 200");
?>