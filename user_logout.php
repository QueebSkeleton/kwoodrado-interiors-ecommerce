<?php
  session_start();

  // If user is not logged in, redirect to home page
  if(!isset($_SESSION["email_address"])) {
    die("<html><body><script>".
    "alert('You\'re not logged in. Simply redirecting you to home page.'); ".
    "window.location.replace('/');</script></body></html>");
  }

  unset($_SESSION["email_address"]);
  die("<html><body><script>".
    "alert('Successfully logged out.'); ".
    "window.location.replace('/');</script></body></html>");
?>
