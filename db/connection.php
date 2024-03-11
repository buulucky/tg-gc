<?php
$server_name = "localhost";
$user_name = "tggcyhu_sukan";
$password = "Buu22025tg+";
$database_name = "tggcyhu_gcb_db";

date_default_timezone_set('Asia/Bangkok');

try {
  $conn = new PDO("mysql:host=$server_name;dbname=$database_name", $user_name, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}