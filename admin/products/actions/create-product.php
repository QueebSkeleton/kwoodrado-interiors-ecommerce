<?php

  // Only allow POST requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get product details from request parameters
  $name = $_POST["name"];
  $category_name = $_POST["category_name"] != "" ? $_POST["category_name"] : NULL;
  $description = $_POST["description"];
  $is_enabled = $_POST["is_enabled"];
  // TODO: Add functionality for images
  $stock_keeping_unit = $_POST["stock_keeping_unit"];
  $units_in_stock = $_POST["units_in_stock"];
  $cost_per_item = $_POST["cost_per_item"];
  $unit_price = $_POST["unit_price"];
  $compare_to_price = $_POST["compare_to_price"];
  $is_taxable = isset($_POST["is_taxable"]) ? 1 : 0;

  // TODO: Add validation layer

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../../../../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Prepare insert statement
  $insert_stmt = mysqli_prepare($conn, "INSERT INTO `product`(`category_name`, `name`, `description`, `is_taxable`,".
   "`cost_per_item`, `unit_price`, `compare_to_price`, `stock_keeping_unit`, `units_in_stock`, `is_enabled`) VALUES (?,?,?,?,?,?,?,?,?,?)");

  // Bind the product values
  mysqli_stmt_bind_param($insert_stmt, "sssidddsii", $category_name, $name, $description, $is_taxable,
    $cost_per_item, $unit_price, $compare_to_price, $stock_keeping_unit, $units_in_stock,
    $is_enabled);

  // Execute insert statement
  mysqli_stmt_execute($insert_stmt);

  // Get product id
  $product_insert_id = $insert_stmt -> insert_id;

  // Close insert statement
  mysqli_stmt_close($insert_stmt);

  // Save all images

  // Prepare insert image statement
  $insert_img_stmt = mysqli_prepare($conn, "INSERT INTO `product_image` VALUES (?, ?)");
  foreach($_FILES["images"]["name"] as $key => $filename) {
    $tmp_name = $_FILES["images"]["tmp_name"][$key];
    $name = basename($filename);

    // If file was moved successfully, save location to database
    if(move_uploaded_file($tmp_name, "../../../product-images/$name")) {
      mysqli_stmt_bind_param($insert_img_stmt, "is", $product_insert_id, $name);
      mysqli_stmt_execute($insert_img_stmt);
    }
  }

  // Close all
  mysqli_stmt_close($insert_img_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/products/panel.php");

?>
