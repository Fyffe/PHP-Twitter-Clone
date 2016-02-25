<?php 
	session_start();
	$user_id = $_SESSION['user_id'];

	include "connect.php";
	$query = mysqli_query($con, "SELECT username
	  FROM users
	  WHERE id='$user_id'
	  ");
	$un = mysqli_fetch_assoc($query);
	$username = $un['username'];
	
	function getTime2($t_time){
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
?>
<nav class="navbar navbar-fixed-top navbar-light bg-faded" id="navbar-main">
  <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
    &#9776;
  </button>
  <div class="collapse navbar-toggleable-xs" id="collapsingNavbar2">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-offset-3 col-md-6 col-md-offset-0 col-lg-4 col-lg-offset-0">
          <ul class="nav navbar-nav">
            <li class="nav-item">
              <a href="http://infoprem.pl/kolu" class="nav-link">HOME</a>
            </li>
            <li class="nav-item dropdown dropdown-mentions">
             <a href="#" class="dropdown-toggle nav-link" id="dropdownMentions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              MENTIONS
			  <?php 
					$state = "none";
					include "connect.php";
					$receivy = mysqli_query($con, "SELECT id, tweet_id, user1_id, user2_id, timestamp, received
													FROM mentions
													WHERE user2_id = $user_id AND received = '0'
													ORDER BY timestamp DESC
													LIMIT 0, 5
													") or die("FAILXD");
					if(mysqli_num_rows($receivy) > 0){
						$count = mysqli_num_rows($receivy);
						$state = "";
					}
					else
					{
						$state = "none";
					}
					mysqli_close($con);
			  ?>
              <span class='label label-pill label-warning' style='display: <?php echo $state ?>;'><?php echo $count; ?></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMentions">
			<?php 			
				include "connect.php";
				$mentions = mysqli_query($con, "SELECT id, tweet_id, user1_id, user2_id, timestamp, received
												FROM mentions
												WHERE user2_id = $user_id
												ORDER BY timestamp DESC
												LIMIT 0, 5
												");
				if(mysqli_num_rows($mentions) > 0){
					while($mention = mysqli_fetch_array($mentions)){
						$mentioninguserid = $mention['user1_id'];
						$query101 = mysqli_query($con, "SELECT username
													FROM users
													WHERE id = '$mentioninguserid'
													");
						$row101 = mysqli_fetch_assoc($query101);
						$mentioningusername = $row101['username'];
						echo "<a href='#' class='dropdown-item  text-muted center-block  p-l-md p-r-lg'>";
						echo "<small class='mention-timestamp center-block text-right'><i class='fa fa-clock-o'></i> &nbsp;".getTime2($mention['timestamp'])."</small>";
						echo "<img src='http://infoprem.pl/kolu/default.png' class='img-navbar img-responsive img-thumbnail img-circle'/>
						&nbsp;".$mentioningusername." mentioned you!
						</a>";     
					}
				}
				else
				{
					echo "<p class='dropdown-item text-muted center-block'>No one has mentioned you yet!</p>";
				}
				mysqli_close($con);
				?>
            </div>
          </li>
        </ul>
      </div>
      <div class="col-xs-12 col-sm-offset-0 col-sm-10 col-md-4 col-md-offset-0 col-lg-3 pull-right">
        <form class="form-inline navbar-form" action="search.php" method="get" class="form-search">
          <div class="input-group">
           <input class="form-control" type="text" name="name" placeholder="Search">
           <span class="input-group-btn">
            <button class="btn btn-warning btn-search" type="submit">Search</button>
          </span>
        </div>
      </form>
    </div>
    <div class="col-xs-12 col-sm-2 col-sm-offset-0 col-md-2 col-md-offset-0 col-md-push-1 col-lg-1 col-lg-offset-3 hidden-xs-down">
      <ul class="nav navbar-nav">
        <li class="nav-item">
          <div class="dropdown">
            <a href="#" class="dropdown-toggle" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="http://infoprem.pl/kolu/default.png" class="img-navbar img-responsive img-thumbnail img-circle"/>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu">
              <a href="<?php echo "./".$username; ?>" class="dropdown-item text-info text-center"><b><?php echo $username; ?> &nbsp;<i class="fa fa-user"></i></b></a>
              <a href="./settings.php" class="dropdown-item text-warning text-center"><b>Settings &nbsp;<i class="fa fa-cogs"></i></b></a>
              <a href="./logout.php" class="dropdown-item text-danger text-center"><b>Logout &nbsp;<i class="fa fa-sign-out"></i></b></a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="col-xs-12 hidden-sm-up">
      <ul class="nav navbar-nav">
        <li class="nav-item">
          <a href="#" class="nav-link"><span class="text-info">Profile &nbsp;<i class="fa fa-user"></i></span></a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link"><span class="text-warning">Settings &nbsp;<i class="fa fa-cogs"></i></span></a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link"><span class="text-danger">Logout &nbsp;<i class="fa fa-sign-out"></i></span></a>
        </li>
      </ul>
    </div>
  </div>
</div>
</nav>