<!DOCTYPE html>
<html>
<head>
  <title>Weather Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<h2>Check Current Weather</h2>
<form method="GET">
  <input type="text" name="city" placeholder="Enter City" required>
  <button type="submit">Check Weather</button>
</form>
<?php
if(isset($_GET['city'])) {
  $apiKey = "YOUR_OPENWEATHERMAP_API_KEY";
  $city = urlencode($_GET['city']);
  $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  $response = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($response);
  if($data && isset($data->main)) {
    echo "<h4>Weather in $data->name</h4>";
    echo "<p>Temperature: {$data->main->temp}°C</p>";
    echo "<p>Humidity: {$data->main->humidity}%</p>";
    echo "<p>Description: " . ucfirst($data->weather->description) . "</p>";
  } else {
    echo "<p>Weather info not available!</p>";
  }
}
?>
</body>
</html>
