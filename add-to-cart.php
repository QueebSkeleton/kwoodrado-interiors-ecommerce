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

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Select product with given id
  $result = mysqli_query($conn, "SELECT product.category_name, product.name, product.unit_price, product.units_in_stock, product.is_taxable, product_image.path FROM product LEFT JOIN (SELECT product_id, MIN(local_filesystem_location) AS path FROM product_image GROUP BY product_id) AS product_image ON product_image.product_id = product.id WHERE id = $id");

  $row = mysqli_fetch_assoc($result);

  // If shopping cart is not set yet, initialize as empty array
  if(!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
  } else {
    // If shopping cart already contains the product, dont proceed
    foreach($_SESSION["cart"] as $cart_item) {
      if($cart_item -> product_id == $id) {
        die("<html><body><script>alert('This item is already in your cart.'); window.location.replace('/shop.php?category=".$row["category_name"]."');</script></body></html>");
      }
    }
  }

  if($row) {
    $cartItem = new ShoppingCartItem();
    $cartItem -> product_id = $id;
    $cartItem -> product_name = $row["name"];
    $cartItem -> product_image_name = $row["path"];
    $cartItem -> final_unit_price = $row["unit_price"];
    $cartItem -> is_taxable = $row["is_taxable"];
    $cartItem -> quantity = 1;
    $cartItem -> subtotal = $cartItem -> final_unit_price * $cartItem -> quantity;
    $_SESSION["cart"][] = $cartItem;

    http_response_code(200);
  } else {
      http_response_code(400);
      echo "Invalid product.";
  }

  mysqli_close($conn);

  echo "<html><body><script>alert('Successfully added to cart.'); window.location.replace('/shop.php?category=".$row["category_name"]."');</script></body></html>";
?>
