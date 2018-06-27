<?php

function invalidData($message) {
  echo '{"status" : "fail", "message": "'.$message.'"}';
  die();
}

if (isset($_GET['id'])) {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "coordinates";
  // build connection
  $con = mysqli_connect($servername, $username, $password, $dbname);
  if (!$con) {
    invalidData("Connection failed: ".myslqi_connect_error());
  }
  mysqli_select_db($con, $dbname);
  // check if id is in database
  $stmt = mysqli_prepare($con, "SELECT * FROM sets WHERE id = ?");
  mysqli_stmt_bind_param($stmt,'i', $_GET['id']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id, $status);
  $i = 0;
  while (mysqli_stmt_fetch($stmt)) {
    $i++;
  }
  if ($i != 1) {// || $id == 17) {
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
      $obj->longitude = $row["longitude"];
      $obj->lattitude = $row["lattitude"];
      array_push($list, $obj);
    }

    $result = new stdClass();
    $result->id = $id;
    $result->status = $status;
    $result->data = $list;
    echo json_encode($result);
    // send result

  } else {
    invalidData("Invalid status");
  }
  mysqli_stmt_close($stmt);

} else {
  invalidData("No id sent");
}
?>
