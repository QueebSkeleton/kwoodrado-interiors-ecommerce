<?php

  // Only allow POST requests
  // TODO: Add error-handling mechanism
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Get category details from request parameters
  $id = $_POST["id"];
  $name = $_POST["name"];
  $parent_category_id = $_POST["parent_category_id"] != "0" ? $_POST["parent_category_id"] : NULL;
  $description = $_POST["description"];

  // Parse config.ini file then get db credentials
  $config = parse_ini_file("../../../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Prepare update statement
  $update_stmt = mysqli_prepare($conn, "UPDATE `product_category` SET `name`=?,".
    "`description`=?,`parent_category_id`=? WHERE `id`=?");

  // Bind the category values
  mysqli_stmt_bind_param($update_stmt, "ssii", $name, $description, $parent_category_id, $id);

  // Execute update statement
  mysqli_stmt_execute($update_stmt);

  // Close all
  mysqli_stmt_close($update_stmt);
  mysqli_close($conn);

  // TODO: add error cases
  // Redirect if success
  header("Location: /admin/categories/panel.php");

?>