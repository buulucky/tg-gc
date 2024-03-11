<?php
include("../../db/connection.php");

// Assuming $conn holds the connection to your database

date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d H:i:s");

$flight_id = $_POST['flight_id'];
$equipmentTypes = $_POST['equipmentTypes'];
$job_status = 'Pending';

try {
  $conn->beginTransaction();

  $stmt = $conn->prepare("INSERT INTO jobs (job_date, equipment_type, job_status, flight_id) VALUES (:job_date, :equipment_type, :job_status, :flight_id)");

  foreach ($equipmentTypes as $equipmentType) {
    $stmt->bindParam(':job_date', $date);
    $stmt->bindParam(':equipment_type', $equipmentType);
    $stmt->bindParam(':job_status', $job_status);
    $stmt->bindParam(':flight_id', $flight_id);
    $stmt->execute();
  }

  $conn->commit();

  $response = array(
    "status" => "ok",
    "success" => true,
    "message" => "Record(s) created successfully!"
  );

  echo json_encode($response);
} catch (PDOException $e) {
  $conn->rollback();

  $response = array(
    "status" => "error",
    "success" => false,
    "message" => "Record creation failed: " . $e->getMessage()
  );

  echo json_encode($response);
}
