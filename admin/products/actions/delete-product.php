<?php

  // Only allow GET requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get product details from request parameters
  $id = $_GET["id"];

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../../../../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Prepare delete statement
  $delete_stmt = mysqli_prepare($conn, "DELETE FROM product WHERE `id`= ?");

  // Bind the product values
  mysqli_stmt_bind_param($delete_stmt, "i", $id);

  // Execute delete statement
  mysqli_stmt_execute($delete_stmt);

  // Close all
  mysqli_stmt_close($delete_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/products/panel.php");

?>