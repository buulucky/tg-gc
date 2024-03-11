<?php
session_start();

// date_default_timezone_set('Asia/Bangkok');
// $expireAfter = 60; // 1 minute

// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expireAfter)) {
//   session_unset();
//   session_destroy();
//   $_SESSION['err_session_expire'] = "Session expired. Please log in again.";
//   header('location: index.php');
// }

// $_SESSION['last_activity'] = time();

include('db/connection.php');

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    header('location: index.php');
  } else {
    $select_stmt = $conn->prepare("SELECT COUNT(username) AS count_uname, password, name, pers_no, role FROM users WHERE username = :username");
    $select_stmt->bindParam(':username', $username);
    $select_stmt->execute();

    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['count_uname'] == 0) {
      $_SESSION['err_uname'] = "ไม่มี username นี้ในระบบ";
      header('location: index.php');
    } else {
      if (password_verify($password, $row['password'])) {
        switch ($row['role']) {
          case 'user_gcb2':
            $_SESSION['user_gcb2'] = $username;
            $_SESSION['name'] = $row['name'];
            $_SESSION['pers_no'] = $row['pers_no'];
            $_SESSION['admin_gcb2'] = false;
            header('location: gcb2/index.php');
            break;

          case 'admin_gcb2':
            $_SESSION['admin_gcb2'] = $username;
            $_SESSION['name'] = $row['name'];
            $_SESSION['pers_no'] = $row['pers_no'];
            $_SESSION['admin_gcb2'] = true;
            header('location: gcb2/index.php');
            break;

          case 'user_gcb1':
            $_SESSION['user_gcb1'] = $username;
            $_SESSION['name'] = $row['name'];
            header('location: gcb1/index.php');
            break;

          default:
            $_SESSION['error'] = "Something went wrong";
            header("location: index.php");
        }
      } else {
        $_SESSION['err_pw'] = "รหัสผ่านไม่ถูกต้อง";
        header('location: index.php');
      }
    }
  }
}
