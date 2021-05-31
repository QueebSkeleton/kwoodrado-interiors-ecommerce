<?php

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get product details from request parameters
  $id = $_POST["id"];
  $name = $_POST["name"];
  $category_id = $_POST["category_id"] != "0" ? $_POST["category_id"] : NULL;
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

  // Prepare update statement
  $update_stmt = mysqli_prepare($conn, "UPDATE `product` SET `category_id`=?,".
    "`name`=?,`description`=?,`is_taxable`=?,`cost_per_item`=?,".
    "`unit_price`=?,`compare_to_price`=?,`stock_keeping_unit`=?,".
    "`units_in_stock`=?,`is_enabled`=? WHERE `id`=?");

  // Bind the product values
  mysqli_stmt_bind_param($update_stmt, "issidddsiii", $category_id, $name, $description, $is_taxable,
    $cost_per_item, $unit_price, $compare_to_price, $stock_keeping_unit, $units_in_stock,
    $is_enabled, $id);

  // Execute update statement
  mysqli_stmt_execute($update_stmt);

  // Close all
  mysqli_stmt_close($update_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/products/panel.php");

?>