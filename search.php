<?php
session_start();
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php
	include "head.html";
	?>
</head>
<body class='m-t-lg m-b-lg p-t-lg'>

	<?php
	if($user_id)
	include 'navbar.php';
	?>


	<div class='container-fluid m-b-lg'>
		<div class='row m-b-lg'>
			<h2 class='text-center'>PHP Twitter clone</h2>
			<h5 class='text-center'>Twit something!</h5>
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

		if($_GET['name']!=""){
			$what = $_GET['name'];
			echo "<div class='row bg-faded text-center'>
				<div class='col-xs-12'>
					<h3 class='m-b-0 p-b-md p-t-md'>Users with <span class='text-primary'>'$what'</span> in name:</h3>
				</div>
			</div>";
			include "connect.php";
			$users = mysqli_query($con, "SELECT id, username, followers, following, tweets
			FROM users
			WHERE username LIKE '%$what%'
			ORDER BY username ASC
			LIMIT 0, 10
			");

			if(mysqli_num_rows($users)>0){
				while($user = mysqli_fetch_array($users)){
					$id = $user['id'];
					echo"<div class='row m-b'>
						<div class='col-sm-offset-1 col-sm-10 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-4'>
							<div class='card card-inverse bg-warning panel-user'>
								<div class='card-header bg-warning text-center'>
									<div class='row'>
										<div class='col-xs-4 col-sm-2 col-md-2'>
											<a href='.' class='btn btn-warning btn-sm pull-left'>Back</a>
										</div>
										<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
											<h4 class='m-b-0 p-b-0'><a href='./".$user['username']."'>$".$user['username']."</a></h4>
										</div>";
										if($user['id']){
											if($user_id != $user['id']){
												include "connect.php";
												$query2 = mysqli_query($con, "SELECT id, user1_id
												FROM following
												WHERE user1_id='$user_id' AND user2_id='$id'
												");
												if(mysqli_num_rows($query2)>0){
													echo "
													<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
														<a href='unfollow.php?userid=".$user['id']."&username=".$user['username']."' class='btn btn-danger btn-sm pull-right'>Leave alone</a>
													</div>";
												}
												else{
													echo "
													<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
														<a href='follow.php?userid=".$user['id']."&username=".$user['username']."' class='btn btn-danger btn-sm pull-right'>Stalk</a>
													</div>";
												}
												mysqli_close($con);
											}
											

											echo"
										</div>
									</div>
									<div class='card-block p-b-0'>
										<div class='row'>
											<div class='col-xs-12 col-lg-10 '>
												<div class='row'>
													<div class='col-xs-4 col-xs-offset-4 col-sm-3 col-sm-offset-0 col-md-2 col-md-offset-0 col-lg-2 col-lg-offset-1'>
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
																	echo "<small class='text-muted'>".$username." is following you</small>";
																}
																echo"
															</div>
														</div>
													</div>
													<div class='col-xs-12 col-sm-8 col-sm-offset-0 col-md-offset-0  col-lg-8 col-lg-offset-1 text-center m-t-md'>
														<ul class='list-inline'>
															<div class='row'>
																<div class='col-xs-4 col-sm-4 col-lg-4'>
																	<li><h5><a href='#' class='btn btn-warning btn-sm'>Twits:".$user['tweets']."</a></h5></li>
																</div>
																<div class='col-xs-4 col-sm-4 col-lg-4'>
																	<li><h5><a href='stalkers.php?id=".$user['id']."' class='btn btn-warning btn-sm'>Stalkers: ".$user['followers']."</a></h5></li>
																</div>
																<div class='col-xs-4 col-sm-4 col-lg-4'>
																	<li><h5><a href='stalking.php?id=".$user['id']."' class='btn btn-warning btn-sm'>Stalking: ".$user['following']."</a></h5></li>
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
						</div>";
					}
					else{
						echo "
						<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
							<a href='./register.php' class='btn btn-danger btn-sm pull-right'>Sign Up</a>
						</div>";
					}
				}
			}
			else{
				echo"
				<div class='row m-t'>
					<div class='col-xs-12 text-center'>
						<h3>
							<p>No users found. :-(</p>
							<p>Go ahead and look for someone else!</p>
							<p><a href='.' class='btn btn-info btn-sm'>Back</a></p>
						</h3>
					</div>
				</div>
				";
			}
			@mysqli_close($con);
		} else{
			echo"
			<div class='row m-t'>
				<div class='col-xs-12 text-center'>
					<h3>
						<p>Sorry, invalid query. :-(</p>
						<p><a href='.' class='btn btn-info btn-sm'>Back</a></p>
					</h3>
				</div>
			</div>
			";
		}




		?>


	</div>
	<?php include "footer.html" ?>
</body>
</html>
