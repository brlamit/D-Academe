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
$data = json_decode(file_get_contents("php://input"), true);

// Validate course ID
$courseId = intval($data['courseId'] ?? ($_GET['course_id'] ?? 0));
if ($courseId <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid course ID"]));
}

// Fetch course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die(json_encode(["status" => "error", "message" => "Course not found"]));
}

$courseName = htmlspecialchars($course['name']);
$coursePrice = floatval($course['token_price'] ?? 0.0);

// Ensure valid price
if ($coursePrice <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid course price"]));
}

// Generate unique order ID
$order_id = uniqid();
$_SESSION['order_id'] = $order_id;

// Hardcoded Khalti API Key
$khalti_secret_key = "55561f78d07a4991877e810edde844cd";

// Prepare Khalti API Payment Request
$postFields = json_encode([
    "return_url" => "http://localhost/D-Academe/D-Academe/user/response-pay.php",
    "website_url" => "http://localhost/D-Academe/D-Academe/user/?page=buy-course",
    "amount" => $coursePrice * 100, // Convert to paisa
    "purchase_order_id" => $order_id,
    "purchase_order_name" => $courseName,
    "customer_info" => [
        "name" => $user_name,
        "email" => $user_email,
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

// Decode API response
$responseArray = json_decode($response, true);

if (isset($responseArray['payment_url'])) {
    // Redirect the user to Khalti payment URL
    echo "<script>window.location.href = '" . $responseArray['payment_url'] . "';</script>";
    exit();
} else {
    echo "<pre>API Response: ";
    print_r($responseArray);
    echo "</pre>";
    die("❌ Payment initiation failed.");
}

// After payment success
if ($pidx && $user_id) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Key $khalti_secret_key",
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($curlError) {
        die('❌ Curl Error: ' . $curlError);
    }

    $responseArray = json_decode($response, true);

    if (!isset($responseArray['status']) || !isset($responseArray['total_amount'])) {
        die('❌ No valid payment data received. Please try again.');
    }

    $amount = $responseArray['total_amount'] ?? 0;
    $status = $responseArray['status'] ?? 'Failed';
    $transaction_id = $responseArray['transaction_id'] ?? '';
    $fee = $responseArray['fee'] ?? 0;
    $refunded = $responseArray['refunded'] ?? false;

    // Ensure enrollments table exists
    $createTableQuery = "CREATE TABLE IF NOT EXISTS paid_course_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        user_name VARCHAR(255) NOT NULL,
        course_id INT NOT NULL,
        course_name VARCHAR(255) NOT NULL,
        course_price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        content TEXT DEFAULT NULL,
        transaction_id VARCHAR(255) NOT NULL,
        status ENUM('Enrolled', 'Completed') DEFAULT 'Enrolled',
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($createTableQuery);

    if (trim(strtolower($status)) === 'completed' && $course_id > 0) 
        {
        // Fetch user details
        $userQuery = "SELECT name FROM users WHERE id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("i", $user_id);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userData = $userResult->fetch_assoc();
        $user_name = $userData['name'] ?? 'Unknown';
        $userStmt->close();

        // Fetch course details
        $query = "SELECT * FROM courses WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die(json_encode(["error" => "❌ Course not found."]));
        }

        $course = $result->fetch_assoc();
        $courseId = $course['id'] ?? 0;
        $courseName = $course['name'] ?? '';
        $coursePrice = $course['price'] ?? 0.0;
        $courseImage = $course['image'] ?? null;
        $courseDescription = $course['description'] ?? null;
        $courseContent = $course['course_content'] ?? null;

        // Check if user is already enrolled
        $checkEnrollment = "SELECT * FROM paid_course_enrollments WHERE user_id = ? AND course_id = ?";
        $checkStmt = $conn->prepare($checkEnrollment);
        $checkStmt->bind_param("ii", $user_id, $courseId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            die(json_encode(["error" => "❌ You are already enrolled in this course."]));
        }

        // Enroll user in course
        $stmt = $conn->prepare("INSERT INTO paid_course_enrollments (user_id, user_name, course_id, course_name, course_price, image, description, content, transaction_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Enrolled')");
        if ($stmt) {
            $stmt->bind_param("issssssss", $user_id, $user_name, $courseId, $courseName, $coursePrice, $courseImage, $courseDescription, $courseContent, $transaction_id);
            $message = $stmt->execute() ? '✅ Payment successful! You are enrolled in the course.' : '❌ Error enrolling in course: ' . $stmt->error;
            $stmt->close();
        } else {
            $message = '❌ Database error: ' . $conn->error;
        }
    }  {
        $message = '❌ Payment failed or invalid course ID.';
    }
} else {
    $message = '❌ Pidx or user ID missing.';
}

echo json_encode(["status" => "success", "message" => $message]);
?>
