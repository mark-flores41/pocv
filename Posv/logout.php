<?php
session_start(); // Start the session

// Destroy the session to log out the user
session_destroy();

// Redirect the user to the login page or the home page
header("Location: index.php");
exit();
?>