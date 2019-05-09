<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}
include 'filesLogic.php';

// Redirect to homepage if user use the search bar
$input = mysqli_real_escape_string($conn, stripcslashes(trim($_GET['search_input'])));
if ($input !== "") {
  header("location:homepage_2.php?search_input=".$input);
}

?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
  <!-- Mobile Specific Meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Favicon-->
  <link rel="shortcut icon" href="img/fav.png">
  <!-- Author Meta -->
  <meta name="author" content="codepixer">
  <!-- Meta Description -->
  <meta name="description" content="">
  <!-- Meta Keyword -->
  <meta name="keywords" content="">
  <!-- meta character set -->
  <meta charset="UTF-8">
  <!-- Site Title -->
  <title>Upload Page</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500|Rubik:500" rel="stylesheet">
  <!--
  CSS
  ============================================= -->
  <link rel="stylesheet" href="css/linearicons.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/nice-select.css">
  <link rel="stylesheet" href="css/animate.min.css">
  <link rel="stylesheet" href="css/owl.carousel.css">
  <link rel="stylesheet" href="css/userpage.css">
  <link rel="stylesheet" type="text/css" href="css/userpage2-demo.css">

  <style>
  * {
    box-sizing: border-box;
  }

  /* Create three equal columns that floats next to each other */
  .column {
    float: center;
    width: 100.00%;
    display: none; /* Hide all elements by default */
  }

  /* Content */
  .content {
    background-color: white;
    padding: 10px;
  }

  /* The "show" class is added to the filtered elements */
  .show {
    display: block;
  }

  /* Style the buttons */
  .btn {
    border: none;
    outline: none;
    padding: 12px 16px;
    background-color: white;
    cursor: pointer;
  }

  .btn:hover {
    background-color: #ddd;
  }

  .btn.active {
    background-color: #666;
    color: white;
  }
