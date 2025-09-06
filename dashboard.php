<!DOCTYPE html>
<html>
<head>
  <title>Crop Advisory</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<h2>Crop Suitability Guide & Soil Health Tips</h2>
<?php
include 'db.php';
$user_id = $_SESSION['id'] ?? 1;
$result = mysqli_query($conn, "SELECT soil_type FROM soils WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");
if($row = mysqli_fetch_assoc($result)){
  $soil = $row['soil_type'];
  echo "<h4>Best Crops for $soil Soil</h4>";
  $crops = mysqli_query($conn, "SELECT name FROM crops WHERE suitable_soil='$soil'");
  echo "<ul>";
  while($c = mysqli_fetch_assoc($crops)) echo "<li>".$c['name']."</li>";
  echo "</ul><h4>Fertilizer Recommendations</h4>";
  $fert = mysqli_query($conn, "SELECT c.name, f.recommendation FROM fertilizers f JOIN crops c ON f.crop_id=c.id WHERE f.soil_type='$soil'");
  while($f = mysqli_fetch_assoc($fert)) echo "<b>{$f['name']}:</b> {$f['recommendation']}<br>";
  echo "<h4>Soil Health Tips</h4><ul><li>Rotate crops regularly.</li><li>Use compost to enrich soil.</li><li>Avoid overwatering.</li></ul>";
}
?>
</body>
</html>
