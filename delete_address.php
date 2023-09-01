<?php
  session_start();

  // Only allow GET requests
  if($_SERVER["REQUEST_METHOD"] != "GET") {
    header("Status: 405 Method Not Allowed");
    die("Only GET requests are allowed.");
  }

  // if not logged in, redirect to home page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Redirecting you to home page.'); ".
    "window.location.replace('/');</script></body></html>");
  }

  $email_address = $_SESSION["email_address"];
  $address = $_GET["address"];

  // Create connection to database
  require_once($_SERVER["DOCUMENT_ROOT"]."/dbconnection.php");
  $conn = get_connection();

  if(!mysqli_query($conn, "DELETE FROM customer_address WHERE customer_email_address='$email_address' AND address='$address'")) {
    mysqli_close($conn);
    die("<html><body><script>".
      "alert('An error occured while trying to remove your address. Try again later.'); ".
      "window.location.replace('/my-profile.php');</script></body></html>");
  }

  echo "<html><body><script>".
    "alert('Successfully removed address.'); ".
    "window.location.replace('/my-profile.php');</script></body></html>";
  mysqli_close($conn);

?>