</style>
<style type="text/css">
.form-style{
  max-width: 900px;
  background: #FAFAFA;
  padding: 30px;
  margin: 50px auto;
  box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
  border-radius: 10px;
  border: 6px solid #305A72;
}
.form-style ul{
  padding:0;
  margin:0;
  list-style:none;
}
.form-style ul li{
  display: block;
  margin-bottom: 10px;
  min-height: 35px;
}
.form-style ul li  .field-style{
  box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  padding: 8px;
  outline: none;
  border: 1px solid #B0CFE0;
  -webkit-transition: all 0.30s ease-in-out;
  -moz-transition: all 0.30s ease-in-out;
  -ms-transition: all 0.30s ease-in-out;
  -o-transition: all 0.30s ease-in-out;

  }.form-style ul li  .field-style:focus{
    box-shadow: 0 0 5px #B0CFE0;
    border:1px solid #B0CFE0;
  }
  .form-style ul li .field-split{
    width: 49%;
  }
  .form-style ul li .field-full{
    width: 100%;
  }
  .form-style ul li input.align-left{
    float:left;
  }
  .form-style ul li input.align-right{
    float:right;
  }
  .form-style ul li textarea{
    width: 100%;
    height: 100px;
  }
  .form-style ul li input[type="button"],
  .form-style ul li input[type="submit"] {
    -moz-box-shadow: inset 0px 1px 0px 0px #3985B1;
    -webkit-box-shadow: inset 0px 1px 0px 0px #3985B1;
    box-shadow: inset 0px 1px 0px 0px #3985B1;
    background-color: #216288;
    border: 1px solid #17445E;
    display: inline-block;
    cursor: pointer;
    color: #FFFFFF;
    padding: 8px 18px;
    text-decoration: none;
    font: 12px Arial, Helvetica, sans-serif;
  }
  .form-style ul li input[type="button"]:hover,
  .form-style ul li input[type="submit"]:hover {
    background: linear-gradient(to bottom, #2D77A2 5%, #337DA8 100%);
    background-color: #28739E;
  }
  .btn {
    color: #0099CC;
    background: transparent;
    border: 2px solid #0099CC;
    border-radius: 6px;
    border: none;
    color: white;
    padding: 16px 32px;
    text-align: center;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    cursor: pointer;
    text-decoration: none;
    text-transform: uppercase;
  }
  .btn1 {
    background-color: white;
    color: black;
    border: 1.5px solid #216288;
    transition: 0.3s;
    border-radius: 10px;
    line-height: 20px;
  }
  .btn1:hover {
    background-color: #216288;
    color: white;
  }
  button {
    border: none;
    padding: 10px;
    border-radius: 5px;
  }
  .alert {
    padding: 20px;
    background-color: #f44336;
    color: white;
    opacity: 1;
    transition: opacity 0.6s;
    margin-bottom: 15px;
  }

  .alert.success {background-color: #4CAF50;}
  .alert.info {background-color: #2196F3;}
  .alert.warning {background-color: #ff9800;}

  .closebtn {
    margin-left: 15px;
    color: white;
    font-weight: bold;
    float: right;
    font-size: 22px;
    line-height: 20px;
    cursor: pointer;
    transition: 0.3s;
  }

  .closebtn:hover {
    color: black;
  }
</style>
</head>

<body>
  <!--================ Start header Top Area =================-->
  <section class="header-top">
    <div class="container box_1170">
      <div class="row align-items-center justify-content-between">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <a href="index.php" class="logo">
            <img src="img/logo7.png" alt="">
          </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 search-trigger">
          <a style="cursor:pointer" class="search">
            <i class="lnr lnr-magnifier" id="search"></i></a>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!--================ End header top Area =================-->

  <!-- Start header Area -->
  <header id="header">
    <div class="container box_1170 main-menu">
      <div class="row align-items-center justify-content-center d-flex">
        <nav id="nav-menu-container">
          <ul class="nav-menu">
            <li class="menu-active"><a href="homepage_2.php">Home</a></li>
						<li><a href="following.php">Following</a></li>
						<li><a href="userpage2.php">Profile</a></li>
						<li><a href="upload.php">Upload</a></li>
						<li><a href="download.php">My Files</a></li>
						<li><a href="contact.php">Contact</a></li>
						<li><a href="logout.php">Log Out</a></li>

          </ul>
        </nav>
      </div>
    </div>

    <div class="search_input" id="search_input_box">
      <div class="container box_1170">
        <form action"upload.php" method="GET" class="d-flex justify-content-between">
          <input type="text" class="form-control" name="search_input" id="search_input" placeholder="Search Here">
          <button type="submit" class="btn"></button>
          <span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
        </form>
      </div>
    </div>
  </header>
  <!-- End header Area -->





  <!-- Start main body Area -->
  <div class="main-body section-gap">
    <div class="container box_1170">

      <div class="row">
        <div class="col-lg-8 post-list">
          <!-- UPLOAD message-->
          <?php
          if(isset($_GET['message_up'])) {
            $message = $_GET['message_up'];
            if (strcasecmp($message, 'success') == 0) {
              ?>
              <div class="alert success">
                <span class="closebtn">&times;</span>
                <strong>Success!</strong> The site didn't crash and you've just uploaded a file!
              </div>
              <script>
              var close = document.getElementsByClassName("closebtn");
              var i;

              for (i = 0; i < close.length; i++) {
                close[i].onclick = function(){
                  var div = this.parentElement;
                  div.style.opacity = "0";
                  setTimeout(function(){ div.style.display = "none"; }, 600);
                }
              }
              </script>
              <?php
            } else {
              ?>
              <div class="alert">
                <span class="closebtn">&times;</span>
                <strong>ERM ...</strong> <?php echo $message; ?>
              </div>
              <script>
              var close = document.getElementsByClassName("closebtn");
              var i;

              for (i = 0; i < close.length; i++) {
                close[i].onclick = function(){
                  var div = this.parentElement;
                  div.style.opacity = "0";
                  setTimeout(function(){ div.style.display = "none"; }, 600);
                }
              }
              </script>
              <?php
            }
          }
          ?>
          <!-- END UPLOAD Message-->
          <!-- Start Post Area -->
          <section class="post-area">

            <form class="form-style" action = "upload.php" method = "post" enctype="multipart/form-data">
              <ul>
                <li>
                  <h3>Upload Files</h3>
                  <hr>
                  <p>a) Select a file</p>
                  <br>
                  <input type="file" name="myfile" id="myfile">
                  <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                </li>
                <li>
                  <p>b) Description</p>
                  <br>
                  <textarea name="filedesc" class="field-style" placeholder="Description"></textarea>
                </li>
                <button type="submit" name="save" class="btn1">Upload File</button>
              </ul>
            </form>

            <!-- Portfolio Gallery Grid -->


          </section>

          <!-- Start Post Area -->
        </div>
        <div class="col-lg-4 sidebar">


          <div class="single-widget protfolio-widget">
            <h4 class="title"> Upload Page</h4>
            <img class="img-fluid" src="img/uploadpic.png" alt="">

            <h4>Share!</h4>

            <p class="p-text">
              It's your turn to express yourself!
              You can share images, documents, songs, videos and
              more!
            </p>


          </div>


        </div>

      </div>
    </div>
  </div>
  <!-- Start main body Area -->

  <!-- start footer Area -->
  <footer class="footer-area section-gap">
    <div class="container box_1170">

      <div class="row footer-bottom d-flex justify-content-between align-items-center">
        <p class="col-lg-12 footer-text text-center">
          File Sharing by Nurul Haque, Khang Dang, Lawrence
        </p>
      </div>
    </div>
  </footer>
  <!-- End footer Area -->
  <script>
  filterSelection("all")
  function filterSelection(c) {
    var x, i;
    x = document.getElementsByClassName("column");
    if (c == "all") c = "";
    for (i = 0; i < x.length; i++) {
      w3RemoveClass(x[i], "show");
      if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
    }
  }

  function w3AddClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
      if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
    }
  }

  function w3RemoveClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
      while (arr1.indexOf(arr2[i]) > -1) {
        arr1.splice(arr1.indexOf(arr2[i]), 1);
      }
    }
    element.className = arr1.join(" ");
  }


  // Add active class to the current button (highlight it)
  var btnContainer = document.getElementById("myBtnContainer");
  var btns = btnContainer.getElementsByClassName("btn");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function(){
      var current = document.getElementsByClassName("active");
      current[0].className = current[0].className.replace(" active", "");
      this.className += " active";
    });
  }
</script>
<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
crossorigin="anonymous"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
<script src="js/easing.min.js"></script>
<script src="js/hoverIntent.js"></script>
<script src="js/superfish.min.js"></script>
<script src="js/jquery.ajaxchimp.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.tabs.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/waypoints.min.js"></script>
<script src="js/mail-script.js"></script>
<script src="js/main copy.js"></script>
</body>

</html>
