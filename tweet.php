<?php 
	session_start();
	$user_id = $_SESSION['user_id'];
?>
<?php
	ob_start();
	if($user_id){
		if($_POST['tweet']!=""){
			$tweet = htmlentities($_POST['tweet']);
			$timestamp = time();
			include 'connect.php';
			$query = mysqli_query($con, "SELECT username
								  FROM users 
								  WHERE id ='$user_id'
								");
			$row = mysqli_fetch_assoc($query);
			$username = $row['username'];
			mysqli_query($con, "INSERT INTO tweets(username, user_id, tweet, timestamp) 
						 VALUES ('$username', '$user_id', '$tweet', $timestamp)
						");
			$tweetdata = mysqli_query($con, "SELECT *
											FROM tweets
											WHERE user_id='$user_id' AND tweet='$tweet'
											");
			$row2 = mysqli_fetch_assoc($tweetdata);
			$tweetid = $row2['id'];
			$tweettime = $row2['timestamp'];
			if (preg_match_all('/(?<!\w)@(\w+)/', $tweet, $matches)){
				$allmentioned = $matches[1];
				foreach ($allmentioned as $mentioned){
					$userdata = mysqli_query($con, "SELECT *
									FROM users
									WHERE username = '$mentioned'
									");
					$mentioneduser = mysqli_fetch_assoc($userdata);
					$mentionedid = $mentioneduser['id'];
					if($mentioneduser['id'] != $user_id){
						mysqli_query($con, "INSERT INTO mentions(tweet_id, user1_id, user2_id, timestamp) 
											VALUES ('$tweetid', '$user_id', '$mentionedid', '$tweettime')
											") or die($tweetid);				
					}
				}
			}		
			mysqli_query($con, "UPDATE users
						 SET tweets = tweets + 1
						 WHERE id='$user_id'
						");
			mysqli_close($con);
			header("Location: index.php");
		}
	}
	ob_end_flush();
?>