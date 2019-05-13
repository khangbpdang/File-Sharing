<?php
 	SESSION_START();
  $_SESSION = array(); // remove values in session array by assigning a new array
 	session_destroy(); // destroy the current session
 	header('location:login.html');
?>
