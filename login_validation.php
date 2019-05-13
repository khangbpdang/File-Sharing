<?php
	SESSION_START();
	//$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
	require_once('connect_db.php');

	if (isset($_POST["username"])) {

		// Prevent SQL injection
		$username = stripcslashes(trim($_POST['username']));
		$password = stripcslashes($_POST['password']);
		$username = mysqli_real_escape_string($conn, $username);
		$password = mysqli_real_escape_string($conn, $password);

		// encrypt password hash
		$password = md5($password);

		// select statement to check whether username and password is indeed in database
		$statement = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."'";
		$res = mysqli_query($conn, $statement);
		$num = mysqli_num_rows($res);
		if ($num > 0) {
			//echo "Successful Login";
			$_SESSION["username"]  = strtolower($username);
			header("location:homepage_2.php");
		} else {
			$error = "Unable to log in";
			header("location:login.html?error=$'".$error."'");
		}
	}
?>
