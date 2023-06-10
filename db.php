<?php
  $dbservername='localhost';
  $dbname='hwdb';
  $dbusername='clement';
  $dbpassword='clementdbhw';
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  //set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>