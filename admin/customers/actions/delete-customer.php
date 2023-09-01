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

  // Get customer details from request parameters
  $email_address = $_GET["email_address"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Prepare delete statement
  $delete_stmt = mysqli_prepare($conn, "DELETE FROM customer WHERE `email_address`= ?");

  // Bind the category values
  mysqli_stmt_bind_param($delete_stmt, "s", $email_address);

  // Execute delete statement
  mysqli_stmt_execute($delete_stmt);

  // Close all
  mysqli_stmt_close($delete_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/customers/panel.php");

?>
