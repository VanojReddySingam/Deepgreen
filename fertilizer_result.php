<?php
include 'db.php';
$soil_type = $_POST['soil_type'];
$crop = $_POST['crop'];
$result = mysqli_query($conn, "SELECT recommendation FROM fertilizers WHERE soil_type='$soil_type' AND crop_id=(SELECT id FROM crops WHERE name='$crop')");
if($row = mysqli_fetch_assoc($result)) {
  echo "<h4>Fertilizer Recommendation for $crop on $soil_type:</h4><p>{$row['recommendation']}</p>";
} else {
  echo "<p>No data found. Please consult an expert.</p>";
}
echo "<a href='fertilizer.php'>Back</a>";
?>
