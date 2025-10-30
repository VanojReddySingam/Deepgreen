<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Handle AI Image Detection
if (isset($_FILES['soil_image']) && $_FILES['soil_image']['error'] === UPLOAD_ERR_OK) {
    $image_path = "uploads/" . uniqid() . "_" . basename($_FILES['soil_image']['name']);
    if (!is_dir("uploads")) mkdir("uploads", 0777, true);
    move_uploaded_file($_FILES['soil_image']['tmp_name'], $image_path);

    $apiKey ="AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo";
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

    $mimeType = mime_content_type($image_path);
    $imageBase64 = base64_encode(file_get_contents($image_path));

    // ✅ Strict prompt for extremely short output
    $prompt = <<<EOT
Analyze this soil image and return ONLY JSON in the EXACT format:
{"soil_type":"", "fertility":"", "suggested_fertilizer":""}.
Use **single words or very short phrases only**.
Do NOT add any extra sentences, explanations, bullet points, or notes.
Values must be concise (max 2–3 words each).
EOT;

    $payload = [
        "contents" => [[
            "parts" => [
                ["text" => $prompt],
                [
                    "inlineData" => [
                        "mimeType" => $mimeType,
                        "data" => $imageBase64
                    ]
                ]
            ]
        ]]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 20
    ]);

    $raw = curl_exec($ch);
    curl_close($ch);

    // Extract JSON
    preg_match('/\{.*\}/s', $raw, $matches);
    $aiData = isset($matches[0]) ? json_decode($matches[0], true) : null;

    if (is_array($aiData)) {
        echo "<div class='card'>
            <h3>Soil Result (AI)</h3>
            <p><b>Soil:</b> " . esc($aiData['soil_type'] ?? '-') . "</p>
            <p><b>Fertility:</b> " . esc($aiData['fertility'] ?? '-') . "</p>
            <p><b>Fertilizer:</b> " . esc($aiData['suggested_fertilizer'] ?? '-') . "</p>
        </div>";
    } else {
        echo "<span style='color:red;'>AI failed. Raw output: " . esc($raw) . "</span>";
    }
    exit;
}

echo "<span style='color:red;'>No valid data provided.</span>";
?>
