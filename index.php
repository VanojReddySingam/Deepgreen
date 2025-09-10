<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
include "nav.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Farmer Assist - Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
</head>

<body>
  <h1>🌱 Farmer Assist Dashboard</h1>

  <section>
    <h2>Plant Disease Detection</h2>
    <form id="diseaseForm" enctype="multipart/form-data" onsubmit="event.preventDefault(); uploadImage();">
      <input type="file" name="plant_image" accept="image/*" required>
      <button type="submit">Detect Disease</button>
    </form>
    <div id="loading" style="display:none;">Loading...</div>
    <div id="disease-result"></div>
  </section>

  <section>
    <h2>Soil → Crop Recommendation</h2>
    <form id="soilForm" onsubmit="event.preventDefault(); recommendCrops();">
      <input type="text" name="soil_type" placeholder="Soil Type (e.g. Black)" required>
      <input type="text" name="ph" placeholder="pH Value">
      <input type="text" name="nitrogen" placeholder="Nitrogen (N)">
      <input type="text" name="phosphorus" placeholder="Phosphorus (P)">
      <input type="text" name="potassium" placeholder="Potassium (K)">
      <button type="submit">Get Recommendations</button>
    </form>
    <div id="crop-result"></div>
  </section>
</body>

</html>