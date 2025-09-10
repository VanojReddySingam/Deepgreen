<?php
session_start();

// 🔑 Keys
define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY');
define('SEARCH_API_KEY', 'YOUR_GOOGLE_SEARCH_API_KEY');
define('SEARCH_CX', '');

// Gemini API
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . GEMINI_API_KEY);

// User input
$soil_type = $_POST['soil_type'] ?? '';
$crop = $_POST['crop'] ?? '';

echo "<h4>Fertilizer Recommendation for $crop on $soil_type:</h4>";

if (empty($soil_type) || empty($crop)) {
    echo "<p>Please provide both soil type and crop.</p>";
    exit;
}

// Step 1: Build search query
$query = urlencode("Best fertilizers for $crop in $soil_type soil 2025");

// Step 2: Call Google Custom Search API
$search_url = "https://www.googleapis.com/customsearch/v1?q=$query&key=" . SEARCH_API_KEY . "&cx=" . SEARCH_CX;
$search_resp = file_get_contents($search_url);
$search_data = json_decode($search_resp, true);

// Extract top 5 results
$fertilizer_info = [];
if (isset($search_data['items'])) {
    foreach (array_slice($search_data['items'], 0, 5) as $item) {
        $fertilizer_info[] = $item['title'] . " - " . $item['snippet'];
    }
}
if (empty($fertilizer_info)) {
    echo "<p>No fertilizers found from web search.</p>";
    exit;
}

// Step 3: Ask Gemini to summarize into fertilizer table
$prompt = "You are an agricultural advisor. Based on these search results for best fertilizers for {$crop} in {$soil_type} soil:\n\n"
    . implode("\n", $fertilizer_info) .
    "\n\nReturn ONLY JSON in this format:\n[
      {\"fertilizer\":\"Brand name / chemical\",\"nutrients\":\"Key nutrients\",\"usage\":\"How/when to apply\"}
    ]";

$data = json_encode([
    'contents' => [[
        'parts' => [['text' => $prompt]]
    ]]
]);

$ch = curl_init(GEMINI_API_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data
]);
$response = curl_exec($ch);
curl_close($ch);

$res = json_decode($response, true);
$aiText = $res['candidates'][0]['content']['parts'][0]['text'] ?? '';
$fertilizers = json_decode($aiText, true);

// Step 4: Display in table
if (is_array($fertilizers)) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Fertilizer</th><th>Nutrients</th><th>Usage</th></tr>";
    foreach ($fertilizers as $f) {
        echo "<tr>
                <td>" . htmlspecialchars($f['fertilizer']) . "</td>
                <td>" . htmlspecialchars($f['nutrients']) . "</td>
                <td>" . htmlspecialchars($f['usage']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Could not parse AI output.</p><pre>" . htmlspecialchars($aiText) . "</pre>";
}
echo "<a href='fertilizer.php'>Back</a>";
