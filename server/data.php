<?php
include 'dbConnect.php';
$db = new MySQLDatabase();

function invalidData($message) {
  echo '{"status" : "fail", "message": "'.$message.'"}';
  $db->disconnect();
  die();
}

if (isset($_GET['id'])) {
  if (!$con = $db->connect()) {
    invalidData("Connection failed: ".myslqi_connect_error());
  }
  // check if id is in database
  $stmt = mysqli_prepare($con, "SELECT * FROM sets WHERE id = ?");
  mysqli_stmt_bind_param($stmt,'i', $_GET['id']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id, $status);
  $i = 0;
  while (mysqli_stmt_fetch($stmt)) {
    $i++;
  }
  if ($i != 1) {
    invalidData("Invalid id");
  }
  // see what type
  if (strcmp("pending",$status) == 0) {
    echo "{\"id\" : \"{$id}\", \"status\" : \"{$status}\"}";
    die();
  } else if (strcmp("ready", $status) == 0) {
    // add coordinates to array
    $list = array();
    $qryResult = mysqli_query($con, "SELECT * FROM locations WHERE id ='{$id}'");

    while($row = mysqli_fetch_array($qryResult)) {
      $obj = new stdClass();
      $obj->name = $row["name"];
      $obj->address = $row["address"];
      $obj->location = new stdClass();
      $obj->location->lat = floatval($row["lattitude"]);
      $obj->location->lng = floatval($row["longitude"]);
      array_push($list, $obj);
    }

    $result = new stdClass();
    $result->id = $id;
    $result->status = $status;
    $result->data = $list;
    // send result
    echo json_encode($result);

  } else {
    invalidData("Invalid status");
  }
  mysqli_stmt_close($stmt);
  $db->disconnect();
} else {
  invalidData("No id sent");
}
?>
