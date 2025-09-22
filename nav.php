<?php
echo '<nav>
  <a href="index.php">Home</a>';

// Show Login next to Home if user is not signed in
if (!isset($_SESSION['username'])) {
    echo '<a href="login.php">Login</a>';
}

// Other main links
echo '
  <a href="soil_detection.php">Soil Detection</a>
  <a href="fertilizer.php">Fertilizer Advisor</a>
  <a href="disease_detection.php">Disease Detection</a>
  <a href="weather.php">Weather</a>
  <a href="crop.php">crop Advisory</a>'
  ;

// Show Logout at the end if user is signed in
if (isset($_SESSION['username'])) {
    echo '<a href="logout.php">Logout</a>';
}

echo '</nav>';
?>
