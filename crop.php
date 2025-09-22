<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Crop Advisory System</title>
  <link rel="stylesheet" href="style.css"> <!-- Link your existing CSS -->
</head>
<body>
  <section class="crop-section">
    <h2>Crop Advisory System</h2>
    <form id="cropForm">
      <label for="soil_type">Soil Type:</label>
      <select id="soil_type" name="soil_type" required>
        <option value="">--Select--</option>
        <option value="Black Soil">Black Soil</option>
        <option value="Red Soil">Red Soil</option>
        <option value="Brown Soil">Brown Soil</option>
        <option value="Laterite Soil">Laterite Soil</option>
        <option value="Alluvial Soil">Alluvial Soil</option>
        <option value="Clay Soil">Clay Soil</option>
      </select>

      <label for="season">Season:</label>
      <select id="season" name="season" required>
        <option value="">--Select--</option>
        <option value="Summer">Summer</option>
        <option value="Monsoon">Monsoon</option>
        <option value="Winter">Winter</option>
      </select>

      <label for="water_availability">Water Availability:</label>
      <select id="water_availability" name="water_availability" required>
        <option value="">--Select--</option>
        <option value="Low">Low</option>
        <option value="Medium">Medium</option>
        <option value="High">High</option>
        <option value="Rain Dependent">Rain Dependent</option>
      </select>

      <label for="area_size">Area Size (in acres):</label>
      <input type="number" id="area_size" name="area_size" min="0.1" step="0.1" required>

      <button type="submit">Get Crop Advice</button>
      <button type="button" class="back-button" onclick="window.location.href='index.php'">Back</button>

    </form>

    <div id="crop-result"></div>
  </section>

  <script>
    document.getElementById('cropForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const resultDiv = document.getElementById('crop-result');
      resultDiv.style.opacity = 1;
      resultDiv.innerHTML = "Processing... please wait.";

      const formData = {
        soil_type: document.getElementById('soil_type').value,
        season: document.getElementById('season').value,
        water_availability: document.getElementById('water_availability').value,
        area_size: document.getElementById('area_size').value
      };

      try {
        const response = await fetch('process.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.error) {
          resultDiv.innerHTML = `<span class="error">Error: ${data.error}</span>`;
        } else {
          let text = data.advice;

          // Replace AI crop name markdown __**Crop**__ with bold + underline
          text = text.replace(/__\*\*(.*?)\*\*__/g, '<b><u>$1</u></b>');

          // Split by double newlines (each crop block)
          const blocks = text.split(/\n\n+/);
          let finalHTML = '';
          blocks.forEach(block => {
            finalHTML += block.replace(/\n/g, '<br>') + '<br><br>';
          });

          resultDiv.innerHTML = finalHTML;
        }
      } catch (err) {
        resultDiv.innerHTML = `<span class="error">Request failed: ${err.message}</span>`;
      }
    });
  </script>
</body>
</html>
