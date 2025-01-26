<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Clockin' Login Page</title>
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img src="./img/logo.png" alt="Clockin' Logo" class="logo">
    </div>

    
    <div class="form-container">
    
      <h1 class="centered-text">LOGIN</h1>
      <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_data'): ?>
        <p class="centered-text">Incorrect email or password!</p>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'empty_field'): ?>
        <p class="centered-text">Fill in all fields!</p>
      <?php elseif (isset($_GET['message']) && $_GET['message'] === 'registered'): ?>
        <p class="centered-text">Registration was successful. You can now log in.</p>
      <?php endif; ?>

      <form action="./controllers/LoginController.php" method="POST" class="form">
        <input type="email" name="email" placeholder="EMAIL" class="input-field" required>
        <input type="password" name="password" placeholder="PASSWORD" class="input-field" required>
        <button type="submit" class="btn login-btn">LOGIN</button>
      </form>

      <a href="register.php"><button type="button" class="btn register-btn">REGISTER</button></a>
    </div>
  </div>
</body>
</html>

