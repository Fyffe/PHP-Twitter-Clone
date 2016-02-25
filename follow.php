<?php 
	session_start();
	$user_id = $_SESSION['user_id'];
?>
<?php
	ob_start();
	if($_GET['userid']  && $_GET['username']){
		if($_GET['userid']!=$user_id){
			$follow_userid = $_GET['userid'];
			$follow_username = $_GET['username'];
			include 'connect.php';
			$query = mysqli_query($con, "SELECT id
								   FROM following 
								   WHERE user1_id='$user_id' AND user2_id='$follow_userid'
								  ");
			mysqli_close($con);
			if(!(mysqli_num_rows($query)>=1)){
				include 'connect.php';
				mysqli_query($con, "INSERT INTO following(user1_id, user2_id) 
							 VALUES ('$user_id', '$follow_userid')
							");
				mysqli_query($con, "UPDATE users
							 SET following = following + 1
							 WHERE id='$user_id'
							");
				mysqli_query($con, "UPDATE users
							 SET followers = followers + 1
							 WHERE id='$follow_userid'
							");
				mysqli_close($con);
			}
			header("Location: ./".$follow_username);
		}
	}
	ob_end_flush();
?>