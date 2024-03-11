<?php
session_start();

if (!isset($_SESSION['admin_gcb2']) && !isset($_SESSION['user_gcb2'])) {
  header("location: ../index.php");
}

include("../db/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GC-B Job Mobile</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="css/mobile.css">
</head>

<body>

  <?php
  try {
    $stmt = $conn->prepare("SELECT flights.flight_id, flights.flight_no, flights.register,
      flights.bay, flights.date, jobs.job_id, jobs.equipment_type, jobs.equipment_no, 
      DATE_FORMAT(jobs.start_time, '%H%i') AS formatted_start_time,
      DATE_FORMAT(jobs.stop_time, '%H%i') AS formatted_stop_time,
      jobs.username_1, jobs.username_2, jobs.job_status, jobs.note 
      FROM jobs
      INNER JOIN flights ON jobs.flight_id = flights.flight_id 
      WHERE (jobs.username_1 = :username OR jobs.username_2 = :username) AND 
            (jobs.job_status != 'Completed' AND jobs.job_status != 'Cancel' AND jobs.stop_time =''); ");



    $stmt->bindParam(':username', $_SESSION['pers_no']);
    $stmt->execute();

    // ดึงข้อมูลทั้งหมดเป็น associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if (count($rows) > 0) {
      // วนลูปผลลัพธ์
      foreach ($rows as $row) { ?>
        <!-- แสดงผลข้อมูลต่าง ๆ -->
        <section class="job-details">
          <?php echo "<p>Job ID : " . $row['job_id'] . "</p>"; ?>
          <?php echo "<p>Flight : " . $row['flight_no'] . "</p>"; ?>
          <?php echo "<p>Register : " . $row['register'] . "</p>"; ?>
          <?php echo "<h1>BAY : " . $row['bay'] . "</h1>"; ?>
          <?php echo "<p>Request : " . $row['equipment_type'] . "</p>"; ?>
          <?php echo "<p>Equipment No : " . $row['equipment_no'] . "</p>"; ?>

          <?php if (empty($row['formatted_start_time'])) : ?>
            <!-- ถ้า start_time ยังไม่มีข้อมูล แสดงปุ่ม Start -->
            <button class="start-btn" onclick="startJob(<?php echo $row['job_id']; ?>)">
              Start
            </button>
          <?php else : ?>
            <!-- ถ้า start_time มีข้อมูล แสดงปุ่ม Stop -->
            <button class="stop-btn" onclick="stopJob(<?php echo $row['job_id']; ?>)">
              Stop
            </button>
          <?php endif; ?>
        </section>
        <div class="a" style="color: white;"></div>
  <?php
      }
    } else {
      echo "<h2 style='color: white;'>กรุณารอคำสั่ง</h2>";
    }
  } catch (PDOException $e) {
    // จัดการข้อผิดพลาดของฐานข้อมูล
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
  }
  ?>
  <script>
    function startJob(jobId) {
      var userConfirmation = confirm("คุณต้องการที่จะเริ่มงานหรือไม่?");

      if (userConfirmation) {
        $.ajax({
          url: 'server/start-job.php',
          method: 'POST',
          data: {
            job_id: jobId
          },
          success: function(response) {
            console.log(response);
            location.reload();
          },
          error: function(error) {
            console.error(error);
          }
        });
      }
    }

    function stopJob(jobId) {
      var userConfirmation = confirm("คุณต้องการที่จะหยุดงานหรือไม่?");

      if (userConfirmation) {
        $.ajax({
          url: 'server/stop-job.php',
          method: 'POST',
          data: {
            job_id: jobId
          },
          success: function(response) {
            console.log(response);
            location.reload();
          },
          error: function(error) {
            console.error(error);
          }
        });
      }
    }
  </script>
</body>

</html>