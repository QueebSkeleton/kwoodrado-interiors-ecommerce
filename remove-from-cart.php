<?php
  session_start();

  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(400);
    die("Only GET requests are allowed.");
  }

  // If id is not given, throw bad request
  if(!isset($_GET["id"]) || empty($_GET["id"])) {
    http_response_code(400);
    die("Invalid product.");
  }

  class ShoppingCartItem {
    public $product_id;
    public $product_name;
    public $product_image_name;
    public $final_unit_price;
    public $quantity;
    public $is_taxable;
    public $subtotal;
  }

  $id = $_GET["id"];

  // Find product and remove
  for($i = 0; $i < count($_SESSION["cart"]); $i++) {
    if($_SESSION["cart"][$i] -> product_id == $id) {
      unset($_SESSION["cart"][$i]);
      $_SESSION["cart"] = array_values($_SESSION["cart"]);
      echo "<html><body><script>alert('Successfully removed item from cart.'); window.location.replace('/my-cart.php');</script></body></html>";
    }
  }

  echo "<html><body><script>alert('Product was not found in cart. Exiting.'); window.location.replace('/my-cart.php');</script></body></html>";
?>
