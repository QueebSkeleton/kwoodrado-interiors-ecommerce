<?php
  // Only allow post requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die("Only POST requests are allowed.");
  }

  // Check if all values are set
  if(!(isset($_POST["first_name"]) && isset($_POST["last_name"]) &&
    isset($_POST["phone_number"]) && isset($_POST["email_address"]) &&
    isset($_POST["password"]))) {
    http_response_code(400);
    die("Invalid credentials.");
  }

  // Get the credentials
  $first_name = $_POST["first_name"];
  $last_name = $_POST["last_name"];
  $phone_number = $_POST["phone_number"];
  $email_address = $_POST["email_address"];
  $password = $_POST["password"];

  // Get connection
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  // Insert customer
  if(mysqli_query($conn, "INSERT INTO customer(`first_name`, `last_name`, `phone_number`, `email_address`, `password`) ".
    "VALUES('$first_name', '$last_name', '$phone_number', '$email_address', '$password')")) {
      http_response_code(200);
  } else {
    http_response_code(400);
  }

  // Close mysqli
  mysqli_close($conn);
?>
