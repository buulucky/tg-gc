<?php

session_start();

include("../../db/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $newpassword = $_POST['pers_no'];
    try {
        $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = :newpassword WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':newpassword', $newpassword);

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
