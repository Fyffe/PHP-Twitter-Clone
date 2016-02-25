<?php
session_start();
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "head.html"; ?>
</head>
<body class='m-t-lg m-b-lg p-t-lg'>

	<?php
	if($user_id)
	include 'navbar.php';
	?>


	<div class='container-fluid m-b-lg'>
		<div class='row m-b-lg'>
			<h2 class='text-center'>Twitler</h2>
			<h5 class='text-center'>Twit something but stay PC!</h5>
		</div>


			<?php
			function getTime($t_time){
				$pt = time() - $t_time;
				if ($pt>=86400)
				$p = date("F j, Y",$t_time);
				elseif ($pt>=3600)
				$p = (floor($pt/3600))."h";
				elseif ($pt>=60)
				$p = (floor($pt/60))."m";
				else
				$p = $pt."s";
				return $p;
			}
			
			if($_GET['hashtag']!=""){
				$hashtag = $_GET['hashtag'];
				echo "<div class='row bg-faded text-center'>
					<div class='col-xs-12'>
						<h3 class='m-b-0 p-b-md p-t-md'>Twits with <span class='text-danger'>#$hashtag</span>:</h3>
					</div>
				</div>";
				include "connect.php";
				$tweets = mysqli_query($con, "SELECT id, username, user_id, tweet, timestamp
				FROM tweets
				WHERE tweet REGEXP '^#$hashtag' OR tweet REGEXP ' #$hashtag'
				ORDER BY timestamp DESC
				LIMIT 0, 10
				");
				if(mysqli_num_rows($tweets)>0){
					while($tweet = mysqli_fetch_array($tweets)){
						echo "<div class='container-fluid'>
							<div class='row tweet-wrapper'>
								<div class='col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2  col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3'>
									<blockquote class='blockquote tweet'>
										<div class='row'>
											<div class='col-xs-12'>
												<div class='row'>
													<div class='col-xs-offset-0 col-sm-offset-0 text-center ua-wrapper col-xs-3 col-sm-2 col-lg-1'>
														<p><a href='./".$tweet['username']."'><small class='username-small'>$".$tweet['username']."</small>
															<img src='/kolu/default.png' class='img-thumbnail img-circle avatar'/></a></p>
														</div>";
														if($tweet['user_id'] == $user_id){
															echo"<div class='pull-right'>
																<form action='tweetdel.php?id=".$tweet['id']."' method='post'>
																	<input type='hidden' name='id' value='".$tweet['id']."' />
																	<button type='submit' class='btn btn-sm text-danger btn-remove'><i class='fa fa-times'></i></button>
																</form>
															</div>";
														}
														echo"
														<div class='col-xs-5 col-xs-offset-1 col-sm-3 col-sm-offset-3 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-3 text-center '>
															<small class='text-muted timestamp'><i class='fa fa-clock-o'></i>&nbsp;".getTime($tweet['timestamp'])."</small>
														</div>
														<div class='col-xs-8 col-md-8 tweet-content'>";
															$new_tweet = preg_replace('/@(\\w+)/','<a href=./$1>$0</a>',$tweet['tweet']);
															$new_tweet = preg_replace('/#(\\w+)/','<a href=./hashtag/$1>$0</a>',$new_tweet);
															echo $new_tweet;
															echo"
														</div>
													</div>
												</div>
											</div>
										</blockquote>
									</div>
								</div>
							</div>";
						}
					}
					else{
						echo "<div class='row m-t'>
							<div class='col-xs-12 text-center'>
								<h3>
									<p>No tweets found. :-(</p>
									<p>Go ahead and look for a different hashtag!</p>
									<p><a href='.' class='btn btn-warning btn-sm'>Back</a></p>
								</h3>
							</div>
						</div>";
					}
					mysqli_close($con);
				}
				else{
					echo "<div class='row m-t'>
						<div class='col-xs-12 text-center'>
							<h3>
								<p>Sorry, invalid hashtag. :-(</p>
								<p><a href='.' class='btn btn-warning btn-sm'>Back</a></p>
							</h3>
						</div>
					</div>";
				}
				?>
		
		</div>
		<?php include "footer.html" ?>
	</body>
	</html>
