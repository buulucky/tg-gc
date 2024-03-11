<?php
// เริ่มต้น Session
session_start();

if (!isset($_SESSION['admin_gcb2']) && !isset($_SESSION['user_gcb2'])) {
  header("location: ../index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GC-B2</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="css/index.css" />
</head>

<body>
  <?php include("code/navbar.php"); ?>
  <main>
    <div class="maintable">
      <table>
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
            <th>Operator 1</th>
            <th>Operator 2</th>
            <th>Note</th>
            <th>Submit</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="maintable_data"></tbody>
      </table>
    </div>
  </main>
  <?php include("code/botton-add.php"); ?>
  <script src="code/script.js"></script>

  <!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
      let refreshIntervalId;
      const tableElement = document.getElementById('maintable_data');
      tableElement.addEventListener('click', function(event) {
        if (event.target.tagName.toLowerCase() === 'td') {
          console.log('คลิกที่ตาราง');
          clearInterval(refreshIntervalId);
          setTimeout(startRefresh, 60000);
        }
      });
      startRefresh();

      function startRefresh() {
        // เริ่มต้นการรีเฟรชทุก 5 วินาที
        refreshIntervalId = setInterval(refreshSection, 5000);
      }
    });

    function refreshSection() {
      maintableList();
    }
  </script> -->
</body>

</html>