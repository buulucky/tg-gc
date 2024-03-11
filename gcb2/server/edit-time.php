<?php
include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $value = $_POST['value']; // นำค่ากลับไปแสดง
    $field = $_POST['field']; // รับค่าอางอิงถึงชื่อ ฟิล

    // ตรวจสอบคอลัมน์ที่ถูกต้อง
    if ($field !== 'start_time' && $field !== 'stop_time') {
        echo 'Invalid field name';
        exit;
    }

    try {
        $sql = "SELECT start_time, stop_time FROM jobs WHERE job_id = :job_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $oldStartTime = $row['start_time'];
        $oldStopTime = $row['stop_time'];

        if ($field === 'start_time') {
            // หากต้องการอัพเดทเวลาเริ่มงาน
            $newStartTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($oldStartTime)) . ' ' . $value));

            $sql = "UPDATE jobs SET start_time = :value WHERE job_id = :job_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':value', $newStartTime);
        } else {
            // หากต้องการอัพเดทเวลาหยุดงาน
            $newStopTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($oldStopTime)) . ' ' . $value));

            $sql = "UPDATE jobs SET stop_time = :value WHERE job_id = :job_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':value', $newStopTime);
        }

        $stmt->bindParam(':job_id', $job_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Update successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    exit();
}
