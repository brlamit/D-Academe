<?php
include('dbconnection.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    die(json_encode(["status" => "error", "message" => "User not logged in."]));
}

$user_id = intval($_SESSION['id']);
$user_name = htmlspecialchars($_SESSION['name'] ?? '');
$user_email = htmlspecialchars($_SESSION['email'] ?? '');

$data = json_decode(file_get_contents("php://input"), true);
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

if ($coursePrice <= 0) {
    die(json_encode(["status" => "error", "message" => "Invalid course price"]));
}

$order_id = uniqid();
$_SESSION['order_id'] = $order_id;

// Khalti API Key
$khalti_secret_key = "55561f78d07a4991877e810edde844cd";

// Prepare Khalti API Payment Request
$postFields = json_encode([
    "return_url" => "http://localhost/D-Academe/D-Academe/user/response-pay.php",
    "website_url" => "http://localhost/D-Academe/D-Academe/user/?page=buy-course",
    "amount" => $coursePrice * 100,
    "purchase_order_id" => $order_id,
    "purchase_order_name" => $courseName,
    "customer_info" => [
        "name" => $user_name,
        "email" => $user_email,
    ]
]);

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

$responseArray = json_decode($response, true);

if (isset($responseArray['payment_url'])) {
    echo "<script>window.location.href = '" . $responseArray['payment_url'] . "';</script>";
    exit();
} else {
    die(json_encode(["status" => "error", "message" => "Payment initiation failed."]));
}

// After successful payment
if (!empty($_GET['pidx']) && $user_id) {
    $pidx = $_GET['pidx'];

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
        die(json_encode(["status" => "error", "message" => "Curl Error: $curlError"]));
    }

    $responseArray = json_decode($response, true);

    if (!isset($responseArray['status']) || !isset($responseArray['total_amount'])) {
        die(json_encode(["status" => "error", "message" => "No valid payment data received."]));
    }

    $amount = $responseArray['total_amount'] ?? 0;
    $status = strtolower(trim($responseArray['status'] ?? 'Failed'));
    $transaction_id = $responseArray['transaction_id'] ?? '';

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

    if ($status === 'completed' && $courseId > 0) {
        $userQuery = "SELECT name FROM users WHERE id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("i", $user_id);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userData = $userResult->fetch_assoc();
        $user_name = $userData['name'] ?? 'Unknown';
        $userStmt->close();

        // Fetch course details again
        $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();

        if (!$course) {
            die(json_encode(["status" => "error", "message" => "Course not found."]));
        }


        $courseId = $course['id'] ?? 0;
        $courseName = $course['name'] ?? '';
        $coursePrice = $course['token_price'] ?? 0.0;
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
            die(json_encode(["status" => "error", "message" => "You are already enrolled in this course."]));
        }

        // Enroll user in course
        $stmt = $conn->prepare("INSERT INTO paid_course_enrollments (user_id, user_name, course_id, course_name, course_price, image, description, content, transaction_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Enrolled')");
        $stmt->bind_param("issssssss", $user_id, $user_name, $courseId, $courseName, $coursePrice, $courseImage, $courseDescription, $courseContent, $transaction_id);
        $stmt->execute();
        $stmt->close();

        // Redirect immediately to Khalti confirmation URL
        header("Location: https://test-pay.khalti.com/wallet?pidx=$pidx");
        exit();
    }
}

// echo json_encode(["status" => "error", "message" => "Payment failed or invalid course ID."]);
?>
