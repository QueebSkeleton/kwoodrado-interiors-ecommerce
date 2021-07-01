<?php
  session_start();

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // Check if admin is already logged in
  if(isset($_SESSION["admin"])) {
    header('Location: /admin/dashboard.php');
    die();
  }

  // Get credentials
  $username = $_POST["username"];
  $password = $_POST["password"];

  if($username == "admin" && $password == "admin123") {
    $_SESSION["admin"] = 1;
    header('Location: /admin/dashboard.php');
  } else {
    header('Location: /admin/login-form.php?error=Invalid credentials. Try again.');
  }
?>
