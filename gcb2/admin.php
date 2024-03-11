<?php
// เริ่มต้น Session
session_start();

if ($_SESSION['admin_gcb2'] !== true) {
  header("location: ../index.php");
  exit();
}

include("../db/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GC-B2 Admin</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="css/admin.css" />
</head>

<body>
  <?php include("code/navbar.php"); ?>

  <main>
    <div class="users-table">

      <table id="usersTable">
        <thead>
          <tr>
            <th>username</th>
            <th>pers no</th>
            <th>name</th>
            <th>Function</th>
            <th>Status</th>
            <th>Role</th>
            <th>Reset Password</th>
          </tr>
        </thead>
        <tbody>

          <?php
          $stmt = $conn->prepare("SELECT * FROM users;");
          $stmt->execute();
          $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($users as $user) {
          ?>
            <tr>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['pers_no']; ?></td>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['function']; ?></td>
              <td><?php echo $user['status']; ?></td>
              <td><?php echo $user['role']; ?></td>
              <td>
                <button type="button" class="btn-reset-password" onclick="resetPassword(<?php echo $user['user_id']; ?>, <?php echo $user['pers_no']; ?>)">
                  Reset
                </button>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    function resetPassword(user_id, pers_no) {
      var userConfirmation = confirm("คุณต้องการที่จะรีเซ็ตรหัสผ่านหรือไม่?");
      if (userConfirmation) {
        $.ajax({
          url: 'server/resetpassword.php',
          method: 'POST',
          data: {
            user_id: user_id,
            pers_no: pers_no
          },
          success: function(response) {
            location.reload();
          },
          error: function(error) {
            console.error(error);
          }
        });
      }
    }


    // function resetPassword(user_id) {
    //   $.ajax({
    //     type: 'POST',
    //     url: 'server/resetpassword.php',
    //     data: {
    //       user_id: user_id,
    //     },
    //     success: function(data) {
    //       var response = JSON.parse(data);

    //     },
    //     error: function(xhr, status, error) {
    //       console.error(xhr.responseText);
    //     }
    //   });
    // }
  </script>

</body>

</html>