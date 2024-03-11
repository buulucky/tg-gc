<?php
include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $value = $_POST['value']; // นำค่ากลับไปแสดง
    $field = $_POST['field']; // รับค่าอางอิงถึงชื่อ ฟิล

    try {
        $sql = "UPDATE jobs SET $field = :value WHERE job_id = :job_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':value', $value);
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
