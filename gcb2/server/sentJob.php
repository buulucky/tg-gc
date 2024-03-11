<?php

session_start();

include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $job_id = $_POST['job_id'];
    $flight_id = $_POST['flight_id'];
    $status = 'Completed';

    $stmt = $conn->prepare("UPDATE jobs SET job_status = :status, sender = :sender WHERE job_id = :job_id");
    $stmt->bindParam(':job_id', $job_id);
    $stmt->bindParam(':sender', $_SESSION['name']);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {

      $checkAllCompletedStmt = $conn->prepare("SELECT COUNT(*) AS count FROM jobs WHERE flight_id = :flight_id AND job_status != 'Completed' AND job_status != 'Cancel';");
      $checkAllCompletedStmt->bindParam(':flight_id', $flight_id);
      $checkAllCompletedStmt->execute();
      $result = $checkAllCompletedStmt->fetch(PDO::FETCH_ASSOC);
      $allJobsCompleted = ($result['count'] == 0);

      if ($allJobsCompleted) {
        $flightStatus = ($status == 'Completed') ? 'Completed' : 'Not Completed';
        $updateFlightStmt = $conn->prepare("UPDATE flights SET flight_status = :flightStatus WHERE flight_id = :flight_id");
        $updateFlightStmt->bindParam(':flightStatus', $flightStatus);
        $updateFlightStmt->bindParam(':flight_id', $flight_id);

        if ($updateFlightStmt->execute()) {
          $response = array(
            "status" => "success",
            "success" => true,
            "message" => "Job and Flight updated successfully."
          );
          echo json_encode($response);
        } else {
          $response = array(
            "status" => "error",
            "success" => false,
            "message" => "Error updating flight."
          );
          echo json_encode($response);
        }
      } else {
        $response = array(
          "status" => "error",
          "success" => false,
          "message" => "Not all jobs completed."
        );
        echo json_encode($response);
      }
    } else {
      $response = array(
        "status" => "error",
        "success" => false,
        "message" => "Error updating job."
      );
      echo json_encode($response);
    }
  } catch (PDOException $e) {
    $response = array(
      "status" => "error",
      "success" => false,
      "message" => "Error: " . $e->getMessage()
    );
    echo json_encode($response);
  }
} else {
  $response = array(
    "status" => "error",
    "success" => false,
    "message" => "Invalid data received."
  );
  echo json_encode($response);
}
