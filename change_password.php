<?php
  session_start();

  // if not logged in, redirect to home page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to home page.'); ".
    "window.location.replace('/');</script></body></html>");
  }

  $password_old = $_POST["password_old"];
  $password_new = $_POST["password_new"];
  $password_new_retype = $_POST["password_new_retype"];

  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();
  
  // Retrieve current password from database
  $email_address = $_SESSION["email_address"];
  $user_result = mysqli_query($conn, "SELECT password FROM customer WHERE email_address = '$email_address'");
  $current_password = mysqli_fetch_assoc($user_result)["password"];

  if($current_password != $password_old) {
    mysqli_close($conn);
    die("<html><body><script>".
    "alert('You entered the wrong old password.'); ".
    "window.location.replace('/my-profile.php');</script></body></html>");
  }

  if($password_new != $password_new_retype) {
    mysqli_close($conn);
    die("<html><body><script>".
    "alert('New password does not match retype.'); ".
    "window.location.replace('/my-profile.php');</script></body></html>");
  }

  if(!mysqli_query($conn, "UPDATE customer SET password = '$password_new' WHERE email_address='$email_address'")) {
    mysqli_close($conn);
    die("<html><body><script>".
    "alert('A database error occured while trying to update your password. Try again in a couple minutes.'); ".
    "window.location.replace('/my-profile.php');</script></body></html>");
  }

  mysqli_close($conn);
  session_destroy();
  die("<html><body><script>".
  "alert('Password has been successfully updated. Please log in with your new password.'); ".
  "window.location.replace('/login.php');</script></body></html>");
?>
