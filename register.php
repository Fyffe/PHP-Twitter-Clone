<?php

session_start();
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
  <?php include "head.html" ?>
 <script src="/kolu/ajax/form-validation.js"></script>
  
</head>
<body class="m-b-lg p-t-lg m-t-lg">
  <div class="container">
    <h2 class="text-center">PHP Twitter Clone</h2>
    <h5 class="text-center">Register Your Account</h5>

	<script type="text/javascript">
		function validate(form) {
		  var re = /^[a-z,A-Z]+$/i;

		  if (!re.test(form.foo.value)) {
			alert('Please enter only letters from a to z');
			return false;
		  }
		}
	</script>
	
    <div class="jumbotron form-wrapper m-t-lg">
      <div class="row">
        <div class="col-xs-12">
        <form action="register.php" method="POST" role="form" class="register-form">
          <?php
		  
			function generateRandomString() {
				$length = 11;
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$activationcode = '';
				for ($i = 0; $i < $length; $i++) {
					$activationcode .= $characters[rand(0, $charactersLength - 1)];
				}
				return $activationcode;
			}
		  
		  
		  
          if($_POST['btn']=="submit-register-form"){
            if($_POST['username'] != "" && $_POST['password'] != "" && $_POST['confirm-password'] != "" && $_POST['email'] != ""){
              if($_POST['password'] == $_POST['confirm-password']){
    			  if(!preg_match('/\s/', $_POST['username'])){
					  if(!preg_match('/[^a-z0-9]/i', $_POST['username'])){
						  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
							include 'connect.php';
							$username = strtolower($_POST['username']);
							$email = $_POST['email'];
							$query2 = mysqli_query($con, "SELECT email
										FROM users
										WHERE email='$email'
										");
							if(!(mysqli_num_rows($query2) >= 1)){
								$query = mysqli_query($con, "SELECT username
															  FROM users 
															  WHERE username='$username'
															  ");
								mysqli_close($con);
								if(!(mysqli_num_rows($query) >= 1)){
								  $password = md5($_POST['password']);
								  include 'connect.php';
								  $activcode = generateRandomString();
								  mysqli_query($con, "INSERT INTO users(username, password, email, code) 
								   VALUES ('$username', '$password', '$email', '$activcode')
								   ") or die('nope, didnt work');
								  $mailcont = "Click this link to activate your Twitler account: http://infoprem.pl/kolu/mailactivation.php?code=".$activcode."&username=".$username;
								  mail($email, "Account activation code for Twitler!", $mailcont, "From: Twitler");
								  mysqli_close($con);
								  echo "<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Your account has been created! :D</div>";
								  echo "<a href='.'class='btn btn-warning'>Back</a>";
								  echo "</form>";
								  echo "<br>";
								  include "footer.html";
								  echo "</body>";
								  echo "</html>";
								  exit;
								}
								else{
								  $error_msg="Username already exists!";
								}
							}
							else{
								$error_msg="This email has been used already!";
							}							
						  }
						  else{
							  $error_msg="The email address you've provided is incorrect!";
						  }
					  }
					  else{
						  $error_msg="Username can not contain special characters (except for '_' and '-')!";
					  }
				  }
				  else
				  {
					  $error_msg="Username can not contain white spaces!";
				  }
              }
              else{
                $error_msg="Passwords did not match!";
              }
            }
            else{
              $error_msg="All fields must be filled!";
            }
          }
          ?>
          <div class="row">
            <div class="col-sm-offset-1 col-md-4 col-md-offset-4">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="text" class="form-control input-username" placeholder="Username" name="username" value="<?php echo $_POST['username']; ?>">
              </div>
            </div>
          </div>
          <div class="col-sm-offset-1 col-md-4 col-md-offset-4">
            <div class="form-group">
              <input type="email" class="form-control input-email" placeholder="E-mail Address" name="email" value="<?php echo $_POST['email']; ?>">
            </div>
            </div>
            <div class="col-sm-offset-1 col-md-4 col-md-offset-4">
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password">
              </div>
            </div>
            <div class="col-sm-offset-1 col-md-4 col-md-offset-4">
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm-password">
              </div>
            </div>
            <div class="col-sm-offset-1 col-md-4 col-md-offset-4">
              <button type="submit" class="btn btn-warning btn-block" name="btn" value="submit-register-form" onclick="validate()">Register</button>
              <a href="." class="btn btn-danger btn-block">Go Home</a>
            </div>
          </div>
          </div>
        </form>
        </div>
		<?php
			if(@$error_msg){
			  echo "<br/><br/><div class='alert alert-danger alert-dismissible'>
			  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>".$error_msg."</div>";
			}
		?>
      </div>
      <?php include "footer.html" ?>
    </div>
  </body>
  </html>