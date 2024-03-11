<?php
include("../../db/connection.php");

date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d H:i:s");

$flight_on = $_POST['flight_on'];
$register = $_POST['register'];
$bay = $_POST['bay'];
$flight_status = 'Pending';

try {
  $stmt = $conn->prepare("INSERT INTO flights (flight_no, register, bay, flight_status, date) VALUES (:flight_no, :register, :bay, :flight_status, :date)");

  $stmt->bindParam(':flight_no', $flight_on);
  $stmt->bindParam(':register', $register);
  $stmt->bindParam(':bay', $bay);
  $stmt->bindParam(':flight_status', $flight_status);
  $stmt->bindParam(':date', $date);

  $stmt->execute();

  $response = [
    'status' => 'ok',
    'success' => true,
    'message' => 'Record created successfully!'
  ];
  echo json_encode($response);
} catch (PDOException $e) {
  $response = [
    'status' => 'error',
    'success' => false,
    'message' => 'Record creation failed: ' . $e->getMessage()
  ];
  echo json_encode($response);
}
