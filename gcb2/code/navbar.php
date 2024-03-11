<nav>
  <div class="navbar">
    <div class="nav-con">
      <div class="logo">
        <img src="../images/logo_thai.png" style="width: 120px" />
      </div>
      <div class="logo">
        <?php echo $_SESSION['name']; ?>
        <?php echo $_SESSION['pers_no']; ?>
      </div>
      <ul class="menu">
        <li><a href="index.php">MainTable</a></li>
        <li><a href="report.php">Report</a></li>
        <li><a href="mobile.php">Mobile</a></li>
        <?php
        // เช็คว่า $_SESSION['admin_gcb2'] เป็น true หรือไม่
        if ($_SESSION['admin_gcb2']) {
          echo '<li><a href="admin.php">Admin</a></li>';
        }
        ?>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>