<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
	header("location:login.html");
}
$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
if (mysqli_connect_error()) {
	die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
	$username = $_SESSION["username"];
	$sql = "SELECT * FROM files";

	$input = mysqli_real_escape_string($conn, stripcslashes(trim($_GET['search_input'])));

	$cond = "";

	// Formulate condition for searching
	// Require: search_input from URL which should be passed in through the search bar
	if ($input !== '') {
		$query = preg_split('/\s+/', $input, -1, PREG_SPLIT_NO_EMPTY); // split search queries into terms
		foreach ($query as $text) {
			$cond .= "file_name LIKE '%" .$text ."%' OR
			username LIKE '%" .$text ."%' OR
			file_type LIKE '%" .$text ."%' OR
			size LIKE '%" .$text ."%' OR
			file_desc LIKE '%" .$text ."%' OR
			dt_uploaded LIKE '%" .$text ."%' OR
			filesound LIKE '%".metaphone(mysqli_real_escape_string($conn, $text))."%' OR ";
		}
		$cond = substr($cond, 0, -4); // ignore the last 4 letters " OR "
		$sql = "SELECT * FROM files WHERE ". $cond ." ORDER BY file_name, dt_uploaded DESC" ;
		//echo $sql;
	} else {
		$sql = "SELECT * FROM files ORDER BY dt_uploaded DESC";
	}
	//$sql = "SELECT * FROM files";
	$result = mysqli_query($conn, $sql);
	$files = mysqli_fetch_all($result, MYSQLI_ASSOC);
	//echo mysqli_num_rows($result);


	function trimSentence($str){
		if (strlen($str) > 140)
		{
			$str = substr($str, 0, 140);
			$str = explode(' ', $str);
			array_pop($str); // remove last word from array
			$str = implode(' ', $str);
			echo $str." ...";
		} else {
			echo $str;
		}
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
	<title>Home Page</title>

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
	<link rel="stylesheet" href="css/homepage.css">
	<link rel="stylesheet" href="css/filepage.css">

	<link rel="stylesheet" type="text/css" href="css/userpage2-demo.css">
</head>
<style>

.btncust .p1 {
	color:#FFF;
}
.btncust:hover .p1 {
	color:#484848;
}
.btncust.active .p1{
	color:#383838;
}
.btncust {
	border: none;
	outline: none;
	padding: 1px 1px;
	padding-left: 10px;
	padding-right: 10px;
	border-radius: 10px;
	width:100%;
	line-height: 10px;
	background-color: #1e1c27;
	cursor: pointer;

	text-align:left;
}
/* Add a light grey background on mouse-over */
.btncust:hover {
	background-color: #c0c0c0;

}

/* Add a dark background to the active button */
.btncust.active {
	background-color: #ddd;
	color:#B22222;
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

.show {
	display: inline-block;
}

</style>
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
			<div align="center" >
				<h1 style="color:white; font-size:150%;">
					<?php
					echo 'Welcome  ' . $_SESSION["username"];
					?>
				</h1>
			</div>
		</div>

		<div class="search_input" id="search_input_box">
			<div class="container box_1170">
				<form action"homepage_2.php" method="GET" class="d-flex justify-content-between">
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
					<!-- Start Post Area -->
					<section class="post-area">

						<!-- Portfolio Gallery Grid -->
						<div class="row">
							<!-- Test Post 	<div class="col-lg-6 col-md-6">-->

							<?php
							foreach ($files as $file):
								switch($file['file_type']):
									case "docx":
									?>

									<div class="column documents col-lg-6 col-md-6">
										<div class="content">
											<div class="single-post-item">
												<div class="post-thumb" style = "height:480px">
													<img class="img-fluid" src="img/archive/c9.png" alt="">
												</div>
												<div class="post-details">
													<h4><a href="filepage.php?id=<?php echo $file['file_id'];?>"><?php echo $file['file_name']; ?></a></h4>
													<p><?php echo trimSentence($file['file_desc']); ?></p>
													<div class="blog-meta">
														<hr>
														<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y, g:i:s a", strtotime($file['dt_uploaded']));?></a>
														<a class="m-gap"><span class="lnr lnr-bubble">
															<?php
															$id = $file['file_id'];
															echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
															?>
														</span>
													</a>
													<br>
													<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<?php 		break;
								case "txt":
								?>

								<div class="column documents col-lg-6 col-md-6">
									<div class="content">
										<div class="single-post-item">
											<div class="post-thumb">
												<img class="img-fluid" src="img/archive/c10.png" alt="">
											</div>
											<div class="post-details">
												<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
												<p><?php echo trimSentence($file['file_desc']); ?></p>
												<div class="blog-meta">
													<hr>
													<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a", strtotime($file['dt_uploaded'])); ?></a>
													<a class="m-gap"><span class="lnr lnr-bubble">
														<?php
														$id = $file['file_id'];
														echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
														?>
													</span>
												</a>
												<br>
												<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php 		break;
							case "jpg":
							?>

							<div class="column images col-lg-6 col-md-6">
								<div class="content">
									<div class="single-post-item">
										<div class="post-thumb" style = "height:400px">
											<img class="img-fluid" src="uploads/<?php echo $file['file_hash']. '.' .$file['file_type']; ?>" alt="">
										</div>
										<div class="post-details">
											<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
											<p><?php echo trimSentence($file['file_desc']); ?></p>
											<div class="blog-meta">
												<hr>
												<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS Y, g:i:s a", strtotime($file['dt_uploaded'])); ?></a>
												<a class="m-gap"><span class="lnr lnr-bubble">
													<?php
													$id = $file['file_id'];
													echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
													?>
												</span>
											</a>
											<br>
											<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php 		break;
						case "png":
						?>

						<div class="column images col-lg-6 col-md-6">
							<div class="content">
								<div class="single-post-item">
									<div class="post-thumb">
										<img class="img-fluid" style="height:400px; object-fit: cover;" src="uploads/<?php echo $file['file_hash']. '.' .$file['file_type'] ?>" alt="">
									</div>
									<div class="post-details">
										<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
										<p><?php echo trimSentence($file['file_desc']); ?></p>
										<div class="blog-meta">
											<hr>
											<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a", strtotime($file['dt_uploaded'])); ?></a>
											<a class="m-gap"><span class="lnr lnr-bubble">
												<?php
												$id = $file['file_id'];
												echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
												?>
											</span>
										</a>
										<br>
										<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php 		break;
					case "mp3":
					?>

					<div class="column audio col-lg-6 col-md-6">
						<div class="content">
							<div class="single-post-item">
								<div class="post-thumb">
									<img class="img-fluid" src="img/archive/c1.jpg" alt="">
								</div>
								<div class="post-details">
									<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
									<p><?php echo trimSentence($file['file_desc']); ?></p>
									<div class="blog-meta">
										<hr>
										<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a", strtotime($file['dt_uploaded'])); ?></a>
										<a class="m-gap"><span class="lnr lnr-bubble">
											<?php
											$id = $file['file_id'];
											echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
											?>
										</span>
									</a>
									<br>
									<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php 		break;
				case "pdf":
				?>

				<div class="column documents col-lg-6 col-md-6">
					<div class="content">
						<div class="single-post-item">
							<div class="post-thumb">
								<img class="img-fluid" src="img/archive/c1.jpg" alt="">
							</div>
							<div class="post-details">
								<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
								<p><?php echo trimSentence($file['file_desc']); ?></p>
								<div class="blog-meta">
									<hr>
									<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a", strtotime($file['dt_uploaded'])); ?></a>
									<a class="m-gap"><span class="lnr lnr-bubble">
										<?php
										$id = $file['file_id'];
										echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
										?>
									</span>
								</a>
								<br>
								<a href="userpage2.php?user=<?php echo $file['username'];?>" class="m-gap"><?php echo "Uploaded by " . $file['username'];?></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php break; endswitch;  endforeach;?>

		</div>
	</section>
	<!-- End Test Post-->

	<!-- Start Post Area -->
</div>
<div class="col-lg-4 sidebar">
	<div class="single-widget category-widget">
		<h4 class="title">Post Categories</h4>
		<div id="myBtnContainer">
			<button class="btncust active" onclick="filterSelection('all')"> <p class="p1"><img src="img/bullet.png" alt=""> All</p></button> <br>
			<button class="btncust" onclick="filterSelection('audio')"><p class="p1"><img src="img/bullet.png" alt=""> Audio</p></button> <br>
			<button class="btncust" onclick="filterSelection('documents')"> <p class="p1"><img src="img/bullet.png" alt=""> Documents</p></button> <br>
			<button class="btncust" onclick="filterSelection('images')"> <p class="p1"><img src="img/bullet.png" alt=""> Images</p></button> <br>
			<!--<button class="btncust" onclick="filterSelection('pdf')"> <p class="p1"><img src="img/bullet.png" alt=""> PDF</p></button> <br>-->
			<script>
			// Add active class to the current button (highlight it)
			var btnContainer = document.getElementById("myBtnContainer");
			var btns = btnContainer.getElementsByClassName("btncust");
			for (var i = 0; i < btns.length; i++) {
				btns[i].addEventListener("click", function(){
					var current = document.getElementsByClassName("active");
					current[0].className = current[0].className.replace(" active", "");
					this.className += " active";

				});
			}
			</script>
		</div>
	</div>

	<div class="single-widget tags-widget">
		<h4 class="title">Post Tags</h4>
		<ul>
			<li><a href="#">Lifestyle</a></li>
			<li><a href="#">Art</a></li>
			<li><a href="#">Adventure</a></li>
			<li><a href="#">Food</a></li>
			<li><a href="#">Techlology</a></li>
			<li><a href="#">Fashion</a></li>
			<li><a href="#">Architecture</a></li>
			<li><a href="#">Science</a></li>
			<li><a href="#">Politics</a></li>
		</ul>
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
