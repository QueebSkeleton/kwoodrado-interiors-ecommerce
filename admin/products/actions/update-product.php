<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get product details from request parameters
  $id = $_POST["id"];
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

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Prepare update statement
  $update_stmt = mysqli_prepare($conn, "UPDATE `product` SET `category_name`=?,".
    "`name`=?,`description`=?,`is_taxable`=?,`cost_per_item`=?,".
    "`unit_price`=?,`compare_to_price`=?,`stock_keeping_unit`=?,".
    "`units_in_stock`=?,`is_enabled`=? WHERE `id`=?");

  // Bind the product values
  mysqli_stmt_bind_param($update_stmt, "sssidddsiii", $category_name, $name, $description, $is_taxable,
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
