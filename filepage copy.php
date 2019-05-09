<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
	header("location:login.html");
} else {
	$session_name = $_SESSION["username"];
	$fileid = $_GET['id'];
	$statement = "SELECT * FROM files WHERE file_id=$fileid";
	$conn = mysqli_connect('127.0.0.1', 'root', 'Overdrive08', 'mytestdb');
	if (mysqli_connect_error()) {
		die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
	} else {
		$res = mysqli_query($conn, $statement);
		$rows = mysqli_num_rows($res);
		if ($rows == 0) {
			header("location:homepage_2.php");
		}
		$file = mysqli_fetch_assoc($res);
		date_default_timezone_set('America/New_York');

		// username of user who uploaded the file
		$username = $file['username'];

		// Check followership of current user and the uploader of the file
		$query = "SELECT * FROM follow WHERE sender_id = '$session_name' AND receiver_id = '$username'";
		$res2 = mysqli_query($conn, $query);
		$num = mysqli_num_rows($res2);

		// Comment
		$comment_query = "SELECT * FROM comment WHERE file_id = $fileid ORDER BY comment_dt";
		$comment_res = mysqli_query($conn, $comment_query);
		$comments = mysqli_fetch_all($comment_res, MYSQLI_ASSOC);
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
	<title>File Page</title>

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

	<style type="text/css">

	.btn1 {
		background: #7c32ff;
		padding: 10px 30px;
		border-radius: 10px;
		line-height: 30px;
		border: 1px solid #7c32ff;
		color: #fff;
		transition: 0.2s;
		float: right;
	}
	.btn1:hover {
		background: transparent;
		color: #000;
	}
	.img1 {
		width: 20%;
	}
</style>

<style>
#myImg {
	border-radius: 5px;
	cursor: pointer;
	transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	padding-top: 100px; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
	margin: auto;
	display: block;
	width: 80%;
	max-width: 700px;
}

/* Caption of Modal Image */
#caption {
	margin: auto;
	display: block;
	width: 80%;
	max-width: 700px;
	text-align: center;
	color: #ccc;
	padding: 10px 0;
	height: 150px;
}

/* Add Animation */
.modal-content, #caption {
	-webkit-animation-name: zoom;
	-webkit-animation-duration: 0.6s;
	animation-name: zoom;
	animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
	from {-webkit-transform:scale(0)}
	to {-webkit-transform:scale(1)}
}

@keyframes zoom {
	from {transform:scale(0)}
	to {transform:scale(1)}
}

/* The Close Button */
.close {
	position: absolute;
	top: 15px;
	right: 35px;
	color: #f1f1f1;
	font-size: 40px;
	font-weight: bold;
	transition: 0.3s;
}

