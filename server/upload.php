<?php
// server info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coordinates";



if (isset($_REQUEST["data"])) {
  $data = json_decode($_REQUEST["data"]);
  $con = mysqli_connect($servername, $username, $password, $dbname);
  if (!$con) {
    die("Connection failed: ".myslqi_connect_error());
  }
  // mysqli_select_db($con, $dbname);
  // create new id
  // mysqli_query($con, "INSERT INTO sets (status) VALUES ('pending')");
  // $id = mysqli_insert_id($con); //saves new id
  $id = 10;
  // echo json_encode('{"type" : "pending", "id":"'.$id.'"}');
  // insert data to locations
  // $stmt = mysqli_stmt_init($con);
  for($i = 0; $i < count($data); $i++) {
    // find coordinates
    $addr = str_replace(" ", "+",  $data[$i]->address); // replace all the white space with "+" sign to match with google search pattern
    $response = file_get_contents("http://maps.google.com/maps/api/geocode/json?sensor=false&address=$addr");
    $json = json_decode($response); //generate array object from the response from the web
    // echo "addr:". $data[$i]->address."\n";
    $lat = $json->results[0]->geometry->location->lat;
    $lng = $json->results[0]->geometry->location->lng;
    // echo $lat.",".$lng."\n\n";
    // insert data
    $stmt = mysqli_prepare($con, "INSERT INTO locations (id, name, address"
      .", longitude, lattitude) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt,'issdd',$id,$data[$i]->name,$data[$i]->address,
      $lng, $lat);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  // find long & lat

  // echo $data[0]->name;
  // change state

} else {
  echo json_encode('{"type" : "fail"}');
}
?>
