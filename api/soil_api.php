<?php
// Farmers AI Assistant 🚜
// Expo quick-demo version (API key inline, no env)

$apiKey = "AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo"; // ⚠️ paste your Gemini API key here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // === CASE 1: IMAGE UPLOAD ===
    if (isset($_POST['image_mode']) && $_POST['image_mode'] == '1' && isset($_FILES['soil_image'])) {
        $imagePath = $_FILES['soil_image']['tmp_name'];
        $imageBase64 = base64_encode(file_get_contents($imagePath));

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";
        $payload = [
            "contents" => [[
                "parts" => [
                    ["text" => "Analyze this soil image. Identify soil type, crop suggestions, and fertilizer recommendations."],
                    ["inlineData" => ["mimeType" => "image/jpeg", "data" => $imageBase64]]
                ]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $raw = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($raw, true);
        $output = $res['candidates'][0]['content']['parts'][0]['text'] ?? "AI could not analyze the image.";
        echo "<h3>Gemini Soil Analysis (Image Mode)</h3><p>" . nl2br(htmlspecialchars($output)) . "</p>";
        exit;
    }

    // === CASE 2: MANUAL SOIL PARAMETERS ===
    $ph = $_POST['ph'] ?? '';
    $nitrogen = $_POST['nitrogen'] ?? '';
    $phosphorus = $_POST['phosphorus'] ?? '';
    $potassium = $_POST['potassium'] ?? '';
    $soil_type = $_POST['soil_type'] ?? '';

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";
    $payload = [
        "contents" => [[
            "parts" => [[
                "text" => "Soil data: pH=$ph, Nitrogen=$nitrogen, Phosphorus=$phosphorus, Potassium=$potassium, Soil Type=$soil_type. 
                Suggest suitable crops, fertilizers, and pesticides in a simple farmer-friendly way."
            ]]
        ]]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $raw = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($raw, true);
    $output = $res['candidates'][0]['content']['parts'][0]['text'] ?? "AI could not generate recommendations.";
    echo "<h3>Gemini Soil Analysis (Manual Input)</h3><p>" . nl2br(htmlspecialchars($output)) . "</p>";
    exit;
}

echo "Invalid request.";
exit;
