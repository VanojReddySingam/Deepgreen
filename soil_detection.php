<?php ?>
<!DOCTYPE html>
<html>

<head>
  <title>Soil Detection</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <?php include 'nav.php'; ?>
  <h2>Soil Detection Tool</h2>
  <form id="soilForm" enctype="multipart/form-data">
    <label>Enter Soil Parameters Manually:</label>
    <input type="number" name="ph" step="0.01" placeholder="Soil pH" required>
    <input type="number" name="nitrogen" placeholder="Nitrogen (mg/kg)" required>
    <input type="number" name="phosphorus" placeholder="Phosphorus (mg/kg)" required>
    <input type="number" name="potassium" placeholder="Potassium (mg/kg)" required>
    <select name="soil_type">
      <option value="sandy">Sandy</option>
      <option value="clay">Clay</option>
      <option value="loamy">Loamy</option>
    </select>
    <button type="submit">Detect & Save</button>
    <p>OR</p>
    <label>Detect from Soil Image:</label>
    <input type="file" name="soil_image" accept="image/*">
    <button type="button" id="autoDetectBtn" name="image_mode" value="1">Auto Detect</button>
  </form>
  <div id="result"></div>
  <script>
    document.getElementById('soilForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      document.getElementById('result').innerHTML = "Detecting...";
      const response = await fetch('api/soil_api.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.text();
      document.getElementById('result').innerHTML = data;
    });

    document.getElementById('autoDetectBtn').addEventListener('click', async function(e) {
      const form = document.getElementById('soilForm');
      const formData = new FormData(form);
      formData.append('image_mode', '1');

      document.getElementById('result').innerHTML = "Detecting from image...";
      const response = await fetch('api/soil_api.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.text();
      document.getElementById('result').innerHTML = data;
    });
  </script>
</body>

</html>