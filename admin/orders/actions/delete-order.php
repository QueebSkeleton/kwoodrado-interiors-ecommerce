<?php
  // Check if admin is already logged in
  session_start();
  if(!isset($_SESSION["admin"])) {
    header('Location: /admin/login-form.php?error=Log in first before you access admin-specific pages.');
    die();
  }

  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get order id from request parameters
  $id = $_GET["id"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Execute delete statement
  mysqli_query($conn, "DELETE FROM placed_order WHERE id = '$id'");

  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/orders/all-orders.php");

?>
