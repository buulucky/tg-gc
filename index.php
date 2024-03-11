<?php session_start(); ?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TG-GC Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/login.css">
</head>

<body class="text-center">

  <?php if (isset($_SESSION['err_pw']) || isset($_SESSION['error']) || isset($_SESSION['err_uname']) || isset($_SESSION['err_fill'])) : ?>
    <script>
      window.onload = function() {
        <?php if (isset($_SESSION['err_pw'])) : ?>
          alert("<?php echo $_SESSION['err_pw']; ?>");

        <?php elseif (isset($_SESSION['error'])) : ?>
          alert("<?php echo $_SESSION['error']; ?>");

        <?php elseif (isset($_SESSION['err_uname'])) : ?>
          alert("<?php echo $_SESSION['err_uname']; ?>");

        <?php elseif (isset($_SESSION['err_fill'])) : ?>
          alert("<?php echo $_SESSION['err_fill']; ?>");

        <?php endif ?>

        <?php
        unset($_SESSION['err_pw']);
        unset($_SESSION['error']);
        unset($_SESSION['err_uname']);
        unset($_SESSION['err_fill']);
        ?>
      };
    </script>
  <?php endif ?>

  <div class="login">
    <main class="form-signin">
      <form action="login_db.php" method="post">
        <img class="mb-4" src="images/logo_thai.png" width="240">
        <h1 class="h4 mb-3 fw-normal">Welcome To GC-System</h1>

        <div class="form-floating">
          <input type="text" class="form-control" name="username" placeholder="">
          <label>Personal Number</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" name="password" placeholder="">
          <label>Password</label>
        </div>

        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn-sign-in" type="submit" name="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">By Sukan 2023 - 2024</p>
      </form>
    </main>
  </div>
</body>

</html>