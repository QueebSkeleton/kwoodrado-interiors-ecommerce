<?php
  session_start();

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

  for($i = 0; $i < count($_SESSION["cart"]); $i++) {
    $product_id = $_POST["productid"][$i];
    $quantity = $_POST["quantity"][$i];

    // Linear search for product in cart
    foreach($_SESSION["cart"] as $index => $cart_item) {
      if($cart_item -> product_id == $product_id) {
        $cart_item -> quantity = $quantity;
        $cart_item -> subtotal = $cart_item -> final_unit_price * $cart_item -> quantity;
        break;
      }
    }
  }

    echo "<html><body><script>alert('Successfully updated cart with new quantities.'); window.location.replace('/my-cart.php');</script></body></html>";

?>
