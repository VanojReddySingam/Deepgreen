<?php
session_start();

// 🔑 Keys (⚠️ move to env variables in production)
define('GEMINI_API_KEY', 'AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo');
define('SEARCH_API_KEY', 'AIzaSyB1xITWvnL472nladDnidC0LynQDZRPdbw');
define('SEARCH_CX', 'e7a84b2a3fe664a75');

// Gemini API
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . GEMINI_API_KEY);

// User input
$soil_type = $_POST['soil_type'] ?? '';
$crop = $_POST['crop'] ?? '';

echo "<h4>Fertilizer Recommendation for $crop on $soil_type soil:</h4>";

if (empty($soil_type) || empty($crop)) {
    echo "<p>Please provide both soil type and crop.</p>";
    exit;
}

// Step 1: Build search query
$query = urlencode("Best fertilizers for $crop in $soil_type soil 2025");

// Step 2: Call Google Custom Search API
$search_url = "https://www.googleapis.com/customsearch/v1?q=$query&key=" . SEARCH_API_KEY . "&cx=" . SEARCH_CX;
$search_resp = @file_get_contents($search_url);

if ($search_resp === false) {
    echo "<p>Error fetching data from Google Search API.</p>";
    exit;
}

$search_data = json_decode($search_resp, true);

// Extract top 5 results
$fertilizer_info = [];
if (!empty($search_data['items'])) {
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
    "\n\nIf the search results are unclear, use your own knowledge. 
Return ONLY JSON (at least 3 fertilizers) in this format:\n[
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
if (curl_errno($ch)) {
    echo "<p>Error calling Gemini API: " . curl_error($ch) . "</p>";
    curl_close($ch);
    exit;
}
curl_close($ch);

$res = json_decode($response, true);

// Extract AI text safely
$aiText = '';
if (isset($res['candidates'][0]['content']['parts'][0]['text'])) {
    $aiText = $res['candidates'][0]['content']['parts'][0]['text'];
}

// Debug: uncomment if needed
// echo "<pre>RAW AI OUTPUT:\n" . htmlspecialchars($aiText) . "</pre>";

// Clean possible ```json ... ```
$aiText = trim($aiText);
$aiText = preg_replace('/```json|```/', '', $aiText);

// Try to extract JSON inside [ ... ]
if (preg_match('/\[(.*)\]/s', $aiText, $matches)) {
    $aiText = "[" . $matches[1] . "]";
}

// Convert to array
$fertilizers = json_decode($aiText, true);

// Step 4: Fallback if AI returns nothing
if (empty($fertilizers) || !is_array($fertilizers)) {
    $fertilizers = [
        [
            "fertilizer" => "Urea",
            "nutrients" => "Nitrogen (46%)",
            "usage" => "Apply during sowing and early growth stages"
        ],
        [
            "fertilizer" => "DAP (Diammonium Phosphate)",
            "nutrients" => "Nitrogen (18%), Phosphorus (46%)",
            "usage" => "Apply at sowing for strong root development"
        ],
        [
            "fertilizer" => "Muriate of Potash (MOP)",
            "nutrients" => "Potassium (60%)",
            "usage" => "Apply before flowering to improve yield and disease resistance"
        ]
    ];
}

// Step 5: Display in table
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

echo "<br><a href='fertilizer.php'>Back</a>";
