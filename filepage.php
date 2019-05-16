<?php
SESSION_START();
if(!isset($_SESSION["username"])) {
	header("location:login.html");
} else {
	// connect to database
	require_once('connect_db.php');

	// get current username and file id
	$session_name = $_SESSION["username"];
	$fileid = $_GET['id'];

	// database query to retrieve all files based on passed in file id in URL
	$statement = "SELECT * FROM files WHERE file_id=$fileid";

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

		// Profile pic display
		$q = "SELECT * FROM users WHERE username='$username'";
		$res = mysqli_query($conn, $q);
		$profile = mysqli_fetch_assoc($res);

		// Comment query and fetch according to file id
		$comment_query = "SELECT * FROM (SELECT
			comment.*, users.prof_name_hash, users.prof_file_type
			FROM (SELECT username FROM comment UNION
				SELECT username FROM users
			) n
			LEFT JOIN
			comment
			ON comment.username = n.username LEFT JOIN
			users
			ON users.username = n.username) m WHERE file_id = $fileid ORDER BY comment_dt DESC;";
			$comment_res = mysqli_query($conn, $comment_query);
			$comments = mysqli_fetch_all($comment_res, MYSQLI_ASSOC);

			// Redirect to homepage if user use the search bar
			$input = mysqli_real_escape_string($conn, stripcslashes(trim($_GET['search_input'])));
			if ($input !== "") {
				header("location:homepage_2.php?search_input=".$input);
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
			<!-- The Modal -->
			<div id="myModal" class="modal">
				<span class="close">&times;</span>
				<img class="modal-content" id="img01">
				<div id="caption"></div>
			</div>

			<div class="search_input" id="search_input_box">
				<div class="container box_1170">
					<form action"filepage.php" method="GET" class="d-flex justify-content-between">
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
								<div class="column documents">
									<div class="content">

										<div class="single-post-item">
											<?php if (strcasecmp($file['file_type'], 'pdf') == 0) {?>
												<div style="height:250px"></div>
											<?php }?>
											<div class="post-thumb" style="height:800px">
												<?php if (strcasecmp($file['file_type'], 'jpg') != 0 && strcasecmp($file['file_type'], 'png') != 0) {?>
													<img class="img-fluid" src="img/archive/
													<?php if (strcasecmp($file['file_type'], 'docx') == 0) {
														echo "c9.png";
													} else if (strcasecmp($file['file_type'], 'txt') == 0) {
														echo "c10.png";
													}
													else {
														echo "c".mt_rand(1, 8) . ".jpg";
													}
													?>" alt="">
												<?php } else {?>

													<img id="myImg" src="<?php echo "uploads/". $file['file_hash'] . '.' .$file['file_type'];?>" alt="<?php echo $file['file_desc'];?>" >
												<?php }?>
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

													?>




													<p><?php echo $file['file_desc'];?></p>
													<div class="blog-meta">
														<a class="m-gap"><span class="lnr lnr-calendar-full"></span><?php echo date("F jS, Y  g:i:s  a e", strtotime($file['dt_uploaded'])); ?></a>

													</div>
													<form class="form-style" action = "filepage_handler.php" method = "post" enctype="multipart/form-data">
														<!-- only admins and the user that posted it should see the dividing line and the delete button -->
														<?php
														if (strcasecmp($_SESSION["username"], $file['username']) == 0) {
															?>
															<hr color = "ffffff">
															<h3><button type="submit" name="delete" class="myButton" onclick="return confirm('Are you sure you want to delete this file?')">Delete File</button></h3>
															<input type="hidden" name="id" value="<?php echo $file['file_id']; ?>"/>
															<?php
														} else {
															?>
															<hr color = "ffffff">
															<h3><button type="submit" name="download" class="myButton">Download File</button></h3>
															<input type="hidden" name="id" value="<?php echo $file['file_id']; ?>"/>
															<?php
														}
														?>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>






								<!-- Leaving a comment -->
								<div class="comment-form">
									<h3>Leave a Comment:</h3>
									<br>
									<form id="comment_form" name="comment_form" action="" onsubmit="return post();" method = "post">
										<div class="form-group">
											<textarea class="form-control mb-10" rows="5" pattern="[^\s]+[\w\s-@./#%+~]+[^\s$]" id="message" name="message" placeholder="Message"
											onfocus="this.placeholder = ''" onblur="this.placeholder = 'Message'" required=""></textarea>
										</div>
										<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>"/>
										<button type="submit" name="submit" id="submit" class="btn1">Post Comment</button>
									</form>
								</div>
								<!--END leaving a comment-->
								<br>
								<br>


								<!-- Comment Section -->
								<h3>Comments</h3>
								<hr>
								<div class="comments-area" id="comments-area"  style="">
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
															<img style="display: block; margin-left: auto; margin-right: auto; object-fit: cover;" src=
															<?php
															if (empty($comment['prof_name_hash']) || empty($comment['prof_file_type'])) {
																echo "img/blog/user3.png";
															} else {
																echo "profilepics/".basename($comment['prof_name_hash']). '.' . $comment['prof_file_type'];
															}
															?> width="70" height="70">
														</div>
														<!-- Information on the commenter -->
														<div class="desc">
															<!-- Clicking the persons name should take you to their profile page -->
															<h5><a href="userpage2.php?user=<?php echo $name;?>"><?php echo $name;?></a></h5>
															<!-- Date and Time of the comment -->
															<p class="date"><?php echo $time;?> </p>

															<!-- Actual Comment -->
															<p class="comment">
																<?php echo htmlspecialchars($comm);?>
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
									<img class="img-fluid" src="
									<?php
									if (empty($profile['prof_name_hash']) || empty($profile['prof_file_type'])) {
										echo "img/blog/user3.png";
									} else {
										echo "profilepics/".basename($profile['prof_name_hash']). '.' . $profile['prof_file_type'];
									}
									?>
									" alt="">
									<h4 align="center"><strong><font size="5"><?php echo $username?></font></strong></h4>
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
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/jquery.tabs.min.js"></script>
		<script src="js/jquery.nice-select.min.js"></script>
		<script src="js/waypoints.min.js"></script>
		<script src="js/mail-script.js"></script>
		<script src="js/main copy.js"></script>
	</body>

	</html>
