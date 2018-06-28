<?php
class MySQLDatabase{
  var $link = null;
  var $servername = "localhost";
  var $user = "root";
  var $password = "";
  var $dbname = "coordinates";
  function connect(){
    $this->link = mysqli_connect($this->servername, $this->user, $this->password);
    if(!$this->link){
      die('Not connected : ' . mysqli_connect_error());
    }
    $db = mysqli_select_db($this->link, $this->dbname);
    if(!$db){
      return;
    }
    return $this->link;
  }
  function disconnect(){
    mysqli_close($this->link);
  }
}
?>
