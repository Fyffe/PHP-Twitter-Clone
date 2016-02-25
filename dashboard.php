<?php
function getTime($t_time)
{
  $pt = time() - $t_time;
  if ($pt >= 86400)
  $p = date("F j, Y", $t_time);
  elseif ($pt >= 3600)
  $p = (floor($pt / 3600)) . "h";
  elseif ($pt >= 60)
  $p = (floor($pt / 60)) . "m";
  else
  $p = $pt . "s";
  return $p;
}
if ($user_id) {
  include "connect.php";
  $query = mysqli_query($con, "SELECT username, followers, following, tweets
  FROM users 
  WHERE id='$user_id'
  ");
  mysqli_close($con);
  $row       = mysqli_fetch_assoc($query);
  $username  = $row['username'];
  $tweets    = $row['tweets'];
  $followers = $row['followers'];
  $following = $row['following'];
  echo "<div class='main'>
    <div class='jumbotron m-b-0 dashboard-wrapper'>
      <div class='container-fluid'>
        <div class='row'>
          <div class='col-sm-offset-1 col-sm-10 col-md-8 col-md-offset-2 col-lg-4 col-lg-offset-2'>
            <div class='card card-inverse bg-warning panel-user' id='dashboard-bg'>
              <div class='card-header bg-warning text-center'>
                <h3 class='m-b-0 p-b-0'>Dashboard</h3>
              </div>
              <div class='card-block'>
                <div class='row'>
                  <div class='col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-0 text-center'>
                    <img src='./default.png' class='img-responsive img-thumbnail img-circle avatar m-b'/>
                    <p><a href='./$username' class='btn btn-warning btn-block btn-sm'>$$username</a></p>
                  </div>
                  <div class='col-sm-offset-0 col-md-offset-0 col-lg-offset-1 col-xs-12 col-sm-6 '>
                    <div class='row'>
                      <div class=' col-xs-offset-2 m-b col-xs-8 col-sm-10'>
                        <p><a href='#' class='btn btn-warning btn-block'>Twits: $tweets</a></p>
                      </div>
                    </div>
                    <div class='row'>
                      <div class=' col-xs-offset-2 m-b col-xs-8 col-sm-10'>
                        <p><a href='stalkers.php?id=" . $user_id . "' class='btn btn-warning btn-block'>Stalkers: $followers</a></p>
                      </div>
                    </div>
                    <div class='row'>
                      <div class=' col-xs-offset-2 m-b col-xs-8 col-sm-10'>
                        <p><a href='stalking.php?id=" . $user_id . "' class='btn btn-warning btn-block'>Stalking: $following</a></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>    
            </div>
          </div>
          <div class='col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-offset-0 col-lg-4'>
            <div class='card card-inverse bg-danger panel-tweet' id='twitpost-bg'>
              <div class='card-header text-center bg-warning'>
                <h3 class='m-b-0 p-b-0'>Post a twit</h3>
              </div>
              <div class='card-block'>
                <div class='row'>
                  <div class='col-xs-12'>
                    <form action='tweet.php' method='POST'>
                      <textarea class='form-control m-b' maxlength='150' value='0' rows='4' placeholder='Twit here you white devil' id='twatcont' name='tweet'></textarea>
                      <button type='submit' disabled id='twat-btn' class='btn btn-warning pull-right'>Twit!</button>
                      <p><span id='counter'>150</span> characters left!</p>
                    </form>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  ";
  
  include "connect.php";
  $tweets = mysqli_query($con, "SELECT id, username, user_id, tweet, timestamp
  FROM tweets
  WHERE user_id = $user_id OR (user_id IN (SELECT user2_id FROM following WHERE user1_id='$user_id'))
  ORDER BY timestamp DESC
  LIMIT 0, 10
  ");
  if (mysqli_num_rows($tweets) > 0) {
    while ($tweet = mysqli_fetch_array($tweets)) {
      $twid = $tweet['id'];
      $menty = mysqli_query($con, "SELECT id, tweet_id, user2_id, received
      FROM mentions
      WHERE user2_id = '$user_id' AND tweet_id = '$twid' AND received = '0'
      ") or die("syf");
      $mentyrow = mysqli_fetch_row($menty);
      
      if (mysqli_num_rows($menty) > 0) {
        mysqli_query($con, "UPDATE mentions
        SET received = '1'
        WHERE tweet_id = '$twid'
        ");
      }
      echo "<div class='container-fluid'>
        <div class='row tweet-wrapper'>
          <div class='col-xs-offset-1 col-sm-offset-2 col-md-offset-2 col-lg-offset-3 col-xs-10 col-sm-8 col-lg-6'>
            <blockquote class='blockquote tweet'>
              <div class='row'>
                <div class='col-xs-12'>
                  <div class='row'>
                    <div class='col-xs-offset-0 col-sm-offset-0 text-center ua-wrapper col-xs-3 col-sm-2 col-lg-1'>
                      <p><a href='./" . $tweet['username'] . "'><small class='username-small'>$" . $tweet['username'] . "</small>
                        <img src='./default.png' class='img-thumbnail img-circle avatar'/></a></p>
                      </div>";
                      
                      echo "	
                      <div class='col-xs-offset-1 col-sm-offset-3 col-md-offset-2 col-lg-offset-3 text-center col-xs-5 col-sm-3 col-md-4 '>
                        <small class='text-muted timestamp'><i class='fa fa-clock-o'></i>&nbsp;" . getTime($tweet['timestamp']) . "</small>
                      </div>";
                      if ($tweet['user_id'] == $user_id) {
                        echo "<div class='col-xs-1 col-xs-offset-0 col-sm-offset-2 col-md-offset-2 col-lg-offset-3'>
                          <form action='tweetdel.php?id=" . $tweet['id'] . "' method='post'>
                            <input type='hidden' name='id' value='" . $tweet['id'] . "' />
                            <button type='submit' class='btn btn-sm text-danger btn-remove'><i class='fa fa-times'></i></button>
                          </form>
                        </div>";
                      }
                      echo"
                      <div class='col-xs-8 col-md-8 tweet-content text-justifed'>";
                        $new_tweet = preg_replace('/@(\\w+)/', '<a href=./$1>$0</a>', $tweet['tweet']);
                        $new_tweet = preg_replace('/#(\\w+)/', '<a href=./hashtag/$1>$0</a>', $new_tweet);
                        echo $new_tweet;
                        echo "
                      </div>
                    </div>
                  </div>
                </div>
              </blockquote>
            </div>
          </div>
        </div>
        ";
      }
    } else if ($following <= 0) {
      echo "<br/><br/><h4 style='text-align: center;'>You're not stalking anyone yet so we can't show you any twits. Here - stalk some people...<br/><br/></h4>";
      $users = mysqli_query($con, "SELECT id, username, followers, following
      FROM users
      WHERE id != '$user_id'
      ORDER BY username ASC
      LIMIT 0, 10
      ");
      if (mysqli_num_rows($users) > 0) {
        while ($user = mysqli_fetch_array($users)) {
          echo "<div class='container-fluid' style='overflow:hidden;'>
            <div class='row tweet-wrapper'>
              <div class='col-xs-offset-1 col-sm-offset-2 col-md-offset-2 col-lg-offset-3 col-xs-10 col-sm-8 col-lg-6'>
                <blockquote class='blockquote tweet'>
                  ";
                  echo "<div class='btn-block'><img src='./default.png' class=''img-thumbnail img-circle avatar' alt='avatar picture'/>
                    <a href='./" . $user['username'] . "'><small class='username-small'>$" . $user['username'] . "</small>
                      <div class='pull-right'><a href='#' class='btn btn-warning'>Stalkers: " . $user['followers'] . "</a>
                        <a href='#' class='btn btn-warning' style=''>Stalking: " . $user['following'] . "</a></div></div>
                        ";
                        echo "
                      </div>
                      </div>
                    </blockquote>
                  </div>";
                  echo "</div></div>";
                }
                echo "<br/><br/><br/>";
              }
            } else {
              echo "Nothing is working";
            }
            
            mysqli_close($con);
            
          }
          ?>
          
          <?php
          include "footer.html";
          ?>