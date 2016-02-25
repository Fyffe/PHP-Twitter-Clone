<?php
ob_start();
session_start();
$user_id = $_SESSION['user_id'];

if($_POST['login-btn']=="login-submit"){
	if($_POST['username'] != "" && $_POST['password'] != ""){
		$username = strtolower($_POST['username']);
		include "connect.php";
		$query = mysqli_query($con, "SELECT id, password
			FROM users
			WHERE username='$username'");
		mysqli_close($con);
		if(mysqli_num_rows($query) >= 1){
			$password = md5($_POST['password']);
			$row = mysqli_fetch_assoc($query);
			if($password == $row['password']){
				$_SESSION['user_id']=$row['id'];
				header('Location: index.php');
				exit;
			}
			else{
				$error_msg = "Incorrect username or password!";
			}
		}
		else{
			$error_msg = "Incorrect username or password!";
		}
	}
	else{
		$error_msg = "All fields must be filled out!";
	}
}
ob_end_flush();
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "head.html"; ?>
</head>
<body class="m-t-lg m-b-lg p-t-lg">



	<div class="container m-b-lg">
		<h2 class="text-center">PHP Twitter Clone</h2>
		<h5 class="text-center">Twit something!</h5>
	</div>
	<?php
	include "connect.php";
	if($user_id){
		$active = mysqli_query($con, "SELECT id, active
									FROM users
									WHERE id = '$user_id'
									");
		$isactive = mysqli_fetch_assoc($active);
		$activebool = $isactive['active'];
		if($activebool == 1)
		{
			include "navbar.php";
			include "dashboard.php";
			exit;
		}
		else{
			$error_msg = "You must activate your account through your email in order to login!";
		}
	}
	mysqli_close($con);
	?>

	<?php
	if(@$error_msg){
		echo "<div class='container'><div class='alert alert-danger alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>".$error_msg."</div></div>";
	}
	?>
	<div class="container">
		<div class="jumbotron form-wrapper">
			<div class="row">
				<div class="col-xs-12">
					<form role="form" action="index.php" method="POST" class="login-form">
						<div class="row">
							<div class="col-sm-offset-1 col-md-4 col-md-offset-4">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-addon">$</div>
										<input type="text" name="username" placeholder="Username" class="form-control"/>
									</div>
								</div>
							</div>
							<div class="col-sm-offset-1 col-md-4 col-md-offset-4">
								<div class="form-group">
									<input type="password" name="password" placeholder="Password" class="form-control"/>
								</div>
							</div>
							<div class="col-sm-offset-1 col-md-4 col-md-offset-4">
								<button type="submit" class="btn btn-warning btn-block" name="login-btn" value="login-submit">Log In</button>
								<a href="register.php" class="btn btn-danger btn-block">Register!</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include "footer.html"; ?>
</body>
</html>