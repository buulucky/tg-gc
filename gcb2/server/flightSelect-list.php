<?php
include("../../db/connection.php");

try {
  $sql = "SELECT * FROM `flights` 
  WHERE flight_status != 'Completed'
  ORDER BY `flight_id` DESC";
  $stmt = $conn->query($sql);

  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($data);
} catch (PDOException $e) {

  echo "Failed to connect: " . $e->getMessage();
}
