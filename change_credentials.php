<?php
  session_start();

  // Only allow POST requests
  if($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Status: 405 Method Not Allowed");
    die("Only POST requests are allowed.");
  }

  // if not logged in, redirect to home page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to home page.'); ".
    "window.location.replace('/');</script></body></html>");
  }

  $current_email_address = $_SESSION["email_address"];
  $email_address = $_POST["email_address"];
  $first_name = $_POST["first_name"];
  $last_name = $_POST["last_name"];
  $phone_number = $_POST["phone_number"];

  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  if(!mysqli_query($conn, "UPDATE customer SET first_name = '$first_name', last_name = '$last_name', email_address = '$email_address', phone_number = '$phone_number' WHERE email_address = '$current_email_address'")) {
    mysqli_close($conn);
    die("<html><body><script>".
      "alert('An error occured while trying to save your credentials. Try again later.'); ".
      "window.location.replace('/my-profile.php');</script></body></html>");
  }

  if($current_email_address == $email_address) {
    echo "<html><body><script>".
      "alert('Successfully saved credentials.'); ".
      "window.location.replace('/my-profile.php');</script></body></html>";
  } else {
    session_destroy();
    echo "<html><body><script>".
      "alert('Successfully saved credentials. Please log in with your new email address.'); ".
      "window.location.replace('/login.php');</script></body></html>";
  }

  mysqli_close($conn);

?>
