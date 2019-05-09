<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
		header("location:login.html");
} else {
		header("location:homepage_2.php");
}

?>
