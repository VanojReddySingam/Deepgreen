<!DOCTYPE html>
<html>
<head>
  <title>Weather Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'nav.php'; ?>

<h2>Check Current Weather</h2>

<form method="GET">
  <input type="text" name="city" placeholder="Enter City" required>
  <button type="submit">Check Weather</button>
</form>

<?php
if (isset($_GET['city'])) {
  $apiKey = '3626e126fd884ef73217afd74a08ed1a'; // Replace with your actual API key
  $city = urlencode($_GET['city']); // Sanitize user input for URL
  $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response);

  if ($data && isset($data->main)) {
    echo "<div class='weather-info'>";
    echo "<h4>Weather in {$data->name}</h4>";
    echo "<p>Temperature: {$data->main->temp}°C</p>";
    echo "<p>Humidity: {$data->main->humidity}%</p>";
    echo "<p>Description: " . ucfirst($data->weather[0]->description) . "</p>";
    echo "</div>";
  } else {
    echo "<p>Weather info not available. Please check the city name and try again.</p>";
  }
}
?>

</body>
</html>
