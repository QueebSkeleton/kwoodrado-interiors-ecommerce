<?php
  session_start();

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    die("Only POST requests are allowed.");
  }

  // If id is not given, throw bad request
  if(!isset($_POST["id"]) || empty($_POST["id"])) {
    http_response_code(400);
    die("Invalid product.");
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

  $id = $_POST["id"];
  $quantity = $_POST["quantity"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Select product with given id
  $result = mysqli_query($conn, "SELECT product.category_name, product.name, product.unit_price, product.units_in_stock, product.is_taxable, product_image.path FROM product LEFT JOIN (SELECT product_id, MIN(local_filesystem_location) AS path FROM product_image GROUP BY product_id) AS product_image ON product_image.product_id = product.id WHERE id = $id");

  $row = mysqli_fetch_assoc($result);

  // If shopping cart already contains the product, dont proceed
  $item_already_in_cart = False;

  // If shopping cart is not set yet, initialize as empty array
  if(!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
  } else {
    foreach($_SESSION["cart"] as $cart_item) {
      if($cart_item -> product_id == $id) {
        $item_already_in_cart = True;
        $cart_item_product = $cart_item;
        break;
      }
    }
  }

  if($row) {
    if(!$item_already_in_cart) {
      $cart_item = new ShoppingCartItem();
      $cart_item -> product_id = $id;
      $cart_item -> product_name = $row["name"];
      $cart_item -> product_image_name = $row["path"];
      $cart_item -> final_unit_price = $row["unit_price"];
      $cart_item -> units_in_stock = $row["units_in_stock"];
      $cart_item -> is_taxable = $row["is_taxable"];
      $cart_item -> quantity = $quantity;
      $cart_item -> subtotal = $cart_item -> final_unit_price * $cart_item -> quantity;
      $_SESSION["cart"][] = $cart_item;
    } else {
      $cart_item_product -> quantity = $quantity;
    }
    http_response_code(200);
  } else {
      http_response_code(400);
      echo "Invalid product.";
  }

  mysqli_close($conn);

  echo "<html><body><script>alert('Successfully added to cart.'); window.location.replace('/shop.php?category=".$row["category_name"]."');</script></body></html>";
?>
