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
  // TODO: add functionality for thumbnail
  $name = $_POST["name"];
  $parent_category_name = $_POST["parent_category_name"] != "" ? $_POST["parent_category_name"] : NULL;
  $description = $_POST["description"];

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../../../../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Prepare insert statement
  $insert_stmt = mysqli_prepare($conn, "INSERT INTO `product_category`(`name`, `description`, `parent_category_name`) VALUES (?, ?, ?)");

  // Bind the category values
  mysqli_stmt_bind_param($insert_stmt, "sss", $name, $description, $parent_category_name);

  // Execute insert statement
  mysqli_stmt_execute($insert_stmt);

  // Close all
  mysqli_stmt_close($insert_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/categories/panel.php");

?>
