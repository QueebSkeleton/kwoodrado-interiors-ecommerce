<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }

  // Only allow POST requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get category details from request parameters
  $current_name = $_POST["current_name"];
  $name = $_POST["name"];
  $parent_category_name = $_POST["parent_category_name"] != "" ? $_POST["parent_category_name"] : NULL;
  $description = $_POST["description"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Prepare update statement
  $update_stmt = mysqli_prepare($conn, "UPDATE `product_category` SET `name`=?,".
    "`description`=?,`parent_category_name`=? WHERE `name`=?");

  // Bind the category values
  mysqli_stmt_bind_param($update_stmt, "ssss", $name, $description, $parent_category_name, $current_name);

  // Execute update statement
  mysqli_stmt_execute($update_stmt);

  // Close all
  mysqli_stmt_close($update_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/categories/panel.php");

?>
