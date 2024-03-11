<?php
include("../../db/connection.php");

date_default_timezone_set('Asia/Bangkok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $job_id = $_POST['job_id'];
  $date = date('Y-m-d H:i:s');
  $in_progress = 'In Progress';

  try {
    $conn->beginTransaction();

    $stmtCheck = $conn->prepare("SELECT start_time FROM jobs WHERE job_id = :job_id");
    $stmtCheck->bindParam(':job_id', $job_id);
    $stmtCheck->execute();
    $startTimeResult = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (empty($startTimeResult['start_time'])) {

      $stmt = $conn->prepare("UPDATE jobs SET start_time = :start_time WHERE job_id = :job_id");
      $stmt->bindParam(':start_time', $date);
      $stmt->bindParam(':job_id', $job_id);

      if ($stmt->execute()) {

        $stmt = $conn->prepare("UPDATE jobs SET job_status = :job_status WHERE job_id = :job_id");
        $stmt->bindParam(':job_status', $in_progress);
        $stmt->bindParam(':job_id', $job_id);

        if ($stmt->execute()) {
          $conn->commit();
          $response = array(
            "status" => "ok",
            "success" => true,
            "message" => "Update successful in job table. Start time updated."
          );
          echo json_encode($response);
        } else {
          $conn->rollBack();
          $response = array(
            "status" => "error",
            "success" => false,
            "message" => "Update failed in job table. Error updating job status.",
            "error_info" => $stmt->errorInfo()
          );
          echo json_encode($response);
        }
      } else {
        $conn->rollBack();
        $response = array(
          "status" => "error",
          "success" => false,
          "message" => "Error updating start time."
        );
        echo json_encode($response);
      }
    } else {
      $conn->rollBack();
      $response = array(
        "status" => "error",
        "success" => false,
        "message" => "Job already started. Start time exists."
      );
      echo json_encode($response);
    }
  } catch (PDOException $e) {
    $conn->rollBack();
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
