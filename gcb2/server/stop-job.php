<?php
include("../../db/connection.php");

date_default_timezone_set('Asia/Bangkok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $job_id = $_POST['job_id'];
  $date = date('Y-m-d H:i:s');
  $in_progress = 'In Progress';

  try {
    $stmtCheck = $conn->prepare("SELECT stop_time FROM jobs WHERE job_id = :job_id");
    $stmtCheck->bindParam(':job_id', $job_id);
    $stmtCheck->execute();
    $stopTimeResult = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (empty($stopTimeResult['stop_time'])) {

      $stmt = $conn->prepare("UPDATE jobs SET stop_time = :stop_time WHERE job_id = :job_id");
      $stmt->bindParam(':stop_time', $date);
      $stmt->bindParam(':job_id', $job_id);

      if ($stmt->execute()) {
        $response = array(
          "status" => "ok",
          "success" => true,
          "message" => "Stop time updated successfully."
        );
        echo json_encode($response);
      } else {
        $response = array(
          "status" => "error",
          "success" => false,
          "message" => "Error updating stop time."
        );
        echo json_encode($response);
      }
    } else {
      $response = array(
        "status" => "error",
        "success" => false,
        "message" => "Job has already been stopped. Stop time exists."
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
  header('HTTP/1.1 400 Bad Request');
  exit();
}
