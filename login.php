<?php
session_start();

// ✅ Redirect already signed-in users back to index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['gmail']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            unset($_SESSION['new_signup']); // ensures login shows "Welcome" for existing users
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - DeepGreen</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { display: flex; flex-direction: column; min-height: 100vh; }
    .login-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 30px; }
    .login-box { width: 100%; max-width: 420px; padding: 40px 30px; border-radius: 16px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); box-shadow: 0 10px 35px rgba(0,0,0,0.25); animation: fadeInUp 1s ease forwards; }
    .login-box h2 { text-align: center; margin-bottom: 25px; color: #2d3436; }
    .form-group { position: relative; margin-bottom: 20px; }
    .form-group i { position: absolute; top: 50%; transform: translateY(-50%); color: #6c5ce7; }
    .form-group input { width: 100%; box-sizing: border-box; padding: 12px 16px 12px 42px; border-radius: 8px; border: 1px solid #dfe6e9; font-size: 1em; background: #f9f9ff; transition: all 0.3s ease; }
    .form-group input:focus { border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108,92,231,0.2); outline: none; }
    .toggle-password { position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #6c5ce7; }
    .toggle-password:hover { color: #fd79a8; }
    .login-footer { text-align: center; margin-top: 15px; }
    .login-footer a { color: #6c5ce7; font-weight: 600; text-decoration: none; transition: color 0.3s ease; }
    .login-footer a:hover { color: #fd79a8; }
  </style>
</head>
<body>

  <!-- Hero Header -->
  <header class="hero-header">
    <div class="hero-overlay">
      <h1 class="hero-title">DeepGreen</h1>
      <p class="hero-subtitle">A Smart Agricultural Advisory Platform</p>
    </div>
  </header>

  <?php include 'nav.php'; ?>

  <!-- Login Form -->
  <div class="login-container">
    <div class="login-box">
      <h2>Login to DeepGreen</h2>
      <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

      <form method="POST" action="">
        <div class="form-group">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="gmail" placeholder="Enter your Gmail" required>
        </div>

        <div class="form-group">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="Enter your password" required>
          <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" class="start-btn">Login</button>
      </form>

      <div class="login-footer">
        <p>Don’t have an account? <a href="signup.php">Sign Up</a></p>
      </div>
    </div>
  </div>

  <!-- JS for toggle password -->
  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
      const type = passwordField.type === "password" ? "text" : "password";
      passwordField.type = type;
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>

</body>
</html>
