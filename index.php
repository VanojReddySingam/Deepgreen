<!DOCTYPE html>
<html>
<head>
  <title>DeepGreen - Plant Disease & Crop Advisory Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
  <section style="max-width:750px;margin:2em auto;">
    <h2>Welcome to DeepGreen</h2>
    <p>A unified web platform for plant disease detection, soil & fertilizer analysis, crop advisory, and weather guidance.</p>
  </section>
  <div class="container" style="display:flex;flex-wrap:wrap;gap:1.5em;justify-content:center;">
    <div class="card" style="width:260px;">
      <h3>Soil Detection</h3>
      <a href="soil_detection.php" class="start-btn">Start Soil Detection</a>
    </div>
    <div class="card" style="width:260px;">
      <h3>Fertilizer Advisor</h3>
      <a href="fertilizer.php" class="start-btn">Fertilizer Recommendation</a>
    </div>
    <div class="card" style="width:260px;">
      <h3>Plant Disease Detection (AI)</h3>
      <a href="disease_detection.php" class="start-btn">Detect Disease</a>
    </div>
    <div class="card" style="width:260px;">
      <h3>Weather Dashboard</h3>
      <a href="weather.php" class="start-btn">Check Weather</a>
    </div>
    <div class="card" style="width:260px;">
      <h3>Crop Advisory</h3>
      <a href="dashboard.php" class="start-btn">Crop Suitability Guide</a>
    </div>
  </div>
  <footer style="text-align:center;margin:2em 0 1em 0;color:#aaa;">
    &copy; 2025 DeepGreen Project | Powered by HTML, CSS, JS, PHP, MySQL, AI
  </footer>
</body>
</html>
