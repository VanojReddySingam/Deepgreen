<?php
session_start();

// Clear all session data
session_unset();
session_destroy();

// Redirect to index.php after logout
header("Location: index.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logged Out</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    .logout-container {
      max-width: 500px;
      margin: 10vh auto;
      padding: 40px;
      background: linear-gradient(135deg, #fd79a8, #74b9ff);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      text-align: center;
      color: #fff;
      animation: fadeInUp 0.8s ease forwards;
      opacity: 0;
    }

    .logout-container h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .logout-container p {
      font-size: 1.1rem;
      margin-bottom: 30px;
    }

    .logout-btn {
      background: linear-gradient(90deg, #55efc4, #81ecec);
      color: #2d3436;
      font-weight: 600;
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      box-shadow: 0 6px 18px rgba(85, 239, 196, 0.5);
      transition: 0.3s ease;
      display: inline-block;
    }

    .logout-btn:hover {
      transform: translateY(-4px) scale(1.03);
      box-shadow: 0 10px 28px rgba(85, 239, 196, 0.6);
    }
  </style>
</head>
<body>
  <div class="logout-container">
    <h2>You’ve been logged out</h2>
    <p>Thank you for using <strong>DeepGreen</strong>. We hope to see you again soon 🌱</p>
    <a href="index.php" class="logout-btn">Go to Home</a>
  </div>
</body>
</html>