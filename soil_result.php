<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// Function to escape output
function esc($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Handle Manual Input
if (isset($_POST['ph'], $_POST['nitrogen'], $_POST['phosphorus'], $_POST['potassium'], $_POST['soil_type']) && !isset($_FILES['soil_image'])) {
  $ph = floatval($_POST['ph']);
  $nitrogen = intval($_POST['nitrogen']);
  $phosphorus = intval($_POST['phosphorus']);
  $potassium = intval($_POST['potassium']);
  $soil_type = esc($_POST['soil_type']);

  // Example simple logic to suggest fertilizer
  $fertilizer = "Balanced NPK";
  if ($nitrogen < 50) $fertilizer = "Nitrogen-rich fertilizer";
  elseif ($phosphorus < 30) $fertilizer = "Phosphorus-rich fertilizer";
  elseif ($potassium < 40) $fertilizer = "Potassium-rich fertilizer";

  echo "
    <div class='card'>
        <h3>Soil Detection Result</h3>
        <p><b>Soil Type:</b> $soil_type</p>
        <p><b>pH:</b> $ph</p>
        <p><b>Nitrogen:</b> $nitrogen mg/kg</p>
        <p><b>Phosphorus:</b> $phosphorus mg/kg</p>
        <p><b>Potassium:</b> $potassium mg/kg</p>
        <h4>Suggested Fertilizer:</h4>
        <p>$fertilizer</p>
    </div>
    ";
  exit;
}

// Handle AI Image Detection
if (isset($_FILES['soil_image']) && $_FILES['soil_image']['error'] === UPLOAD_ERR_OK) {
  $image_path = "uploads/" . uniqid() . "_" . basename($_FILES['soil_image']['name']);
  if (!is_dir("uploads")) mkdir("uploads", 0777, true);
  move_uploaded_file($_FILES['soil_image']['tmp_name'], $image_path);

  // AI API Key (replace with your real key)
  $apiKey = "AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo";
  $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

  // Encode image to base64
  $mimeType = mime_content_type($image_path);
  $imageBase64 = base64_encode(file_get_contents($image_path));

  // AI request payload
  $payload = [
    "contents" => [[
      "parts" => [
        [
          "text" => "Analyze this soil image. Detect soil type, fertility status, and suggest fertilizer,give in short sentences. Return ONLY JSON in this format: {\"soil_type\": \"...\",\"fertility\": \"...\",\"suggested_fertilizer\": \"...\"}"
        ],
        [
          "inlineData" => [
            "mimeType" => $mimeType,
            "data" => $imageBase64
          ]
        ]
      ]
    ]]
  ];

  // Call AI API
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30
  ]);
  $raw = curl_exec($ch);
  if (curl_errno($ch)) {
    echo "<span style='color:red;'>AI request error: " . esc(curl_error($ch)) . "</span>";
    exit;
  }
  curl_close($ch);

  // Decode AI response
  $res = json_decode($raw, true);
  $aiText = $res['candidates'][0]['content']['parts'][0]['text'] ?? '';
  preg_match('/\{.*\}/s', $aiText, $matches);
  $aiData = isset($matches[0]) ? json_decode($matches[0], true) : null;

  if (is_array($aiData)) {
    $soil_type = esc($aiData['soil_type'] ?? 'Unknown');
    $fertility = esc($aiData['fertility'] ?? 'Unknown');
    $suggested_fertilizer = esc($aiData['suggested_fertilizer'] ?? 'Balanced NPK');

    echo "
        <div class='card'>
            <h3>Soil Detection Result (AI)</h3>
            <img src='$image_path' alt='Soil Image'>
            <p><b>Detected Soil Type:</b> $soil_type</p>
            <p><b>Fertility Status:</b> $fertility</p>
            <h4>Suggested Fertilizer:</h4>
            <p>$suggested_fertilizer</p>
        </div>
        ";
  } else {
    echo "<span style='color:red;'>AI analysis failed. Raw output: " . esc($aiText) . "</span>";
  }

  exit;
}

// If nothing matches
echo "<span style='color:red;'>No valid data provided.</span>";
