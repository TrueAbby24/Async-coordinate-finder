<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coordinates";

$con = mysqli_connect($servername, $username, $password, $dbname);
if (!$con) {
  echo "";
  die("Connection failed: ".myslqi_connect_error());
}
mysqli_select_db($con, $dbname);

$data = array();
$result = mysqli_query($con, "SELECT id FROM sets");
while ($row = mysqli_fetch_array($result)) {
  array_push($data, $row["id"]);
}
mysqli_close($con);

echo json_encode($data);
?>
