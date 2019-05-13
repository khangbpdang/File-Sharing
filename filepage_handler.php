<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}
// connect to the database
require_once('connect_db.php');

$username = $_SESSION["username"];

// select current user's own files
$sql = "SELECT * FROM files WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$files = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Delete file
// Privilege requirements: owner of the file
if (isset($_POST['delete']) && isset($_POST['id'])) {
  $id = $_POST['id'];

  $sql = "SELECT * FROM files WHERE file_id=$id";
  $result = mysqli_query($conn, $sql);
  $file = mysqli_fetch_assoc($result);
  $filepath = $pathToUpload . $file['file_hash'] . '.' . $file['file_type'];
  if (file_exists($filepath) && (strcasecmp($username, $file['username']) == 0)) {
    $sql = "DELETE FROM files WHERE file_id=$id";
    $result = mysqli_query($conn, $sql);
    unlink($filepath);
    //$file = mysqli_fetch_assoc($result);
    //mysql_query("DELETE FROM tbl_uploads WHERE id=".$id);
    header("location:download.php?message_del=success");
    exit;
  } else {
    //$message = urlencode("You don't own this file :) Please don't do this again! ...");
    header("location:download.php?message_del=failure");
  }
}

// Downloads files
if (isset($_POST['download']) && isset($_POST['id'])) {
  $id = $_POST['id'];

  // fetch file to download from database
  $sql = "SELECT * FROM files WHERE file_id=$id";
  $result = mysqli_query($conn, $sql);
  $file = mysqli_fetch_assoc($result);
  $filepath = $pathToUpload . $file['file_hash'] . '.' . $file['file_type'];

  // Force download
  if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    // basename($filepath)
    header('Content-Disposition: attachment; filename=' . $file['file_name'] . '.' . $file['file_type']);
    //header('Content-Disposition: attachment; filename=' . basename($filepath));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($pathToUpload . $file['file_hash'] . '.' . $file['file_type']));
    readfile($pathToUpload . $file['file_hash']. '.' . $file['file_type']);

    // Now update downloads count
    $newCount = $file['downloads'] + 1;
    $updateQuery = "UPDATE files SET downloads=$newCount WHERE file_id=$id";
    mysqli_query($conn, $updateQuery);
    header("location:filepage.php?id=$id"."1");
    exit;
  } else {
    //$message = urlencode("You don't own this file :) Please don't do this again! ...");
    header("location:filepage.php?id=$id"."2");

  }
}

// Follow
$query = "SELECT * FROM follow WHERE sender_id = $username";
$res = mysqli_query($conn, $query);
$file = mysqli_fetch_assoc($res);
if (isset($_POST['follow']) && isset($_POST['user']) && isset($_POST['file_id'])) {
  // owner of the file
  $uploader = $_POST['user'];

  // file ID number
  $id = $_POST['file_id'];

  // Query to insert the current follow relationship into follow table
  $follow = "INSERT INTO follow (sender_id, receiver_id) VALUES ('$username', '$uploader')";
  if (mysqli_query($conn, $follow)) {
    header("location:filepage.php?id=".$id);
  } else {
    echo "<script type='text/javascript'>
            alert('ERROR IN FOLLOW');
          </script>";
  }
}

//Unfollow
if (isset($_POST['unfollow']) && isset($_POST['user']) && isset($_POST['file_id'])) {
  // owner of the file
  $uploader = $_POST['user'];

  // file ID number
  $id = $_POST['file_id'];

  // Query to remove follow relationship between current user and file owner
  $unfollow = "DELETE FROM follow WHERE sender_id = '$username' AND receiver_id = '$uploader'";
  if (mysqli_query($conn, $unfollow)) {
    header("location:filepage.php?id=".$id);
  } else {
    echo "<script type='text/javascript'>
            alert('ERROR IN UNFOLLOW');
            location=filepage.php?id=".$id."
          </script>";
  }
}


?>
