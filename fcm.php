<?php
require 'vendor/autoload.php';

use Google\Client;

// Path to your service account key file
$serviceAccountFile = 'path/to/your/service-account-file.json';

// FCM endpoint URL
$fcmUrl = 'https://fcm.googleapis.com/v1/projects/YOUR_PROJECT_ID/messages:send';

// The device token you want to send the notification to
$deviceToken = 'DEVICE_TOKEN_HERE';

// Notification data
$notification = [
    'title' => 'Your Notification Title',
    'body'  => 'Your Notification Body',
    'icon'  => 'your_icon_url' // Optional
];

// Create a Google Client
$client = new Client();
$client->setAuthConfig($serviceAccountFile);
$client->addScope('https://www.googleapis.com/auth/firebase.messaging');

// Get the OAuth 2.0 token
$token = $client->fetchAccessTokenWithAssertion()['access_token'];

// Message payload
$message = [
    'message' => [
        'token' => $deviceToken,
        'notification' => $notification
    ]
];

// Convert message to JSON
$jsonMessage = json_encode($message);

// Set up headers
$headers = [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
];

// Initialize curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fcmUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);

// Execute curl
$result = curl_exec($ch);

// Check for errors
if ($result === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
}

// Close curl
curl_close($ch);

// Print result
echo $result;
?>