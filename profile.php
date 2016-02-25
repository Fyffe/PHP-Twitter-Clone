<?php
session_start();
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <?php include './head.html' ?>
</head>
<body class='m-t-lg m-b-lg p-t-lg'>

    <?php
	if($user_id)
	include 'navbar.php';
	?>

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
	if($_GET['username']){
		include 'connect.php';
		$username = strtolower($_GET['username']);
		$query = mysqli_query($con, "SELECT id, username, followers, following, tweets
		FROM users
		WHERE username='$username'
		");
		mysqli_close($con);
		if(mysqli_num_rows($query)>=1){
			$row = mysqli_fetch_assoc($query);
			$id = $row['id'];
			$username = $row['username'];
			$tweets = $row['tweets'];
			$followers = $row['followers'];
			$following = $row['following'];
			echo "<div class='container-fluid m-b-lg'>
				<div class='row'>
					<h2 class='text-center'>Twitler</h2>
					<h5 class='text-center'>Twit something but stay PC!</h5>
				</div>
				<div class='row'>

					<div class='jumbotron m-b-0 m-t-lg dashboard-wrapper'>
						<div class='container-fluid'>
							<div class='row'>
								<div class='col-sm-offset-1 col-sm-10 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3'>
									<div class='card card-inverse bg-warning panel-user'>
										<div class='card-header bg-warning text-center'>
											<div class='row'>
												<div class='col-xs-4 col-sm-2 col-md-2'>
													<a href='.' class='btn btn-warning btn-sm pull-left'>Back</a>
												</div>
												<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
													<h3 class='m-b-0 p-b-0'>$$username</h3>
												</div>";
												if($user_id){
													if($user_id!=$id){
														include 'connect.php';
														$query2 = mysqli_query($con, "SELECT id
														FROM following
														WHERE user1_id='$user_id' AND user2_id='$id'
														");
														mysqli_close($con);
														if(mysqli_num_rows($query2)>=1){
															echo "
															<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
															<a href='unfollow.php?userid=$id&username=$username' class='btn btn-danger btn-sm pull-right'>Leave alone</a>
															</div>";
														}
														else{
															echo "
															<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
															<a href='follow.php?userid=$id&username=$username' class='btn btn-danger btn-sm pull-right'>Stalk</a>
															</div>";
														}
													}
												}
												else{
													echo "
													<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
													<a href='./register.php' class='btn btn-danger btn-sm pull-right'>Sign Up</a>
													</div>";
												}
												echo"
											</div>

										</div>
										<div class='card-block'>
											<div class='row'>
												<div class='col-xs-12 col-lg-12 '>
													<div class='row'>
														<div class='col-xs-4 col-xs-offset-4 col-sm-4 col-sm-offset-0 col-md-2 col-md-offset-0 col-lg-2 col-lg-offset-1'>
															<img src='./default.png' class='img-responsive img-thumbnail avatar img-circle'/>
														</div>
														<div class='col-xs-12 col-sm-6 col-md-6 col-md-offset-0 col-lg-6 m-t text-center'>
															<div class='row'>
																<div class='col-xs-12'>";
																	include 'connect.php';
																	$query3 = mysqli_query($con, "SELECT id
																	FROM following
																	WHERE user1_id='$id' AND user2_id='$user_id'
																	");
																	mysqli_close($con);
																	if(mysqli_num_rows($query3)>=1){
																		echo "<small class='text-muted'>$username is following you</small>";
																	}
																	echo"
																</div>
															</div>
														</div>
														<div class='col-xs-12 col-sm-8 col-sm-offset-0 col-md-offset-0  col-lg-8 col-lg-offset-0 text-center m-t-lg'>
															<ul class='list-inline'>
																<div class='row'>
																	<div class='col-xs-4 col-lg-3'>
																		<li><h5><a href='#' class='btn btn-warning btn-block'>Twits: $tweets</a></h5></li>
																	</div>
																	<div class='col-xs-4 col-lg-3'>
																		<li><h5><a href='stalkers.php?id=".$id."' class='btn btn-warning btn-block'>Stalkers: $followers</a></h5></li>
																	</div>
																	<div class='col-xs-4 col-lg-3'>
																		<li><h5><a href='stalking.php?id=".$id."' class='btn btn-warning btn-block'>Stalking: $following</a></h5></li>
																	</div>
																</div>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>";


				include "connect.php";
				$tweets = mysqli_query($con, "SELECT id, username, user_id, tweet, timestamp
				FROM tweets
				WHERE user_id = $id
				ORDER BY timestamp DESC
				");
				while($tweet = mysqli_fetch_array($tweets)){
					$twid = $tweet['id'];
					$menty = mysqli_query($con, "SELECT id, tweet_id, user2_id, received
											FROM mentions
											WHERE user2_id = '$user_id' AND tweet_id = '$twid' AND received = '0'
											") or die ("syf");
					$mentyrow = mysqli_fetch_row($menty);
					
					if(mysqli_num_rows($menty) > 0)
					{
						mysqli_query($con, "UPDATE mentions
											SET received = '1'
											WHERE tweet_id = '$twid'
											");
					}
					echo"
					<div class='row tweet-wrapper'>
						<div class='col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2  col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3'>
							<blockquote class='blockquote tweet'>
								<div class='row'>
									<div class='col-xs-12'>
										<div class='row'>
											<div class='col-xs-offset-0 col-sm-offset-0 text-center ua-wrapper col-xs-3 col-sm-2 col-lg-1'>
												<p><a href='./".$tweet['username']."'><small class='username-small'>$".$tweet['username']."</small>
													<img src='./default.png' class='img-thumbnail img-circle avatar'/></a></p>
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
						</div>";
					}
					mysqli_close($con);
				}
				else{
					echo "<div class='alert alert-danger'>This profile doesn't exist!</div>";
					echo "<a href='.' class='btn btn-warning'>Back</a>";
				}
			}
			?>

		</div>
		<?php include './footer.html' ?>
	</body>
	</html>
