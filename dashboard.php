<?php
// Start session
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include DB connection
include 'db.php';

// Check DB connection
if(!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Use session ID or default to 1
$user_id = $_SESSION['id'] ?? 1;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Crop Advisory</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'nav.php'; ?>

<h2>Crop Suitability Guide & Soil Health Tips</h2>

<?php
$result = mysqli_query($conn, "SELECT soil_type FROM soils WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");

if(!$result) {
    echo "<p>Error fetching soil data: " . mysqli_error($conn) . "</p>";
} elseif($row = mysqli_fetch_assoc($result)) {

    $soil = $row['soil_type'];
    echo "<h4>Best Crops for $soil Soil</h4>";

    $crops = mysqli_query($conn, "SELECT name FROM crops WHERE suitable_soil='$soil'");
    if($crops && mysqli_num_rows($crops) > 0){
        echo "<ul>";
        while($c = mysqli_fetch_assoc($crops)) echo "<li>".$c['name']."</li>";
        echo "</ul>";
    } else {
        echo "<p>No suitable crops found for $soil soil.</p>";
    }

    echo "<h4>Fertilizer Recommendations</h4>";
    $fert = mysqli_query($conn, "SELECT c.name, f.recommendation 
                                  FROM fertilizers f 
                                  JOIN crops c ON f.crop_id=c.id 
                                  WHERE f.soil_type='$soil'");
    if($fert && mysqli_num_rows($fert) > 0){
        while($f = mysqli_fetch_assoc($fert)) echo "<b>{$f['name']}:</b> {$f['recommendation']}<br>";
    } else {
        echo "<p>No fertilizer recommendations found.</p>";
    }

    echo "<h4>Soil Health Tips</h4>
          <ul>
            <li>Rotate crops regularly.</li>
            <li>Use compost to enrich soil.</li>
            <li>Avoid overwatering.</li>
          </ul>";

} else {
    echo "<p>No soil data found for your account.</p>";
}
?>

</body>
</html>
  