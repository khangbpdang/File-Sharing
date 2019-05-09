<?php
SESSION_START();
echo "Start Initialize!\n";
$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
if (mysqli_connect_error()) {
  die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
  $sql = "SELECT * FROM files";
  $result = mysqli_query($conn, $sql);
  $files = mysqli_fetch_all($result, MYSQLI_ASSOC);
  foreach ($files as $file) {
    echo $id;
    $id = $file['file_id'];
    $filesounds = metaphone($file['file_name']).metaphone($file['username']).metaphone($file['file_type']).metaphone($file['size']).metaphone($file['file_desc']).metaphone($file['dt_uploaded']);
    echo $filesounds;
    $sql2 = "UPDATE files SET filesound='$filesounds' WHERE file_id=$id";
    $result = mysqli_query($conn, $sql2);
    echo $id . " | " . $file['file_name'] . " | " . $file['filesound'] . "\n";
  }
}
?>
