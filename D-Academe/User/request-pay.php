<?php

include('dbconnection.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    die(json_encode(["status" => "error", "message" => "User not logged in."]));
}

$user_id = intval($_SESSION['id']);
$user_name = htmlspecialchars($_SESSION['name'] ?? '');
$user_email = htmlspecialchars($_SESSION['email'] ?? '');

// Read JSON input for POST request
$json_input = file_get_contents("php://input");
$data = json_decode($json_input, true);

// Validate course ID (from POST or GET)
$courseId = isset($data['courseId']) ? intval($data['courseId']) : (isset($_GET['course_id']) ? intval($_GET['course_id']) : 0);

if ($courseId <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid course ID received: " . $courseId]));
}

// Fetch course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die(json_encode(["status" => "error", "message" => "Course not found in DB"]));
}

$courseName = urlencode($course['name']); // URL encode course name
$coursePrice = floatval($course['token_price'] ?? 0.0);

// Ensure valid price
if ($coursePrice <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid course price"]));
}

// Generate unique order ID
$order_id = uniqid();
$_SESSION['order_id'] = $order_id;

// Hardcoded Khalti API Key (replace with your actual secret key)
$khalti_secret_key = "live_secret_key_68791341fdd94846a146f0457ff7b455";

// Prepare Khalti API Payment Request
$postFields = json_encode([
    "return_url" => "http://localhost/D-Academe/D-Academe/user/response-pay.php",  // Redirect URL after payment
    "website_url" => "http://localhost/D-Academe/D-Academe/user/?page=buy-course", // Website URL for further interaction
    "amount" => $coursePrice * 100, // Convert to paisa
    "purchase_order_id" => $courseId,
    "purchase_order_name" => $courseName,
    "course_id" => $courseId, // Add course_id in transaction detail
    "transaction_detail" => [
        "course_id" => $courseId,        // Add course_id in transaction detail
        "course_name" => $courseName,    // Add course_name in transaction detail
        "price" => $coursePrice          // Add price in transaction detail
    ],
    "customer_info" => [
        "name" => $user_name,
        "email" => $user_email,
        "phone" => $data['phone'] ?? 'N/A',  // Optional phone field
        "address" => $data['address'] ?? 'N/A' // Optional address field
    ]
]);

// Initialize cURL for sending the request to Khalti API
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_HTTPHEADER => [
        'Authorization: Key ' . $khalti_secret_key,
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);

// Handle cURL error
if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
    exit();
}

// Parse the response from Khalti API
$responseArray = json_decode($response, true);

if (isset($responseArray['error'])) {
    echo 'Error: ' . $responseArray['error'];
    exit();
} elseif (isset($responseArray['payment_url'])) {
    // Redirect the user to the Khalti payment page
    header('Location: ' . $responseArray['payment_url']);
    exit();
} else {
    // Handle successful payment initiation
    echo 'Unexpected response: ' . $response;
    exit();
}

curl_close($curl);
?>