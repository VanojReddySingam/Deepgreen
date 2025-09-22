<?php
header('Content-Type: application/json');

// ✅ Gemini API key & URL
$apiKey = 'AIzaSyDPkKH6qBKtSWkK5ehS0lSphoAkS4pHfmo';
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

// Rate limiting (20 requests/min per IP)
define('MAX_REQUESTS_PER_MINUTE', 20);
$ip = $_SERVER['REMOTE_ADDR'];
$rateFile = sys_get_temp_dir() . '/cropadv_rate_' . md5($ip) . '.json';
$rate = ['count' => 0, 'ts' => time()];
if (file_exists($rateFile)) {
    $rate = json_decode(file_get_contents($rateFile), true) ?: ['count'=>0,'ts'=>time()];
}
if (time() - $rate['ts'] < 60) {
    if ($rate['count'] >= MAX_REQUESTS_PER_MINUTE) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests']);
        exit;
    }
    $rate['count']++;
} else {
    $rate = ['count' => 1, 'ts' => time()];
}
file_put_contents($rateFile, json_encode($rate));

// Read input
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$required = ['soil_type','season','water_availability','area_size'];
foreach ($required as $r) {
    if (empty($data[$r])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing field: $r"]);
        exit;
    }
}

$input = array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);

// Build AI prompt
// Build prompt
$prompt = "You are an expert agronomist. A farmer gave these details:\n";

// Loop through input and format
foreach ($input as $k => $v) { 
    $prompt .= ucfirst(str_replace('_', ' ', $k)) . ": $v\n"; 
}

// Append instructions for AI response
$prompt .= "\nPlease suggest the top 5 suitable crops.\n";
$prompt .= "Format the answer in HTML as follows:\n";
$prompt .= "<b><u>Crop Name</u></b><br>\n";
$prompt .= "- Reason for suitability (2–3 lines)<br>\n";
$prompt .= "- Planting season and care tips (2–3 lines)<br>\n";
$prompt .= "- Approximate yield or market benefit (1–2 lines)<br><br>\n";
$prompt .= "Keep the response concise but clear (about 20–25 lines total).";

// Gemini payload
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

$url = $apiUrl . '?key=' . $apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$err = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err) {
    http_response_code(502);
    echo json_encode(['error' => 'AI request failed', 'detail' => $err]);
    exit;
}
if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code(502);
    echo json_encode(['error' => 'AI returned HTTP ' . $httpCode, 'raw' => $response]);
    exit;
}

$respJson = json_decode($response, true);
$outputText = $respJson['candidates'][0]['content']['parts'][0]['text'] ?? $response;

echo json_encode(['ok' => true, 'advice' => $outputText]);
?>
