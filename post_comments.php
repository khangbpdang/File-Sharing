<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}
// connect to database
require_once('connect_db.php');

// set comment timezone
date_default_timezone_set('America/New_York');

// Check database connection
if (mysqli_connect_error()) {
  die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
  // Check if data (user's comment and file ID) is sent via post from filepage.php
  if(isset($_POST['user_comm']) && isset($_POST['file_id'])) {
    $comment = mysqli_real_escape_string($conn, stripcslashes(trim($_POST['user_comm'])));
    $name = $_SESSION['username'];
    $id = $_POST['file_id'];
    $timestamp = date('Y-m-d G:i:s');
    $query1 = "INSERT INTO comment (username, comment, comment_dt, file_id) VALUES ('$name','$comment', '$timestamp', $id)";
    $insert = mysqli_query($conn, $query1);
    $query2 = "SELECT * FROM users WHERE username = '$name'";
    $select = mysqli_fetch_assoc(mysqli_query($conn, $query2));

    if (empty($select['prof_name_hash']) || empty($select['prof_file_type'])) {
      $path = "img/blog/user3.png";
    } else {
      $path = "profilepics/".basename($select['prof_name_hash']). '.' . $select['prof_file_type'];
    }
    $timestamp = date("F jS, Y  g:i:s  a e", strtotime($timestamp));
    echo '
    <div class="comment-list">
    <div class="single-comment justify-content-between d-flex">
    <div class="user justify-content-between d-flex">
    <div class="thumb">
    <img src="'. $path.
    '" width="70" height="70">
    </div>
    <div class="desc">
    <h5><a href="userpage2.php?user='. $name .'">' . $name . '</a></h5>
    <p class="date">'.$timestamp.' </p>
    <p class="comment">
    '.htmlspecialchars($comment).'
    </p>
    </div>
    </div>
    </div>
    </div>';
  }
}

?>
