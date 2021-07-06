<?php
  session_start();

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    die("Only POST requests are allowed.");
  }

  class ShoppingCartItem {
    public $product_id;
    public $product_name;
    public $product_image_name;
    public $final_unit_price;
    public $units_in_stock;
    public $quantity;
    public $is_taxable;
    public $subtotal;
  }

  #database/config.ini access
  $config = parse_ini_file('../config.ini');

  $user_logged_in_email = $_SESSION['email_address'];

  $billing_address = $_POST['billing_address'];
  $shipping_address = $_POST['shipping_address'];
  $additional_notes = $_POST['additional_notes'];

  $connect = new mysqli($config['db_server'], $config['db_user'], $config['db_password'], $config['db_name']);
  if($connect->query("INSERT INTO placed_order (customer_email_address, shipping_address, billing_address, additional_notes, placed_in, status) VALUES ('$user_logged_in_email', '$shipping_address', '$billing_address', '$additional_notes', NOW(), 'NEW') ")) {
    $statement = $connect->prepare("INSERT INTO placed_order_item (order_id, product_id, final_unit_price,  is_taxable,  quantity) VALUES (?, ?, ?, ?, ?)");
    $statement->bind_param("iidii", $order_id, $product_id, $final_unit_price, $is_taxable, $quantity);
    $order_id = $connect -> insert_id;

    foreach($_SESSION["cart"] as $cart_item) {
      $product_id = $cart_item->product_id;
      $final_unit_price = $cart_item->final_unit_price;
      $is_taxable = $cart_item->is_taxable;
      $quantity = $cart_item->quantity;
      $statement->execute();
    }

    $statement->close();
    unset($_SESSION["cart"]);

    echo "<script>alert('Your order has been placed!'); window.location.replace('/my-orders.php');
      </script>";
  } else {
    $statement->close();

    echo "<script>alert('An error occured while trying to save your order. Please try again in a few minutes.'); window.location.replace('/my-orders.php');
      </script>";
  }



?>
