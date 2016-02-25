<?php
ob_start();
session_start();
$user_id = $_SESSION['user_id'];

if(@$_POST['pass-change-btn']=="pass-change"){
	if($_POST['password']!="" && $_POST['password-conf']!=""){
		if($_POST['password'] == $_POST['password-conf']){
			$newpassword = md5($_POST['password']);
			include "connect.php";
			mysqli_query($con, "UPDATE users
				SET password = '$newpassword'
				WHERE id='$user_id'
				");
				mysqli_close($con);
				echo "<div class='container'><div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Your password has been changed!</div></div>";
			}
			else{
				$error_msg = "Passwords did not match!";
			}
		}
		else{
			$error_msg = "All fields must be filled!";
		}
	}

	if(@$_POST['email-change-btn'] == "email-change"){
		if($_POST['email'] != ""){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				$newemail = $_POST['email'];
				include "connect.php";
				mysqli_query($con, "UPDATE users
					SET email='$newemail'
					WHERE id='$user_id'
					");
					mysqli_close($con);
					echo "<div class='container'><div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Your email has been changed!</div></div>";
				}
				else{
					$error_msg = "This is not an correct email!";
				}
			}
			else{
				$error_msg = "All fields must be filled!";
			}
		}

		if(@$_POST['acc-del-btn'] == "acc-del"){
			include "connect.php";

			$followed = mysqli_query($con, "SELECT *
				FROM following
				WHERE user1_id = '$user_id'
				") or die("Cannot select followed");

				while($followeduser = mysqli_fetch_array($followed)){
					$hisid = $followeduser['user2_id'];
					$message = $hisid;
					echo "<script type='text/javascript'>alert('$message');</script>";
					mysqli_query($con, "UPDATE users
						SET followers = followers - 1
						WHERE id='$hisid'
						") or die("cannot update followed".mysql_error);
					}

					$following = mysqli_query($con, "SELECT *
						FROM following
						WHERE user2_id = '$user_id'
						") or die("cannot select following");

						while($followinguser = mysqli_fetch_array($following)){
							$hisid2 = $followinguser['user1_id'];
							mysqli_query($con, "UPDATE users
								SET following = following - 1
								WHERE id = '$hisid2'
								") or die("cannot udpdate following".mysql_error);
							}

							mysqli_query($con, "DELETE FROM users
								WHERE id = '$user_id'
								");

								mysqli_close($con);
								include "logout.php";
								header('Location: index.php');
							}

							ob_end_flush();
							?>

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
										<div class='col-xs-12'>
											<h2 class='text-center'>Twitler</h2>
											<h5 class='text-center'>Twit something but stay PC!!</h5>
										</div>
										<?php
										if(@$error_msg){
											echo "<div class='container'><div class='alert alert-danger alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>".$error_msg."</div></div>";
										}
										?>
									</div>

									<div class='row'>
										<div class='jumbotron m-b-0'>
											<div class='container-fluid'>
												<div class='row text-center'>
													<div class='container'>
														<div class='col-xs-4 col-sm-2 col-md-2'>
															<a href='.' class='btn btn-warning btn-sm pull-left'>Back</a>
														</div>
														<div class='col-xs-4 col-sm-2 col-sm-offset-3'>
															<h3 class='m-b-0 p-b-0'>Settings</h3>

														</div>
														<div class='col-xs-12'>
															<hr>
														</div>
													</div>
												</div>
												<div class='row'>
													<?php
													if($user_id)
													{
														echo "<div class='col-sm-6 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-2'>
															<div class='card card-inverse bg-warning panel-user'>
																<div class='card-header bg-warning text-center'>
																	<h4 class='m-b-0 p-b-0'>Change your password</h4>
																</div>
																<div class='card-block'>
																	<div class='row'>
																		<div class='col-xs-12'>
																			<form role='password-change' action='settings.php' method='POST' class='login-form'>
																				<div class='row'>
																					<div class='col-xs-12'>
																						<div class='form-group'>
																							<input type='password' name='password' placeholder='Password' class='form-control'/>
																						</div>
																						<div class='form-group'>
																							<input type='password' name='password-conf' placeholder='Confirm password' class='form-control'/>
																						</div>
																						<div class='form-group'>
																							<button type='submit' class='btn btn-warning btn-sm btn-block' name='pass-change-btn' value='pass-change'>Change password</button>
																						</div>
																					</div>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														</div>";
														include "connect.php";
														$query2 = mysqli_query($con, "SELECT email
																					FROM users
																					WHERE id='$user_id'
																					");
														$oldemail = mysqli_fetch_assoc($query2);
														echo"<div class='col-sm-6 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-0'>
															<div class='card card-inverse bg-warning panel-user'>
																<div class='card-header bg-warning text-center'>
																	<h4 class='m-b-0 p-b-0'>Change your email or delete your account</h4>
																</div>
																<div class='card-block'>
																	<div class='row'>
																		<div class='col-xs-12'>
																			<form role='email-change' action='settings.php' method='POST' class='login-form'>
																				<div class='row'>
																					<div class='col-xs-12'>
																						<div class='form-group'>
																							<input type='email' class='form-control' name='email' value='".$oldemail['email']."'/>
																						</div>
																						<div class='form-group'>
																							<button type='submit' class='btn btn-warning btn-sm btn-block' name='email-change-btn' value='email-change'>Change email</button>
																						</div>
																					</div>
																				</div>
																			</form>
																		</div>
																	</div>
																	<div class='row'>
																		<div class='col-xs-12'>
																			<form role='account-delete' action='settings.php' method='POST' class='login-form'>
																				<div class='row'>
																					<div class='col-xs-12'>
																						<div class='form-group'>"; ?>
																							<button type='submit' class='btn btn-danger btn-block' name='acc-del-btn' value='acc-del' onclick='return confirm("Are you sure?")'>Delete account</button>
																							<?php echo"
																						</div>
																					</div>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														</div>";
													} else{
														echo"
															<h4 class='text-center text-danger'>You need an account to see this page!</h4>
														";
													}
													?>



												</div>
											</div>
										</div>
									</div>
								</div>


							<?php include "footer.html" ?>
						</body>
						</html>