.close:hover,
.close:focus {
	color: #bbb;
	text-decoration: none;
	cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
	.modal-content {
		width: 100%;
	}
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

					<!-- Start Post Area -->
					<section class="post-area">

						<!-- Portfolio Gallery Grid -->
						<form class="form-style" action = "filepage_handler.php" method = "post" enctype="multipart/form-data">
							<div class="row">
								<div class="column documents">
									<div class="content">
										<div class="single-post-item">
											<div class="post-thumb">
												<img class="img-fluid" src="<?php if (strcasecmp($file['file_type'], 'jpg') == 0) {echo "uploads/". $file['file_hash'] . '.' .$file['file_type'];}?>" alt="">
												<img id="myImg" src="img_snow.jpg" alt="Snow" style="width:100%;max-width:300px">

												<!-- The Modal -->
												<div id="myModal" class="modal">
													<span class="close">&times;</span>
													<img class="modal-content" id="img01">
													<div id="caption"></div>
												</div>
											</div>
											<div class="post-details">
												<h4><a><font size="3"><?php echo $file['file_name']; ?></font></a></h4>

												<!-- Preview based on file type -->
												<?php if (strcasecmp($file['file_type'], 'mp3') == 0) {?>
													<audio width="600px" controls>
														<source src="uploads/<?php echo $file['file_hash'] . '.' .$file['file_type'];?>" type="audio/mpeg">
															Your browser does not support the audio element.
														</audio>
														<hr>
														<br>
													<?php }
													if (strcasecmp($file['file_type'], 'pdf') == 0) {
														?>
														<!--<embed type="application/pdf" src="" width="700px" height="820px" />-->
														<!--<iframe src="" style="width: 100%;height: 1000%;border: none;" ></iframe>-->
														<!--<object data="" type="application/pdf" width="700" height="740">
														<a href="">test.pdf</a>
													</object>-->


													<object data="uploads/<?php echo $file['file_hash'] . '.' .$file['file_type'];?>" type="application/pdf" width="700" height="740">
														<embed src="uploads/<?php echo $file['file_hash'] . '.' .$file['file_type'];?>" width="700px" height="740px" />
															<p>This browser does not support PDFs. Please download the PDF to view it:
																<a href="uploads/<?php echo $file['file_hash'] . '.' .$file['file_type'];?>">Download PDF</a>.
															</p>
														</embed>
													</object>
													<!-- END Preview based on file type -->

													<hr>
													<br>
												<?php }
												if (strcasecmp($file['file_type'], 'jpg') == 0) {
													?>

													<?php
												}
												?>
												<h5></h5>
												<p><?php echo $file['file_desc'];?></p>
												<hr>
												<br>
												<p><?php echo $file['file_desc'];?></p>
												<div class="blog-meta">
													<a href="#" class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y  g:i:s  a e", strtotime($file['dt_uploaded'])); ?></a>

												</div>
												<!-- only admins and the user that posted it should see the dividing line and the delete button -->
												<?php
												if (strcasecmp($_SESSION["username"], $file['username']) == 0) {
													?>
													<hr color = "ffffff">
													<h3><button type="submit" name="delete" class="myButton" onclick="return confirm('Are you sure you want to delete this file?')">Delete File</button></h3>
													<input type="hidden" name="id" value="<?php echo $file['file_id']; ?>"/>
													<?php
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>

						<!-- Comment Section -->

						<h3>Comments</h3>
						<hr>

						<div class="comments-area" id="comments-area" style="overflow-y: scroll; height:500px;">
							<!-- ADDED COMMENT-->
							<?php

							foreach ($comments as $comment):
								{
									$name=$comment['username'];
									$comm=$comment['comment'];
									$time= date("F jS, Y, g:i:s a e", strtotime($comment['comment_dt']));
									?>
									<div class="comment-list">
										<div class="single-comment justify-content-between d-flex">
											<div class="user justify-content-between d-flex">
												<!-- Profile picture of commenter. -->
												<!-- All photos are resized to 70x70 to fit the design -->
												<div class="thumb">
													<img src="img/blog/c1.jpg" width="70" height="70">
												</div>
												<!-- Information on the commenter -->
												<div class="desc">
													<!-- Clicking the persons name should take you to their profile page -->
													<h5><a href="userpage2.php?user=<?php echo $name;?>"><?php echo $name;?></a></h5>
													<!-- Date and Time of the comment -->
													<p class="date"><?php echo $time;?> </p>

													<!-- Actual Comment -->
													<p class="comment">
														<?php echo $comm;?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<?php
								} endforeach;
								?>


								<!-- END ADDED COMMENT-->

							</div>

							<!-- Leaving a comment -->
							<div class="comment-form">
								<h4>Leave a Comment:</h4>
								<form id="comment_form" name="comment_form" action="" onsubmit="return post();" method = "post">
									<div class="form-group">
										<textarea class="form-control mb-10" rows="5" pattern="[\w\s-@./#%+~]+" id="message" name="message" placeholder="Message"
										onfocus="this.placeholder = ''" onblur="this.placeholder = 'Message'" required=""></textarea>
									</div>
									<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>"/>
									<button type="submit" name="submit" id="submit" class="btn1">Post Comment</button>
								</form>
							</div>
							<!--END leaving a comment-->
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
							<script type="text/javascript">
							function post()
							{
								var comment = document.getElementById("message").value;
								if(comment) {
									$.ajax
									({
										type: 'post',
										url: 'post_comments.php',
										data:
										{
											user_comm:comment,
											file_id: '<?php echo $fileid; ?>'
										},
										success: function (response)
										{
											document.getElementById("comments-area").innerHTML=response+document.getElementById("comments-area").innerHTML;
											$('#message').val('');

										}
									});
								}

								return false;
							}
							</script>
							<!-- END Comment Section -->

						</section>
						<!-- Start Post Area -->
					</div>


					<div class="col-lg-4 sidebar">


						<div class="single-widget protfolio-widget">
							<h4 class="title"> Posted By </h4>
							<img class="img-fluid" src="img/blog/user3.png" alt="">

							<h4 align="center"><strong><font size="5" color="red"><?php echo $username?></font></strong></h4>
							<br>
							<form class="form-style" action = "filepage_handler.php" method = "post" enctype="multipart/form-data">
								<?php if (strcasecmp($_SESSION["username"], $username) != 0) {?>
									<?php if ($num <= 0) {?>
										<center><h3><button type="submit" name="follow" class="myButton">Follow User</button></h3></center>
										<input type="hidden" name="user" value="<?php echo $username ?>"/>
										<input type="hidden" name="file_id" value="<?php echo $file['file_id']; ?>"/>
									<?php } else {?>
										<center><h3><button type="submit" name="unfollow" class="myButton">Following</button></h3></center>
										<input type="hidden" name="user" value="<?php echo $username ?>"/>
										<input type="hidden" name="file_id" value="<?php echo $file['file_id']; ?>"/>
									<?php }
								}?>
							</form>
						</div>


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
// Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
	modal.style.display = "block";
	modalImg.src = this.src;
	captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	modal.style.display = "none";
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
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.tabs.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/waypoints.min.js"></script>
<script src="js/mail-script.js"></script>
<script src="js/main copy.js"></script>
</body>

</html>
