<?php 

session_start();
// Check if user has already logged in to the application
if (isset($_SESSION['access_token']) || isset($_SESSION['access_token']['oauth_token']) || isset($_SESSION['access_token']['oauth_token_secret']))
{
    header('Location: timeline#home');
}
?>
<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>rtCamp Twitter</title>
		<link rel="stylesheet" href="css/foundation.css" />
		<link rel="stylesheet" href="css/docs.css" />
		<script src="js/modernizr.js"></script>
	</head>
	<body>
		<div class="off-canvas-wrap docs-wrap" >
			<div class="inner-wrap" >
				<section class="main-section">
					<div class="large-12 columns" style="margin-top: 100px;text-align: center">
						<center> <a href="login.php"><img src="images/login-twitter.png" alt="Sign in with Twitter"/></a></center>
					</div>
				</section>
			</div>
		</div>

		<script src="js/jquery.js"></script>
		<script src="js/foundation.min.js"></script>
		<script>
			$(document).foundation();
		</script>
	</body>
</html>
