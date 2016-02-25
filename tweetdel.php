<?php 
	session_start();
	$user_id = $_SESSION['user_id'];
?>
<?php
	ob_start();
	$tweetid = $_POST['id'];
	if($user_id){
		if($_POST['id']!=""){
			$timestamp = time();
			include 'connect.php';
			$query = mysqli_query($con, "DELETE FROM tweets
								  WHERE id = $tweetid
								  LIMIT 1
								");
			if(mysqli_affected_rows($con) == 1){
				mysqli_query($con, "UPDATE users
						 SET tweets = tweets - 1
						 WHERE id='$user_id'
						");
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
			else{
				echo "<strong>Failed to delete!</strong>";
			}
			mysqli_close($con);
		}
	}
	ob_end_flush();
?>	