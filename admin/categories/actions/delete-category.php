<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }

  // Only allow GET requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get category details from request parameters
  $name = $_GET["name"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Prepare delete statement
  $delete_stmt = mysqli_prepare($conn, "DELETE FROM product_category WHERE `name`= ?");

  // Bind the category values
  mysqli_stmt_bind_param($delete_stmt, "s", $name);

  // Execute delete statement
  mysqli_stmt_execute($delete_stmt);

  // Close all
  mysqli_stmt_close($delete_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/categories/panel.php");

?>
