<?php
session_start();
header("Content-Type: application/json");

// Your Gemini API key
$apiKey = "AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo";

// Check if file is uploaded
if (!isset($_FILES['leaf_image'])) {
  echo json_encode(["success" => false, "error" => "No image uploaded"]);
  exit;
}

// Ensure uploads folder exists
if (!is_dir("uploads")) mkdir("uploads", 0777, true);

// Save uploaded image
$image_path = "uploads/" . uniqid() . "_" . basename($_FILES['leaf_image']['name']);
if (!move_uploaded_file($_FILES['leaf_image']['tmp_name'], $image_path)) {
  echo json_encode(["success" => false, "error" => "Failed to save uploaded image"]);
  exit;
}

// Detect MIME type dynamically
$mimeType = mime_content_type($image_path);

// Encode image to base64
$imageBase64 = base64_encode(file_get_contents($image_path));

// Prepare Gemini Vision payload
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";
$payload = [
  "contents" => [[
    "parts" => [
      [
        "text" => "Analyze this plant leaf image. 
Identify the disease (if any), confidence, symptoms, treatment, and prevention. 
Return ONLY JSON in this format:
{
  \"disease\": \"...\",
  \"confidence\": \"...\",
  \"symptoms\": \"...\",
  \"treatment\": \"...\",
  \"prevention\": \"...\"
}"
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

// Send request to Gemini API
$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
  CURLOPT_POSTFIELDS => json_encode($payload)
]);
$raw = curl_exec($ch);
curl_close($ch);

// Debug log
file_put_contents('debug.json', $raw);

// Decode AI response safely
$res = json_decode($raw, true);
$aiText = $res['candidates'][0]['content']['parts'][0]['text'] ?? '';

// Extract JSON from AI response (in case it includes extra text)
preg_match('/\{.*\}/s', $aiText, $matches);
$aiData = isset($matches[0]) ? json_decode($matches[0], true) : null;

// Return response
if (is_array($aiData)) {
  echo json_encode([
    "success" => true,
    "image_path" => $image_path,
    "disease" => $aiData['disease'] ?? 'Unknown',
    "confidence" => $aiData['confidence'] ?? 'N/A',
    "symptoms" => $aiData['symptoms'] ?? 'N/A',
    "treatment" => $aiData['treatment'] ?? 'N/A',
    "prevention" => $aiData['prevention'] ?? 'N/A'
  ]);
} else {
  echo json_encode([
    "success" => false,
    "error" => "AI response not valid",
    "raw" => $aiText,
    "image_path" => $image_path
  ]);
}
exit;
