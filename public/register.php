<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Clockin' Register Page</title>
  <link rel="stylesheet" href="./css/register.css">
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img src="./img/logo.png" alt="Clockin' Logo" class="logo">
    </div>

    <div class="form-container">
    <h1 class="centered-text">REGISTER</h1>
      <?php if (isset($_GET['error']) && $_GET['error'] === 'empty_field'): ?>
        <p class="centered-text">Fill in all fields!</p>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'wrong_repeat_password'): ?>
        <p class="centered-text">Passwords are not the same!</p>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
        <p class="centered-text">User with the given email already exists!</p>
      <?php endif; ?>

      <form action="../controllers/RegisterController.php" method="POST" class="form">
        <input type="text" name="firstname" placeholder="FIRSTNAME" class="input-field" required>
        <input type="text " name="lastname" placeholder="LASTNAME" class="input-field" required>
        <input type="email" name="email" placeholder="EMAIL" class="input-field" required>
        <input type="password" name="password" placeholder="PASSWORD" class="input-field" required>
        <input type="password" name="repeat_password" placeholder="REPEAT PASSWORD" class="input-field" required>

        <button type="submit" class="btn login-btn">REGISTER</button>
      </form>
      <a href="login.php"><button class="btn register-btn">LOGIN</button></a>
    </div>
  </div>
</body>
</html>
