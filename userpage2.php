<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
	header("location:login.html");
} else {
	$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
	if (mysqli_connect_error()) {
		die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
	} else {

		$username = $_SESSION["username"];
		//include "filesLogic.php";
		include "userpage_handler.php";

		// Check for user identity
		if (isset($_GET['user'])) {
			$visit = $_GET['user'];
			$q = "SELECT * FROM users WHERE username='$visit'";
			$res = mysqli_query($conn, $q);
			$num = mysqli_num_rows($res);
			$profile = mysqli_fetch_assoc($res);
			if ($num == 0) {
				header("location:userpage2.php");
			}
		} else {
			$visit = $_SESSION["username"];
			$q = "SELECT * FROM users WHERE username='$visit'";
			$res = mysqli_query($conn, $q);
			$profile = mysqli_fetch_assoc($res);
		}

		// file
		$sql = "SELECT * FROM files WHERE username='$visit'";
	  $result = mysqli_query($conn, $sql);
	  $files = mysqli_fetch_all($result, MYSQLI_ASSOC);

		// Search
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
			$cond = "(".substr($cond, 0, -4).")";
			$sql = "SELECT * FROM files WHERE username='$username' AND ".$cond;
			$result = mysqli_query($conn, $sql);
		  $files = mysqli_fetch_all($result, MYSQLI_ASSOC);

		}
		//echo "Success!" ." | ". mysqli_num_rows($result) ." | ". metaphone($input);
		//else {
		//	$sql = "SELECT * FROM files WHERE username='$username'";
		//}


		// Check followership of current user and the uploader of the file
		$query = "SELECT * FROM follow WHERE sender_id = '$username' AND receiver_id = '$visit'";
		$res2 = mysqli_query($conn, $query);
		$num = mysqli_num_rows($res2);

		date_default_timezone_set('America/New_York');



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
	<title>Profile Page</title>

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
	<link rel="stylesheet" type="text/css" href="css/userpage.css">
	<link rel="stylesheet" type="text/css" href="css/component.css" />
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
	<link rel="stylesheet" type="text/css" href="css/userpage2-demo.css">
	<link rel="stylesheet" type="text/css" href="css/userpage2-normalize.css">

	<style>


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

	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}


	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
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
		background-color: black;
		color: white;
		border: 2px solid #008CBA;
	}
	.btn1:hover {
		background-color: #008CBA;
		color: black;
	}

	.myButton {
		-moz-box-shadow:inset 0px 0px 15px 3px #23395e;
		-webkit-box-shadow:inset 0px 0px 15px 3px #23395e;
		box-shadow:inset 0px 0px 15px 3px #23395e;
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2e466e), color-stop(1, #415989));
		background:-moz-linear-gradient(top, #2e466e 5%, #415989 100%);
		background:-webkit-linear-gradient(top, #2e466e 5%, #415989 100%);
		background:-o-linear-gradient(top, #2e466e 5%, #415989 100%);
		background:-ms-linear-gradient(top, #2e466e 5%, #415989 100%);
		background:linear-gradient(to bottom, #2e466e 5%, #415989 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#2e466e', endColorstr='#415989',GradientType=0);
		background-color:#2e466e;
		-moz-border-radius:17px;
		-webkit-border-radius:17px;
		border-radius:17px;
		border:1px solid #1f2f47;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:15px;
		padding:6px 13px;
		text-decoration:none;
		text-shadow:0px 1px 0px #263666;

	}
	.myButton:hover {
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #415989), color-stop(1, #2e466e));
		background:-moz-linear-gradient(top, #415989 5%, #2e466e 100%);
		background:-webkit-linear-gradient(top, #415989 5%, #2e466e 100%);
		background:-o-linear-gradient(top, #415989 5%, #2e466e 100%);
		background:-ms-linear-gradient(top, #415989 5%, #2e466e 100%);
		background:linear-gradient(to bottom, #415989 5%, #2e466e 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#415989', endColorstr='#2e466e',GradientType=0);
		background-color:#415989;
	}
	.myButton:active {
		position:relative;
		top:1px;
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
				<form action"userpage2.php" method="GET" class="d-flex justify-content-between">
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
						<style>
						.crop {
							overflow:hidden;
							max-width: center;
							white-space:nowrap;
							text-overflow:ellipsis;
						}
						</style>
						<!-- Portfolio Gallery Grid -->
						<div class="row">
							<!-- Added post -->
							<?php
							foreach ($files as $file):
								switch($file['file_type']):
									case "docx":
									?>
									<div class="column documents">
										<div class="content">
											<div class="single-post-item">
												<div class="post-thumb">
													<img class="img-fluid" src="img/archive/c9.png" alt="">
												</div>
												<div class="post-details">
													<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
													<p><?php echo $file['file_desc']; ?></p>

													<div class="blog-meta">
														<a  class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y, g:i:s a e", strtotime($file['dt_uploaded']));?></a>
														<a class="m-gap"><span class="lnr lnr-bubble"><?php
														$id = $file['file_id'];
														echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
														?>
													</span>
												</a>
												<br>

											</div>
										</div>
									</div>
								</div>
							</div>
							<?php 		break;
							case "txt":
							?>
							<div class="column documents">
								<div class="content">
									<div class="single-post-item">
										<div class="post-thumb">
											<img class="img-fluid" src="img/archive/c10.png" alt="">
										</div>
										<div class="post-details">
											<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
											<p><?php echo $file['file_desc']; ?></p>
											<div class="blog-meta">
												<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
												<a class="m-gap"><span class="lnr lnr-bubble"><?php
												$id = $file['file_id'];
												echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
												?>
											</span>
										</a>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php 		break;
					case "jpg":
					?>
					<div class="column images">
						<div class="content">
							<div class="single-post-item">
								<div class="post-thumb">
									<img class="img-fluid" src="uploads/<?php echo $file['file_hash']. '.' .$file['file_type']; ?>" alt="">
								</div>
								<div class="post-details">
									<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
									<p><?php echo $file['file_desc']; ?></p>
									<div class="blog-meta">
										<a  class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS Y, g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
										<a class="m-gap"><span class="lnr lnr-bubble"><?php
										$id = $file['file_id'];
										echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
										?>
									</span>
								</a>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php 		break;
			case "png":
			?>
			<div class="column images">
				<div class="content">
					<div class="single-post-item">
						<div class="post-thumb">
							<img class="img-fluid" src="uploads/<?php echo $file['file_hash']. '.' .$file['file_type'] ?>" alt="">
						</div>
						<div class="post-details">
							<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
							<p><?php echo $file['file_desc']; ?></p>
							<div class="blog-meta">
								<a  class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
								<a class="m-gap"><span class="lnr lnr-bubble"><?php
								$id = $file['file_id'];
								echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
								?>
							</span>
						</a>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php 		break;
	case "mp3":
	?>
	<div class="column audio">
		<div class="content">
			<div class="single-post-item">
				<div class="post-thumb">
					<img class="img-fluid" src="img/archive/c1.jpg" alt="">
				</div>
				<div class="post-details">
					<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
					<p class="crop"><?php echo $file['file_desc']; ?></p>
					<div class="blog-meta">
						<a  class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
						<a class="m-gap"><span class="lnr lnr-bubble"><?php
						$id = $file['file_id'];
						echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
						?>
					</span>
				</a>
				<br>
			</div>
		</div>
	</div>
</div>
</div>

<?php 		break;
case "pdf":
?>
<div class="column documents">
	<div class="content">
		<div class="single-post-item">
			<div class="post-thumb">
				<img class="img-fluid" src="img/archive/c1.jpg" alt="">
			</div>
			<div class="post-details">
				<h4><a href="filepage.php?id=<?php echo $file['file_id']?>"><?php echo $file['file_name']; ?></a></h4>
				<p><?php echo $file['file_desc']; ?></p>
				<div class="blog-meta">
					<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y g:i:s a e", strtotime($file['dt_uploaded'])); ?></a>
					<a class="m-gap"><span class="lnr lnr-bubble"><?php
					$id = $file['file_id'];
					echo "  ".mysqli_num_rows(mysqli_query($conn, "SELECT * FROM comment WHERE file_id = $id"));
					?>
				</span>
			</a>
			<br>
		</div>
	</div>
</div>
</div>
</div>
<?php break; endswitch; endforeach;?>


<!-- End added post-->



</div>
</section>
<!-- Start Post Area -->
</div>


<div class="col-lg-4 sidebar">


	<div class="single-widget protfolio-widget">
		<h4 class="title"> User Profile</h4>
		<img class="img-fluid" style="display: block; margin-left: auto; margin-right: auto; object-fit: cover; max-width: 298px; max-height: 298px" src=
		<?php
		if (empty($profile['prof_name_hash']) || empty($profile['prof_file_type'])) {
			echo "img/blog/user3.png";
	 	} else {
			echo "profilepics/".basename($profile['prof_name_hash']). '.' . $profile['prof_file_type'];
		}
		 ?>

		 alt="">

		<h4 style="text-align:center; padding:25px"><font size="5">
			<?php
			echo $visit;
			?>
		</font>
	</h4>

	<p class="p-text">
		This is a description of the user.
		Birthday and hobbies
		whatever else they may want.

	</p>
	<br>

	<?php if (strcasecmp(trim($visit), $username) != 0) { ?>
		<!-- everyone besides the user himself should see this button below
		<h3><button type="submit" name="save" class="myButton">Follow User</button></h3>-->
		<form class="form-style" action = "userpage_handler.php" method = "post" enctype="multipart/form-data">
			<?php if ($num <= 0) {?>
				<center><h3><button type="submit" name="follow" class="myButton">Follow User</button></h3></center>
				<input type="hidden" name="user" value="<?php echo $visit ?>"/>

			<?php } else {?>
				<center><h3><button type="submit" name="unfollow" class="myButton">Following</button></h3></center>
				<input type="hidden" name="user" value="<?php echo $visit ?>"/>

			<?php }?>
		</form>
	<?php } else { ?>
		<form class="form-style" action = "account_handler.php" method = "post" enctype="multipart/form-data">
			<!-- only admins and account owner should see the dividing line and the delete button -->
			<hr color = "ffffff">
			<center><h3><button type="submit" name="acc_del_btn" class="myButton" onclick="return confirm('Are you sure you want to delete this account?')">Delete Account</button></h3></center>
			<input type="hidden" name="user" value="<?php echo $_SESSION['username']; ?>"/>

		</form>

	<?php } ?>
</div>

<div class ="single-widget category-widget">
	<h4 class="title">Post Categories</h4>
	<div id ="myBtnContainer">
		<button class="btncust active" onclick="filterSelection('all')"> <p class="p1"><img src="img/bullet.png" alt=""> All</p></button> <br>
		<button class="btncust" onclick="filterSelection('audio')"><p class="p1"><img src="img/bullet.png" alt=""> Audio</p></button> <br>
		<button class="btncust" onclick="filterSelection('documents')"> <p class="p1"><img src="img/bullet.png" alt=""> Documents</p></button> <br>
		<button class="btncust" onclick="filterSelection('images')"> <p class="p1"><img src="img/bullet.png" alt=""> Images</p></button> <br>
		
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
<?php if (strcasecmp(trim($visit), $username) == 0) { ?>
<div class ="single-widget category-widget">
	<h4 class="title">Upload Profile Picture</h4>
	<p>1) Select File</p>
	<br>
	<form action="userpage2.php" method="post" enctype="multipart/form-data">
		<div>

			<input type="file" name="myfile" id="myfile" class="inputfile inputfile-1" accept="image/jpeg, image/png">
			<label for="myfile" style="color:white"><svg xmlns="http://www.w3.org/2000/svg" color="white" width="20" height="17" viewBox="0 0 20 17"><path stroke"red" d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file &hellip;</span></label>


			<script src="js/custom-file-input.js"></script>
			<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>


			<br>
			<br>
			<p>2) Upload Picture</p>
			<br>
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<h3><button type="submit" name="profile" class="myButton">Upload Profile Picture</button></h3>
		</div>
	</form>
</div>
<?php }?>




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
	if (c == "all") {
		c = "";
	}
	for (i = 0; i < x.length; i++) {
		w3RemoveClass(x[i], "show");
		if (x[i].className.indexOf(c) > -1) {
			w3AddClass(x[i], "show");
		}
	}
}

function w3AddClass(element, name) {
	var i, arr1, arr2;
	arr1 = element.className.split(" ");
	arr2 = name.split(" ");
	for (i = 0; i < arr2.length; i++) {
		if (arr1.indexOf(arr2[i]) == -1) {
			element.className += " " + arr2[i];
		}
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
