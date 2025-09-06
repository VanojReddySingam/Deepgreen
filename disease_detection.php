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
</body>
</html>
