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
<?php include 'nav.php'; ?>
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
      <label for="crop_season">Crop Season:</label>
<select id="crop_season" name="crop_season" required>
  <option value="">--Select--</option>
  <option value="Rabi">Rabi</option>
  <option value="Kharif">Kharif</option>
  <option value="Zaid">Zaid</option>
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
  area_size: document.getElementById('area_size').value,
  crop_season: document.getElementById('crop_season').value   // new field
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
  <div class="article-container">   
  <article class="article-section">   
    <h2>Crops</h2>   
    <img src="assets/cropss.jpg" alt="Crops" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Crops refer to cultivated plants grown for food, fiber, fuel, or other agricultural purposes. They are an essential part of human life and play a major role in the economy, nutrition, and daily sustenance.
    </p>   
    <p>   
      Understanding different crop types, their growth requirements, and seasonal patterns helps farmers maximize yield and maintain soil health. Technological advances in agriculture have also improved crop management and production efficiency.
    </p>   
  </article> <!-- Article Section -->  
   
  <article class="article-section">   
    <h2>Cropping in India</h2>   
    <img src="assets/cropping-india.jpg" alt="Cropping in India" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      India has diverse cropping patterns due to its varied climate, soil types, and geography. Major crops include rice, wheat, maize, pulses, cotton, and sugarcane, with regional variations influencing what is grown.
    </p>   
    <p>   
      Cropping practices in India are influenced by the monsoon, irrigation availability, and traditional agricultural methods. Efficient crop rotation, mixed cropping, and modern farming techniques help improve yields and sustain agricultural productivity.
    </p>   
  </article>        
</div>

</body>
</html>