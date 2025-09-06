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
</body>
</html>
