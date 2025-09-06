<?php
session_start();
include 'db.php';
$user_id = $_SESSION['id'] ?? 1; // For demo, default user ID 1
if(isset($_POST['image_mode'])) {
  $image_path = "uploads/" . uniqid() . "_" . $_FILES['soil_image']['name'];
  move_uploaded_file($_FILES['soil_image']['tmp_name'], $image_path);
  $ph = 6.7; $nitrogen = 100; $phosphorus = 30; $potassium = 250; $soil_type = "loamy";
} else {
  $ph = $_POST['ph'];
  $nitrogen = $_POST['nitrogen'];
  $phosphorus = $_POST['phosphorus'];
  $potassium = $_POST['potassium'];
  $soil_type = $_POST['soil_type'];
}
$date = date("Y-m-d");
mysqli_query($conn, "INSERT INTO soils (user_id,soil_type,ph,nitrogen,phosphorus,potassium,date_recorded)
 VALUES('$user_id','$soil_type','$ph','$nitrogen','$phosphorus','$potassium','$date')");
echo "<script>alert('Soil data saved!'); location.href='dashboard.php';</script>";
?>
