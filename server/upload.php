<?php
// server info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coordinates";

if (isset($_GET["data"])) {
  $data = json_decode($_GET["data"]);
  $con = mysqli_connect($servername, $username, $password, $dbname);
  if (!$con) {
    echo '{"type" : "fail"}';
    die("Connection failed: ".myslqi_connect_error());
  }
  mysqli_select_db($con, $dbname);
  // create new id
  mysqli_query($con, "INSERT INTO sets (status) VALUES ('pending')");
  $id = mysqli_insert_id($con); //saves new id
  echo '{"type" : "pending", "id":'.$id.'}';
  // finishes connection with client
  header('Connection: close');
  header('Content-Length: '.ob_get_length());
  ob_end_flush();
  ob_flush();
  flush();

  // insert data to locations
  for($i = 0; $i < count($data); $i++) {
    // find coordinates
    $addr = str_replace(" ", "+",  $data[$i]->address);
    //ensures that even if we go over the query limit it will try again
    do {
      $response = file_get_contents("http://maps.google.com/maps/api/".
      "geocode/json?sensor=false&address=$addr");
      $json = json_decode($response);
    } while ($json->status != "OK");
    $lat = $json->results[0]->geometry->location->lat;
    $lng = $json->results[0]->geometry->location->lng;
    // insert data
    $stmt = mysqli_prepare($con, "INSERT INTO locations (id, name, address"
      .", longitude, lattitude) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt,'issdd',$id,$data[$i]->name,$data[$i]->address,
      $lng, $lat);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  // change state
  mysqli_query($con, "UPDATE sets SET status = 'ready' WHERE id =".$id);
  mysqli_close($con);
} else {
  echo '{"type" : "fail"}';
}
?>
