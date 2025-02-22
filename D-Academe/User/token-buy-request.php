<?php
include('dbconnection.php');
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    die(json_encode(["status" => "error", "message" => "User not logged in."]));
}

$user_id = intval($_SESSION['id']);
$user_name = htmlspecialchars($_SESSION['name'] ?? '');
$user_email = htmlspecialchars($_SESSION['email'] ?? '');

// Read JSON input for token purchase (e.g., number of tokens)
$data = json_decode(file_get_contents("php://input"), true);

// Validate token quantity from either POST/GET requests
$tokenQuantity = intval($data['tokenQuantity'] ?? ($_GET['token_amt'] ?? 0));
if ($tokenQuantity <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid token quantity"]));
}

// Set token price (this could be dynamic or fetched from a database)
$tokenPricePerUnit = 100; // Example: 100 NRS per token
$totalPrice = $tokenQuantity * $tokenPricePerUnit;

// Ensure valid price
if ($totalPrice <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid total price for tokens"]));
}

// Generate a unique order ID
$order_id = uniqid();
$_SESSION['order_id'] = $order_id;

// Use the correct Khalti secret key (for sandbox during testing)
$khalti_secret_key = "test_secret_key"; // Replace with your test secret key

// Prepare Khalti API Payment Request
$postFields = json_encode([
    "return_url" => "http://localhost/D-Academe/D-Academe/user/response-pay.php", // Adjust for your environment
    "website_url" => "http://localhost/D-Academe/D-Academe/user/?page=buy-token",
    "amount" => $totalPrice * 100, // Convert to paisa (e.g., 1000 NRS = 100000 paisa)
    "purchase_order_id" => $order_id,
    "purchase_order_name" => "Token Purchase: $tokenQuantity tokens",
    "customer_info" => [
        "name" => $user_name,
        "email" => $user_email,
    ]
]);

// Initiate cURL for sending the API request to Khalti
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/', // For sandbox, change the URL accordingly
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_HTTPHEADER => [
        "Authorization: Key $khalti_secret_key",
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);
$curlError = curl_error($curl);
curl_close($curl);

if ($curlError) {
    die(json_encode(["status" => "error", "message" => "Payment request failed: $curlError"]));
}

// Decode API response from Khalti
$responseArray = json_decode($response, true);

// Check if payment URL is provided
if (isset($responseArray['payment_url'])) {
    // Redirect the user to Khalti's payment URL
    echo "<script>window.location.href = '" . $responseArray['payment_url'] . "';</script>";
    exit();
} else {
    // Handle errors and provide useful debugging information
    echo "<pre>API Response: ";
    print_r($responseArray);
    echo "</pre>";
    die("❌ Payment initiation failed.");
}
?>
