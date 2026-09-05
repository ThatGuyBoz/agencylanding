<?php
header('Access-Control-Allow-Origin: https://yourdomain.com');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email']);
    exit;
}

$apiKey  = 'your_api_key_here';
$listId  = 'your_list_id_here';
$dc      = 'us21'; // the part after the dash in your API key

$url = "https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode("anystring:{$apiKey}")
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'email_address' => $email,
        'status'        => 'subscribed'
    ])
]);

$response = curl_exec($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($status === 200) {
    echo json_encode(['ok' => true]);
} elseif (($result['title'] ?? '') === 'Member Exists') {
    http_response_code(409);
    echo json_encode(['error' => 'Already subscribed']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Subscription failed']);
}