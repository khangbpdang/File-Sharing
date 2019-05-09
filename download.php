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
  <title>Download Page</title>


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



  <!--===============================================================================================-->
  	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
  <!--===============================================================================================-->
  	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <!--===============================================================================================-->
  	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
  <!--===============================================================================================-->
  	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
  <!--===============================================================================================-->
  	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
  <!--===============================================================================================-->
    <link rel="stylesheet" href="css/userpage.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
  	<link rel="stylesheet" type="text/css" href="css/table.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" href="css/userpage.css">
<link rel="stylesheet" type="text/css" href="css/userpage2-demo.css">



<style type="text/css">
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
        <form action"download.php" method="GET" class="d-flex justify-content-between">
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

            <div class="limiter">
              <div class="container-table100">
                <div class="wrap-table100">
                  <div class="table100 ver1 m-b-110">
                    <div class="table100-head">
                      <table>
                        <thead>
                          <tr >
                            <th class="column1">Filename</th>
                            <th class="column2">File Type</th>
                            <th class="column3">Size</th>
                            <th class="column4">Description</th>
                            <th class="column5">Downloads</th>
                            <th class="column6">Action</th>
                          </tr>
                        </thead>
                      </table>
                    </div>

                    	<div class="table100-body js-pscroll">
                      <table>
                        <tbody>
                          <?php
                          foreach ($files as $file):
                            ?>
                            <tr >
                              <td class="column1"><a class="a1" href="filepage.php?id=<?php echo $file['file_id'];?>"><?php echo $file['file_name']; ?></a></td>
                              <td class="column2"><?php echo strtoupper($file['file_type']); ?></td>
                              <td class="column3"><?php echo floor($file['size'] / 1000) . ' KB'; ?></td>
                              <td class="column4"><?php echo trimSentence($file['file_desc']); ?></td>
                              <td class="column5"><?php echo $file['downloads']; ?></td>
                              <td class="column6">
                                <a class="primary-btn submit_btn text-uppercase" style="width:5.00rem; text-align:center;"  href="download.php?file_id=<?php echo $file['file_id'] ?>">Download</a>
                                <p style="padding:5px"/>
                                <a class="primary-btn submit_btn text-uppercase" style="width:5.00rem; text-align:center;" href="download.php?file_rm_id=<?php echo $file['file_id'] ?>"
                                  onclick="return confirm('Are you sure you want to delete this file?')">
                                  Delete
                                </a>
                              </td>
                            </tr>
                          <?php endforeach;?>

                        </tbody>

                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--===============================================================================================-->
          	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
          <!--===============================================================================================-->
          	<script src="vendor/bootstrap/js/popper.js"></script>
          	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
          <!--===============================================================================================-->
          	<script src="vendor/select2/select2.min.js"></script>
          <!--===============================================================================================-->
          	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
          	<script>
          		$('.js-pscroll').each(function(){
          			var ps = new PerfectScrollbar(this);

          			$(window).on('resize', function(){
          				ps.update();
          			})
          		});


          	</script>
            <!-- Portfolio Gallery Grid -->
            <script src="js/scrollbar.js"></script>


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


<script src="vendor/jquery/jquery-3.2.1.min.js"></script>


<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
crossorigin="anonymous"></script>
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

<link rel="stylesheet" type="text/css" href="css/util.css">


</body>

</html>
