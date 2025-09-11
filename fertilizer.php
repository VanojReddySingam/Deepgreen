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
  <title>Fertilizer Recommendation</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'nav.php'; ?>
<h2>Fertilizer Recommendation</h2>
<form method="POST" action="fertilizer_result.php">
  <label>Choose Soil Type:</label>
  <select name="soil_type">
    <option value="sandy">Sandy</option>
    <option value="clay">Clay</option>
    <option value="loamy">Loamy</option>
  </select>
  <label>Choose Crop:</label>
  <select name="crop">
    <option value="wheat">Wheat</option>
    <option value="rice">Rice</option>
    <option value="maize">Maize</option>
  </select>
  <button type="submit">Get Recommendation</button>
</form>
<!-- Article Section -->   
<div class="article-container">   
  <article class="article-section">   
    <h2>Fertilizers</h2>   
    <img src="assets/fertilizers.jpg" alt="Fertilizers" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      Fertilizers are substances that provide essential nutrients to plants, promoting healthy growth and increased crop yields. They typically contain nitrogen (N), phosphorus (P), and potassium (K), along with secondary nutrients and micronutrients needed for plant development.
    </p>   
    <p>   
      The use of fertilizers enhances soil nutrient levels, especially in areas with nutrient-deficient soils. However, excessive or unbalanced fertilizer use can lead to soil degradation, water pollution, and negative impacts on the environment and human health.
    </p>   
  </article> <!-- Article Section -->  
  
  <article class="article-section">   
    <h2>Fertilizers in India</h2>   
    <img src="assets/fertilizers-india.jpg" alt="Fertilizers Used in India" style="width:100%; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />   
    <p>   
      In India, a variety of fertilizers are used to meet the diverse agricultural needs across regions. Commonly used chemical fertilizers include urea (nitrogen-rich), DAP (diammonium phosphate), MOP (muriate of potash), and complex NPK fertilizers that contain a mix of nutrients.
    </p>   
    <p>   
      The Indian government plays a key role in regulating fertilizer distribution and subsidies to ensure affordability for farmers. Recently, there has been a growing emphasis on balanced fertilization and integrated nutrient management to improve soil health and productivity.
    </p>   
  </article>        
</div>       

<article class="article-section2">   
  <h2>Natural Fertilizers</h2>   
  <img src="assets/natural-fertilizers.jpg" alt="Natural Fertilizers" />   
  <p>   
    Natural fertilizers, also known as organic fertilizers, are derived from plant, animal, or mineral sources. Examples include compost, manure, bone meal, and green manure, which enrich the soil with organic matter and essential nutrients without harming the environment.
  </p>   
  <p>   
    These fertilizers improve soil structure, increase microbial activity, and enhance water retention capacity. As sustainable farming practices gain momentum, natural fertilizers are increasingly being adopted as an eco-friendly alternative to chemical fertilizers.
  </p>   
</article>

</body>
</html>
