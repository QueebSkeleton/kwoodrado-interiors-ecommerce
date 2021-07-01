<?php
  // Only allow post requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die("Only POST requests are allowed.");
  }

  // Check if email and password is set
  if(!(isset($_POST["email_address"]) && isset($_POST["password"]))) {
    http_response_code(400);
    die("No email or password was in the request.");
  }

  // Get the credentials
  $email_address = $_POST["email_address"];
  $password = $_POST["password"];

  // Parse config file
  $config = parse_ini_file("../config.ini");

  // Get connection
  $conn = mysqli_connect($config["db_server"], $config["db_user"], $config["db_password"], $config["db_name"]);

  // Fetch user
  $result = mysqli_query($conn, "SELECT * FROM customer WHERE email_address = '$email_address' AND password = '$password'");

  if(mysqli_num_rows($result) == 1) {
      http_response_code(200);
      session_start();
      $_SESSION["email_address"] = $email_address;
  } else {
    http_response_code(401);
  }

  // Close mysqli
  mysqli_close($conn);
?>
