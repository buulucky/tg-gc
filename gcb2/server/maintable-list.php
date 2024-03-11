<?php
include("../../db/connection.php");

try {
  $sql = "SELECT flights.flight_id,flights.flight_no,flights.register,flights.bay,jobs.job_id,jobs.job_date,jobs.equipment_type,jobs.equipment_no, 
    DATE_FORMAT(jobs.start_time, '%H:%i') AS t_start_time,
    DATE_FORMAT(jobs.stop_time, '%H:%i') AS t_stop_time,
    DATE_FORMAT(jobs.job_date, '%d-%m') AS jobs_date,
    jobs.username_1,jobs.username_2,jobs.job_status,jobs.note 
    FROM flights
    LEFT JOIN jobs ON flights.flight_id = jobs.flight_id
    WHERE jobs.job_status != 'Completed' AND jobs.job_status != 'Cancel' ORDER BY jobs.job_id DESC";

  $stmt = $conn->query($sql);

  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($data);
} catch (PDOException $e) {
  echo "Failed to query data: " . $e->getMessage();
}
