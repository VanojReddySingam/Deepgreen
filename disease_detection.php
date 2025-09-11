
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Plant Disease Detection</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'nav.php'; ?>
<h2>AI Plant Disease Detection</h2>
<form id="diseaseForm" enctype="multipart/form-data">
  <input type="file" name="leaf_image" accept="image/*" required>
  <button type="button" onclick="uploadImage()">Detect Disease</button>
  <div id="loading" style="display:none;">Analyzing...</div>
</form>
<div id="disease-result"></div>
<!-- Article Section -->   
<div class="article-container">   
  <article class="article-section">   
    <h2>Plant Disease</h2>   
    <img src="assets/plant-disease.jpg" alt="Plant Disease" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Plant diseases are caused by a variety of pathogens such as fungi, bacteria, viruses, and nematodes, which can significantly impact crop health and agricultural productivity. Common plant diseases include blight, rust, wilt, and mildew, each affecting different crops in distinct ways.
    </p>   
    <p>   
      Environmental factors like humidity, temperature, and poor soil conditions often contribute to the spread of these diseases. Effective management includes crop rotation, resistant seed varieties, and timely application of fungicides or biological controls.
    </p>   
  </article> <!-- Article Section -->  
  
  <article class="article-section">   
    <h2>Disease Detection</h2>   
    <img src="assets/disease-detection.jpg" alt="Disease Detection" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Early disease detection is essential to prevent crop loss and ensure timely intervention. Advances in technology, especially AI-powered image recognition and sensor-based systems, now allow farmers to detect plant diseases quickly and accurately in the field.
    </p>   
    <p>   
      These tools can analyze leaf patterns, discoloration, and other symptoms to identify diseases in real-time, even before visible signs appear. Combined with mobile apps and satellite data, disease detection technology is transforming modern farming into a more proactive and data-driven practice.
    </p>   
  </article>        
</div>

</body>
</html>
