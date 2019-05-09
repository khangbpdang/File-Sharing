<?php
	SESSION_START();
	$con = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');


	if (isset($_POST["username"])) {
		$username = stripcslashes(trim($_POST['username']));
		$password = stripcslashes($_POST['password']);
		$username = mysqli_real_escape_string($con, $username);
		$password = mysqli_real_escape_string($con, $password);
		$password = md5($password);
		$statement = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."'";
		$res = mysqli_query($con, $statement);
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
	//	echo "IF ENTERED";
	//}

	//echo "EYYYY";
	// Get values passed from login.html


	// Prevent SQL injection






	//$row= mysqli_num_rows($res);

	//if ($row == 1) {
	//	echo "Login Successful!";
	//} else {
	//	echo "Invalid Login Details";
	//}
	//if($res) {
	//	while($arr = mysqli_fetch_array($res)) {
	//	echo "Login Successful...";
	//	}
	//}


	//if(!$row)
	//{
	//	echo "Invalid Login Details";
	//}
?>
