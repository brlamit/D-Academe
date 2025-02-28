<?php
include 'dbconnection.php';
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['id'])) {
    die("Error: User is not logged in.");
}

$user_id = intval($_SESSION['id']);
$user_name = htmlspecialchars($_SESSION['name'] ?? '');
$user_email = htmlspecialchars($_SESSION['email'] ?? '');
$course_id = isset($_GET['purchase_order_id']) ? (int) $_GET['purchase_order_id'] : 0;
$pidx = $_GET['pidx'] ?? null;

if (!$pidx) {
    die("Error: Payment ID (pidx) is missing.");
}

// Call Khalti API to verify the payment
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
    CURLOPT_HTTPHEADER => [
        'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);
if ($response === false) {
    die("CURL Error: " . curl_error($curl));
}
curl_close($curl);

$responseArray = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Decode Error: " . json_last_error_msg());
}

// Check if transaction was successful
if (!isset($responseArray['status'])) {
    die("Error: Status key not found in API response.");
}

if ($responseArray['status'] === 'Completed') {
    $transaction_id = $responseArray['transaction_id'];

    // Retrieve course details from database
    $stmt = $conn->prepare("SELECT id, name, image, description, token_price, course_content, date_of_upload FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Error: Course not found.");
    }

    $course = $result->fetch_assoc();
    $course['course_content'] =$course['course_content']; // Build IPFS URL

    $stmt->close();

    // Check if user is already enrolled
    $stmt = $conn->prepare("SELECT * FROM paid_course_enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        
    
    header("Location: message.php");
    }
    
    $stmt->close();
// Insert enrollment record
$stmt = $conn->prepare("INSERT INTO paid_course_enrollments 
    (user_id, user_name, course_id, course_name, course_price, image, description, content, transaction_id) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("issssssss", 
    $user_id, 
    $user_name,
    $course['id'], 
    $course['name'], 
    $course['token_price'], 
    $course['image'], 
    $course['description'], 
    $course['course_content'], 
    $transaction_id
);

// Execute the statement and check if it was successful
if ($stmt->execute()) {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Transaction successful.",
            text: "✅ Payment successful! You are enrolled in the course.",
            showConfirmButton: false,
            timer: 10000
        });
    </script>';
    
    header("Location: message.php");
} else {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Enrollment Failed.",
            text: "❌ There was an error processing your enrollment. Please try again.",
            showConfirmButton: true
        });
    </script>';
}

$stmt->close();
}
?>
