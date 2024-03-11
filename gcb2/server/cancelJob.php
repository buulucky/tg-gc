<?php

session_start();

include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $job_id = $_POST['job_id'];
  $cancel = 'Cancel';

  try {
    $stmt = $conn->prepare("UPDATE jobs SET job_status = :cancel, sender = :sender  WHERE job_id = :job_id");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->bindParam(':cancel', $cancel);
    $stmt->bindParam(':sender', $_SESSION['name']);

    // Execute the statement
    $stmt->execute();

    $response = array(
      "status" => "success",
      "success" => true,
      "message" => "Job canceled successfully"
    );
    echo json_encode($response);
  } catch (PDOException $e) {
    $response = array(
      "status" => "error",
      "success" => false,
      "message" => "Error: " . $e->getMessage()
    );
    echo json_encode($response);
  }
} else {
  header('HTTP/1.1 400 Bad Request');
  exit();
}
