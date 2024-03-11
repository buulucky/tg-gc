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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GC-B2 Report</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
  <link rel="stylesheet" href="css/report.css" />
</head>

<body>
  <?php include("code/navbar.php"); ?>
  <main>
    <div class="maintable">
      <?php
      if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $stmt = $conn->prepare("SELECT 
        flights.flight_id, flights.flight_no, flights.register, flights.bay, 
        jobs.job_id, jobs.equipment_type, jobs.equipment_no, jobs.sender,
        DATE_FORMAT(jobs.job_date, '%d/%m/%y') AS formatted_job_date,
        DATE_FORMAT(jobs.start_time, '%H:%i') AS formatted_start_time,
        DATE_FORMAT(jobs.stop_time, '%H:%i') AS formatted_stop_time,
        username_1.name AS username_1_name,
        username_2.name AS username_2_name,
        jobs.job_status, jobs.note
        FROM flights
        LEFT JOIN jobs ON flights.flight_id = jobs.flight_id
        LEFT JOIN users AS username_1 ON jobs.username_1 = username_1.username
        LEFT JOIN users AS username_2 ON jobs.username_2 = username_2.username
        WHERE DATE(jobs.job_date) BETWEEN :startDate AND :endDate
        ORDER BY job_id DESC;");

        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
      } else {
        $currentTimestamp = time();
        $currentDate = date('Y-m-d', $currentTimestamp);
        $stmt = $conn->prepare("SELECT 
        flights.flight_id, flights.flight_no, flights.register, flights.bay, 
        jobs.job_id, jobs.equipment_type, jobs.equipment_no, jobs.sender,
        DATE_FORMAT(jobs.job_date, '%d/%m/%y') AS formatted_job_date,
        DATE_FORMAT(jobs.start_time, '%H:%i') AS formatted_start_time,
        DATE_FORMAT(jobs.stop_time, '%H:%i') AS formatted_stop_time,
        username_1.name AS username_1_name,
        username_2.name AS username_2_name,
        jobs.job_status, jobs.note
        FROM flights
        LEFT JOIN jobs ON flights.flight_id = jobs.flight_id
        LEFT JOIN users AS username_1 ON jobs.username_1 = username_1.username
        LEFT JOIN users AS username_2 ON jobs.username_2 = username_2.username
        WHERE DATE(jobs.job_date) = :startDate
        ORDER BY job_id DESC;");

        $stmt->bindParam(':startDate', $currentDate);
      }

      $stmt->execute();
      $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <div class="search">
        <form action="" method="post">
          <label for="startDate">วันที่เริ่มต้น: </label>
          <input type="date" id="startDate" name="startDate" value="<?php echo isset($startDate) ? $startDate : ''; ?>">
          <label for="endDate">วันที่สิ้นสุด: </label>
          <input type="date" id="endDate" name="endDate" value="<?php echo isset($endDate) ? $endDate : ''; ?>">
          <input class="btn-search" type="submit" value="ค้นหา">
        </form>
      </div>
      <table id="myTable">
        <thead>
          <tr>
            <th>JobID</th>
            <th>Date</th>
            <th>Flight</th>
            <th>A/C Reg.</th>
            <th>BAY</th>
            <th>Required</th>
            <th>Equ No.</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Operator</th>
            <th>Note</th>
            <th>Sender</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($jobs as $job) {
            $statusColor = '';
            switch ($job['job_status']) {
              case 'Completed':
                $statusColor = 'background-color: #00b6ce; padding: 5px 6px;';
                break;
              case 'Cancel':
                $statusColor = 'background-color: #ff0000; padding: 5px 19px;';
                break;
              case 'Pending':
                $statusColor = 'background-color: #ffc400; padding: 5px 16px;';
                break;
              case 'In Progress':
                $statusColor = 'background-color: #4caf50; padding: 5px 5px;';
                break;
              default:
                $statusColor = '';
                break;
            }
          ?>
            <tr>
              <td><?php echo $job['job_id']; ?></td>
              <td><?php echo $job['formatted_job_date']; ?></td>
              <td><?php echo $job['flight_no']; ?></td>
              <td><?php echo $job['register']; ?></td>
              <td><?php echo $job['bay']; ?></td>
              <td><?php echo $job['equipment_type']; ?></td>
              <td><?php echo $job['equipment_no'] !== '' ? $job['equipment_no'] : '-'; ?></td>
              <td><?php echo $job['formatted_start_time'] == '' ? '-' : $job['formatted_start_time']; ?></td>
              <td><?php echo $job['formatted_stop_time'] == '' ? '-' : $job['formatted_stop_time']; ?></td>
              <td><?php echo $job['username_1_name'] == '' ? '-' : $job['username_1_name']; ?> <?php echo $job['username_2_name']; ?></td>
              <td><?php echo $job['note'] !== '' ? $job['note'] : '-'; ?></td>
              <td><?php echo $job['sender'] !== '' ? $job['sender'] : '-'; ?></td>
              <td><a class="show-status" style="<?php echo $statusColor; ?>"><?php echo $job['job_status']; ?></a></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script>
    new DataTable('#myTable', {
      layout: {
        topStart: {
          buttons: ['copy', 'excel', 'pdf']
        }
      },
      pageLength: 25,
      language: {
        emptyTable: "ไม่มีข้อมูลในตาราง",
        zeroRecords: "ไม่พบข้อมูลที่ตรงกับการค้นหา",
        paginate: {
          previous: "ก่อนหน้า",
          next: "ถัดไป"
        }
      },
    });

    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.buttons-copy').style.backgroundColor = '#4CAF50';
      document.querySelector('.buttons-copy').style.color = '#ffffff';

      document.querySelector('.buttons-excel').style.backgroundColor = '#008CBA';
      document.querySelector('.buttons-excel').style.color = '#ffffff';

      document.querySelector('.buttons-pdf').style.backgroundColor = '#E20074';
      document.querySelector('.buttons-pdf').style.color = '#ffffff';
    });
  </script>
</body>

</html>