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
    die("Only GET requests are allowed.");
  }

  // Check if order id exists in request parameters
  if(!(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"]))) {
    header("Status: 400");
    die("Invalid order id given.");
  }

  // Tell mysqli to throw exceptions
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  // Parse configuration file
  $config = parse_ini_file("../../../../config.ini");

  // Create connection to database
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  try {
    // Update the status of the order to DENIED
    $update_status_stmt = mysqli_prepare($conn, "UPDATE `placed_order` SET `status` = 'DENIED' WHERE `id` = ?");
    // Bind the id
    mysqli_stmt_bind_param($update_status_stmt, "i", $_GET["id"]);
    // Execute the update statement
    mysqli_stmt_execute($update_status_stmt);
    // Close the statement
    mysqli_stmt_close($update_status_stmt);
  } catch(mysqli_sql_exception $exception) {
    header("Status: 500");
    die();
  }

  // Close the connection
  mysqli_close($conn);

  // Set header to success
  header("Status: 200");
?>
