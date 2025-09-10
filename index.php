<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DeepGreen - Plant Disease & Crop Advisory Portal</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Hero Header with Background Image -->
  <header class="hero-header">
    <div class="hero-overlay">
      <h1 class="hero-title">DeepGreen</h1>
      <p class="hero-subtitle">A Smart Agricultural Advisory Platform</p>
    </div>
  </header>

  <!-- Navigation -->
  <?php include 'nav.php'; ?>

  <!-- Dynamic Greeting Text -->
  <?php if (isset($_SESSION['username'])): ?>
    <p style="text-align:left; margin:10px 0 0 10px; font-weight:900; font-size:1.2em; color:black;">
        <?php 
        if (!empty($_SESSION['new_signup'])) {
            echo "Hello, " . htmlspecialchars($_SESSION['username']) . " 👋";
            unset($_SESSION['new_signup']); // only show once
        } else {
            echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
        }
        ?>
    </p>
  <?php endif; ?>

  <!-- Welcome Section -->
  <section class="hero-content">
    <h2>Welcome to DeepGreen</h2>
    <p>
      A unified platform for plant disease detection, soil and fertilizer analysis, crop advisory, and weather insights.
    </p>
  </section>

  <!-- Feature Cards -->
  <section class="features">
    <div class="feature-grid">

      <div class="card soil-card">
        <h3>Soil Detection</h3>
        <div class="action-row">
          <a href="soil_detection.php" class="start-btn">Start Soil Detection</a>
        </div>
      </div>

      <div class="card fertilizer-card">
        <h3>Fertilizer Advisor</h3>
        <div class="action-row">
          <a href="fertilizer.php" class="start-btn">Fertilizer Recommendation</a>
        </div>
      </div>

      <div class="card plant-card">
        <h3>Plant Disease Detection (AI)</h3>
        <div class="action-row">
          <a href="disease_detection.php" class="start-btn">Detect Disease</a>
        </div>
      </div>

      <div class="card weather-card">
        <h3>Weather Dashboard</h3>
        <div class="action-row">
          <a href="weather.php" class="start-btn">Check Weather</a>
        </div>
      </div>

      <div class="card crop-card">
        <h3>Crop Advisory</h3>
        <div class="action-row">
          <a href="dashboard.php" class="start-btn">Crop Suitability Guide</a>
        </div>
      </div>

    </div>
  </section>

  <!-- Article Section -->
  <article class="article-section">
  <h2>Sustainable Farming</h2>
  <img src="assets/sustainable-farming.jpg" alt="Sustainable Farming" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />
  <p>
    As agriculture moves toward more sustainable practices, technologies like AI-based plant disease detection,
    precision fertilizer recommendations, and weather-integrated crop planning are reshaping the future of farming.
  </p>
  <p>
    DeepGreen is committed to empowering farmers with tools that increase yield, reduce waste, and promote eco-friendly practices. 
    Stay tuned as we continue to roll out features and guides to support our green revolution.
  </p>
</article>


  <!-- Footer -->
  <footer style="text-align:center; margin:2em 0 1em 0; color:black;">
    &copy; 2025 DeepGreen Project | Powered by HTML, CSS, JS, PHP, MySQL, AI
  </footer>

</body>
</html>
