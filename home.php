<?php
session_start();
require_once ('lib/twitteroauth/twitteroauth.php');
require_once ('config.php');

// tf access tokens are not available,clear session and redirect to login page.
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	header('Location: clearsession.php');
}
// get user access tokens from the session.
$access_token = $_SESSION['access_token'];

// create a TwitterOauth object with tokens.
$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

// get the current user's info
$user_info = $twitteroauth -> get('account/verify_credentials');

if (isset($user_info -> errors) && $user_info -> errors[0] -> message == 'Rate limit exceeded') {
	//echo "<script>alert('Twetter auth Error: Rate limit exceeded'); </script>Go to Login Page click <a href='clearsession.php'>here</a>";
	//exit ;
}

//get the followers list
$friend_list = $twitteroauth -> get("https://api.twitter.com/1.1/followers/list.json?cursor=-1&screen_name=" . $user_info -> screen_name . "&skip_status=true&include_user_entities=false&count=30");
?>

<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
	<head>
		<title>rtCamp Twitter</title>
		<link rel="stylesheet" href="css/foundation.css" />
		<link rel="stylesheet" href="css/modal.css">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/modernizr.js"></script>
	</head>
	<body>
		<div class="row">
			<div class="large-3 panel columns">
				<img class="proImg" height="500px" width="500px" src="<?php echo str_replace("_normal", "",$user_info->profile_image_url);?>">
				<div style="text-align: center">
					<h6><?php echo $user_info->name?></h6>
					<h6>@<?php echo $user_info->screen_name?></h6>
				</div>
				
				
				<!-- Search Bar -->
				<div class="row">
					<div class="large-12 columns">
						<div class="radius">
							<div class="row">
								<div class="columns">
									<input type="text" placeholder="Search followers" id="filter" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Search Bar -->

				<!-- Thumbnails -->
				<div class="row" style="height: 440px; width:100%; overflow-y:scroll; margin-bottom: 10px;">
					<ul class="tweet-user-list" >
                        <?php 
							if($friend_list->users)
							{
								foreach ($friend_list->users as $friends) { ?>
								<li>
									<a href="javascript:void(0)" id="<?php echo $friends->screen_name?>" class="followers" >
									<div class="large-12" style="position: relative;padding-left: 0.9375rem;padding-right: 0.9375rem;float: left;">
										<img src="<?php echo $friends->profile_image_url?>" alt="profile image" >
										<div>
											<span class="user-title"><?php echo $friends->name?></span>
											<p class="user-desc">@<span class="screen-name"><?php echo $friends->screen_name?></span></p>
										</div>
									</div>
									</a>
								</li>
                        <?php }
									}
						 ?>
                        </ul>
				</div>
				<!-- End Thumbnails -->
				
				
			</div>

			<div class="large-9 columns">
				
					<!--<img class="banner" src="<?php echo $user_info->profile_banner_url?>/web">-->
					<div class="button-bar" style="margin-left: 60%;">
					  <ul class="button-group radius" style="width: 100%;">
					    <li><a href="javascript:void(0)" style="font-size: 13px;" id="home" class="large-12 button tiny">My Feed</a></li>
					    <li><a href="javascript:void(0)" style="font-size: 13px;" id="my-tweets" class="large-12 column button tiny">MyTweet</a></li>
					    <li><a href="clearsession.php" style="font-size: 13px;" id="logout" class="large-12 column button tiny">Logout</a></li>
					  </ul>
					 </div>

					<div class="large-12" style="margin-left: 60%;">
						<a href="#" style="width: 280px;" class="button radius" data-dropdown="drop1">Download</a>
						<ul id="drop1" class="f-dropdown" data-dropdown-content>
						  <li><a id="export-csv">CSV</a></li>
						  <li><a id="export-xls">XLS</a></li>
						</ul>
					</div>
					<div style="margin-top:-15%;">
					<div class="large-4 panel radius" style="text-align: center; margin: 10px;padding: 10px">
						<strong><?php echo $user_info -> friends_count; ?></strong> Following
					</div>
					<div class="large-4 panel radius" style="text-align: center; margin: 10px;padding: 10px">
						<strong><?php echo $user_info -> followers_count; ?></strong> Followers
					</div>
					<div class="large-3 panel radius" style="text-align: center; margin: 10px;padding: 10px">
						<strong><?php echo $user_info -> statuses_count; ?></strong> Tweets
					</div>
					</div>
				
				<div class="tweet-thread" style="height: 475px; width:100%; overflow-y:scroll; margin-bottom: 10px;">
					<div id="loading-overlay">
                         <img width="20%" src="images/loading.gif" alt='loading'>
                    </div><!--//#loading-overlay-->
					<div class="large-12 columns" id="tweets">
						<script id="tweet-template" type="text/x-handlebars-template">
                         {{#if this}}
                            {{#data}}
                            	
                            	<div class="radius panel" style="margin-top: 20px;">
									<div class="tweet bubble-left" id="{{id_str}}">
										<img src="{{user.profile_image_url}}" alt="profile image">
										<label class="tweet-user" id="">{{user.name}}<span><a href="www.twitter.com/{{user.screen_name}}" target="_blank">@{{user.screen_name}}</a></span></label>
										<label class="tweet-timestamp" id="">{{getDateTime created_at}}</label>
										<p class="tweet-text">{{twityfy text}}</p>
										<p class="links">
											{{#if_eq user.screen_name compare="<?php echo $user_info->screen_name?>"}}
		                                        <a href="javascript:void(0)" class="delete-tweet" title="Delete this tweet">Delete</a>
		                                    {{else}}
		                                        {{#if retweeted}}
		                                            <a href="javascript:void(0)" class="retweeted" name="{{id_str}}" title="Undo retweet">Retweeted</a>
		                                        {{else}}
		                                            <a href="javascript:void(0)" class="retweet" title="Retweet">Retweet</a>
		                                        {{/if}}
		                                    {{/if_eq}}
		                                    
		                                    {{#if favorited}}
		                                        <a href="javascript:void(0)" class="favorited" title="Undo favorite">Favorited</a>
		                                    {{else}}
		                                        <a href="javascript:void(0)" class="favorite" title="Favorite">Favorite</a>
		                                    {{/if}}
										</p>
									</div>
								</div>
                            {{/data}}
                        {{else}}
                            <div> No Tweets yet</div>
                        {{/if}}
                         </script>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Footer -->
		<footer class="row">
			<div class="large-12 columns">
				<hr />
			</div>
		</footer>
		
		<!--modal for retweeting-->
		<div id="retweet-modal" class="modal hidden fade" data-backdrop="static">
			<div class="modal-header">
		    	<h3 class="modal-title">Retweet this to your follolwers?</h3>
		    </div><!--//.modal-header-->
		    <div class="modal-body">
		    	
		    </div><!--//.modal-body-->
		    <div class="modal-footer">
		        <button type="button" id="btn-retweet">Retweet</button>
		        <button type="button" data-dismiss="modal" class="cancel">Cancel</button>
		    </div><!--//.modal-footer-->
		</div><!--//#retweet-modal-->
		
		<!--modal for retweeting-->
		<div id="delete-modal" class="modal hidden fade" data-backdrop="static">
			<div class="modal-header">
		    	<h3 class="modal-title">Are you sure you want to delete this tweet?</h3>
		    </div><!--//.modal-header-->
		    <div class="modal-body">
		    	
		    </div><!--//.modal-body-->
		    <div class="modal-footer">
		        <button type="button" id="btn-delete">Delete</button>
		        <button type="button" data-dismiss="modal" class="cancel">Cancel</button>
		    </div><!--//.modal-footer-->
		</div><!--//#delete-modal-->
		
		<!--script src="js/jquery.js"></script-->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/jquery-1.8.1.min.js"><\/script>')</script>
		<script src="js/foundation.js"></script>
		<script type="text/javascript" src="lib/handlebars.js"></script>
		<script type="text/javascript" src="lib/moment.js"></script>
		<script type="text/javascript" src="lib/bootstrap-modal.js"></script>
		<script type="text/javascript" src="js/handlebar-helper.js"></script>
		<script type="text/javascript" src="js/Tweet.js"></script>
		<script type="text/javascript" src="js/TweetUI.js"></script>     
		<script type="text/javascript" src="js/script.js"></script>
		<script type="text/javascript" src="js/strings.js"></script>
		<script type="text/javascript" src="js/foundation.dropdown.js"></script>
		<script>
			$(document).foundation();
			var doc = document.documentElement;
			doc.setAttribute('data-useragent', navigator.userAgent);
		</script>
	</body>
</html>
