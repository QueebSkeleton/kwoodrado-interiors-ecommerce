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

  // Get customer details from request parameters
  $first_name = $_POST["first_name"];
  $last_name = $_POST["last_name"];
  $phone_number = $_POST["phone_number"];
  $current_email_address = $_POST["current_email_address"];
  $email_address = $_POST["email_address"];
  $password = $_POST["password"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Prepare update statement
  $update_stmt = mysqli_prepare($conn, "UPDATE `customer` SET `email_address`= ?,`password`= ?,`first_name`= ?,`last_name`= ?,`phone_number`= ? WHERE `email_address` = ?");

  // Bind the customer values
  mysqli_stmt_bind_param($update_stmt, "ssssss", $email_address, $password, $first_name, $last_name, $phone_number, $current_email_address);

  // Execute update statement
  mysqli_stmt_execute($update_stmt);

  // Close all
  mysqli_stmt_close($update_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/customers/panel.php");

?>
