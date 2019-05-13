<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
} else {
  //create connection to database
  require_once('connect_db.php');

  // Check connection to database and report errors in case of failure
  if (mysqli_connect_error()) {
    die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
  } else {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && (strcasecmp($_POST['service'], "Please Choose A Subject") != 0)) {
      $name = trim($_POST['name']);
      $contact_email = filter_var($_POST['email'],  FILTER_SANITIZE_EMAIL);
      if (preg_match('~[0-9]+~', $name) || !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) { // Check if full name contains numbers or valid email
        echo "<script type='text/javascript'>
        alert('Invalid characters for Full Name');
        location='contact.php';
        </script>";
      } else {

        $from_email = "khangdpdang@gmail.com";
        //$contact_email = (filter_var($contact_email,  FILTER_SANITIZE_EMAIL));
        $to_email = "dummycontact4321@gmail.com";
        //$service = $_POST['service'];
        $message = stripcslashes(trim($_POST['message']));
        $message = mysqli_real_escape_string($conn, $message);
        $header = 'From: webmaster@example.com' . "\r\n" .
                  'Reply-To: webmaster@example.com' . "\r\n" .
                  'X-Mailer: PHP/' . phpversion();
        $subject = "Title: ".$_POST['service'];
        $email_body = " Full Name: $name.\n Email: $contact_email.\n Message: $message.\n";

        //mail($to_email, "SAMPLE", "HOW ARE YOU!");
        $result = mail($to_email, $subject, $email_body, $header);
        header("location:contact.php?message=".$result);

      }
    }
    else {
      echo "<script type='text/javascript'>
      alert('All fields are required!');
      location='contact.php';
      </script>";
    }
  }
}
?>
