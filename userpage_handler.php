<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}
$username = $_SESSION["username"];
$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
if (mysqli_connect_error()) {
  die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
  // Follow
  $query = "SELECT * FROM follow WHERE sender_id = $username";
  $res = mysqli_query($conn, $query);
  $file = mysqli_fetch_assoc($res);
  if (isset($_POST['follow']) && isset($_POST['user'])) {
    // owner of the file
    $visit = $_POST['user'];

    // Query to insert the current follow relationship into follow table
    $follow = "INSERT INTO follow (sender_id, receiver_id) VALUES ('$username', '$visit')";
    if (mysqli_query($conn, $follow)) {
      header("location:userpage2.php?user=".$visit);
    } else {
      echo "<script type='text/javascript'>
      alert('ERROR IN FOLLOW');
      </script>";
    }
  }

  //Unfollow
  if (isset($_POST['unfollow']) && isset($_POST['user'])) {
    // owner of the file
    $visit = $_POST['user'];

    // Query to remove follow relationship between current user and file owner
    $unfollow = "DELETE FROM follow WHERE sender_id = '$username' AND receiver_id = '$visit'";
    if (mysqli_query($conn, $unfollow)) {
      header("location:userpage2.php?user=".$visit);
    } else {
      echo "<script type='text/javascript'>
      alert('ERROR IN UNFOLLOW');
      </script>";
    }
  }

  //Profile Pic
  if (isset($_POST['profile'])) { // if profile button on the form is clicked
    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];

    // get the file extension
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // getting the extension of the file


    // destination of the file on the server
    //$destination = 'uploads/' . $username .'/'. $filename;
    $destination = '/Users/khangdang/Sites/profilepics/' . basename($username) . '.' . $extension;

    // the physical file on a temporary uploads directory on the server
    $filetmp = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

    // Check extension of uploaded profile picture
    if (!in_array($extension, ['jpg', 'png'])) {
      //echo "You file extension must be .jpg, .png";
      $message = "You file extension must be .jpg or .png: " . $extension;
      header("location:userpage2.php?message_up=".$message);
    } elseif ($_FILES['myfile']['size'] > 10485760) { // file shouldn't be larger than 10Megabyte
      $message = "File too large!";
      header("location:userpage2.php?message_up=".$message);
    } else {
      // remove original file from profilepics folder
      $select = "SELECT * FROM users WHERE username = '$username'";
      $res = mysqli_query($conn, $select);
			$profile = mysqli_fetch_assoc($res);
      $filepath = '/Users/khangdang/Sites/profilepics/' . basename($profile['prof_name_hash']). '.' . $profile['prof_file_type'];
      if (file_exists($filepath)) {
        unlink($destination);
      } else {
        header("location:userpage2.php?message_up=failure");
      }

      // move the uploaded (temporary) file to the specified destination
      if (move_uploaded_file($filetmp, $destination)) {
        $sql = "UPDATE users SET prof_name_hash = '$username', prof_file_type = '$extension' WHERE username = '$username'";
        if (mysqli_query($conn, $sql)) {
          header("location:userpage2.php?message_up=success");
        }
      } else {
        header("location:userpage2.php?message_up=failure");
      }
    }
  }
}
mysqli_close(connection);
?>
