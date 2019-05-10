<?php
SESSION_START();
$host = "127.0.0.1";
$dbUsername = "root";
$dbPassword = "Overdrive08";
$dbname = "mytestdb";
//create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

// Check connection to database and report errors in case of failure
if (mysqli_connect_error()) {
  die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
  $name = trim($_POST['name']);
  $username = trim(strtolower($_POST['username']));
  $password = $_POST['password'];

  //Server side input validation via regex in IF statement
  if (!preg_match("/[A-Za-z\s]+/", $name) || !preg_match("/^(?=.{6,15}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/", $username) || !preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[ #?!@$%^*-]).{8,}$/", $password)) { // Check if full name contains numbers
    echo "<script type='text/javascript'>
    location='signup.html';
    alert('Invalid characters for one the fields');
    </script>";
  } else {

    // Sanitize full name, username and password
    $name = mysqli_real_escape_string($conn, stripcslashes($username)); // Full name
    $username = mysqli_real_escape_string($conn, stripcslashes($username)); // username
    $password = mysqli_real_escape_string($conn, stripcslashes($password)); // password

    $email = trim($_POST['email']);
    $repass = stripcslashes($_POST['repeat-pass']);
    $password = md5($password);
    $repass = mysqli_real_escape_string($conn, $repass);
    $repass = md5($repass);


    if (!empty($username) && !empty($password) && !empty($name) && !empty($email) && (hash_equals($password, $repass))) {

      $SELECT = "SELECT email From users Where username = ? OR email = ? Limit 1";
      $INSERT = "INSERT Into users (username, password, email, name) values(?, ?, ?, ?)";
      //Prepare statement
      $stmt = $conn->prepare($SELECT);
      $stmt->bind_param("ss", $username, $email);
      $stmt->execute();
      $stmt->bind_result($username, $email);
      $stmt->store_result();
      $rnum = $stmt->num_rows;

      // if there is not an account created with the username or email entered
      if ($rnum==0) {
        $stmt->close();
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("ssss", $username, $password, $email, $name);
        $stmt->execute();


        echo "<script type='text/javascript'>
        alert('New account created sucessfully');
        location='login.html';
        </script>";
        //header("location:login.html");
      } else {

        //header("location:signup.html");
        echo "<script type='text/javascript'>
        alert('Someone already register using this email or username');
        location='signup.html';
        </script>";
      }
      $stmt->close();
      $conn->close();
    } else {
      if ($password != $repass) {
        echo "<script type='text/javascript'>
        alert('Password does not match with one another.');
        location='signup.html';
        </script>";
        //echo "Password hash: " .$password. "\nRepasses hash: ".$repass;
      }
      else {
        echo "<script type='text/javascript'>
        alert('All field are required');
        </script>";
      }
      //echo "All field are required";
      die();
    }
  }
}

?>
