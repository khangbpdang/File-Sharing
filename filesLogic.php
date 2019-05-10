<?php
SESSION_START();
// connect to the database
$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
if (mysqli_connect_error()) {
  die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
  $username = $_SESSION["username"];

  // select all files in the database
  $sql = "SELECT * FROM files WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $files = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $filedesc = $_POST['filedesc'];

  // Uploads files
  if (isset($_POST['save'])) { // if save button on the form is clicked

    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];
    $filename=str_replace('_',' ',$filename);
    $filename = stripcslashes($filename);
    $filename = mysqli_real_escape_string($conn, $filename);

    // get the file extension
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // getting the extension of the file

    // format filename
    $filename = trim($filename); // trim any white spaces, return, newline, null and tab characters before and after the file name
    $filename = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $filename); // remove multiple consecutive white spaces within filename
    $filename = pathinfo($filename, PATHINFO_FILENAME); // getting the name of file without the extension
    $filename = stripcslashes($filename);
    $filename = mysqli_real_escape_string($conn, $filename);

    // hased name of the file for easier storage = hash(filename + current time & date)
    $filehash = md5($filename . '.'. $extension . date('Y/m/d H:i:s'));

    // sanitize user's description of the file
    $filedesc = trim($filedesc); // trim any white spaces and tab characters before and after the file description
    $filedesc = stripcslashes($filedesc);
    $filedesc = mysqli_real_escape_string($conn, $filedesc);

    // destination of the file on the server
    //$destination = 'uploads/' . $username .'/'. $filename;
    $destination = '/Users/khangdang/Sites/uploads/' . basename($filehash) . '.' . $extension;

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

    // Check valid file extension
    if (!in_array($extension, ['txt', 'jpg', 'png', 'mp3', 'pdf', 'docx'])) {
      //echo "You file extension must be .txt, .jpg, .png, .mp3, .pdf or .docx";
      $message = "You file extension must be .txt, .jpg, .png, .mp3, .pdf or .docx";
      header("location:upload.php?message_up=".$message);
    } elseif ($_FILES['myfile']['size'] > 10000000) { // file shouldn't be larger than 10Megabyte
      $message = "File too large!";
      header("location:upload.php?message_up=".$message);
    } else {
      // move the uploaded (temporary) file to the specified destination
      if (move_uploaded_file($file, $destination)) {
        date_default_timezone_set("America/New_York");
        $filesounds = metaphone($filename).metaphone($username).metaphone($extension).metaphone($size).metaphone($filedesc).metaphone($date);
        $sql = "INSERT INTO files (file_hash, file_name, username, file_type, size, file_desc, dt_uploaded, downloads, filesound) VALUES ('$filehash', '$filename', '$username', '$extension', '$size', '$filedesc', CURRENT_TIMESTAMP(), 0, '$filesounds')";

        //$sql2 = "INSERT INTO filesounds (file_name, username, file_type, size, file_desc, dt_uploaded) VALUEs ('$filename', '$username', '$extension', '$size', '$filedesc', '$date')";
        if (mysqli_query($conn, $sql)) {
          //echo "<script type='text/javascript'>
          //        File uploaded successfully
          //      </script>";
          header("location:upload.php?message_up=success");
        }
      } else {
        //echo "Failed to upload file.";
        header("location:upload.php?message_up=failure");
      }
    }
  }

  // Downloads files
  if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];

    // fetch file to download from database
    $sql = "SELECT * FROM files WHERE file_id=$id";
    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = '/Users/khangdang/Sites/uploads/' . $file['file_hash'] . '.' . $file['file_type'];

    if (file_exists($filepath) && (strcasecmp($username, $file['username']) == 0)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      // basename($filepath)
      header('Content-Disposition: attachment; filename=' . $file['file_name'] . '.' . $file['file_type']);
      //header('Content-Disposition: attachment; filename=' . basename($filepath));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize('/Users/khangdang/Sites/uploads/' . $file['file_hash'] . '.' . $file['file_type']));
      readfile('/Users/khangdang/Sites/uploads/' . $file['file_hash']. '.' . $file['file_type']);

      // Now update downloads count
      $newCount = $file['downloads'] + 1;
      $updateQuery = "UPDATE files SET downloads=$newCount WHERE file_id=$id";
      mysqli_query($conn, $updateQuery);
      header("location:download.php?message_dwn=success");
      exit;
    } else {
      //$message = urlencode("You don't own this file :) Please don't do this again! ...");
      header("location:download.php?message_dwn=failure");

    }
  }

  // Delete Files
  if (isset($_GET['file_rm_id'])) {
    $id = $_GET['file_rm_id'];

    $sql = "SELECT * FROM files WHERE file_id=$id";
    $result = mysqli_query($conn, $sql);
    $file = mysqli_fetch_assoc($result);
    $filepath = '/Users/khangdang/Sites/uploads/' . $file['file_hash'] . '.' . $file['file_type'];
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
}
?>
