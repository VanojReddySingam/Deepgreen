<!DOCTYPE html>
<html>
<head>
  <title>Weather Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    /* Optional basic styles to better visualize forecast blocks */
    .weather-info, .forecast-day {
      border: 1px solid #ddd;
      padding: 15px;
      margin: 10px 0;
      border-radius: 8px;
      max-width: 300px;
    }
    .forecast-container {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .forecast-day img, .weather-info img {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<h2>Check Current Weather & 5‑Day Forecast</h2>

<form method="GET">
  <input type="text" name="city" placeholder="Enter City" required>
  <button type="submit">Check Weather</button>
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch data using cURL
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);

    // Handle curl errors
    if (curl_errno($ch)) {
        echo "<p style='color:red;'>cURL Error: " . curl_error($ch) . "</p>";
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    return $output;
}

if (isset($_GET['city']) && !empty(trim($_GET['city']))) {
    $apiKey = 'f9edcd06eae802eb06824393503c1367';  // Your active API key
    $city = urlencode(trim($_GET['city']));

    // URLs for current weather and forecast
    $currentUrl = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric";

    // Fetch current weather data
    $currentResponse = fetchData($currentUrl);
    $cur = json_decode($currentResponse);

    if ($cur && isset($cur->cod) && $cur->cod == 200) {
        $iconUrl = "https://openweathermap.org/img/wn/{$cur->weather[0]->icon}@2x.png";
        echo "<div class='weather-info'>";
        echo "<h4>Weather in " . htmlspecialchars($cur->name) . "</h4>";
        echo "<img src=\"$iconUrl\" alt='Weather icon'>";
        echo "<p>Temperature: " . htmlspecialchars($cur->main->temp) . "°C</p>";
        echo "<p>Humidity: " . htmlspecialchars($cur->main->humidity) . "%</p>";
        echo "<p>Description: " . ucfirst(htmlspecialchars($cur->weather[0]->description)) . "</p>";
        echo "</div>";
    } else {
        $err = isset($cur->message) ? ucfirst(htmlspecialchars($cur->message)) : "Unable to fetch current weather.";
        echo "<p style='color:red;'>Error: $err</p>";
    }

    // Fetch 5-day forecast data
    $forecastResponse = fetchData($forecastUrl);
    $fc = json_decode($forecastResponse);

    if ($fc && isset($fc->cod) && $fc->cod == "200") {
        echo "<h3>5‑Day Forecast</h3>";
        echo "<div class='forecast-container'>";

        $grouped = [];

        // Group forecast items by date (showing first forecast of each day)
        foreach ($fc->list as $item) {
            $date = substr($item->dt_txt, 0, 10);
            if (!isset($grouped[$date])) {
                $grouped[$date] = $item;
            }
        }

        // Display each day's forecast summary
        foreach ($grouped as $date => $day) {
            $icon = "https://openweathermap.org/img/wn/{$day->weather[0]->icon}@2x.png";
            echo "<div class='forecast-day'>";
            echo "<h4>$date</h4>";
            echo "<img src=\"$icon\" alt='Forecast icon'>";
            echo "<p>" . htmlspecialchars($day->main->temp) . "°C</p>";
            echo "<p>" . ucfirst(htmlspecialchars($day->weather[0]->description)) . "</p>";
            echo "</div>";
        }

        echo "</div>";
    } else {
        $err = isset($fc->message) ? ucfirst(htmlspecialchars($fc->message)) : "Unable to fetch forecast.";
        echo "<p style='color:red;'>Error: $err</p>";
    }
}
?>

</body>
</html>
