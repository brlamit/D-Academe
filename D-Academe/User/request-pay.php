<?php
include('dbconnection.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = intval($_SESSION['id']);
$user_name = htmlspecialchars($_SESSION['name'] ?? '');

// Read JSON input for POST request
$data = json_decode(file_get_contents("php://input"), true);

// Extract courseId from POST or GET
$courseId = intval($data['courseId'] ?? ($_GET['course_id'] ?? 0));
if ($courseId <= 0) {
    die("Invalid course ID");
}

// Fetch course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die("Course not found");
}

$courseName = htmlspecialchars($course['name'] ?? '');
$coursePrice = floatval($course['token_price'] ?? 0.0);

// Generate an order ID
$order_id = uniqid();
$_SESSION['order_id'] = $order_id;

// Prepare Khalti API Payment Request
$postFields = json_encode([
    "return_url" => "http://localhost/D-Academe/D-Academe/user/response-pay.php",
    "website_url" => "http://localhost/D-Academe/D-Academe/user/?page=buy-course",
    "amount" => $coursePrice * 100, // Convert to paisa
    "purchase_order_id" => $order_id,
    "purchase_order_name" => $courseName,
    "customer_info" => [
        "name" => $user_name,
        "email" => $_SESSION['email'],
    ]
]);

// Send API Request to Khalti
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_HTTPHEADER => [
        'Authorization: Key 55561f78d07a4991877e810edde844cd', // Replace with your Khalti key
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);
$curlError = curl_error($curl);
curl_close($curl);

if ($curlError) {
    die("Payment request failed: " . $curlError);
}

$responseArray = json_decode($response, true);
if (isset($responseArray['payment_url'])) {
    header("Location: " . $responseArray['payment_url']); // Redirect directly to payment gateway
    exit();
} else {
    die("Payment initiation failed.");
}
?>
