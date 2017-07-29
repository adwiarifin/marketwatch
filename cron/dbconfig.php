<?php

$dbhost   = "localhost";
$dbuser   = "kesatria_adwi";
$dbpass   = "3of77g9qN03g";
$dbname   = "kesatria_marketwatch";

$conn = '';
try {
  $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}
