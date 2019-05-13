<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
	header("location:login.html");
}
// connect to database
require_once('connect_db.php');
if (mysqli_connect_error()) {
	die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
} else {
	$username = $_SESSION["username"];

	// select files for following.php
	$select = "SELECT * FROM (SELECT files.*, follow.sender_id from (SELECT receiver_id FROM follow where sender_id = '$username') n
	LEFT JOIN
	follow
	ON follow.receiver_id = n.receiver_id AND follow.sender_id = '$username'
	LEFT JOIN
	files
	ON files.username = n.receiver_id) m ORDER BY dt_uploaded DESC";
	$copy = $select;
	$input = mysqli_real_escape_string($conn, stripcslashes(trim($_GET['search_input'])));
	if ($input !== '') {
		$query = preg_split('/\s+/', $input, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($query as $text) {
			$cond .= "file_name LIKE '%" .$text ."%' OR
			username LIKE '%" .$text ."%' OR
			file_type LIKE '%" .$text ."%' OR
			size LIKE '%" .$text ."%' OR
			file_desc LIKE '%" .$text ."%' OR
			dt_uploaded LIKE '%" .$text ."%' OR
			filesound LIKE '%".metaphone(mysqli_real_escape_string($conn, $text))."%' OR ";
		}
		$cond = substr($cond, 0, -4);
		$select = "SELECT * FROM (SELECT files.*, follow.sender_id from (SELECT receiver_id FROM follow where sender_id = '$username') n
		LEFT JOIN
		follow
		ON follow.receiver_id = n.receiver_id AND follow.sender_id = '$username'
		LEFT JOIN
		files
		ON files.username = n.receiver_id) m WHERE ". $cond ." ORDER BY file_name, dt_uploaded DESC";

		//header("location:homepage_2.php?search_input=". $input);
	} else {
		$select = $copy;
	}
	$result = mysqli_query($conn, $select);
	$files = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// select follow for following.php
	$select2 = "SELECT * FROM (SELECT users.* from (SELECT receiver_id FROM follow WHERE sender_id = '$username') n
	LEFT JOIN
	users
	ON users.username = n.receiver_id
	LEFT JOIN
	follow
	ON follow.receiver_id = n.receiver_id AND follow.sender_id = '$username') m";

	$result2 = mysqli_query($conn, $select2);
	$follows = mysqli_fetch_all($result2, MYSQLI_ASSOC);



}

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
	<title>Following Page</title>

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
	<link rel="stylesheet" href="css/following.css">
	<link rel="stylesheet" type="text/css" href="css/userpage2-demo.css">




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
				<form action"following.php" method="get" class="d-flex justify-content-between">
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
						<div class="row">

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
												<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS Y, g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
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
										<img class="img-fluid" src="uploads/<?php echo $file['file_hash']. '.' .$file['file_type'] ?>" alt="">
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
	<!-- Start Post Area -->
</div>


<div class="col-lg-4 sidebar">

	<!-- Users the person is following -->

	<div class="single-widget category-widget">

		<h4 class="title">Following</h4>
		<div class="follows-area" style="overflow-y: scroll; height:500px;">
			<ul>
				<?php foreach($follows as $follow):?>
					<li>
						<a href="userpage2.php?user=<?php echo $follow['username'];?>" >
							<p class="title"><img style=" object-fit: cover;" src="
								<?php
								if (empty($follow['prof_name_hash']) || empty($follow['prof_file_type'])) {
									echo "img/blog/user3.png";
								} else {
									echo "profilepics/".basename($follow['prof_name_hash']). '.' . $follow['prof_file_type'];
								}
								?>
								" width="80" height="80">
								<font size="4">
									<?php
									echo  trimSentence($follow['username']);
									?>
								</font>

							</p>
						</a>
					</li>
				<?php endforeach;?>
				<!--
				<li>
				<a href="#" class="justify-content-between align-items-center d-flex">
				<p class="title"><img style="margin-left: auto; margin-right: auto; object-fit: cover;"  src="img/blog/c1.jpg" width="80" height="80"/>
				<div class="divcust"> Katie Johnson <br>
				<button type="submit" name="follow" class="myButton">Unfollow</button>
			</div>
		</p>

	</a>
</li>


<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="60" height="60">Nurul Haque </p>
</a>
</li>

<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="80" height="80">Khang Dang </p>
</a>
</li>

<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="60" height="60">Nurul Haque </p>
</a>
</li>

<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="80" height="80">Khang Dang </p>
</a>
</li>
<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="60" height="60">Nurul Haque </p>
</a>
</li>

<li>
<a href="#" class="justify-content-between align-items-center d-flex">
<p class="title"><img src="img/blog/user3.png" width="80" height="80">Khang Dang </p>
</a>
</li>
-->

</ul>
</div>
</div>



<!-- Users the person is following -->


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

<script src="js/filter.js"></script>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
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
