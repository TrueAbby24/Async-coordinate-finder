<?php
include 'dbConnect.php';
$db = new MySQLDatabase();
if (!$con = $db->connect()) {
  echo json_encode(array());
}

$data = array();
$result = mysqli_query($con, "SELECT id FROM sets");
while ($row = mysqli_fetch_array($result)) {
  array_push($data, $row["id"]);
}
$db->disconnect();

echo json_encode($data);
?>
