<?php
include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flight_id = $_POST['flight_id'];
    $value = $_POST['value']; // นำค่ากลับไปแสดง
    $field = $_POST['field']; // รับค่าอางอิงถึงชื่อ ฟิล

    try {
        $sql = "UPDATE flights SET $field = :value WHERE flight_id = :flight_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':flight_id', $flight_id);

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
