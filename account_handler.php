<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}

// current session user's username
$username = $_SESSION["username"];

// connect to the database
require_once('connect_db.php');

// select all files from the database that belong to the user
$sql = "SELECT * FROM files WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$files = mysqli_fetch_all($result, MYSQLI_ASSOC);

// remove user from database
$sql2 = "DELETE FROM users WHERE username='$username'";
$sql3 = "DELETE FROM follow WHERE sender_id='$username' OR receiver_id='$username'";
$sql4 = "DELETE FROM comment WHERE username='$username'";
$sql5 = "SELECT * FROM users WHERE username='$username'";


// if the DELETE ACCOUNT button is pressed
if (isset($_POST['acc_del_btn']) && isset($_POST['user'])) {
  // iterate over each row in the files table to delete each file in the uploads folder
  foreach ($files as $file) {
    // retrieve the file id from each row
    $id = $file['file_id'];
    // file path of each file
    $filepath = $pathToUpload . $file['file_hash'] . '.' . $file['file_type'];
    if (file_exists($filepath) && (strcasecmp($username, $file['username']) == 0)) {

      //remove each file from database
      unlink($filepath);
      $sql11 = "DELETE FROM files WHERE file_id=$id";
      $result11 = mysqli_query($conn, $sql11);

      //remove comments of each file from database
      $sql12 = "DELETE FROM comment WHERE file_id=$id";
      $result12 = mysqli_query($conn, $sql12);
    }
  }

  // Remove profile pic in profilepics directory
  $result5 = mysqli_query($conn, $sql5);
  $profile = mysqli_fetch_assoc($result5);
  $profilepath = $pathToProfile . $profile['prof_name_hash'] . '.' . $profile['prof_file_type'];
  //if (file_exists($filepath) && (strcasecmp($username, $profile['username']) == 0)) {
  unlink($profilepath);
  //}

  // remove comments this current user have made
  $result4 = mysqli_query($conn, $sql4);

  // remove following relationship of users
  $result3 = mysqli_query($conn, $sql3);

  // remove user's account row in the table users
  $result2 = mysqli_query($conn, $sql2);

  // logout from current session and redirect to login page
  header("location:logout.php");
}
?>
