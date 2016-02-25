<?php 
	include "connect.php";
	$givenname = $_GET['username'];
	$givencode = $_GET['code'];
	$query = mysqli_query($con, "SELECT code, active
								FROM users
								WHERE code = '$givencode' AND username = '$givenname'
								");
	$row = mysqli_fetch_assoc($query);
	$wantedcode = $row['code'];
	if($row['active'] == 0){
		if($givencode == $wantedcode){
			mysqli_query($con, "UPDATE users
								SET active = 1
								WHERE username = '$givenname'
								");
			echo "<h2>Your account has been activated!</h2>";
		}
		else
		{
			echo "<h2>Wrong activation code!</h2>";
		}
	}
	else{
		echo "<h2>This account has been activated before!</h2>";
	}
?>