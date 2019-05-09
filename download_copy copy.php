<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
  header("location:login.html");
}
include 'filesLogic.php';
function trimSentence($str){
  if (strlen($str) > 140)
  {
    $str = substr($str, 0, 200);
    $str = explode(' ', $str);
    array_pop($str); // remove last word from array
    $str = implode(' ', $str);
    echo $str." ...";
  }
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
  <title>Download Page</title>

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
  <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
  <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
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
  /*[ Scroll bar ]*/
  .js-pscroll {
    position: relative;
    overflow: hidden;
  }

  .tablediv {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
    -moz-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
    -webkit-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
    -o-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
    -ms-box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
  }
  .user_files {
    border-collapse: collapse;
    width: 100%;

  }



  .user_files td, .user_files th {
    border: 1px solid	#D3D3D3;
    padding: 8px;

  }

  .user_files tr:nth-child(even){background-color: #f2f2f2;}

  .user_files tr:hover {background-color: #ddd;}

  .user_files th {
    padding-top: 12px;
    padding-bottom: 12px;
    background-color: #778eff  ;
    color: white;

  }

  .user_files td {
    color:#2F4F4F;
  }

  .user_files .ps__rail-y {
    width: 9px;
    background-color: transparent;
    opacity: 1 !important;
    right: 5px;
  }
  .user_files .ps__rail-y::before {
    content: "";
    display: block;
    position: absolute;
    background-color: #ebebeb;
    border-radius: 5px;
    width: 100%;
    height: calc(100% - 30px);
    left: 0;
    top: 15px;
    background-color: #ebebeb;
  }

  .user_files .ps__rail-y .ps__thumb-y {
    width: 100%;
    right: 0;
    background-color: transparent;
    opacity: 1 !important;
  }
  .user_files .ps__rail-y .ps__thumb-y::before {
    content: "";
    display: block;
    position: absolute;
    background-color: #cccccc;
    border-radius: 5px;
    width: 100%;
    height: calc(100% - 30px);
    left: 0;
    top: 15px;
  }

  table.sortable thead {
    background-color:#eee;
    color:#666666;
    font-weight: bold;
    cursor: default;
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
  .a1 {
    color:blue;
  }
  .a1:hover {
    color: #000080;
  }
</style>
</head>

<body>
  <!--================ Start header Top Area =================-->
  <section class="header-top">
    <div class="container box_1170">
      <div class="row align-items-center justify-content-between">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <a href="index.html" class="logo">
            <img src="img/logo7.png" alt="">
          </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 search-trigger">
          <a href="#" class="search">
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
        <form class="d-flex justify-content-between">
          <input type="text" class="form-control" id="search_input" placeholder="Search Here">
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
          <!-- Display Error-->
          <!-- DELETE error-->
          <?php
          if(isset($_GET['message_del'])) {
            $message = $_GET['message_del'];
            if (strcasecmp($message, 'failure') == 0) {
              ?>
              <div class="alert">
                <span class="closebtn">&times;</span>
                <strong>HEYYY!</strong> You don't own this file :D Please don't do this again! ...
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
              <div class="alert success">
                <span class="closebtn">&times;</span>
                <strong>Success!</strong> You have just deleted a file.
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
          <!-- END DELETE error-->

          <!-- DOWNLOAD error-->
          <?php
          if(isset($_GET['message_dwn'])) {
            $message = $_GET['message_dwn'];
            if (strcasecmp($message, 'failure') == 0) {
              ?>
              <div class="alert">
                <span class="closebtn">&times;</span>
                <strong>ERM ...</strong> Something went wrong I guess :P ...
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
              <div class="alert success">
                <span class="closebtn">&times;</span>
                <strong>Success!</strong> The site didn't crash and you've just downloaded a file!
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
          <!-- END DOWNLOAD Error-->

          <!-- Start Post Area -->
          <section class="post-area">
            <div class="tablediv">
              <table class="user_files" style="width:100%">
                <thead>
                  <tr class="row100 head">
                    <!--<th>ID</th>-->
                    <th style="width:20%; text-align:center;">Filename</th>
                    <th style="width:0%; text-align:center;">File Type</th>
                    <th style="width:9%; text-align:center;">Size</th>
                    <th style="width:40%; text-align:center;">Description</th>
                    <th style="width:0%; text-align:center;">Downloads</th>
                    <th style="text-align:center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($files as $file):
                    ?>
                    <tr>

                      <td style="width:20%"><a class="a1" href="filepage.php?id=<?php echo $file['file_id'];?>"><?php echo $file['file_name']; ?></a></td>
                      <td style="width:0%;"><?php echo strtoupper($file['file_type']); ?></td>
                      <td style="width:9%;"><?php echo floor($file['size'] / 1000) . ' KB'; ?></td>
                      <td style="width:40%;"><?php echo trimSentence($file['file_desc']); ?></td>
                      <td style="width:0%;"><?php echo $file['downloads']; ?></td>
                      <td align="center">
                        <a class="primary-btn submit_btn text-uppercase" style="width:8.00rem"  href="download.php?file_id=<?php echo $file['file_id'] ?>">Download</a>

                        <hr>
                        <a class="primary-btn submit_btn text-uppercase" style="width:8.00rem" href="download.php?file_rm_id=<?php echo $file['file_id'] ?>"
                          onclick="return confirm('Are you sure you want to delete this file?')">
                          Delete
                        </a>
                      </td>
                    </tr>
                  <?php endforeach;?>

                </tbody>

              </table>
            </div>


            <!-- Portfolio Gallery Grid -->


          </section>

          <!-- Start Post Area -->
        </div>
        <div class="col-lg-4 sidebar">


          <div class="single-widget protfolio-widget">
            <h4 class="title"> Download Page</h4>
            <img class="img-fluid" src="img/downloadpic.png" alt="">

            <h4>Take What's Yours!</h4>

            <p class="p-text">
              Find an organized list of everything you
              have uploaded.  Download what you need!
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

<script>
$('.js-pscroll').each(function(){
  var ps = new PerfectScrollbar(this);

  $(window).on('resize', function(){
    ps.update();
  })
});


</script>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
crossorigin="anonymous"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="js/w3.js"></script>
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
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="js/scrollbar.js"></script>
<script src="vendor/select2/select2.min.js"></script>
</body>

</html>
