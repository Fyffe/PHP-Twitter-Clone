<?php
$username = $_POST['username'];

include '/kolu/connect.php';

if ($username != "") {
  if(strlen($username) >= 4){
    if (!preg_match('/\s/', $_POST['username'])) {
      if (!preg_match('/[^a-z0-9]/i', $_POST['username'])) {
        $query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        if (mysqli_num_rows($query) > 0) {
          $data = ['msg' => 'Username already in use!', 'type' => 'danger'];
          echo json_encode($data);
        }
        else {
          $data = ['msg' => 'Username is free!', 'type' => 'success'];
         echo json_encode($data);
        }
      }
      else {
        $data = ['msg' => 'Username can only contain "-" and "_" as special characters!', 'type' => 'danger'];
        echo json_encode($data);
      }
    }
    else {
      $data = ['msg' => 'Username can only contain numbers and letters!', 'type' => 'danger'];
     echo json_encode($data);
    }
  } else{
    $data = ['msg' => 'Username is too short!', 'type' => 'danger'];
    echo json_encode($data);
  }
}
else {
  $data = ['msg' => 'Username can not be empty!', 'type' => 'danger'];
  echo json_encode($data);
}
?>
