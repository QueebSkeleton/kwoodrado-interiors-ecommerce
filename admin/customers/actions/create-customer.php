<?php

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
  $email_address = $_POST["email_address"];
  $password = $_POST["password"];

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../../../../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Prepare insert statement
  $insert_stmt = mysqli_prepare($conn, "INSERT INTO `customer`(`first_name`, `last_name`, `phone_number`, `email_address`,".
    " `password`) VALUES (?, ?, ?, ?, ?)");

  // Bind the category values
  mysqli_stmt_bind_param($insert_stmt, "sssss", $first_name, $last_name, $phone_number, $email_address, $password);

  // Execute insert statement
  mysqli_stmt_execute($insert_stmt);

  // Close all
  mysqli_stmt_close($insert_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/customers/panel.php");

?>