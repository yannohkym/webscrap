<?php


// Install the PHP cURL library if it is not already installed
//This will work if the library is installed
if (!function_exists('curl_init')) {
    die('cURL is not installed. Install it and try again.');
}

// Router login URL and connected devices URL
$login_url = 'http://router.example.com/login';
$devices_url = 'http://router.example.com/devices';


// Router login credentials
$username = 'admin';
$password = 'password';

// New SSID for the WiFi
$ssid = 'MyNewWiFi';
$ssid_url ='http://router.example.com/ssid/url';
// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$username&password=$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Send login request
$response = curl_exec($ch);

// Get HTTP status code
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL session
curl_close($ch);

// Check if login was successful
if ($status_code == 200) {
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $devices_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send request to get list of connected devices
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Parse HTML to extract list of connected devices
    $dom = new DOMDocument();
    $dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    $devices = $xpath->query('//table[@id="connected-devices"]/tr');

    // Print list of connected devices
    foreach ($devices as $device) {
        echo $device->nodeValue . "\n";
    }

    // Change SSID of WiFi
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $ssid_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "ssid=$ssid");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send request to change SSID
    $response = curl_exec($ch);


    curl_close($ch);
} else {
    // incase of login failed.
    echo 'Login was unsuccessful';
}


