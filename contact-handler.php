<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
} else {
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
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['email']) && (strcasecmp($_POST['service'], "Please Choose A Subject") != 0)) {
      $name = trim($_POST['name']);
      if (preg_match('~[0-9]+~', $name)) { // Check if full name contains numbers
        echo "<script type='text/javascript'>
        alert('Invalid characters for Full Name');
        location='contact.php';
        </script>";
      } else {
        $from_email = "khangdpdang@gmail.com";
        $contact_email = trim($_POST['email']);
        $to_email = trim('dummycontact4321@gmail.com');
        $service = $_POST['service'];
        $message = stripcslashes(trim($_POST['message']));
        $message = mysqli_real_escape_string($conn, $message);
        $header = "From: $contact_email";
        $subject = "Title: $service";
        $email_body = " Full Name: $name.\n Email: $contact_email.\n Message: $message.\n";

        mail($to_email, $subject, $email_body, $header);
        header("location:contact.php");
      }
    } else {
      echo "<script type='text/javascript'>
      alert('All fields are required!');
      location='contact.php';
      </script>";
    }
  }
}
?>
