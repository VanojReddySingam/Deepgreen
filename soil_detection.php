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
  <!-- Article Section -->   
<div class="article-container">   
  <article class="article-section">   
    <h2>Soil</h2>   
    <img src="assets/soil.jpg" alt="Soil" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Soil is a vital natural resource that supports plant life and ecosystems. It is composed of minerals, organic matter, water, and air, forming the foundation for agriculture and forestry. The health of soil directly affects crop productivity, water retention, and biodiversity.  
    </p>   
    <p>   
      Different types of soil, such as sandy, clay, silt, and loam, have varying properties influencing their use and management. Proper soil management practices, including erosion control and organic amendments, are essential to maintain soil health and prevent degradation.   
    </p>   
  </article> <!-- Article Section -->  
  
  <article class="article-section">   
    <h2>Soil Fertility</h2>   
    <img src="assets/soil-fertility.jpg" alt="Soil Fertility" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Soil fertility refers to the ability of soil to supply essential nutrients to plants in adequate amounts for growth. It depends on factors like nutrient content, soil pH, organic matter, and microbial activity. Fertile soil ensures healthy crops and higher yields.  
    </p>   
    <p>   
      Maintaining soil fertility involves practices such as crop rotation, green manuring, balanced fertilization, and minimizing soil erosion. Sustainable soil fertility management plays a crucial role in long-term agricultural productivity and environmental conservation.  
    </p>   
  </article>        
</div>       

<article class="article-section2">   
  <h2>Soils in India</h2>   
  <img src="assets/soils-india.jpg" alt="Soils in India" />   
  <p>   
    India has diverse soil types ranging from alluvial, black, red, laterite, to desert soils, each supporting different agricultural activities. Alluvial soils are highly fertile and found in the Indo-Gangetic plains, while black soils are rich in clay and ideal for cotton cultivation.  
  </p>   
  <p>   
    Soil management in India faces challenges such as erosion, salinity, and nutrient depletion due to intensive farming. Efforts to improve soil health include promoting organic farming, soil testing, and adopting region-specific soil conservation techniques to enhance agricultural sustainability.  
  </p>   
</article>

</body>

</html>